<?php

if(!$_SERVER['REQUEST_SCHEME']) {
	$_SERVER['REQUEST_SCHEME']='https';
}
$q = $_POST['text'];
if(!$q) {
	$q = $_GET['text'];
}


$user_id = $_POST['user_id'];
if(!$user_id) {
	$user_id = $_GET['user_id'];
}


define('URL_SITE',$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/giphy/');

if($user_id) {
	$userfile='./users/'.$user_id;
	$payload=false;
	if($q) {
		$url = 'https://api.giphy.com/v1/gifs/search?api_key=ZmkZAA15r7XsYDxj7cmvMZkVdPtsiPfl&q='.$q;
		$data = file_get_contents($url);
		if($data = json_decode($data,true)) {
			$rand = array_rand($data['data']);
			$gif = $data['data'][$rand];
			$image = '![]('.$gif['images']['preview_gif']['url'].' =?x100 "'.$gif['title'].'")';

			$payload = array (
				'response_type'=>'ephemeral',
				'username'=>'Giphy',
				'icon_url'=> URL_SITE.'giphy.png',
				'text'=>'Recherche Giphy pour "**'.$q.'**"'."\n".$image."\n".' Si vous voulez envoyer ce gif, entrez la commande `/gif` . Sinon, relancez `/gif '.$q.'`',
			);
			file_put_contents($userfile,json_encode($gif));
		
		}
	} else {
		if(file_exists($userfile) && time() - filemtime($userfile) < 180) {
			$gif = json_decode(file_get_contents($userfile),true);
			$image = '![]('.$gif['images']['preview_gif']['url'].' "'.$gif['title'].'")';
			$payload = array (
				'response_type'=>'in_channel',
				'icon_url'=> URL_SITE.'giphy.png',
				'text'=>$image,
			);
		}
		unlink($userfile);
	}


	if($payload) {
		header('Content-Type: application/json');

		echo json_encode($payload);
	}
}

