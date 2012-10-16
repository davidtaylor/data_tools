<?php
require_once('../_header.php');


$email_type = array(
	'Action',
	'Newsletter',
	'Donation Appeal',
	'Event',
	'Update',
	'Kicker',
	'Welcome',
	'Other'

);

$donate_page_type = array(
	'Web',
	'Email',
	'Event',
	'Action',
	'EOY',
	'Other'
);

$action_type = array(
	'Petition',
	'Target',
	'Call',
	'Sign Up',
	'Content Creation',
	'Other'
);


function update_campaign($table,$key,$campaign,$items ){
	global $db, $DIA;
	$x=0;
	
	foreach($items as $i){
		$d['campaign'] = $campaign;
		$d['key']= $i;
		$where= $key.'='.$i;
		print_r($d);
		$db->AutoExecute($table,$d,'UPDATE',$where);
		$out = $DIA->save($table,$d);
		$x++;
	}
	echo '<br>'.$out. ' items updated';

}

function update_type($table,$key,$type,$items,$type_name ){
	global $db, $DIA;
	$x=0;
	
	foreach($items as $i){
		$d[$type_name] = $type;
		$d['key']= $i;
		$where= $key.'='.$i;
		print_r($d);
		$db->AutoExecute($table,$d,'UPDATE',$where);
		$out = $DIA->save($table,$d);
		$x++;
	}
	echo '<br>'.$out. ' items updated';

}

function display_campaign_list($table,$key){
	global $db,$type_options,$type,$object_type;
	
	if ($object_type){
		$object_type_sql = ', '.$object_type;
		$object_type_order_sql = $object_type . ' ASC, ';

	}
		
	$sql = 'select Reference_Name, campaign, '.$key.' as key_name  '.$object_type_sql.' from '.$table.' order by campaign asc, '.$object_type_order_sql.' Reference_Name   ASC';
	
	echo $sql;
	$S = $db->Execute($sql) or die($sql.$db->errorMsg());
	$r = $S->GetArray();
	
	
	echo '<form >';
	echo '<input type="hidden" name="type" value="'.$type.'">';
	echo '<select name="campaign"><option value="">Select Campaign</option>'.campaign_options().'</select>';
	if ($type_options) {
		echo '<select name="object_type"><option value="">Select Type</option>';
		
		foreach($type_options as $i){
			echo '<option>'.$i.'</option>';
		}
		echo '</select>';
	
	}
	echo '<input type="submit">';
	echo '<table class="xtable xtable-bordered"><thead><tr><th></th><th></th><th>Name</th><th>Campaign</th><th>type</th></tr> </thead><tbody>';
	
	foreach($r as $i){
		echo '<tr><td><input name="key[]" type="checkbox" value="'.$i['key_name'].'"></td><td>'.$i['key_name'].'</td><td>'.$i['Reference_Name'].'</td><td>'.$i['campaign'].'</td><td>'.$i[$object_type].'</td></tr>';
	}

	echo '</table></form>';
}







if ($_REQUEST['type']){
	$type = $_REQUEST['type'];
} else {
	$type= 'action';
}


if ($type == 'action'){
	$table = 'action';
	$table_key = 'action_KEY';
	$object_type ='action_type';
	$type_options= $action_type;
	$url= 'http://act.ran.org/p/dia/action/public/?action_KEY=';	
}

if ($type == 'email'){
	$table = 'email_blast';
	$table_key = 'email_blast_KEY';
	$object_type ='email_type';
	$type_options= $email_type;
	$url= 'https://salsa.wiredforchange.com/o/6022/t/0/blastContent.jsp?email_blast_KEY=';	

}

if ($type == 'donation'){
	$table = 'donate_page';
	$table_key = 'donate_page_KEY';
	$object_type = 'donate_page_type';
	$type_options= $donate_page_type;

}

if ($type == 'tell'){
	$table = 'tell_a_friend';
	$table_key = 'tell_a_friend_KEY';
}

if ($type == 'sign'){
	$table = 'signup_page';
	$table_key = 'signup_page_KEY';
}






if ($_REQUEST['key'] && $_REQUEST['campaign']){
	update_campaign($table,$table_key,$_REQUEST['campaign'],$_REQUEST['key']);
}

if ($_REQUEST['key'] && $_REQUEST['object_type']){
	update_type($table,$table_key,$_REQUEST['object_type'],$_REQUEST['key'],$object_type);
}


?>

<a href="<?php $_SERVER['PHP_SELF']?>?type=action">Action</a> | 
<a href="<?php $_SERVER['PHP_SELF']?>?type=email">Emails</a> | 
<a href="<?php $_SERVER['PHP_SELF']?>?type=tell">Tell A Friend Pages</a> | 
<a href="<?php $_SERVER['PHP_SELF']?>?type=sign">Sign Up Pages</a> | 
<a href="<?php $_SERVER['PHP_SELF']?>?type=donation">Donation</a> | 

<?php display_campaign_list($table,$table_key); ?>
