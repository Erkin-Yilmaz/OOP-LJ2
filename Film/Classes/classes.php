<?php

function redirect($location) {
    header("Location: $location");
    exit;
}


class Database {
    protected $pdo;

    public function __construct($host, $db, $user, $pass) {
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
             $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
             error_log("Database connection error: " . $e->getMessage());
             die("Database connection failed. Contact IT.");
        }
    }
    public function getPDO() {
        return $this->pdo;
    }
}

class AdminManager {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    
  public function login($username, $password) {
    
    $sql = "SELECT admin_id, wachtwoord, gebruikersnaam FROM film_project_admin WHERE gebruikersnaam = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && $password === $admin['wachtwoord']) {
        return $admin;
    }
    return false;
}
}

class ContentManager {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    
    public function getAllFilms() {
        return $this->pdo->query("SELECT film_id, filmnaam, genre FROM film_project_film ORDER BY filmnaam")->fetchAll();
    }
    
    public function getAllActors() {
        return $this->pdo->query("SELECT acteur_id, acteurnaam FROM film_project_acteur ORDER BY acteurnaam")->fetchAll();
    }
    
    public function getFilmById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM film_project_film WHERE film_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getActorById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM film_project_acteur WHERE acteur_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getLinkedActorsByFilmId($film_id) {
        $sql = "SELECT a.acteur_id, a.acteurnaam FROM film_project_acteur_film af JOIN film_project_acteur a ON af.acteur_id = a.acteur_id WHERE af.film_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$film_id]);
        return $stmt->fetchAll();
    }

    public function getPublicFilms() {
        $sql = "
            SELECT 
                f.filmnaam, 
                f.genre, 
                GROUP_CONCAT(a.acteurnaam SEPARATOR ', ') AS acteurs 
            FROM 
                film_project_film f
            LEFT JOIN 
                film_project_acteur_film af ON f.film_id = af.film_id
            LEFT JOIN 
                film_project_acteur a ON af.acteur_id = a.acteur_id
            GROUP BY 
                f.film_id
            ORDER BY 
                f.filmnaam
        ";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function addFilm($naam, $genre) {
        $sql = "INSERT INTO film_project_film (filmnaam, genre) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$naam, $genre]);
    }

    public function updateFilm($id, $naam, $genre) {
        $sql = "UPDATE film_project_film SET filmnaam = ?, genre = ? WHERE film_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$naam, $genre, $id]);
    }

    public function deleteFilm($id) {
        $stmt = $this->pdo->prepare("DELETE FROM film_project_film WHERE film_id = ?");
        return $stmt->execute([$id]);
    }
    
    public function addActor($naam) {
        $sql = "INSERT INTO film_project_acteur (acteurnaam) VALUES (?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$naam]);
    }
    
    public function updateActor($id, $naam) {
        $sql = "UPDATE film_project_acteur SET acteurnaam = ? WHERE acteur_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$naam, $id]);
    }

    public function deleteActor($id) {
        $stmt = $this->pdo->prepare("DELETE FROM film_project_acteur WHERE acteur_id = ?");
        return $stmt->execute([$id]);
    }
    
    public function updateFilmActorLinks($film_id, $actor_ids) {
        $this->pdo->prepare("DELETE FROM film_project_acteur_film WHERE film_id = ?")->execute([$film_id]);
        
        if (empty($actor_ids)) {
            return true;
        }

        $values = [];
        $placeholders = [];
        foreach ($actor_ids as $acteur_id) {
            $placeholders[] = '(?, ?)';
            $values[] = $acteur_id;
            $values[] = $film_id;
        }
        
        $sql = "INSERT INTO film_project_acteur_film (acteur_id, film_id) VALUES " . implode(', ', $placeholders);
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($values);
    }
}
?>