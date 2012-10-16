<?php

require_once('../_header.php');


$sql= 'select * from re_sync ';
$R = $db->Execute($sql) or die($db->errorMsg());	
$r= $R->GetArray();
$x=0;
foreach($r as $i){
	$d['key'] = $i['supporter_KEY'];
	$d['edge_id'] = $i['edge_id'];

	$DIA->save('supporter',$d);
	$x++;
}
echo $x;

require_once('_footer.php');

?>