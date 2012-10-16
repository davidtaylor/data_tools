<?php
###########
#
# Report for seeing all of the most recent donations
#
#############
include_once('../_header.php');



$d['Date_Created'] = '2011-01-13 14:03:27';
$d['Last_Modified'] = '2012-01-13 14:03:27';


$d['Last_Name'] = 'Taylor';
$d['key'] = '42703842';

$DIA->save('supporter', $d);

$o =$DIA->get('supporter', $d['key']);
echo 'done';

print_r($o);

?>