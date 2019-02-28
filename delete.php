<!DOCTYPE HTML>

<?php
require_once('functions.php');
require_once('pdo.php');
require_once('curl.php');

session_start();

if( ! isset($_SESSION['name']) ){
    die("ACCESS DENIED");
}

if( isset($_POST['cancel']) ){
	header('Location: view.php');
	return;
}

if( isset($_POST['delete']) ){
    $_SESSION['msg'] = false;
    $_error = false;

    try {
        $sql = "DELETE FROM auto WHERE auto_id = :auto_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array( ':auto_id' => $_POST['auto_id'] ));
    } catch( Exception $ex ){
        echo("Internal error, please contact support");
        // Why error4?
        error_log("error4.php, SQL error=".$ex->getMessage());
        return;
    }
    $_SESSION['msg'] = 'Record deleted';
    header('Location: view.php');
    return;
}

if( !isset($_GET['auto_id']) ) {
    $_SESSION['msg'] = "Missing auto_id";
    header( 'Location: view.php' );
    return;
}

$stmt = $pdo->prepare('SELECT auto_id, make FROM auto WHERE auto_id = :auto_id');
$stmt->execute(array( ':auto_id' => $_GET['auto_id'] ));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if( $row === false ) {
    $_SESSION['msg'] = 'Bad value for auto_id';
    header( 'Location: view.php' );
    return;
}

?>

<html lang='en'>

<head>
	<meta charset='UTF-8'>
	<link rel='stylesheet' href='css/style.css'>
	<title> AutosDB - d0aae953 </title>
</head>

<body>
	<div id='fb'>
		<header>
			<h1> Confirm: Deleting <?= htmlentities($row["make"]) ?> </h1>
		</header>

		<form class='box' method='post'>
            <input type='hidden' name='auto_id' value='<?= $row["auto_id"] ?>'>
            <input type="submit" class="button" name="delete" value="Delete" >
            <input type="submit" class="button" name="cancel" value="Cancel">
			<?php
			if( isset($_SESSION['msg']) && $_SESSION['msg'] != false ){
                echo "<p id='error'>";
                echo    $_SESSION['msg'];
                echo "</p>";

                unset($_SESSION['msg']);
			}
			?>
		</form>
	</div>
</body>
</html>