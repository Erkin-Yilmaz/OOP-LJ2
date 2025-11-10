<?php
session_start();
require '../database/connect.php'; 

if (!isset($_SESSION['admin_id'])) { redirect('../admin_login.php?action=login'); }

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $contentManager->addFilm($_POST['filmnaam'], $_POST['genre']);
        redirect('admin_dashboard.php?success=Film succesvol toegevoegd');
    } catch (\PDOException $e) {
        $message = ($e->getCode() === '23000') ? "Fout: Film bestaat al." : "Er is een fout opgetreden.";
    }
}

?>
<div class="admin-content">
    <h1>Film Toevoegen</h1>
    <?php if (!empty($message)): ?><p style="color: yellow;"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>

    <form method="POST">
        <label>Filmnaam:</label><br>
        <input type="text" name="filmnaam" required style="width: 100%;"><br>
        <label>Genre:</label><br>
        <input type="text" name="genre" required style="width: 100%;"><br>
        <button type="submit">Film Toevoegen</button>
    </form>
    <p><a href="admin_dashboard.php">Terug naar Dashboard</a></p>
</div>

<style>
body { 
    margin: 0; 
    font-family: sans-serif; 
    background-color: #2e3e2e; 
    color: #f0f0f0; 
}
.main-container { 
    background-color: #556b55;
    min-height: 100vh; 
    padding-bottom: 50px; 
}
.navbar { 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    padding: 15px 40px; 
    background-color: #3e513e; 
}
.auth-controls a, .auth-controls span { 
    background-color: transparent; 
    border: 1px solid #f0f0f0; 
    color: #f0f0f0; 
    padding: 8px 15px; 
    cursor: pointer; 
    text-decoration: none; 
    margin-left: 10px; 
    display: inline-block; 
}
.content-grid { padding: 20px 40px; }
.film-row { 
    display: flex; 
    overflow-x: auto; 
    gap: 15px; 
    padding-bottom: 20px; 
}
.film-card { 
    min-width: 150px; 
    height: 225px; 
    background-color: #4a5d4a; 
    border-radius: 5px; 
    flex-shrink: 0; 
    padding: 10px; 
    display: flex; 
    flex-direction: column; 
    justify-content: flex-end; 
    font-size: 0.85em;
}

.admin-content { 
    background-color: #556b55; 
    padding: 20px; 
    max-width: 900px; 
    margin: 50px auto; 
    border-radius: 8px; 
}
.admin-content input, .admin-content button, .admin-content select { 
    padding: 8px; 
    margin: 5px 0; 
    border: none; 
    border-radius: 3px; 
    background-color: #f0f0f0;
    color: #333;
}
.admin-content button, .admin-content a.button { 
    background-color: #3e513e; 
    color: white; 
    padding: 10px 15px; 
    border: none; 
    cursor: pointer; 
    text-decoration: none; 
    display: inline-block; 
}
.admin-content table { 
    width: 100%; 
    border-collapse: collapse; 
    margin-top: 20px; 
}
.admin-content th, .admin-content td { 
    border: 1px solid #4a5d4a; 
    padding: 10px; 
    text-align: left; 
}

.modal-overlay { 
    display: flex; 
    position: fixed; 
    z-index: 1000; 
    width: 100%; 
    height: 100%; 
    background-color: #556b55; 
    align-items: center; 
    justify-content: center; 
}
.modal-content { 
    background-color: #556b55; 
    padding: 40px; 
    border-radius: 10px; 
    width: 300px; 
    text-align: center; 
  
    box-shadow: 0 0 0 15px #6b886b, 0 0 0 30px #85a385; 
}
.modal-content input { 
    width: 80%; 
    padding: 10px; 
    margin: 10px 0; 
    text-align: center; 
}
</style>