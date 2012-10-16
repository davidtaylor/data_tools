<?php
require_once('../_header.php');


function scrape_it(){
	global $db;
	
	$sql='select * from scrape_pages';
	$P = $db->Execute($sql) or die($sql.$db->errorMsg());
	$items = $P->GetArray();
	
	foreach ($items as $i ){
		$html = file_get_contents($i['url']);
		$data['contents'] = $html;
		$data['url'] = $i['url'];
		$db->AutoExecute('scrape', $data, 'INSERT'); 
	
	}
	echo 'Pages Scraped';
}

function scrape_show_list(){
	global $db;
	$sql='select * from scrape_pages';
	$P = $db->Execute($sql) or die($sql.$db->errorMsg());
	$p = $P->GetArray();
	echo '<table>';
	foreach ($p as $i){
		echo '<tr><td>'.$i['url'].'</td><td><a href="screen_scrape.php?action=remove_site&id='.$i['id'].'">del</a></td></tr>';
	}
	echo '</table>';

}

function scrape_remove_site($id){
	global $db;
	$sql='delete from scrape_pages where id = '.$id;
	$db->Execute($sql) or die($sql.$db->errorMsg());
	echo 'Page Deleted<br>';
}

function scrape_add_site($url){
	global $db;
	$data['url'] = $url;
	$db->AutoExecute('scrape_pages', $data, 'INSERT'); 
	echo 'Site Added<br>';
}


function scrape_show_pages(){
	global $db;
	$sql='select * from scrape order by id desc';
	$S = $db->Execute($sql) or die($sql.$db->errorMsg());
	$s = $S->GetArray();
	
	echo '<table>';
	foreach ($s as $i){
		echo '<tr><td>'.$i['url'].'</td><td>'.$i['Date_Created'].'</td><td><a href="screen_scrape.php?action=show_page&id='.$i['id'].'">view</a></td></tr>';
	}
	echo '</table>';

}

function scrape_show_page($id){
	global $db;
	
	$sql='select * from scrape where id='.$id;
	$S = $db->Execute($sql) or die($sql.$db->errorMsg());
	$s = $S->FetchRow();
	
	echo $s['contents'];
}



switch($_REQUEST['action']){
	case 'show_page':
		scrape_show_page($_REQUEST['id']);
		break;
	case 'scrape':
		scrape_it();
		break;
	case 'remove_site':
		scrape_remove_site($_REQUEST['id']);
		break;
	case 'add_site':
		scrape_add_site($_REQUEST['url']);
		break;
	case 'show_pages':
		echo '<h2>Scraped Pages</h2>';
		scrape_show_pages();
		break;


}


?>
<h2>Sites to Scrape</h2>
<?php scrape_show_list(); ?>
<br><br>
<h2>Add Site To Scrape</h2>
<form action="<?php echo $_SERVER['SELF'] ;?>" method="post">
<label>Add URL to Scrape</label><input name="url">
<input type="hidden" value="add_site" name="action">
<input type="submit" value="submit">
</form>

<a href="screen_scrape.php?action=show_pages">Show scraped pages</a>
<br><br>
<a href="screen_scrape.php?action=scrape">Scrape Pages Now</a>
