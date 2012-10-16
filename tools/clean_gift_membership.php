<?php
###########
#
# Starts the process for cleaning up the gift memberships 
#
#############


#todo
#make a script that will send a welcome message and add a gift person to a list


require_once('../_header.php');

$sql='select * from donation where In_Honor_Email != "" order by Transaction_Date desc';
$S = $db->Execute($sql) or die($db->errorMsg());
$g = $S->GetArray();

echo '<table>';

foreach ($g as $i){
	$sql = 'select * from supporter where Email = "'.$i['In_Honor_Email'].'" ';
	$E = $db->Execute($sql) or die($db->errorMsg());
	if ($E->Fields("supporter_KEY")){
		$email = $E->Fields("supporter_KEY");
	}	else {
		$email =  $i['In_Honor_Email'];
	}
	
	echo '<tr><td>'.$i['In_Honor_Name'].'</td><td>'.$email.'</td><td>'.$i['In_Honor_Address'].'</td><td>'.$i['Tracking_Code'].'</td><td>'.$i['Note'].'</td><td>'.$i['Transaction_Date'].'</td></tr>';
	
}
echo '</table>';

?>