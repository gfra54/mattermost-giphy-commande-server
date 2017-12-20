<?php
if(!$_SERVER['REQUEST_SCHEME']) {
	$_SERVER['REQUEST_SCHEME']='https';
}

if($q = $_GET['text']) {
	$url = 'https://api.giphy.com/v1/gifs/search?api_key=ZmkZAA15r7XsYDxj7cmvMZkVdPtsiPfl&q='.$q;
	$data = file_get_contents($url);
	if($data = json_decode($data,true)) {
		$gifs = array();
		foreach($data['data'] as $gif) {
			if(count($gifs) < 3) {
				$gifs[] = $gif['embed_url'];
			}
		}
		header('Content-Type: application/json');
		$payload = array(
			'username'=>'Giphy',
			'icon_url'=> $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/giphy/giphy.png',
			'text'=>'Recherche Giphy pour "'.$q.'"',
		);
		echo json_encode($payload);
	}
}
