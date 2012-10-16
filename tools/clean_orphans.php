<?php
###########
#
# Interface for cleanin up and managing orphans
#
#############

require_once('../_header.php');


$sql="SELECT * FROM supporter as s, supporter_groups as g where s.supporter_KEY= g.supporter_KEY and g.groups_KEY ='54793'";


function orphan_list(){
	global $db;

	#full list
	$sql="SELECT count(s.Source_Details)as s_count, s.Source_Details as Source_Details FROM supporter as s, supporter_groups as g where s.supporter_KEY= g.supporter_KEY and g.groups_KEY ='54793' group by s.Source_Details order by s_count desc;";
	$R = $db->Execute($sql) or die($db->errorMsg());	
	echo '<table class="table table-bordered"><tr><td>count</td><td>source</td><td>action</td></tr>';
	while (!$R->EOF){
		echo '<tr><td>'.$R->Fields("s_count").'</td><td><a href="'.$_SERVER["PHP_SELF"].'?details='.$R->Fields("Source_Details").'">'. $R->Fields("Source_Details").'</a><td><a href="'.$_SERVER["PHP_SELF"].'?groups='.$R->Fields("Source_Details").'">act</a></td></tr>';
	
		$R->MoveNext();
	}
	echo '</table>';
}


function orphan_details($details){
	global $db;
	
	$sql="SELECT s.* FROM supporter as s, supporter_groups as g where s.supporter_KEY= g.supporter_KEY and g.groups_KEY ='54793' and s.Source_Details ='".$details."' order by Last_Modified";
	$R = $db->Execute($sql) or die($db->errorMsg());	

	echo '<table class="table table-bordered"><thead><tr><th></th><th>Email</th><th>Source</th><th>Source Details </th><th>Source Tracking Code</th><th>Tracking Code</th><th>Receive Email </th><th>Date Created </th><th>Last Modified</th></tr> </thead><tbody>
';
	while (!$R->EOF){
		echo '<tr>';
		echo '<td><a href="https://hq-salsa.wiredforchange.com/salsa/hq/p/salsa/supporter/common/hq/edit?table=supporter&key='.$R->Fields("supporter_KEY").'" target="_blank">salsa</a> <a href="salsa_supporter.php?supporter_KEY='.$R->Fields("supporter_KEY").'" target="_supporter">local</a></td>';
		echo '<td>'.$R->Fields("Email").' </td><td>'.$R->Fields("Source").'</td><td>'.$R->Fields("Source_Details").'</td><td>'.$R->Fields("Source_Tracking_Code").'</td><td>'.$R->Fields("Tracking_Code").'</td><td>'.$R->Fields("Receive_Email").' </td><td>'.$R->Fields("Date_Created").' </td><td>'.$R->Fields("Last_Modified").' </td></tr>
';
		$R->MoveNext();
	}
	echo ' </tbody></table>';
}

function move_to_groups($group,$details){
	global $db;
	global $DIA;
	
	$sql="SELECT s.* FROM supporter as s, supporter_groups as g where s.supporter_KEY= g.supporter_KEY and g.groups_KEY ='54793' and s.Source_Details ='".$details."' order by Last_Modified";
	$R = $db->Execute($sql) or die($db->errorMsg());	
	while (!$R->EOF){
		
		$data['supporter_KEY'] = $R->Fields("supporter_KEY");
		$data['groups_KEY'] = $group;
		
		$DIA->save('supporter_groups', $data);
		
		$R->MoveNext();
	}
}

function select_groups($details){

	echo $details.'<br>';
	echo  '<form action="'.$_SERVER["PHP_SELF"].'" method="post">
	<input type="hidden" name="move_to_groups" value="1">
	<input type="hidden" name="details" value="'.$details.'">
	<select name="groups">
		<option value="43070">Weekly</option>
		<option value="47226">Energy</option>
		<option value="47227">Forests</option>
		<option value="43071">Monthly</option>
		<option value="60024">Devo MD or Event</option>
		<option value="55799">Action No Opt In</option>
		<option value="55800">No opt in donation</option>
		<option value="60230">No opt in problem</option>
	</select>
	<input type="submit" name="submit" value="Add to Group" />
	</form>';
}


#controller functions

if ($_REQUEST['details']) {
	orphan_details($_REQUEST['details']);
} else if ($_REQUEST['groups']) {
	select_groups($_REQUEST['groups']);
}  else if ($_REQUEST['move_to_groups']) {
	select_groups($_REQUEST['details']);
} 
 else {
	orphan_list();

}

?>
<?php require_once('_footer.php');?>




