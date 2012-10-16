<?php
require_once('../_header.php');

#TO DO
# conform that the date format works across the api

#54793

function groups_to_action($groups_KEY, $action_KEY){
	global $db;
	global $DIA;
	
	$x=0;
	$sql='select * from supporter_groups where groups_KEY = "'.$groups_KEY.'"';
	$R = $db->Execute($sql) or die($db->errorMsg());	

	while (!$R->EOF){
		$data['Last_Modified'] = $R->Fields("Last_Modified");
		$data['Date_Created'] = $R->Fields("Last_Modified");
		$data['action_KEY'] = $action_KEY;
		$data['supporter_KEY'] = $R->Fields("supporter_KEY");
	
		$DIA->save( 'supporter_action', $data);
		$x++;
		$R->MoveNext();
	}
	echo $x. ' supporters added to action # '.$action_KEY;
}

function options_action(){
	global $db;
	$sql = 'select * from action order by Date_Created desc';
	$R = $db->Execute($sql) or die($db->errorMsg());	

	while (!$R->EOF){
		echo '<option value='.$R->Fields("action_KEY").'>'.$R->Fields("Reference_Name").'</option>';
		$R->MoveNext();
	}
}




#page controller

if ($_REQUEST['action'] == 'groups'){
	groups_to_action($_REQUEST['groups_KEY'], $_REQUEST['action_KEY']);

}

?>

<h2>Move from group to action</h2>


<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">

<label>Group to move from</label>
<input name="groups_KEY">
<br>
<label>Action to move into</label>
<select name="action_KEY">
<option></option>
<?php echo options_action(); ?>
</select>


<input type="hidden" name="action" value="groups">
<input type="submit" name="submit" value="Move to Action" />
</form>

<?php require_once('_footer.php');?>

