<!DOCTYPE HTML>

<?php
session_start();

// Enters here only in POST request.
if( isset($_POST['email']) ) {
    unset( $_SESSION['email'] );
	$email = ( !empty($_POST['email']) ? $_POST['email'] : '' );
	$pass = ( !empty($_POST['pass']) ? $_POST['pass'] : '' );
	$_SESSION['msg'] = false;

	// TODO: Array to be removed, switched by a database.
	$hash = '1a52e17fa899cf40fb04cfc42e6352f1';

	if (!empty($email) && !empty($pass)) {
		// $error_message = 'Incorrect password';
		$salt = 'XyZzy12*_';
		$_SESSION['wrong_password'] = false;

		// Counts the number of at-signs in the email string.
		$count = 0;
		for( $i=0; $i<strlen($email); $i++ ){
			if( $email[$i] == '@' ){
				$count++;
			}
		}
		if( $count !== 1 ){
            $_SESSION['msg'] = 'Please enter a valid email address';
            header( 'Location: login.php' );
            return;
		}

		if ($count === 1) {
			$check = hash('md5', $salt . $pass);
			if( $check == $hash ){
				error_log( "Login success ".$email );
				// Stores email in SESSION data and redirects to view.php using GET request (without GET parameters this time).
                $_SESSION['name'] = $_POST['email'];
				header( 'Location: view.php' );
				return;
			} else {
				error_log( "Login fail ".$email." $check" );
                $_SESSION['msg'] = "Incorrect password";
                $_SESSION['wrong_password'] = true;
                header( "Location: login.php" );
                return;
			}
		}
	} else {
        $_SESSION['msg'] = "Email and password are required";
        header( "Location: login.php" );
        return;
	}

	// Error. Execution's not supposed to reach here.
	die( "Should not pass by here" );
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
			<h1> Welcome to AutosDB, an automobile database. </h1>
		</header>

		<form class="box" method="post">
			<p>
				<label for='email'>Email: </label>
				<input type='text' name='email' id='email' size='20' value=''>
			</p>
			<p>
				<label for='pass'>Password: </label>
				<input type='password' name='pass' id='pass' size='20' value=''>
			</p>
			<p>
				<input type='submit' class='button' value='Log In'>
			</p>
			<?php
			if( isset($_SESSION['msg']) ){
				echo "<p id='error'>";
				echo    ( $_SESSION['msg'] );
                echo "</p>";
                unset( $_SESSION['msg'] );
				
				if( isset($_SESSION['wrong_password']) && $_SESSION['wrong_password'] === true ){
					echo "<p id='login_tip'>";
					echo    "The password is the programming language used for this application concatenated with <span class='pre'>123</span>.";
                    echo "</p>";
                }
                unset( $_SESSION['wrong_password'] );
			}
			?>
		</form>
	</div>

</body>
</html>
