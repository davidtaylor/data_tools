<?php
require_once("connect.php");

function search_person($term){

	global $db;
	
	$return_arr = array();
	
	$sql= "SELECT supporter_KEY,   Concat(First_Name, ' ', Last_Name,', ', Email)as fullname FROM supporter
WHERE CONCAT( First_Name,  ' ', Last_Name, ' ',Email ) LIKE  '%".$term."%'";
	$S = $db->Execute($sql) or die($db->errorMsg());
	$s = $S->GetArray();

	foreach  ($s as $s){
		$row_array['id'] = $s['supporter_KEY'];
        $row_array['value'] = $s['fullname'];
         
        array_push($return_arr,$row_array);
	}
	$out = $return_arr;
	$out =json_encode($return_arr);
	return $out;
}

function search_person2($term){

	global $db;
	
	$return_arr = array();
	
	$sql= "SELECT    Concat(First_Name, ' ', Last_Name,', ', Email)as fullname FROM supporter
WHERE CONCAT( First_Name,  ' ', Last_Name, ' ',Email ) LIKE  '%".$term."%'";
	$S = $db->Execute($sql) or die($db->errorMsg());
	$s = $S->GetArray();

	foreach  ($s as $s){
		$row_array[] = $s['fullname'];
    #   # $row_array['value'] = $s['fullname'];
         
       # array_push($return_arr,$row_array);
	}
	$out = $return_arr;
	$out =json_encode($row_array);
	return $out;
}



if ($_REQUEST['term']){
	echo search_person2($_REQUEST['term']);
	#print_r(search_person($_REQUEST['term']));
}
?>