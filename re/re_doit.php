<?php 

require_once('../_header.php');

if ($_REQUEST['action'] == 'match'){
	match_emails();
}
if ($_REQUEST['action'] == 'new'){
	they_are_all_new();
}
if ($_REQUEST['action'] == 'salsa'){
	salsa_new_supporter();
	salsa_update_supporter();
}
if ($_REQUEST['action'] == 'delete'){
	delete_everything();
}

$status = re_sync_status()


?>
<h2>Sync Status</h2>
<pre>
<?php echo print_r($status); ?>
</pre>

<h2>Lets do that syncing</h2>

<a href="re_upload.php" >First we upload a new data file</a><br><br>



<a href="re_sync_ids.php">Next we look for at all people with an id map and we hand check the differences.</a>
<br><br>

<a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=match" >Next we look for emails that match and have no map and we map them</a>
<br><br>

<a href="re_sync_names.php" >Next we look for all the people that have names that match and we hand check the differences</a>
<br><br>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=new" >Next we mark everyone left as new</a>
<br><br>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=salsa" >Next we add all the new names to salsa</a>
<br><br>
<a href="re_export_results.php" target="_blank">Next we export the map for RE.</a>
<br><br>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=delete" target="_blank">Last we delete all the data</a>

<?php require_once('_footer.php');?>
