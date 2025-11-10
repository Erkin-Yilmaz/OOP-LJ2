<?php
session_start();
require '../database/connect.php'; 

if (!isset($_SESSION['admin_id'])) { redirect('../admin_login.php?action=login'); }

if (isset($_GET['delete_film_id'])) {
    $contentManager->deleteFilm($_GET['delete_film_id']);
    redirect('admin_dashboard.php?success=Film verwijderd');
}
if (isset($_GET['delete_acteur_id'])) {
    $contentManager->deleteActor($_GET['delete_acteur_id']);
    redirect('admin_dashboard.php?success=Acteur verwijderd');
}

$films = $contentManager->getAllFilms();
$actors = $contentManager->getAllActors();
?>
<div class="admin-content">
    <h1>Admin Dashboard (CRUD)</h1>
    
    <p>
        <a href="add_film.php" class="button">+ Film Toevoegen</a>
        <a href="add_actor.php" class="button">+ Acteur Toevoegen</a>
        <a href="../admin_login.php?action=logout" style="float: right;">Logout</a>
    </p>
    <hr>
    <?php if (isset($_GET['success'])) echo "<p style='color: yellow;'>**" . htmlspecialchars($_GET['success']) . "**</p>"; ?>

    <h2>Beheer Films (Edit/Delete/Link)</h2>
    <table>
        <thead><tr><th>Naam</th><th>Genre</th><th>Acties</th></tr></thead>
        <tbody>
            <?php foreach ($films as $f): ?>
            <tr>
                <td><?php echo htmlspecialchars($f['filmnaam']); ?></td>
                <td><?php echo htmlspecialchars($f['genre']); ?></td>
                <td>
                    <a href="edit_film.php?id=<?php echo $f['film_id']; ?>">Bewerken/Links</a> |
                    <a href="?delete_film_id=<?php echo $f['film_id']; ?>" onclick="return confirm('Verwijderen?');">Verwijderen</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Beheer Acteurs (Edit/Delete)</h2>
    <table>
        <thead><tr><th>Naam</th><th>Acties</th></tr></thead>
        <tbody>
            <?php foreach ($actors as $a): ?>
            <tr>
                <td><?php echo htmlspecialchars($a['acteurnaam']); ?></td>
                <td>
                    <a href="edit_actor.php?id=<?php echo $a['acteur_id']; ?>">Bewerken</a> |
                    <a href="?delete_acteur_id=<?php echo $a['acteur_id']; ?>" onclick="return confirm('Verwijderen?');">Verwijderen</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
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