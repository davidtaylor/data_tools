<?php
require_once('../_header.php');


function action_list($limit=50, $options=NULL){
	global $db;
	
	if ($options['campaign']){
		$where = ' where campaign= "'.$options['campaign'].'" ';
	}
	if ($options['action_type']){
		$where = ' where action_type= "'.$options['action_type'].'" ';
	}
	if (($options['action_type']) && ($options['campaign'])){
		$where = ' where action_type= "'.$options['action_type'].'" and campaign = "'.$options['campaign'].'"  ';
	}
		
	$sql ='select * from action '.$where.' order by action_KEY desc  LIMIT '.$limit;
	$A = $db->Execute($sql) or die($db->errorMsg());
	$a = $A->GetArray();
	
	
	# add the drop downs for type and campaign filter
	$o .='<form action="" method="post"></form>';
	$o = '<table class="table">';
	$o .= ' <thead><tr>';
	$o .= '<th>Name</th>';
	$o .= '<th>Total</th>';
	$o .= '<th>Distinct</th>';
	$o .= '<th>New </th>';
	$o .= '<th>New Deliverable</th>';
	$o .= '<th>Last Week</th>';
	$o .= '<th>New Last Week</th>';
	$o .= '<th></th>';

	$o .= '</tr></thead><tbody>';

	foreach ($a as $a){
		$options= NULL;
		$options = array();
		$o.= '<tr>';
		$o.= '<td><a href="'.$_PHP['SELF'].'?action_KEY='.$a['action_KEY'].'">'.$a['Reference_Name'].'</a></td>';
		
		$o.= '<td>'.action_count($a['action_KEY']).'</td>';
		$o.= '<td>'.action_count_distinct($a['action_KEY']).'</td>';

		$options['slug'] = $a['campaign_slug'];
		$o.= '<td>'.action_count_new($a['action_KEY'],$options).'</td>';

		$options['on_list'] = TRUE;
		$o.= '<td>'.action_count_new($a['action_KEY'],$options).'</td>';

		$options['last_week'] = TRUE;
		$options['on_list'] = FALSE;
		
		$o.= '<td>'.action_count_distinct($a['action_KEY'],$options).'</td>';

		$o.= '<td>'.action_count_new($a['action_KEY'],$options).'</td>';
		$o.= '<td><a href="http://ran.org/act/'.$a['campaign_slug'].'" target="_blank"><i class="icon-eye-open"></i></a><a href="http://act.ran.org/p/dia/action/public/?action_KEY='.$a['action_KEY'].'" target="_blank"><i class="icon-globe"></i></a><a href="https://hq.salsalabs.com/salsa/hq/p/dia/action/hq/edit?object=action&key='.$a['action_KEY'].'" target="_blank"><i class="icon-edit"></i></a><a href="https://hq.salsalabs.com/salsa/hq/p/dia/action/hq/report?action_KEY='.$a['action_KEY'].'" target="_blank"><i class="icon-inbox"></i></a></td>';

		$o.= '</tr>';
	}

	$o .= '</tbody></table>';

	return $o;

}

function action_count_distinct($action_KEY,$options=NULL){
	global $db;
	
	if ($options['last_week'] == TRUE){
		$date_condition = ' and Date_Created >= "'.date("Y-m-d H:i:s", strtotime("-1 week")).'" '; 
	}
	
	$sql = 'select COUNT(distinct supporter_KEY) as count from supporter_action where action_KEY = "'.$action_KEY.'" '.$date_condition;
	$R = $db->Execute($sql) or die($db->errorMsg());
	$r = $R->FetchRow();
	return $r['count'];
	
}

function action_count($action_KEY,$options=NULL){
	global $db;

	if ($options['last_week'] == TRUE){
			$date_condition = ' and Date_Created >= "'.date("Y-m-d H:i:s", strtotime("-1 week")).'" '; 
	}	
	$sql = 'select COUNT(supporter_KEY) as count from supporter_action where action_KEY = "'.$action_KEY.'" '.$date_condition;
	$R = $db->Execute($sql) or die($db->errorMsg());
	$r = $R->FetchRow();
	return $r['count'];
	
}

function action_count_new($action_KEY,$options=NULL){
	global $db;

	if ($options['last_week'] == TRUE){
		$date_condition = ' and Date_Created >= "'.date("Y-m-d H:i:s", strtotime("-1 week")).'" '; 
	}	
	
	$sql = 'select COUNT(supporter_KEY) as count from supporter where Source_Details LIKE "http://act.ran.org/p/dia/action/public/?action_KEY='.$action_KEY.'%" '.$date_condition;
	
	if ($options['on_list'] == TRUE){
		$sql = 'select COUNT(s.supporter_KEY) as count from supporter as s, supporter_groups as g where s.Source_Details LIKE "http://act.ran.org/p/dia/action/public/?action_KEY='.$action_KEY.'%"  and g.groups_KEY = 54801 and g.supporter_KEY = s.supporter_KEY'.$date_condition;
	}
	
	
	$R = $db->Execute($sql) or die($db->errorMsg());
	$r = $R->FetchRow();
	
	if ($r['count'] == 0 && ($options['slug'])){
		$sql = 'select COUNT(supporter_KEY) as count from supporter where Source_Details LIKE "http://ran.org/act/'.$options['slug'].'%" '.$date_condition;
		
		#for supporters that are currently on a list
		if ($options['on_list'] == TRUE){
			$sql = 'select COUNT(s.supporter_KEY) as count from supporter as s, supporter_groups as g where s.Source_Details LIKE "http://ran.org/act/'.$options['slug'].'%" and g.groups_KEY = 54801 and g.supporter_KEY = s.supporter_KEY'.$date_condition;
		}
		$R = $db->Execute($sql) or die($db->errorMsg());
		$r = $R->FetchRow();		
		
	}
	
	return $r['count'];
	
}

function action_referrer($key,$options=NULL){
	global $db;

	$sql ='select distinct referrer from supporter_action where action_KEY ='.$key;
	$A = $db->Execute($sql) or die($db->errorMsg());
	$a = $A->GetArray();
	$x=0;
	foreach($a as $i){
		$sql = 'select COUNT(distinct supporter_KEY) as count from supporter_action where action_KEY = '.$key.' and referrer = "'.$i['referrer'].'" '.$date_condition;
		$R = $db->Execute($sql) or die($db->errorMsg());
		$r = $R->FetchRow();			
		$out[$x]['count'] = $r['count'];
		$out[$x]['referrer'] = $i['referrer'];
		$x++;
		
	}
	
	return $out;
}

function action_partners($key,$options=NULL){
	global $db;

	$sql ='select distinct partner_code from supporter_action where action_KEY ='.$key;
	$A = $db->Execute($sql) or die($db->errorMsg());
	$a = $A->GetArray();
	
	$x=0;
	foreach($a as $i){
		$sql = 'select COUNT(distinct supporter_KEY) as count from supporter_action where action_KEY = '.$key.' and partner_code = "'.$i['partner_code'].'" '.$date_condition;
		$R = $db->Execute($sql) or die($db->errorMsg());
		$r = $R->FetchRow();			
		$out[$x]['count'] = $r['count'];
		$out[$x]['partner_code'] = $i['partner_code'];
		$x++;
	}
	
	return $out;
}

function action_referrer_supporter($key,$options=NULL){
	global $db;

	$sql = 'select COUNT(referrer_supporter_KEY) as count from supporter_action where action_KEY = '.$key.' and referrer_supporter_KEY != 0 '.
	$R = $db->Execute($sql) or die($db->errorMsg());
	$r = $R->FetchRow();
	$out = $r['count'];
	return $out;
}

function action_emails($key,$options=NULL){
	global $db;

	$sql ='select distinct email_blast_KEY from supporter_action where action_KEY ='.$key;
	$A = $db->Execute($sql) or die($db->errorMsg());
	$a = $A->GetArray();
	
	$x=0;
	foreach($a as $i){
		$sql = 'select COUNT(distinct supporter_KEY) as count from supporter_action where action_KEY = '.$key.' and email_blast_KEY = "'.$i['email_blast_KEY'].'" '.$date_condition;
		$R = $db->Execute($sql) or die($db->errorMsg());
		$r = $R->FetchRow();
		
		$sql = 'select e.Reference_Name, s.* from email_blast as e, email_blast_statistics as s where e.email_blast_KEY = s.email_blast_KEY and e.email_blast_KEY = "'.$i['email_blast_KEY'].'" ';
		$E = $db->Execute($sql) or die($db->errorMsg());
		$e = $E->FetchRow();
		
		$out[$x]['count'] = $r['count'];
		$out[$x]['email_blast_KEY'] = $i['email_blast_KEY'];
		$out[$x]['Reference_Name'] = $e['Reference_Name'];
		$out[$x]['Total_Emails'] = $e['Total_Emails'];
		$out[$x]['Emails_Opened'] = $e['Emails_Opened'];
		$out[$x]['Emails_Clicked'] = $e['Emails_Clicked'];
		$out[$x]['Unsubscribe_Percentage'] = $e['Unsubscribe_Percentage'];
		$out[$x]['Unsubscribes'] = $e['Unsubscribes'];
		$x++;
	}
	
	return $out;
}



function action_detail($key){
	global $db;
	
	$sql ='select * from action where action_KEY ='.$key;
	$A = $db->Execute($sql) or die($db->errorMsg());
	$a = $A->FetchRow();

	echo 'Actions Taken: '. action_count($key). '<br>';
	echo 'Unique Actions Taken: '. action_count_distinct($key). '<br>';

	$options['slug'] = $a['campaign_slug'];
	echo 'New Supporters: '. action_count_new($key,$options). '<br>';
	#echo 'Supporter Referrals: '. action_referrer_supporter($key,$options). '<br>';


	$emails = action_emails($key);
	$ref = action_referrer($key);
	$partners = action_partners($key);
	
	
	echo '<h3>Emails</h3>';
	echo '<table class="table"><tr><td>Email</td><td>Count</td><td>Emails Sent</td><td>Emails Opened</td><td>Emails Clicked</td><td>Completed</td><td>Unsub %</td><td>Unsubs</td></tr>';

	foreach($emails as $i){
		if ($i['email_blast_KEY']){
			echo '</tr>';
			echo '<td>'.$i['Reference_Name'] .'</td>';
			echo '<td>'.$i['count'] .'</td>';
			echo '<td>'.$i['Total_Emails'] .'</td>';
			echo '<td>'.$i['Emails_Opened'] .'</td>';
			echo '<td>'.$i['Emails_Clicked'] .'</td>';
			echo '<td>'.($i['count']/($i['Emails_Clicked']- $i['Unsubscribes'])) .'</td>';
	
			echo '<td>'.$i['Unsubscribe_Percentage'] .'</td>';
			echo '<td>'.$i['Unsubscribes'] .'</td>';
			echo '</tr>';
		}
	}
	echo '</table>';

	echo '<h3>Referrer</h3><br>';
	echo '<table class="table"><tr><td></td><td>Count</td></tr>';
	foreach($ref as $i){
			if ($i['referrer']){
			echo '<tr>';
			echo '<td>'.$i['referrer'] .'</td>';
			echo '<td>'.$i['count'] .'</td>';	
			echo '</tr>';
	}
	}
	echo '</table>';
	
	echo '<h3>Partners</h3><br>';
	echo '<table class="table"><tr><td></td><td>Count</td></tr>';
	foreach($partners as $i){
		if ($i['partner_code']){
			echo '<tr>';
			echo '<td>'.$i['partner_code'] .'</td>';
			echo '<td>'.$i['count'] .'</td>';	
			echo '</tr>';
		}
	}
	echo '</table>';
	
	echo '<h2>Action Links</h2>';
	
	action_links($a['campaign_slug']);
}

function action_links($slug){
	
	echo '
	
	Email<br>
	http://ran.org/act/'.$slug.'/?e=[[email_blast_KEY]]&t=f&u=[[supporter_KEY]]
	<br><br>
	Twitter<br>
	http://ran.org/act/'.$slug.'/?t=t
	<br><br>
	Facebook<br>
	http://ran.org/act/'.$slug.'/?t=t
	<br><br>
	Understory<br>
	http://ran.org/act/'.$slug.'/?t=u
	<br><br>
	RAN.org Drupal<br>
	http://ran.org/act/'.$slug.'/?t=r
	<br><br>
	Email Trigger<br>
	http://ran.org/act/'.$slug.'/?&t=a&r=[[supporter_KEY]]
	<br><br>
	Email Trigger - FB Link<br>
	http://ran.org/act/'.$slug.'/?&t=f&r=[[supporter_KEY]]
	<br><br>
	Email Trigger - Twitter Link<br>
	http://ran.org/act/'.$slug.'/?&t=t&r=[[supporter_KEY]]
	<br><br>
	Partner<br>
	http://ran.org/act/'.$slug.'/?t=p&pc=PARTNER_CODE
	<br><br>
	You Tube<br>
	http://ran.org/act/'.$slug.'/?t=y
	
	';
}


function email_list($limit=50, $offset=NULL){

}

$options['action_type'] = $_REQUEST['action_type'];
$options['campaign'] = $_REQUEST['campaign'];
if ($_REQUEST['count']) {$count = $_REQUEST['count'];} else {$count = 50;}



if ($_REQUEST['action_KEY']){
	action_detail($_REQUEST['action_KEY']);
} else {
	echo action_list($count,$options);

}


;
echo '<pre>';
#print_r($ref);
echo '</pre>';

require_once('_footer.php');


?>



