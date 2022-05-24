<?php 
require('config.php');

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<body>
<div class="login">
<h1 class="text-center titre_login mt-11">Créer un compte</h1>
<div class="overflow-hidden flex items-center justify-center">
  <div class="bg-white lg:w-5/12 md:6/12 w-10/12 shadow-3xl">
    <form class="p-12" action="/" method="POST">
    <div class="flex items-center text-lg mb-6 md:mb-8">
        <input type="text" id="nom" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Nom" required/>
      </div>
      <div class="flex items-center text-lg mb-6 md:mb-8">
        <input type="text" id="prenom" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Prenom" required/>
      </div>
      <div class="flex items-center text-lg mb-6 md:mb-8">
        <input type="email" id="email" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Email" required/>
      </div>
      <div class="flex items-center text-lg mb-6 md:mb-8">
        <input type="password" id="mot_de_passe" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Mot de passe" />
      </div>
      <div class="flex items-center text-lg mb-6 md:mb-8">
        <input type="date" id="date_de_naissance" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Date de naissance" />
      </div>
      <div class="flex items-center text-lg mb-6 md:mb-8">
        <input type="text" id="telephpne" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Telehpone" />
      </div>
      <div class="flex items-center text-lg mb-6 md:mb-8">
        <input type="text" id="nationalite" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Nationalite" />
      </div>
      <div class="flex items-center justify-between flex-row-reverse flex-wrap-reverse">
      <button type="submit" class="font-medium p-2 md:p-4 button_login uppercase w-full">Connexion</button>
      <a href="index.php" class="font-medium">Deja un compte ? Cliquez ici</button>
      </div>
    </form>
  </div>
 </div>
 </div>
</body>

</html>


<style>

body {
    background-color: #272838;
}


.button_login {
    background-color: #272838; 
    color : #F8E2CA;
    width : 50%;
}

h1 {
    font-weight: bold;
    color : #F8E2CA;
    font-size: 60px;

}

/* Nouvelles règles si la fenêtre fait au plus 1024px de large */
@media screen and (max-width: 1024px)
{
    .button_login
    {
        width : 100%;

    }
}




</style>