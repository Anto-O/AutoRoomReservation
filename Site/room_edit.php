<?php 
require('config.php');
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

if(!empty($_GET)) {
    if((isset($_GET['id']) && !empty($_GET['id']))) {
        $id = $_GET['id'];

        $url = 'http://localhost:5287/Room/Get?Id='.$_GET['id'];
        $resultGet = executeRequest($url,'',false);
        
        if ($resultGet->Success == false) {
            $_SESSION['erreur'] =  $result->Error;
            header('Location: room_liste.php');
            die;
        }else {
            $room = $resultGet->Content;
            $url = 'http://localhost:5287/Apartment/GetAll';
            $result = executeRequest($url,"",false);

            if ($result->Success==true) {
                $aparts = $result->Content;
            }else{
                $_SESSION["erreur"] = $result->Error;
                header("Locatin: admin.php");
            }
        }
    }else{
        $_SESSION["error"] = "Aucune chambre sélectionner";
        header('Location: /room_liste.php');
    }
}else{
    $_SESSION["error"] = "Aucune chambre sélectionner";
    header('Location: /room_liste.php');
}
if (!empty($_POST)) {
    $_SESSION["old"] = $_POST;
    if((isset($_POST['number']) && !empty($_POST['number'])) && 
        (isset($_POST['price']) && !empty($_POST['price'])) &&
        (isset($_POST['place']) && !empty($_POST['place'])) &&
        (isset($_POST['area']) && !empty($_POST['area'])) &&
        (isset($_POST['apart']) && !empty($_POST['apart']))){
      
        $area = htmlspecialchars($_POST['area']);
        $price = htmlspecialchars($_POST['price']);
        $place = htmlspecialchars($_POST['place']);
        $apartId = htmlspecialchars($_POST['apart']);
        $number = htmlspecialchars($_POST['number']);
        $id = $_POST['id'];
        
        $room = new Room;
        $room->setArea($area);
        $room->setPrice($price);
        $room->setPlace($place);
        $room->setApartId($apartId);
        $room->setNumber($number);
        $room->setId($id);


        $url = 'http://localhost:5287/Room/Update';

        $result = executeRequest($url,$room->toJson(),true); 

        if ($result->Success==true) {
            header('Location: /room_liste.php');
        }else{
            $_SESSION["erreur"] = $result->Error;
            header('Location: /room_edit.php?id='.$id);
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
    <title>modifier une chambre</title>
</head>

<body>
<div class="login">
<h1 class="text-center titre_login mt-11">Modifier une chambre</h1>
<div class="overflow-hidden flex items-center justify-center">
  <div class="bg-white lg:w-5/12 md:6/12 w-10/12 shadow-3xl">
    <form class="p-12" action="/room_edit.php?id=<?=$room->id?>" method="POST">

    <input value="<?=$room->id?>" type="hidden" id="id" name="id" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Id"/>

    
    <div class="flex items-center text-lg mb-6">
        <input type="number" id="number" name="number"  class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Numéro de la chambre" value="<?= empty(getOldValue("number"))? $room->number:getOldValue("number")?>"/>
    </div>
    <div class="flex items-center text-lg mb-6">
        <input type="number" id="area" name="area" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Taille de la chambre" value="<?= empty(getOldValue("area"))? $room->area:getOldValue("area")?>"/>
    </div>
    <div class="flex items-center text-lg mb-6">
        <input type="number" id="price" name="price" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Prix par nuit de la chambre" value="<?= empty(getOldValue("price"))? $room->price:getOldValue("price")?>"/>
    </div>
    <div class="flex items-center text-lg mb-6">
        <input type="number" id="place" name="place" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Nombre de eplace dans la chambre" value="<?= empty(getOldValue("place"))? $room->place:getOldValue("place")?>"/>
    </div>
    <div class="flex items-center text-lg mb-6">
        <select id="apart" name="apart">
            <option value="0" disabled selected> -- Selectionner un appartement -- </option>
                
            <?php foreach ($aparts as $key => $apart):?>
                <?php if($apart->id==$room->apartId):?>
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
        <button type="submit" class="font-medium p-2 md:p-4 button_register uppercase w-full">Modifier</button>
      </div>
    </form>
  </div>
 </div>
 </div>
</body>

</html>

<?php $_SESSION['erreur'] = "";  ?>
<?php $_SESSION['old'] = "";  ?>
