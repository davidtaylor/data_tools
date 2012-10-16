<?php
require_once('_header.php');


function supporter_array_db($supporter_KEY){

	global $db;

	$sql = "select * from supporter where supporter_KEY = ".$supporter_KEY;
	$S = $db->Execute($sql) or die($db->errorMsg());
	$s = $S->FetchRow();
	
	#groups
	$sql = 'select * from supporter_groups where supporter_KEY = '.$supporter_KEY;
	$G = $db->Execute($sql) or die($db->errorMsg());
	$groups = $G->GetArray();
	
	foreach ($groups as $key => $i) {
		$sql= 'select groups_KEY, Group_Name, parent_KEY from groups where groups_KEY = '. $i['groups_KEY'];
		$GD = $db->Execute($sql) or die($db->errorMsg());
		$group_details = $GD->FetchRow();
		$groups[$key]['group']= $group_details;
	}
	/*  NOT YET IN THE MONSTERPANTS DB
	#tags
	$sql = 'select * from tag_data where database_table_KEY=142 and table_key = '.$supporter_KEY;
	$T = $db->Execute($sql) or die($db->errorMsg());
	$tags = $T->GetArray();
	
	foreach ($tags as $key => $i) {
		$sql= 'select tag_KEY, prefix, tag, label from tag where tag_KEY = '. $i['tag_KEY'];
		$TD = $db->Execute($sql) or die($db->errorMsg());
		$tag_details = $TD->FetchRow();
		$tags[$key]['tag']= $tag_details;
	}
	*/
	
	#actions
	$sql= 'select * from supporter_action where supporter_KEY = '. $supporter_KEY;
	$A = $db->Execute($sql) or die($db->errorMsg());
	$actions = $A->GetArray();
	foreach ($actions as $key => $i) {
		$sql= 'select action_KEY,Reference_Name from action where action_KEY = '. $i['action_KEY'];
		$AD = $db->Execute($sql) or die($db->errorMsg());
		$action_details = $AD->FetchRow();
		$actions[$key]['action']= $action_details;
	}
	
	#emails
	$sql= 'select * from email where supporter_KEY = '. $supporter_KEY;
	$E = $db->Execute($sql) or die($db->errorMsg());
	$emails = $E->GetArray();
	foreach ($emails as $key => $i) {
		$sql= 'select email_blast_KEY,Subject,Reference_Name,Date_Requested from email_blast where email_blast_KEY = '. $i['email_blast_KEY'];
		$EB = $db->Execute($sql) or die($db->errorMsg());
		$email_details = $EB->FetchRow();
		$emails[$key]['email']= $email_details;
	}
	
	
	#donations
	$sql= 'select * from donation where supporter_KEY = '. $supporter_KEY;
	$D = $db->Execute($sql) or die($db->errorMsg());
	$donations = $D->GetArray();
	
	
	$s['groups'] = $groups;
	$s['tags'] = $tagss;
	$s['actions'] = $actions;
	$s['emails'] = $emails;
	$s['donations'] = $donations;

	return $s;
}

function supporter_array_salsa($supporter_KEY){

	global $DIA;
	

	$options= array(
		'condition' => array(
		    'table_key='.$supporter_KEY,
		    'database_table_KEY=142'
		  )
	);
	$tags = $DIA->get('tag_data', $options);
	
	
	$options= array(
		'condition' => array(
		    'supporter_KEY='.$supporter_KEY
		  )
	);
	$groups = $DIA->get('supporter_groups', $options);

	$options= array(
		'condition' => array(
		    'supporter_KEY='.$supporter_KEY
		  ),
		#'limit'=>'10',
		'orderBy' => 'Last_Modified DESC',
		 'include' => 'action_KEY,Date_Created'

	);
	$actions = $DIA->get('supporter_action', $options);
	foreach ($actions as $key => $a){
		$options= array(
			'condition' => array(
			    'action_KEY='.$a['action_KEY']
			  ),
		'limit'=>'1',
		'include' => 'Reference_Name'
		);
		$action_details= $DIA->get('action', $options);
		$actions[$key]['action']= $action_details[0];
	}

	
	$options= array(
		'condition' => array(
		    'supporter_KEY='.$supporter_KEY
		  ),
		#'limit'=>'10',
		'orderBy' => 'Last_Modified DESC'
	);
	$emails = $DIA->get('email', $options);
	
	foreach ($emails as $key => $e){
		$options= array(
			'condition' => array(
			    'email_blast_KEY='.$e['email_blast_KEY']
			  ),
		'limit'=>'1',
		'include' => 'Subject,Reference_Name,Date_Requested'
		);
		$emails_blast= $DIA->get('email_blast', $options);
		$emails[$key]['email_blast']= $emails_blast[0];
	}
	
	
	
	$options= array(
		'condition' => array(
		    'supporter_KEY='.$supporter_KEY
		  ),
 		'include' => 'amount,Tracking_Code,RESULT,Transaction_Date'

	);
	$donations = $DIA->get('donation', $options);
	
	
	$s = $DIA->get('supporter', $supporter_KEY);
	
	$s['groups'] = $groups;
	$s['tags'] = $tags;
	$s['actions'] = $actions;
	$s['emails'] = $emails;
	$s['donations'] = $donations;
	
	return $s;
}

$supporter_KEY = 42703842;

#$s = supporter_array_salsa($supporter_KEY);
$s = supporter_array_db($supporter_KEY);

?>
<pre>
<?php 
print_r($s);

?>
</pre>