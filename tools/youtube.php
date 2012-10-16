<?php


function get_youtube($batches){
	$x=0;
	$yt= array();
	
	while ($x <= $batches){
		if ($x ==0 ){
			$off =1;
			$yt = youtube_offset($off);
		} else {
			$off = ($x*25)+1;
			$data= youtube_offset($off);
			$yt = array_merge($yt,$data);
		}
		
		$x++;
	}
	return $yt;
}
	
function youtube_offset($offset){
	$jsonurl = 'https://gdata.youtube.com/feeds/api/users/ranvideo/uploads?v=2&alt=jsonc&%20&start-index='.$offset;
	$json = file_get_contents($jsonurl,0,null,null);
	
	$v = json_decode($json);
	
	$x = 0;
	foreach($v->data->items as $i){
		$o[$x]['id'] =$i->id;
		$o[$x]['title'] = $i->title;
		$o[$x]['description'] = $i->description;
		$x++;
	}
	
	return $o;
	
}



function get_flickr(){
	$jsonurl = 'http://api.flickr.com/services/rest/?method=flickr.photosets.getList&api_key=636a6afb01db7089334b86e4fb2a5e8d&user_id=38705147%40N00&format=json&nojsoncallback=1&auth_token=72157631215064726-e17fcdb0b95d0bdb&api_sig=4f3477b3c46523370a53792ca924f7d1';
	$json = file_get_contents($jsonurl,0,null,null);
	
	$v = json_decode($json);
	
	$x = 0;
	foreach($v->photosets->photoset as $i){
		$o[$x]['id'] =$i->id;
		$o[$x]['title'] = $i->title->_content;
		$o[$x]['description'] = $i->description->_content;
		$x++;
	}
	
	return $o;
	
}



$yt = get_youtube(5);
$flickr= get_flickr();

?>
<pre>
<?php print_r($yt); ?>
<?php print_r($flickr); ?>
</pre>
