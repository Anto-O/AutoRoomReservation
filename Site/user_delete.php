<?php 
require('config.php');


if (!isset($_SESSION["id"]) ) {
    $_SESSION["erreur"] = "Vous devez être connecté pour accéder à cette page";
    header("Location: index.php");
    die;
}
if ($_SESSION["admin"]==false) {
    $_SESSION["erreur"] = "Vous devez être un admin pour accéder à cette page";
    header("Location: accueil.php");
    die;
}

if(!empty($_GET)) {
    if((isset($_GET['id']) && !empty($_GET['id']))) {
        $id = $_GET['id'];

        $url = 'http://localhost:5287/User/Remove?Id=' . $id;
        $result = executeRequest($url,'',false);
        if ($result->Success != true) {
            $_SESSION['erreur'] =  $result->Error;
            header('Location: user_liste.php');
            die;
        }else {
            header('Location: user_liste.php');
            die;
        }
    }
}


?>
