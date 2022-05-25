<?php

function executeRequest($url,$data,$post){

    // créer un objet curl
    $ch = curl_init();

    // rajoute l'option URL
    curl_setopt($ch,CURLOPT_URL, $url);

    // rajoute l'option POST à la requete
    curl_setopt($ch,CURLOPT_POST, $post);

    // rajoute le contenu de la requete
    curl_setopt($ch,CURLOPT_POSTFIELDS, $data);

    // on sait pas
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

    // execute la requete et recupère les données retournées
    $res = curl_exec($ch);
    if ($res==false) {
	    return (object)array("Success"=>false, "Error"=>"Erreur lors de l'appel de l'api");
    }
    
    // retourne sous forme d'objet php les valeurs retournées en json
    return json_decode($res);
}