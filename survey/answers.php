<?php

require_once('../_header.php');

function get_person($supporter_KEY){
	global $db;
	if ($supporter_KEY){
		
		$sql = 'select * from supporter where supporter_KEY = '.$supporter_KEY;
		$S = $db->Execute($sql) or die($sql.$db->errorMsg());
		$s = $S->FetchRow();
		return $s;
	}
}


function free_form($field,$where=NULL){
	global $db;
	$sql = "select * from results where ".$field." != '' ".$where;
	$S = $db->Execute($sql) or die($db->errorMsg());
	$s = $S->GetArray();
	
	foreach ($s as $i){
		$s = get_person($i['Custom_Data']);
		echo $i[$field].' <a href="https://hq.salsalabs.com/salsa/hq/supporter/edit.jsp?table=supporter&key='.$i['Custom_Data'].'" target="_blank">view</a> '.$s['First_Name'].' '.$s['Last_Name'].'<br><br>';
	}
}

$opens = array(
	'Why_do_you_support_RAN___Open_Ended_Response',
	'Work_Other',
	'Describe_Other',
	'Info_Other',
	'Any_other_thoughts_on_RANs_email_communications',
	'Why_do_you_support_RAN___Open_Ended_Response',
	'Connect_Other',
	'Is_there_anything_else_you_would_like_to_share_with_us'
);

$conditions_array = array(
	'Major Donor'=>'and CollectorID != "0" and MajorD =1 ',
	'Online'=>' and CollectorID = "28933038" ',
	'Donors'=>'and CollectorID = "28941647" ',
	'All'=>'and CollectorID != "0" ',
	'Non Profits'=>' and CollectorID != "0" and Describe_Involved_with_other_non_profits != "" ',
	'Rabble'=>' and CollectorID != "0" and Describe_Street_activist_rabble_rouser != "" ',
	'Frontline'=>' and CollectorID != "0" and Describe_Frontline_impacted_community_member != "" ',
	'Business'=>' and CollectorID != "0" and Describe_Business_person != "" ',
	'New'=>' and CollectorID != "0" and Date_Created > "2012-01-01"',
	'Old'=>' and CollectorID != "0" and Date_Created < "2010-01-01"',
	'Facebook'=>' and CollectorID = "28933038"'

);

	$where_online = 'and CollectorID = "28933038" ';
	$where_donors = 'and CollectorID = "28941647" ';
	$where_all = 'and CollectorID != "0" ';
	$where_np = 'and CollectorID != "0" and Describe_Involved_with_other_non_profits != "" ';
	$where_rabble= 'and CollectorID != "0" and Describe_Street_activist_rabble_rouser != "" ';
	$where_frontline= 'and CollectorID != "0" and Describe_Frontline_impacted_community_member != "" ';
	$where_busines= 'and CollectorID != "0" and Describe_Business_person != "" ';
	$where_md= 'and CollectorID != "0" and MajorD =1 ';
	$where_new= 'and CollectorID != "0" and Date_Created > "2012-01-01" ';
	$where_old= 'and CollectorID != "0" and Date_Created < "2010-01-01"';


echo '<ul>';
foreach ($conditions_array as $type => $where) {
	echo '<li><a href="answers.php?type='.$type.'">'.$type.'</a></li>';
}
echo '</ul>';


if ($_REQUEST['type']){
	$where = $conditions_array[$_REQUEST['type']];
	echo 'where statement: '.$where;
}

foreach ($opens as $i){
	echo '<h1>'.$i.'</h1>';
	free_form($i,$where);
}

?>