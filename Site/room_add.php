<?php 
require('config.php');
require('Apartment.php');
require('Room.php');


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

$url = 'http://localhost:5287/Apartment/GetAll';

$result = executeRequest($url,"",false);

if ($result->Success==true) {
    $aparts = $result->Content;
}else{
    $_SESSION["erreur"] = $result->Error;
    header("Locatin: admin.php");
}


if(!empty($_POST)) {
    $_SESSION["old"] = $_POST;
    if((isset($_POST['area']) && !empty($_POST['area'])) && 
        (isset($_POST['price']) && !empty($_POST['price'])) &&
        (isset($_POST['place']) && !empty($_POST['place'])) &&
        (isset($_POST['apart']) && !empty($_POST['apart'])) &&
        (isset($_POST['number']) && !empty($_POST['number']))){

        if ($_POST['apart']===0) {
            $_SESSION["erreur"] = "Veuillez choisir un appartement valide";
            header('Location: /room_add.php');
        }
        $area = htmlspecialchars($_POST['area']);
        $price = htmlspecialchars($_POST['price']);
        $place = htmlspecialchars($_POST['place']);
        $apartId = htmlspecialchars($_POST['apart']);
        $number = htmlspecialchars($_POST['number']);
        
        $room = new Room;
        $room->setArea($area);
        $room->setPrice($price);
        $room->setPlace($place);
        $room->setApartId($apartId);
        $room->setNumber($number);

        $url = 'http://localhost:5287/Room/Add';

        $result = executeRequest($url,$room->toJson(),true); 

        if ($result->Success==true){
            header('Location: /room_liste.php');
        }else{
            $_SESSION["erreur"] = $result->Error;
        }

    }else {
        $_SESSION['erreur'] = "Veuillez remplir tout les champs du formulaire";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une chambre</title>
</head>

<body>
<div class="login">
<h1 class="text-center titre_login mt-11">Ajouter une chambre</h1>
<div class="overflow-hidden flex items-center justify-center">
  <div class="bg-white lg:w-5/12 md:6/12 w-10/12 shadow-3xl">
    <form class="p-12" action="/room_add.php" method="POST">
        <div class="flex items-center text-lg mb-6">
            <input type="number" id="number" name="number" value="<?=getOldValue("number")?>" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Numéro de la chambre"/>
        </div>
        <div class="flex items-center text-lg mb-6">
            <input type="number" id="area" name="area" value="<?=getOldValue("area")?>" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Taille de la chambre"/>
        </div>
        <div class="flex items-center text-lg mb-6">
            <input type="number" id="price" name="price" value="<?=getOldValue("price")?>" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Prix par nuit de la chambre"/>
        </div>
        <div class="flex items-center text-lg mb-6">
            <input type="number" id="place" name="place" value="<?=getOldValue("place")?>" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Nombre de eplace dans la chambre"/>
        </div>
        <div class="flex items-center text-lg mb-6">
            <select id="apart" name="apart">
                <option value="0" disabled selected> -- Selectionner un appartement -- </option>
                    
                <?php foreach ($aparts as $key => $apart):?>
                    <?php if(getOldValue("place")==$apart->id):?>
                        <option value="<?=$apart->id?>" selected><?=$apart->name?></option>
                    <?php else:?>
                        <option value="<?=$apart->id?>"><?=$apart->name?></option>
                    <?php endif;?>
                <?php endforeach;?>
                
            </select>
        </div>
        <?php if(!empty($_SESSION['erreur'])){?> 
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-3">
            <strong class="font-bold"><?php echo $_SESSION['erreur']; ?></strong>
        </div>
        <?php }?>
        <div class="flex items-center justify-between flex-row-reverse flex-wrap-reverse">
            <button type="submit" class="font-medium p-2 md:p-4 uppercase w-full">Créer une chambre</button>
        </div>
    </form>
  </div>
 </div>
 </div>
</body>

</html>

<?php $_SESSION['erreur'] = "";  ?>
<?php $_SESSION['old'] = "";  ?>
