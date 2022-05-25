<?php 

require('config.php');

if(!empty($_GET)) {
    if((isset($_GET['ville']) && !empty($_GET['ville']))) {
        $ville = $_GET['ville'];
    }else {
        $_SESSION['erreur'] =  "Veuillez remplir tout les champs du formulaire";
        header('Location: acceuil.php');
    }
}
?>