<?php
require_once("../_header.php");



if (($_REQUEST['referral_code']) && ($_REQUEST['valid_referral_code'])){
	update_referral($_REQUEST['referral_code'], $_REQUEST['valid_referral_code']);
}

add_missing_referrals();
list_referrals();


function list_referrals(){
	global $db;
	$sql= 'select * from referral_map order by new desc';
	$S = $db->Execute($sql) or die($db->errorMsg());
	$r = $S->GetArray();

	echo '<table class="table table-bordered"><thead><tr><th>referral code</th><th>valid code</th><th>new</th><th></th></tr> </thead><tbody>';

	
	

		
	foreach ($r as $i){
		echo '<tr><td>'.$i['referral_code'].'</td><td>'.$i['valid_referral_code'].'</td><td>'.$i['new'].'</td>';
		echo'<td><form action="'.$_SERVER['PHP_SELF'].'" method="POST"><input type="text" name="valid_referral_code"><input type="hidden" name="referral_code" value="'.$i['referral_code'].'"><input type="submit"></form></td>';
		
		echo '</tr>';
	}

	echo '</table>';

}

function add_missing_referrals(){
	global $db;
	
	$x=0;
	
	$sql= 'select distinct tracking_code as name from supporter_action';
	$T = $db->Execute($sql) or die($db->errorMsg());
	$t = $T->GetArray();

	$sql= 'select distinct referrer as name from supporter_action';
	$R = $db->Execute($sql) or die($db->errorMsg());
	$r = $R->GetArray();
	
	foreach ($t as $i){

		if (!in_referral_map($i['name']) && ($i['name'] != '')){
			$d['referral_code']= $i['name'];
			$d['new'] = 1;
			$db->AutoExecute('referral_map',$d, 'INSERT')or die($db->errorMsg());
			$x++;
		}
	}

	foreach ($r as $i){

		if (!in_referral_map($i['name']) && ($i['name'] != '')){
			$d['referral_code']= $i['name'];
			$d['new'] = 1;
			$db->AutoExecute('referral_map',$d, 'INSERT')or die($db->errorMsg());
			$x++;
		}
	}

	echo $x.' new codes<br>';
}


function in_referral_map($name){
	global $db;
	$sql ="select * from referral_map where referral_code = '".$name."' ";
	$S = $db->Execute($sql) or die($db->errorMsg());
	$s = $S->FetchRow();
	
	if ($s['referral_code']){
		return TRUE;
	}
}

function update_referral($referral_code, $valid_referral_code){
	global $db;
	$d['referral_code'] = $referral_code;
	$d['valid_referral_code'] = $valid_referral_code;
	$d['new'] = 0;
	
	$db->AutoExecute('referral_map',$d,'UPDATE', 'referral_code = "'.$referral_code.'"');
	echo $referral_code . ' updated';

}


?>