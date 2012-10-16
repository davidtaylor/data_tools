<?php


require_once('../_header.php');


if ($_REQUEST['winner'] == 'salsa'){
	winner($_REQUEST['re'],$_REQUEST['s'],$_REQUEST['salsa_email'],$_REQUEST['Receive_Email']);
} else if ($_REQUEST['winner'] == 're'){
	winner($_REQUEST['re'],$_REQUEST['s'],$_REQUEST['re_email'],1);
} else if ($_REQUEST['winner'] == 'new'){
	new_salsa($_REQUEST['re']);
} else if ($_REQUEST['winner'] == 'ignore'){
	ignore($_REQUEST['re']);
}


check_names('id');



?>
<?php require_once('../_footer.php');?>