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
use PHPGangsta\GoogleAuthenticator as GoogleAuthenticator;

// Nieuwe instantie van GoogleAuthenticator
$ga = new GoogleAuthenticator();

$qrCodeUrl = '';
$secret = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Hash het wachtwoord
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Genereer een secret key
    $secret = $ga->createSecret();

    // Sla gebruiker op in de database
    $stmt = $pdo->prepare("INSERT INTO users (username, password, 2fa_secret) VALUES (?, ?, ?)");
    $stmt->execute([$username, $hashedPassword, $secret]);

    // Genereer de QR-code URL
    $qrCodeUrl = $ga->getQRCodeGoogleUrl('TCRHELDEN', $secret);
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Registreren</title>
</head>
<body>

<h1>Registreren</h1>

<form method="post">
    <label>Gebruikersnaam:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Wachtwoord:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Registreren</button>
</form>

<?php if ($qrCodeUrl): ?>
    <h3>Registratie succesvol! Scan deze QR code met Google Authenticator:</h3>
    <img src="<?php echo $qrCodeUrl; ?>" alt="QR Code"><br>
    <p>Sla de geheime sleutel op: <?php echo $secret; ?></p>
<?php endif; ?>

<a href="login.php">Login</a>

</body>
</html>