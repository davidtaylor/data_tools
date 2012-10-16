<?php
require_once("_header.php");

if ($_REQUEST['supporter_KEY']){
	$supporter_KEY =$_REQUEST['supporter_KEY'];
}else {
	$supporter_KEY =42703842;
}
$s_sql= 'select * from supporter where supporter_KEY = "'.$supporter_KEY.'"';
$S = $db->Execute($s_sql) or die($db->errorMsg());
$s = $S->FetchRow();


#donations row
$d_sql='select d.* from donation d  where supporter_KEY= "'.$supporter_KEY.'"';
$D = $db->Execute($d_sql) or die($db->errorMsg());
$d = $D->GetArray();


#actions
$a_sql='select sa.*, a.Reference_Name  from supporter_action sa, action a  where a.action_KEY = sa.action_KEY  and sa.supporter_KEY= "'.$supporter_KEY.'"';
$A = $db->Execute($a_sql) or die($db->errorMsg());
$a = $A->GetArray();


#groups
$g_sql='select sg.*, g.Group_Name  from supporter_groups sg, groups g  where g.groups_KEY = sg.groups_KEY  and sg.supporter_KEY= "'.$supporter_KEY.'"';
$G = $db->Execute($g_sql) or die($db->errorMsg());
$g = $G->GetArray();


#emails
$e_sql='select e.*, eb.Reference_Name  from email e, email_blast eb  where e.email_blast_KEY = eb.email_blast_KEY  and e.supporter_KEY="'.$supporter_KEY.'"';
$E = $db->Execute($e_sql) or die($db->errorMsg());
$e = $E->GetArray();


?>


<h3>Summary</h3>

First Name: <?php echo $s['First_Name'] ;?><br>
Last Name: <?php echo $s['Last_Name'] ;?><br>
Email: <?php echo $s['Email'] ;?><br>
RE ID: <?php echo $s['edge_id'] ;?><br>
Receive_Email: <?php echo $s['Receive_Email'] ;?><br>
supporter_KEY: <?php echo $s['supporter_KEY'] ;?><br>
Last_Modified: <?php echo $s['Last_Modified'] ;?><br>
Date_Created: <?php echo $s['Date_Created'] ;?><br>
Soft_Bounce_Count: <?php echo $s['Soft_Bounce_Count'] ;?><br>
Hard_Bounce_Count: <?php echo $s['Hard_Bounce_Count'] ;?><br>
Email_Status: <?php echo $s['Email_Status'] ;?><br>
Last_Bounce: <?php echo $s['Last_Bounce'] ;?><br>
City: <?php echo $s['City'] ;?><br>
State <?php echo $s['State'] ;?><br>
Zip: <?php echo $s['Zip'] ;?><br>
Country: <?php echo $s['Country'] ;?><br>
Source: <?php echo $s['Source'] ;?><br>
Source_Details: <?php echo $s['Source_Details'] ;?><br>
Source_Tracking_Code: <?php echo $s['Source_Tracking_Code'] ;?><br>
Tracking_Code: <?php echo $s['Tracking_Code'] ;?><br>
uid: <?php echo $s['uid'] ;?>



<h3>Emails</h3>
<table>
	<thead>
		<tr>
			<th>Email</th>
		    <th>Status</th>
		    <th>Last Modified</th>
	    </tr>
    </thead>
<?php  foreach($e as $e){ ?>
    <tr>
    	<td><?php echo $e["Reference_Name"]; ?></td>
    	<td><?php echo $e["Status"]; ?></td>
    	<td><?php echo $e["Last_Modified"]; ?></td>
    </tr>
<?php $E->MoveNext(); } ?>
</table>


<h3>Groups</h3>
<table>
	<thead>
		<tr>
			<th>Group</th>
		    <th>Last_Modified</th>
	    </tr>
    </thead>
<?php foreach($g as $g){ ?>
    <tr>
    	<td><?php echo $g["Group_Name"]; ?></td>
    	<td><?php echo $g["Last_Modified"]; ?></td>
    </tr>
<?php } ?>
</table>


<h3>Actions</h3>
<table>
	<thead>
		<tr>
			<th>Action</th>
		    <th>Date_Created</th>
	    </tr>
    </thead>
<?php foreach($a as $a){ ?>
    <tr>
    	<td><?php echo $a["Reference_Name"]; ?></td>
    	<td><?php echo $a["Date_Created"]; ?></td>
    </tr>
<?php } ?>

</table>

<h3>Donations</h3>
<table>
	<thead>
		<tr>
			<th>Amount</th>
			<th>Tracking Code</th>
			<th>RESULT</th>
			<th>Transaction_Type</th>
		    <th>Transaction_Date</th>
	    </tr>
    </thead>
<?php foreach($d as $d){ ?>
    <tr>
    	<td><?php echo $d["amount"]; ?></td>
    	<td><?php echo $d["Tracking_Code"]; ?></td>
    	<td><?php echo $d["RESULT"]; ?></td>
		<td><?php echo $d["Transaction_Type"]; ?></td>
    	<td><?php echo $d["Transaction_Date"]; ?></td>
    </tr>
<?php  } ?>

</table>
<?php require_once('_footer.php');?>
