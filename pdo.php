<?php
$pdo = new PDO('mysql:localhost;port=8889;dbname=misc', 'dan', 'php123');
$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
?>