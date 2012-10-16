<?php
###########
#
# Report for seeing all of the most recent donations
#
#############
include_once('../_header.php');


if ($_GET['date']) {$date=$_GET['date'];}
else {
$date = date("Y-m-d", strtotime("-1 month"));


}
if ($_GET['rec']== TRUE){
$recu = 'Recurring ';
$options = array('report_condition_KEY' => '111428','condition' =>$date);
$r = $DIA->report('59176',$options);
$e= "&rec=1";
$recvar='<input type="hidden" value ="1" name="rec">';

} else {

$options = array('report_condition_KEY' => '111430','condition' =>$date);
$r = $DIA->report('53934',$options);


}


function mark_donation($key){
	global $DIA;
	$data['Thank_You_Sent'] = '1';
	$data['key'] = $key;
	$DIA->save('donation', $data);
	echo 'done';
}


function display_line($f,$opt=NULL){
	if ($f){
		$out = $f;
		if ($opt == 'nobr'){
			$out .= ' ';
		} else 
			$out .= '<br>';
	}
	return $out;	
}


if ($_REQUEST['action'] ){
	
	
	mark_donation($_REQUEST['key']);
	

}


if (!$_REQUEST['id']) {

	
	$x=0;
	$out = "<h1>".$recu."Donation Report since ".$date."</h1>

";
$out .= '<form action="donation_report.php" method="get">'.$recvar.'<input type="text" value="'.$date.'" name="date"><input type="submit" value="Change Date Range"></form>';
$out .= "
<p><a href='https://hq-salsa.wiredforchange.com/dia/hq/export.jsp?report_KEY=53934&type=xls'>Download Excel</a></p><table class='maintable'>" ;
	$out .= '<tr class="headerrow"><td></td><td>Amount</td><td>Name</td><td>Date</td><td>Status</td><td>Code</td><td>Entered</td></tr>';	

	
	foreach ($r as $v){
		if ($v['RESPMSG'] != "Approved"){
			$color = 'class= "redrow" ';
		} else { $color = '';}
			
		$out .= '<tr '.$color.'><td><a href="donation_report.php?id='.($v['donation_KEY']).$e.'">view</a></td>';
		$out .=  '<td>'.$v['Amount'].'</td>';
		$out .=  '<td>'.$v['FirstName'].' '.$v['LastName'].'</td>';
		$out .=  '<td>'.$v['Transaction_Date'].'</td>';
		$out .=  '<td>'.$v['RESPMSG'].'</td>';
		$out .=  '<td>'.$v['Tracking_Code'].'</td>';
		$out .=  '<td>'.$v['donate_page_KEY'].'</td>';

		$out .=  '</tr>';

		$x++;
	}
	$out .= "</tr></table>" ;

	echo $out;

} else {
	
	$i =$DIA->get_object('donation', $_REQUEST['id']);
	$per =$DIA->get_object('supporter', $i['supporter_KEY']);	

	$o .= "<h1>Donation Report</h1><table border=1 cellpadding=5>";
	$o .= "<tr><td>Supporter<br><br><a href='https://hq-salsa.wiredforchange.com/salsa/hq/p/salsa/supporter/common/hq/edit?table=supporter&key=".$i['supporter_KEY']."'>view in salsa</a></td><td>";
	
	$o .= display_line($per['First_Name'],'nobr');
	$o .= display_line($per['Last_Name']);
	$o .= display_line($per['Street']);
	$o .= display_line($per['Street2']);
	$o .= display_line($per['City'],'nobr');
	$o .= display_line($per['State'],'nobr');
	$o .= display_line($per['Zip']);
	$o .= display_line($per['Country']);
	$o .= display_line($per['Phone']);
	$o .= display_line($per['Email']);
	$o .= display_line($per['Organization']);
	$o .= 'Salsa ID: '.display_line($per['supporter_KEY']);


	
	$o .="</td></tr>";

	
	$o .= "<tr><td>Donation Info:<br><br><a href='https://hq-salsa.wiredforchange.com/dia/hq/edit?table=donation&key=".$i['donation_KEY']."'>view in salsa</a></td><td>";
	
	$o .= display_line($i['amount']);
	$o .= display_line($i['Transaction_Date']);
	$o .= display_line($i['RESPMSG']);
	$o .= 'Source: '.display_line($i['Tracking_Code']);

	if ($i['donate_page_KEY']) { 
		$dp =$DIA->get_object('donate_page', $i['donate_page_KEY']);	
		$o .= 'Donation Page: '. $dp['Reference_Name'].'<br>';
	}

	$o .= display_line($i['Credit_Card_Digits'],'nobr');
	$o .= display_line($i['Credit_Card_Expiration'], 'nobr');
	$o .= display_line($i['cc_type']);


	$o .="</td></tr>";
	

	$o .= "<tr><td>More Donation Info:</td><td>";
	$o .= display_line($i['Note']);
        $o .= display_line($i['In_Memory_Name']);
        $o .= display_line($i['In_Honor_Name']);
	$o .= display_line($i['In_Honor_Email']);
	$o .= display_line($i['In_Honor_Address']);
	$o .= display_line($i['VARCHAR0']);
	$o .= display_line($i['VARCHAR1']);
	$o .= display_line($i['VARCHAR2']);
	$o .= display_line($i['AUTHCODE']);
	$o .= display_line($i['PNREF']);
	$o .= display_line($i['AVS']);
	$o .= display_line($i['Status']);
	
	$o .="</td></tr>";

if ($i['recurring_donation_KEY']){
	
	$rd =$DIA->get_object('recurring_donation', $i['recurring_donation_KEY']);	

	$o .= "<tr><td>Reccuring Donation Info:</td><td>";
	$o .= $rd['PAYPERIOD'].'<br>';
	$o .= $rd['TRXPNREF'].'<br>';
	$o .= $rd['PROFILEID'].'<br>';
	$o .= $rd['Start_Date'].'<br>';
	$o .="</td></tr>";

}
	$o .= "</table>";

	$o .= 'Entered: '.$i['Thank_You_Sent'].'<br>';

$o .= '<form action="donation_report.php" method="post">
<input type="hidden" value="mark_donation" name="action" />
<input type="hidden" value="'.$i['donation_KEY'].'" name="key" />
<input type="hidden" value="donation_report.php" name="redirect" />
<input type="submit" value="Mark As Entered" />
</form>';


echo $o;
	

}
include_once('_footer.php');


?>


