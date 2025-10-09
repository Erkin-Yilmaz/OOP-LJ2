
<?php
    // Functie: programma login OOP 
    // Auteur: Studentnaam
    require_once('classes/User.php');

    $user = new User();
    $errors=[];

    
  if(isset($_POST['login-btn']) ){

    $user->username = $_POST['username'];
    $user->setPassword($_POST['password']);

   
    $errors = $user->validateUser(); 
    if (!is_array($errors)) {
        $errors = [];
    }

    
    if (count($errors) === 0) {
        $errors = $user->loginUser();
        if ($errors === true) {
            header("Location: index.php?login=success");
            exit;
        } else {
            echo "Login mislukt!";
        }
    }

    if (count($errors) > 0) {
        $message = "";
        foreach ($errors as $error) {
            $message .= "{$error}\n";
        }
        echo "<script>alert('{$message}')</script>
        <script>window.location = 'login_form.php'</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<body>
    

        <h3>PHP - PDO Login and Registration</h3>
        <hr/>

            <form action="" method="POST">	
                <h4>Register here...</h4>
                <hr>
                
                <div>
                    <label>Username</label>
                    <input type="text"  name="username" required />
                </div>
                <div >
                    <label>Password</label>
                    <input type="password"  name="password" required />
                </div>
                <div>
                    <label>Email</label>
                    <input type="email" name="email" required />
                </div>
                <br />
                <div>
                    <button type="submit" name="register-btn">Register</button>
                </div>
                <a href="index.php">Home</a>
            </form>


</body>
</html>