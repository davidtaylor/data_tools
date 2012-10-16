<?php
require_once('../_header.php');

if ($_REQUEST['action'] == 'sync_supporter'){

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, MONSTERPANTS_SERVER.'/sync/'.$_REQUEST['supporter_KEY']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$contents = curl_exec ($ch);
	curl_close ($ch);	

}

if ($_REQUEST['action'] == 'sync_object'){

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, MONSTERPANTS_SERVER.'/'.$_REQUEST['object'].'/'.$_REQUEST['date']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$contents = curl_exec ($ch);
	curl_close ($ch);	
	

}

if ($_REQUEST['action'] == 'sync_all'){

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, MONSTERPANTS_SERVER.'/sync_all/'.$_REQUEST['date']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$contents = curl_exec ($ch);
	curl_close ($ch);	

}


echo $contents;
?>


<h2>Sync Supporter</h2>
	<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
		<input type="hidden" name="action" value="sync_supporter">
		Supporter KEY: <input name="supporter_KEY" value="">
		<input type="submit">
	</form>
<h2>Sync all Objects</h2>
	<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
		<input type="hidden" name="action" value="sync_object">
		Object: <input name="object" value="">
		Date: <input name="date" value="">
		<input type="submit">
	</form>

<h2>Sync an Object</h2>
	<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
		<input type="hidden" name="action" value="sync_all">
		Date: <input name="date" value="">
		<input type="submit">
	</form>