<?php
require_once("_header.php");

#$sql="SELECT  distinct Source_Tracking_Code, count(supporter_KEY) FROM `supporter` WHERE Source_Details = "(No Referring info)" group by Source_Tracking_Code";

#$sql="SELECT  distinct Source_Tracking_Code, count(supporter_KEY) FROM `supporter` WHERE Source_Details = "" group by Source_Tracking_Code";

#$sql="SELECT  distinct Source_Details, count(supporter_KEY) FROM `supporter` where  Source_Tracking_Code = '' group by Source_Details";

$sql='SELECT distinct Source_Details FROM `supporter` WHERE Source_Tracking_Code = "(No Original Source Available)" order by Source_Details asc  ';
$S = $db->Execute($sql) or die($db->errorMsg());
$s = $S->GetArray();

foreach ($s as $i){
	echo $i['Source_Details'].'<br>';
	#$url = parse_url($i['Source_Details']);
	#$queryFields = split('[;&]', $url['query']);

#	foreach ($queryFields as $v){
#	}

#	echo '<pre>'.print_r($queryFields).'</pre>';


}

?>