<?php
require_once('../../adodb/toexport.inc.php');
require_once('../connect.php');


header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');

    $rs = $db->Execute('select * from re_update');

 
    print rs2csv($rs); # return a string, CSV format
  

?>

