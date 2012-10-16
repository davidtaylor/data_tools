<?php
###########
#
# Creates the export files for upload into RE based on the last time that we downloaded the files
#
#############


include_once('../_header.php');

#vars
#report 59913
$export_KEY = '5635'; 
$report_condition_KEY = '113482';


#report 59914
$rec_export_KEY = '5636'; 
$rec_report_condition_KEY = '113485';

function get_export($export_KEY){
	global $DIA;
	$last = $DIA->get('export', $export_KEY);
	return $last;
}

function download_it($report_condition_KEY,$export_KEY,$date){
	global $DIA;
	# update report with new since date
	$s['value']  = date('Y-m-d H:i:s',strtotime($date));
	$s['key'] = $report_condition_KEY;
	$DIA->save('report_condition', $s ) ;
	#

	#update export table with new request
	$e['Status']  = 'Active';
	$e['key'] = $export_KEY;
	$DIA->save('export', $e ) ;

	
}







$last = get_export($export_KEY);
$rec = get_export($rec_export_KEY);

if ($_GET['action'] == 'donation'){
	download_it($report_condition_KEY,$export_KEY,$last['Last_Run']);
}
if ($_GET['action'] == 'rec'){
	download_it($rec_report_condition_KEY,$rec_export_KEY,$rec['Last_Run']);
}


?>


<h2>Donation Report</h2>

You last downloaded <a href="https://hq-salsa.wiredforchange.com/o/6022/lists/<?php echo $last['Last_Filename']; ?>">this file</a> with <?php echo $last['Last_Linecount']; ?> rows on <?php echo $last['Last_Run']; ?><br>
<a href="export_update.php?action=donation">Click here</a> to request an update be sent to <?php echo $last['Notification_Email']; ?>

<h2>Recurring Donation Report</h2>
You last downloaded <a href="https://hq-salsa.wiredforchange.com/o/6022/lists/<?php echo $rec['Last_Filename']; ?>">this file</a> with <?php echo $rec['Last_Linecount']; ?> rows on <?php echo $rec['Last_Run']; ?><br>
<a href="export_update.php?action=rec">Click here</a> to request an update be sent to <?php echo $rec['Notification_Email']; ?>





<?php require_once('_footer.php') ;?>