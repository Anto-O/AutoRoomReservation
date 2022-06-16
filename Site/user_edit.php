<?php 
require('config.php');
require('User.php');


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

        $url = 'http://localhost:5287/User/Get?Id='.$_GET['id'];
        $resultGet = executeRequest($url,'',false);

        if ($resultGet->Success == false) {
            $_SESSION['erreur'] =  $result->Error;
            header('Location: user_liste.php');
            die;
        }else {
            $user = $resultGet->User;
        }
    }else{
        $_SESSION["error"] = "Aucun utilisateur sélectionner";
        header('Location: /user_liste.php');
    }
}else{
    $_SESSION["error"] = "Aucun utilisateur sélectionner";
    header('Location: /user_liste.php');
}
if (!empty($_POST)) {
    $_SESSION["old"] = $_POST;
    if((isset($_POST['id']) && !empty($_POST['id'])) && 
        (isset($_POST['firstName']) && !empty($_POST['firstName'])) &&
        (isset($_POST['lastName']) && !empty($_POST['lastName'])) &&
        (isset($_POST['email']) && !empty($_POST['email'])) &&
        (isset($_POST['password']) && !empty($_POST['password'])) &&
        (isset($_POST['phone']) && !empty($_POST['phone'])) &&
        (isset($_POST['birthDate']) && !empty($_POST['birthDate'])) &&
        (isset($_POST['nationality']) && !empty($_POST['nationality']))){
      
        $firstName = htmlspecialchars($_POST['firstName']);
        $lastName = htmlspecialchars($_POST['lastName']);
        $email = htmlspecialchars($_POST['email']);
        $phone = htmlspecialchars($_POST['phone']);
        $birthDate = htmlspecialchars($_POST['birthDate']);
        $nationality = htmlspecialchars($_POST['nationality']);
        $password = $_POST['password'];
        $id = $_POST['id'];
        
        $user = new User;
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setEmail($email);
        $user->setPhone($phone);
        $user->setBirthdate($birthDate);
        $user->setNationality($nationality);
        $user->setPassword($password);
        if ((isset($_POST['admin']) && !empty($_POST['admin'])) && $_POST['admin']==1) {
            $admin = true;
        }else{
            $admin = false;
        }
        $user->setAdmin($admin);

        $user->setId($id);


        $url = 'http://localhost:5287/User/Update';

        $result = executeRequest($url,$user->toJson(),true); 

        if ($result->Success==true) {
            header('Location: /user_liste.php');
        }else{
            $_SESSION["erreur"] = $result->Error;
            header('Location: /user_edit.php?id='.$id);
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
    <title>modifier un utilisateur</title>
</head>

<body>
<div class="login">
<h1 class="text-center titre_login mt-11">Modifier un utilisateur</h1>
<div class="overflow-hidden flex items-center justify-center">
  <div class="bg-white lg:w-5/12 md:6/12 w-10/12 shadow-3xl">
    <form class="p-12" action="/user_edit.php?id=<?=$user->Id?>" method="POST">

        <input value="<?=$user->Id?>" type="hidden" id="id" name="id" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Id"/>
        <input value="<?=$user->password?>" type="hidden" id="password" name="password" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Id"/>
        
        <div class="flex items-center text-lg mb-6">
            <input type="text" id="firstName" name="firstName"  class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Prenom" value="<?= empty(getOldValue("firstName"))? $user->firstname:getOldValue("firstName")?>"/>
        </div>
        <div class="flex items-center text-lg mb-6">
            <input type="text" id="lastName" name="lastName" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Nom de famille" value="<?= empty(getOldValue("lastName"))? $user->lastname:getOldValue("lastName")?>"/>
        </div>
        <div class="flex items-center text-lg mb-6">
            <input type="email" id="email" name="email" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Email" value="<?= empty(getOldValue("email"))? $user->email:getOldValue("email")?>"/>
        </div>
        <div class="flex items-center text-lg mb-6">
            <input type="text" id="phone" name="phone" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Teelephone" value="<?= empty(getOldValue("phone"))? $user->phone:getOldValue("phone")?>"/>
        </div>
        <div class="flex items-center text-lg mb-6">
            <input type="date" id="birthDate" name="birthDate" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="Date de naissance" value="<?= empty(getOldValue("birthDate"))? $user->BirthDate:getOldValue("birthDate")?>"/>
        </div>
        <div class="flex items-center text-lg mb-6">
            <input type="text" id="nationality" name="nationality" class="bg-gray-200 pl-1 py-2 md:py-4 focus:outline-none w-full" placeholder="nationalité" value="<?= empty(getOldValue("nationality"))? $user->nationality:getOldValue("nationality")?>"/>
        </div>
        <div class="flex items-center text-lg mb-6">
            <input type="checkbox" id="admin" name="admin" checked="<?= (empty(getOldValue("admin"))? $user->admin:getOldValue("admin"))==true?"true":"false"?>" value="<?= (empty(getOldValue("admin"))? $user->admin:getOldValue("admin"))==true?"1":"0"?>" onclick="if (this.checked) this.value=1; else this.value=0;" />
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
