<?php

require_once("../_header.php");

function display_new_supporters($date){
	global $db;
	
	$sql= 'select * from supporter where Date_Created >= "'.$date.'"';
	$S = $db->Execute($sql) or die($db->errorMsg());
	$s = $S->GetArray();
	
	$o = '<table>';
	$o .= ' <thead><tr>';
	$o .= '<th>Name</th>';
	$o .= '<th>Source Details</th>';
	$o .= '<th>Source Tracking Code</th>';
	$o .= '<th>Tracking Code</th>';
	$o .= '<th>Source</th>';
	$o .= '<th>email status</th>';

	


	$o .= '</tr></thead><tbody>';

	foreach ($s as $s){
		$o.= '<tr>';
		$o.= '<td>'.$s['First_Name'].' '.$s['First_Name'].'</td>';
		
		$o.= '<td>'.$s['Source_Details'].'</td>';
		$o.= '<td>'.$s['Source_Tracking_Code'].'</td>';
		$o.= '<td>'.$s['Tracking_Code'].'</td>';
		$o.= '<td>'.$s['Source'].'</td>';
		$o.= '<td>'.$s['Receive_Emails'].'</td>';

		$o.= '</tr>';
	}

	$o .= '</tbody></table>';

	return $o;


}

function display_new_supporters_source($date,$field){
	global $db;
	
	$sql='select count(supporter_KEY) as source_count, '.$field.' from supporter where Date_Created >= "'.$date.'" group by '.$field.' order by source_count desc';
	$S = $db->Execute($sql) or die($db->errorMsg());
	$s = $S->GetArray();

echo $sql;
	
	$o = '<table class="table table-bordered table-striped">';
	$o .= ' <thead><tr>';

	$o .= '<th>Source</th>';
	$o .= '<th>Count</th>';
	$o .= '<th>Deliverable</th>';
	$o .= '<th>Deliverable and List</th>';



	$o .= '</tr></thead><tbody>';

	foreach ($s as $s){
	
		$sql='select count(s.supporter_KEY) as source_count, s.'.$field.' from supporter s where  s.Date_Created >= "'.$date.'" and s.'.$field.'="'.$s[$field].'" and s.Receive_Email > 0 ';
		$D = $db->Execute($sql) or die($db->errorMsg());
		$d = $D->FetchRow();
		
		$sql='select count(s.supporter_KEY) as source_count, s.'.$field.' from supporter s, supporter_groups g where s.supporter_KEY=g.supporter_KEY and s.Date_Created >= "'.$date.'" and s.'.$field.'="'.$s[$field].'" and s.Receive_Email > 0 and g.groups_KEY=54801';
		$L = $db->Execute($sql) or die($db->errorMsg());
		$l = $L->FetchRow();

		$total= $total + $s['source_count'];
		$d_total= $d_total + $d['source_count'];
		$l_total= $l_total + $l['source_count'];

		$o.= '<tr>';
		$o.= '<td>'.$s[$field].'</td>';
		$o.= '<td>'.$s['source_count'].'</td>';
		$o.= '<td>'.$d['source_count'].'</td>';
		$o.= '<td>'.$l['source_count'].'</td>';
		$o.= '</tr>';
	}

		$o.= '<tr>';
		$o.= '<td></td>';
		$o.= '<td>'.$total.'</td>';
		$o.= '<td>'.$d_total.'</td>';
		$o.= '<td>'.$l_total.'</td>';
		$o.= '</tr>';

	$o .= '</tbody></table>';

	return $o;

}

if (!$_REQUEST['field']) {$_REQUEST['field'] = 'Source_Details';}

$date= date('Y-m-d',strtotime('1 week ago'));
if ($_REQUEST['date']) {$date=$_REQUEST['date'];}


?>
<a href="<?php echo $_SERVER['PHP_SELF'];?>?field=Source_Tracking_Code">Source Tracking Code</a> | <a href="<?php echo $_SERVER['PHP_SELF'];?>?field=Source_Details">Source Details</a>

<h2>New Supporters Since <?php echo $date; ?></h2>
<?php



echo display_new_supporters_source($date,$_REQUEST['field']);

require_once("_footer.php");
?>