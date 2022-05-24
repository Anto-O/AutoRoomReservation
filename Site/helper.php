<?php

function executeRequest($url,$data,$post){
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, $post);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    if ($res==false) {
	    return (object)array("Success"=>false, "Error"=>"Erreur lors de l'appel de l'api");
    }
    return json_decode($res);
}