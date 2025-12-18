<?php
session_start();

// Database configuratie
$host = 'localhost';
$db   = 'google2fa';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Database verbinding
$pdo = new PDO($dsn, $user, $pass, $options);

// Google Authenticator class includen
require_once '../GoogleAuthenticator.php';

// Namespace gebruiken
use PHPGangsta\GoogleAuthenticator;

// Nieuwe instantie van GoogleAuthenticator
$ga = new GoogleAuthenticator();

$error = '';

// Controleer of het formulier is verstuurd
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Waardes ophalen uit het formulier
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $code     = $_POST['code'] ?? '';

    // Gebruiker ophalen uit de database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        // Controleer wachtwoord
        if (password_verify($password, $user['password'])) {

            // Haal de secret op uit de database
            $secret = $user['2fa_secret'];

            // Controleer de 2FA code (30 seconden tijdsvenster)
            $checkResult = $ga->verifyCode($secret, $code, 2);

            if ($checkResult) {
                // Login succesvol
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Ongeldige 2FA code.';
            }
        } else {
            $error = 'Onjuist wachtwoord.';
        }
    } else {
        $error = 'Gebruiker niet gevonden.';
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Login met 2FA</title>
</head>
<body>

<h1>Login</h1>

<?php if ($error): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<form method="post">
    <label>Gebruikersnaam:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Wachtwoord:</label><br>
    <input type="password" name="password" required><br><br>

    <label>2FA code (Google Authenticator):</label><br>
    <input type="text" name="code" required><br><br>

    <button type="submit">Inloggen</button>
</form>

<a href="register.php">Registreren</a>

</body>
</html>