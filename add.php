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

if( isset($_POST['add']) ){
    $_SESSION['msg'] = false;
    $_error = false;

/*
    if( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1 ){
        $_SESSION['msg'] = "All values are required";
        header( 'Location: add.php' );
        return;
    }
*/
    if( !empty($_POST['imageURL']) ){
        if( substr($_POST['imageURL'], 0, 7)!=='http://' && substr($_POST['imageURL'], 0, 8)!=='https://' ){
            $_SESSION['msg'] = "Invalid URL. URLs shall begin with http:// or https://" ;
            $_error = true;
        } elseif( curl_urlExist($_POST['imageURL']) === false ){
            $_SESSION['msg'] = "Invalid URL. Error executing GET request.";
            $_error = true;
        }
    } elseif( !is_numeric($_POST['year']) || !is_numeric($_POST['mileage']) ){
        $_SESSION['msg'] = 'Mileage and year must be numeric';
        $_error = true;
    } elseif( strlen($_POST['make']) < 1 ){
        $_SESSION['msg'] = 'Make is required';
        $_error = true;
    } elseif( strlen($_POST['model']) < 1 ){
        $_SESSION['msg'] = 'Model is required';
        $_error = true;
    }
    if( $_error == true ){
        $_SESSION['imageURL'] = $_POST['imageURL'];
        $_SESSION['make'] = $_POST['make'];
        $_SESSION['model'] = $_POST['model'];
        $_SESSION['year'] = $_POST['year'];
        $_SESSION['mileage'] = $_POST['mileage'];
        header('Location: add.php');
        return;
    }

    $_POST['imageURL'] = ( !empty($_POST['imageURL']) ? $_POST['imageURL'] : null );

    try {
        $stmt = $pdo->prepare('INSERT INTO auto (imageURL, make, model, year, mileage)
            VALUES (:imageURL, :make, :model, :year, :mileage)');
        $stmt->execute(
            array(
            ':imageURL' => $_POST['imageURL'],
            ':make' => $_POST['make'],
            ':model' => $_POST['model'],
            ':year' => $_POST['year'],
            ':mileage' => $_POST['mileage'])
        );
    } catch( Exception $ex ){
        echo("Internal error, please contact support");
        // Why error4?
        error_log("error4.php, SQL error=".$ex->getMessage());
        return;
    }
    $_SESSION['msg'] = 'Record added';
    header('Location: view.php');
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
			<h1> Adding autos for <?= htmlentities($_SESSION['name']) ?> </h1>
		</header>

		<form class='box' method='post'>
            <p>
				<label for='imageURL'>Image URL (optional): </label>
				<input type='text' name='imageURL' id='imageURL' size='20' value='<?= isset($_SESSION['imageURL']) ? htmlentities($_SESSION['imageURL']) : '' ?>'>
			</p>
			<p>
				<label for='make'>Make: </label>
                <input type='text' name='make' id='make' size='20' value='<?= isset($_SESSION['make']) ? htmlentities($_SESSION['make']) : '' ?>'>
			</p>
			<p>
				<label for='make'>Model: </label>
                <input type='text' name='model' id='model' size='20' value='<?= isset($_SESSION['model']) ? htmlentities($_SESSION['model']) : '' ?>'>
			</p>
			<p>
				<label for='year'>Year: </label>
				<input type='text' name='year' id='year' size='5' value='<?= isset($_SESSION['year']) ? htmlentities($_SESSION['year']) : '' ?>'>
			</p>
			<p>
				<label for='mileage'>Mileage: </label>
				<input type='text' name='mileage' id='mileage' size='5' value='<?= isset($_SESSION['mileage']) ? htmlentities($_SESSION['mileage']) : '' ?>'>
			</p>
			<p>
                <input type="submit" class="button" name="add" value="Add" >
                <input type="submit" class="button" name="cancel" value="Cancel">
			</p>
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