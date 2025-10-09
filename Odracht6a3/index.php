<?php
    // Functie: programma login OOP 
    // Auteur: Studentnaam

    // Initialisatie
    require_once 'classes/User.php';
    
    $user = new User();
?>

<!DOCTYPE html>

<html lang="en">

<body>
    <h3>PDO Login and Registration</h3>
    <hr/>
    <h3>Welcome op de HOME-pagina!</h3>
    <br />

    <?php
    session_start();

    if (isset($_GET['login']) && $_GET['login'] === 'success' && isset($_SESSION['username'])) {
        echo "Logged in successfully.<br>";
        echo "Username: " . htmlspecialchars($_SESSION['username']) . "<br>";
        
        echo "Email: " . htmlspecialchars($_SESSION['email']) . "<br>";
        echo '<a href="?logout=true">Logout</a>';
    } else {
        echo "Je bent niet ingelogd.<br><br>";
        echo '<a href="login_form.php">Login</a>';
    }
    
    ?>

</body>
</html>