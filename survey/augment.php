<?php
require_once('../header.php');

$sql= 'select * from results where Custom_Data !=0';
$R = $db->Execute($sql) or die($db->errorMsg());
$r = $R->GetArray();

	foreach ($r as $i){
		$sql= 'select * from supporter where supporter_KEY = '.$i['Custom_Data'];
		$S = $db->Execute($sql) or die($db->errorMsg());
		$s = $R->FetchRow();
		if ($s['Date_Created']){
			$d['Date_Created'] = $s['Date_Created'];
			$db->AutoExecute('results',$d,'UPDATE', ' Custom_Data = "'.$i['Custom_Data'].'"');
		}
	}

	foreach ($r as $i){
		$sql= 'select * from supporter_groups where groups_KEY = 60633 and supporter_KEY = '.$i['Custom_Data'];
		$S = $db->Execute($sql) or die($db->errorMsg());
		$s = $R->FetchRow();
		if ($s['supporter_groups_KEY']){
			$d['MajorD'] = TRUE;
			$db->AutoExecute('results',$d,'UPDATE', ' Custom_Data = "'.$i['Custom_Data'].'"');
		}
	}

	foreach ($r as $i){
		$sql= 'SELECT distinct supporter_KEY FROM donation where recurring_donation_KEY !=''and  supporter_KEY = '.$i['Custom_Data'];
		$S = $db->Execute($sql) or die($db->errorMsg());
		$s = $R->FetchRow();
		if ($s['supporter_KEY']){
			$d[' Monthly'] = TRUE;
			$db->AutoExecute('results',$d,'UPDATE', ' Custom_Data = "'.$i['Custom_Data'].'"');
		}
	}


?>