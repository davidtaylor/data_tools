<?php
require_once('_header.php');

function report_unsubs($days=NULL){
	if ($days) {
		$days_where = ' and `Unsubscribe_Date` >= curdate() - INTERVAL DAYOFWEEK(curdate())+'.$days.' DAY ';
		$full_days_where = ' where  '.$days_where ;
	}

	$sql = 'select count(supporter_KEY) as count from unsubscribe  '.$full_days_where ;
	$R = $db->Execute($sql) or die($db->errorMsg());
	$total = $R->FetchRow();

	$sql = 'select count(supporter_KEY) as count from unsubscribe where Unsubscribe_Method = "Hard Bounce Count" '.$days_where ;
	$R = $db->Execute($sql) or die($db->errorMsg());
	$bounce = $R->FetchRow();
	
	$sql = 'select count(supporter_KEY) as count from unsubscribe where Unsubscribe_Method = "Web Page" '.$days_where ;
	$R = $db->Execute($sql) or die($db->errorMsg());
	$web = $R->FetchRow();
	
	$sql = 'select count(supporter_KEY) as count from unsubscribe where Unsubscribe_Method = "Email" '.$days_where ;
	$R = $db->Execute($sql) or die($db->errorMsg());
	$email = $R->FetchRow();

	$o['total'] = $total['count'];
	$o['bounce'] = $bounce['count'];
	$o['web'] = $web['count'];
	$o['email'] = $email['count'];
	$o['days'] = $days;
	
	return $o;

}

function report_new($days=NULL){
	if ($days) {
		$days_where = ' and Date_Created >= curdate() - INTERVAL DAYOFWEEK(curdate())+'.$days.' DAY ';

#need to add if they are also on a list of any sort and how opt ins work

		$full_days_where = ' where  '.$days_where ;
	}

	$sql = 'select count(supporter_KEY) as count from supporter  '.$full_days_where ;
	$R = $db->Execute($sql) or die($db->errorMsg());
	$total = $R->FetchRow();

	$sql = 'select count(supporter_KEY) as count from supporter where source_type_category = "Web"  '.$days_where ;
	$R = $db->Execute($sql) or die($db->errorMsg());
	$web = $R->FetchRow();

	$sql = 'select count(supporter_KEY) as count from supporter where source_type_category = "Action"  '.$days_where ;
	$R = $db->Execute($sql) or die($db->errorMsg());
	$action = $R->FetchRow();

	$sql = 'select count(supporter_KEY) as count from supporter where source_type_category = "Donation"  '.$days_where ;
	$R = $db->Execute($sql) or die($db->errorMsg());
	$donation = $R->FetchRow();

	$sql = 'select count(supporter_KEY) as count from supporter where source_type_category = "Data"  '.$days_where ;
	$R = $db->Execute($sql) or die($db->errorMsg());
	$data = $R->FetchRow();
	
	$sql = 'select count(supporter_KEY) as count from supporter where source_type_category = "Field"  '.$days_where ;
	$R = $db->Execute($sql) or die($db->errorMsg());
	$field = $R->FetchRow();

	$o['total'] = $total['count'];
	$o['action'] = $action['count'];
	$o['web'] = $web['count'];
	$o['donation'] = $donation['count'];
	$o['data'] = $data['count'];
	$o['field'] = $field['count'];

	$o['days'] = $days;

}



?>
<h1>What's all this about</h1>
<p>Toys, tools and fun with data</p>

<h2>Fun With Data</h2>
<ul>
	<li><a href="/phpmyadmin">phpmyadmin</a></li>

</ul>

<h2>Data Dashboard</h2>
<ul>

	<li>Days since last RE import</li>
	<li>Days since last de duplication</li>
	<li>New source codes</li>
	<li>New tracking codes</li>
	<li>objects with no campaigns</li>
	<li>orphan count</li>
	<li>are there are unprocessed gift memberships</li>

</ul>

<h2>Online Program</h2>
	<li>new sources</li>
	<li>unsubs in last week/month vs new supporters on a list las week or month</li>
	<li>how most recent emails are doing</li>
	<li>how most recent actions are doing</li>
	<li>reason for last unsubs</li>
	<li>donations bys sources and type</li>
	<li>donations to date vs goals</li>

<?php require_once('_footer.php');

?>
