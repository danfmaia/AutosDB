<!DOCTYPE HTML>

<?php
require_once('functions.php');
require_once('pdo.php');

session_start();

if( ! isset($_SESSION['name']) ){
    die("Not logged in");
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
			<h1> Tracking autos for <?= htmlentities($_SESSION['name']) ?> </h1>
		</header>

		<div class='view_items'>
            <?php
            if( isset($_SESSION['msg']) && $_SESSION['msg'] != false ){
                echo "<p id='success'>";
                echo    $_SESSION['msg'];
                echo "</p>";

                unset($_SESSION['msg']);
            }
            ?>
        </div>

		<div class='view_items'>
			<?php
            echo "<h2> Automobiles </h2>";
			$stmt = $pdo->query( 'SELECT auto_id, imageURL, make, model, year, mileage FROM auto ORDER BY make' );
            if( $stmt->rowCount() == 0 ){
                echo '<p> No rows found </p>';
            } else {
                echo "<table border='1'>
                        <thead><tr>
                            <th> Make </th>
                            <th> Model </th>
                            <th> Year </th>
                            <th> Mileage </th>
                            <th> Action </th>
                        </tr></thead>
                        <tbody>";

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr><td>';
                    if (isset($row['imageURL'])) {
                        echo '<a target="blank" href='.htmlentities($row['imageURL']).'> '.htmlentities($row['make']).' </a>';
                    } else {
                        echo(htmlentities($row['make']));
                    }
                    echo('</td><td>');
                    echo(htmlentities($row['model']));
                    echo('</td><td>');
                    echo(htmlentities($row['year']));
                    echo('</td><td>');
                    echo(htmlentities($row['mileage']));
                    echo('</td><td>');
                    echo('<a href="edit.php?auto_id='.$row['auto_id'].'"> Edit </a> / <a href="delete.php?auto_id='.$row['auto_id'].'"> Delete </a>');
                    echo('</td></tr>');
                }
                echo "	</tbody>
                    </table>";
            }
			?>
            <p>
                <a href='add.php'>Add New Entry</a> || <a href='logout.php'>Logout</a>
            </p>
		</div>
	</div>
</body>
</html>