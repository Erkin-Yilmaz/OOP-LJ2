<?php

// Functie: classdefinitie User 
// Auteur: Studentnaam

class User
{
    // Eigenschappen 
    public string $username = "";
    public string $email = "";
    private string $password = "";

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function showUser()
    {
        echo "<br>Username: $this->username<br>";
        echo "<br>Password: $this->password<br>";
        echo "<br>Email: $this->email<br>";
    }

    // Database connectie methode
    private function dbConnect(): PDO
    {
        $host = "localhost";
        $dbname = "oop_users";
        $username = "root";
        $password = "";
        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function registerUser(): array
    {
        $errors = [];
        $conn = $this->dbConnect();

        if ($this->username != "") {
            // Check user exist in database
            $stmt = $conn->prepare("SELECT id FROM User WHERE username = ?");
            $stmt->execute([$this->username]);
            if ($stmt->fetch()) {
                array_push($errors, "Username bestaat al.");
            } else {
                // username opslaan in tabel User
                $stmt = $conn->prepare("INSERT INTO User (username, password, email) VALUES (?, ?, ?)");
                if ($stmt->execute([$this->username, $this->password, $this->email])) {
                    // Success
                } else {
                    array_push($errors, "Registratie mislukt.");
                }
            }
        }
        return $errors;
    }

public function validateUser() {
    $errors = [];
    if (empty($this->username)) {
        $errors[] = "Username is required.";
    } elseif (strlen($this->username) < 3) {
        $errors[] = "Username must be at least 3 characters.";
    } elseif (strlen($this->username) > 50) {
        $errors[] = "Username must be no more than 50 characters.";
    }
    if (empty($this->password)) {
        $errors[] = "Password is required.";
    }
    return $errors;
}

   public function loginUser(): bool
{
    $conn = $this->dbConnect();
    $stmt = $conn->prepare("SELECT * FROM User WHERE username = ?");
    $stmt->execute([$this->username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['password'] === $this->password) {
        session_start();
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        return true;
    }
    return false;
}

    // Check if the user is already logged in
    public function isLoggedin(): bool
    {
        
        return isset($_SESSION['username']);
    }

    public function getUser(string $username): bool
    {
        $conn = $this->dbConnect();

        $stmt = $conn->prepare("SELECT * FROM User WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $this->username = $user['username'];
            $this->email = $user['email'];
            $this->password = $user['password'];
            return true;
        } else {
            return false;
        }
    }

    public function logout()
    {
       
        
        session_destroy();
    }
}
?>