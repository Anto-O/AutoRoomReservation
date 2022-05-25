<?php 

require('config.php');

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">

    <title>Document</title>
</head>
<?php if (!empty($_SESSION['id'])) { ?>
<body>
    <header>
    <nav class="flex items-center justify-between bg-white px-12">
    <img class="image_navbar" src="/images/lit.png" alt="lit">
    <ul class="flex">
        <li class="mr-6">
            <a class="text-lg link_navbar" href="acceuil.php">Acceuil</a>
        </li>
        <?php
        if (isset($_SESSION["id"]) && $_SESSION["admin"]==true) {
        ?>
            <li class="mr-6">
                <a class="text-lg link_navbar" href="admin.php">Admin</a>
            </li>
        <?php 
        }
        ?>
        <li class="mr-6">
            <a class="text-lg link_navbar" href="deconnexion.php">Se deconnecter</a>
        </li>
    </ul>
    </nav>
    </header>
    <h1 class="text-center mt-24">Acceuil</h1>
    <div class="flex justify-center items-center mt-11">
    <div class="bg-white search p-10 flex justify-center">
        <input type="text" placeholder="Rechercher" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none" id="search_appartment">
        <button class="button_acceuil font-medium p-2 md:p-4 button_login uppercase">Valider</button>
    </div>
    </div>
</body>
<?php }else {
    $_SESSION['erreur'] = "Veuillez vous connecter avant d'acceder au site internet";
    header('Location: index.php');
} ?>
</html>

<style>   
body {
    background-color: #272838;
}

h1 {
    font-weight: bold;
    color : #F8E2CA;
    font-size: 60px;
}

.titre_acceuil {
    margin-top: 100px;
}

.image_navbar {
    width: 100px;
}

.button_acceuil {
    background-color: #272838; 
    color : #F8E2CA;
    width : 50%;
}
.search {
    width: 50%;
}

#search_appartment {
    width: 70%;
}

@media screen and (max-width: 1024px)
{
    ul
    {
         display:flex !important;
        flex-direction: column !important;
    }

    .search {
    width: 80%;
}
}


</style>