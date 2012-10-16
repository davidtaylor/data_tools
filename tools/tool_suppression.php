<?php

require_once('../_header.php');

function source_list(){
	global $db;

	$sql='select distinct source from suppression_file';
	$R = $db->Execute($sql) or die($db->errorMsg());	
	while (!$R->EOF){
		$sql='select count(id) as source_match from suppression_file where source = "'.$R->Fields("source").'" and supporter_KEY != 0';
		$M = $db->Execute($sql) or die($db->errorMsg());	
	
		$sql='select count(id) as source_count from suppression_file where source = "'.$R->Fields("source").'" ';
		$S = $db->Execute($sql) or die($db->errorMsg());	
	
		echo '<option value="'.$R->Fields("source").'">'.$R->Fields("source").'- '.$M->Fields("source_match").' matches of '.$S->Fields('source_count').' total</option>';
		
		$R->MoveNext();
	}

}

function suppression_groups(){
	global $DIA;
	$options = array('condition' => 'parent_KEY=59096');
	$g = $DIA->get('groups',$options);
	foreach ($g as $group){
		echo '<option value="'.$group['groups_KEY'].'">'.$group['Group_Name'].'</option>';
	}	

}

function add_group($Group_Name,$parent_KEY=NULL){
	global $DIA;
	$data['Group_Name'] = $Group_Name;
	$data['parent_KEY'] = $parent_KEY;
	
	$groups_KEY = $DIA->save( 'groups', $data);
	echo 'Group ID#'.$groups_KEY . ' added'; 
}


function add_to_salsa($source,$group){
	global $DIA;
	global $db;
	
	$x=0;
	
	$sql='select * from suppression_file where source="'.$source.'" and supporter_KEY != 0' ;
	$R = $db->Execute($sql) or die($db->errorMsg());	
	while (!$R->EOF){
		$data['supporter_KEY'] = $R->Fields("supporter_KEY");
		$data['groups_KEY'] = $group;
	
		$DIA->save( 'supporter_groups', $data);
		$x++;

		$R->MoveNext();
	}
	
	echo $x . ' added'; 

}

function match_source($source){
	global $db;
	
	$sql='select * from suppression_file where source="'.$source.'"';
	$x=0;
	$y=0;
	
	$R = $db->Execute($sql) or die($db->errorMsg());
	
	while (!$R->EOF){
		$sql ='select supporter_KEY from supporter where Email ="'.$R->Fields("Email").'"';
		$E = $db->Execute($sql) or die($db->errorMsg());
		$email = $E->Fields("supporter_KEY");
		$x++;
		
		if ($email) {
			$data['supporter_KEY'] = $E->Fields("supporter_KEY");
			$where = 'id = "'.$R->Fields("id").'"';
			$db->AutoExecute('suppression_file',$data, 'UPDATE',$where) or die($db->errorMsg());
			$y++;
		}
		
		$R->MoveNext();
	}
	echo $y. ' matched of ' . $x. ' total records';

}



###page controller 

if ($_REQUEST['action'] == 'match'){
	match_source($_REQUEST['source']);
}

if ($_REQUEST['action'] == 'add'){
	add_to_salsa($_REQUEST['source'],$_REQUEST['group']);
}

if ($_REQUEST['action'] == 'add_group'){
	add_group($_REQUEST['Group_Name'],$_REQUEST['parent_KEY']);
}

?>

<h2>Let's Make Suppression List</h2>
<br>

<a href="tool_suppression_upload.php">First thing to do is to upload a new match file </a><br><br>

<h4>Next thing is to match the suppression file to the salsa db</h4>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="form-inline">
<select name="source">
	<option>Select Source to Match to Salsa</option>
	<?php source_list() ?>
</select>
<input type="hidden" name="action" value="match">
<input type="submit" name="submit" value="Match" />
</form>


<h4>Next, create a group in Salsa for suppression matches</h4>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">

<label>New Salsa Group Name:</label>
<input name="Group_Name" value="Online: Swap: Suppression File: " size="50">
<input type="hidden" name ="parent_KEY" value="59096">
<input type="hidden" name="action" value="add_group">
<input type="submit" name="submit" value="Create New Group" />
</form>
<br>

<h4>Now you can add all those matched to Salsa</h4>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<select name="source">
	<option>Select Source to add to Salsa</option>
	<?php source_list() ?>
</select>
<select name="group">
	<option>Select Group to add to in Salsa</option>
	<?php suppression_groups() ?>
</select>

<input type="hidden" name="action" value="add">
<input type="submit" name="submit" value="Add To Salsa" />
</form>
<?php require_once('_footer.php');?>

