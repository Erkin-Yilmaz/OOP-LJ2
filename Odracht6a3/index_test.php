<?php
    // Functie: programma login OOP 
    // Auteur: Studentnaam

    // Initialisatie
    include 'classes/User.php';

    //Main
    $piet = new User();
    $piet->username = "Piet";

    $piet->showUser();

    $jan = new User();
    $jan->username = "Jan";
    $jan->showUser();

?>