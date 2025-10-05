<?php
	// Functie: programma login OOP 
    // Auteur: Studentnaam

    // Initialisatie
	require_once('classes/User.php');
	$user = new User();
	$errors=[];	
	
	// Is de login button aangeklikt?
	if(isset($_POST['login-btn']) ){


		$user->username = $_POST['username'];
		$user->setPassword($_POST['password']);

		$user->showUser();

		// Validatie gegevens
		$errors = $user->validateUser();

		// Indien geen fouten dan inloggen
		if(count($errors)== 0){
			//Inlogen
			if ($user->loginUser()){
				echo "LOgin ok";
				// Ga naar pagina??
				header("location: index.php");
			} else
			{
				array_push($errors, "Login mislukt");
				echo "Login NOT ok";
			}
		}

		if(count($errors) > 0){
			$message = "";
			foreach ($errors as $error) {
				$message .= $error . "\\n";
			}
			
			echo "
			<script>alert('" . $message . "')</script>
			<script>window.location = 'login_form.php'</script>";
		
		}
		
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
	</head>
<body>

	<h3>PHP - PDO Login and Registration</h3>
	<hr/>
	
	<form action="" method="POST">	
		<h4>Login here...</h4>
		<hr>
		
		<label>Username</label>
		<input type="text" name="username" />
		<br>
		<label>Password</label>
		<input type="password" name="password" />
		<br>
		<button type="submit" name="login-btn">Login</button>
		<br>
		<a href="register_form.php">Registration</a>
	</form>
		
</body>
</html>