<?php
$ABS_PATH = '/web/online.ran.org/docs/api/';

if (!$MP) {

require_once($ABS_PATH.'adodb/adodb.inc.php');
$db = NewADOConnection('mysql');
$db->Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);


require_once($ABS_PATH.'democracyinaction-php/api.php');

$node = SALSA_NODE;
$user = SALSA_USER;
$password = SALSA_PASS;
$DIA = new DemocracyInAction_API( $node, $user, $password);

}


?>
