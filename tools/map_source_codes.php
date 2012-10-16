<?php
require_once("../_header.php");

if ($_REQUEST['id']){
	update_source_code();
}


if ($_REQUEST['update']){
	add_missing_codes();
	add_missing_files();
	referral_flag();
	source_ct();
	echo 'source counts updated';
}
?>

<script>
$(document).ready(function(){
	$('.source-form').hide();
	
	$(".show-form").click(function(){
		$("#source-form-"+$(this).attr('source-code-id')).slideToggle("slow");
		return false;
	});	
});

</script>

<a href="<?php echo $_SERVER['PHP_SELF'] ;?>?update=1">Update Counts</a>

<?php

if($_REQUEST['order']){
	$orderby= 'order by '.$_REQUEST['order'];
}

$sql='select * from source_code_map '.$orderby;
$S = $db->Execute($sql) or die($db->errorMsg());
$s = $S->GetArray();

echo '<table class="table xtable-bordered"><thead><tr><th>source</th><th>source type category</th><th>source type subcategory</th><th>source campaign</th><th>source type note</th><th>source type</th><th>referal code</th><th>count</th><th>new</th><th></th></tr> </thead><tbody>';

foreach ($s as $i){
	echo '<tr><td>'.$i['source'];
	echo '<a href="#"source-code-id='.$i['id'].' class="show-form"><i class="icon-pencil"></i></a>';
	echo '<div class="source-form" id="source-form-'.$i['id'].'"><form action="'.$_SERVER['PHP_SELF'].'" method="post">
<input type="hidden" name="id" value="'.$i['id'].'"> 
<label>Category</label><select name="source_type_category">'.source_type_options($i['source_type_category']).'</select>

<label>Sub Category</label><input type="text" name="source_type_subcategory" value="'.$i['source_type_subcategory'].'" class="span2">
<label>Campaign</label><select name="source_campaign">'.campaign_options($i['source_campaign']).'</select>
<label>Note</label><input type="text" name="source_type_note" value="'.$i['source_type_note'].'" class="span2">
<label>Referral</label><input type="text" name="source_referral" value="'.$i['source_referral'].'" class="span2">

<label>KEY</label><input type="text" name="source_key" value="'.$i['source_key'].'" class="span2">

<input type="hidden" name="new" value="0">
<input type="submit" value="update">
</form></div>';
	echo '</td><td>'.$i['source_type_category'].'</td><td>'.$i['source_type_subcategory'].'</td><td>'.$i['source_campaign'].'</td><td>'.$i['source_type_note'].'</td><td>'.$i['source_type'].'</td><td>'.$i['referral_code'].'</td><td>'.$i['ct'].'</td><td>'.$i['new'].'</td><td><a href="" class="edit">edit</a></td></tr>';

}
echo ' </tbody></table>';

function add_missing_codes(){
	global $db;
	
	$x=0;
	
	$sql= 'select distinct Source_Tracking_Code from supporter';
	$S = $db->Execute($sql) or die($db->errorMsg());
	$s = $S->GetArray();
	
	foreach ($s as $i){

		if (!in_source_map($i['Source_Tracking_Code'])&& ($i['Source_Tracking_Code'] != '')){
			$d['source']= $i['Source_Tracking_Code'];
			$d['new'] = 1;
			$d['source_type'] = 'Web';
			$db->AutoExecute('source_code_map',$d, 'INSERT')or die($db->errorMsg());
			$x++;
		}
	}
	echo $x.' new sources<br>';

}

function update_source_code(){
	global $db;
	$d['source_type_category'] =  $_REQUEST['source_type_category'];
	$d['source_type_subcategory'] =  $_REQUEST['source_type_subcategory'];
	$d['source_type_note'] =  $_REQUEST['source_type_note'];
	$d['source_campaign'] =  $_REQUEST['source_campaign'];
	$d['source_referral'] =  $_REQUEST['source_referral'];
	$d['source_key'] =  $_REQUEST['source_key'];

	$d['new'] =  $_REQUEST['new'];

	$where = 'id = '.$_REQUEST['id'];
	
	
	$db->AutoExecute('source_code_map',$d, 'UPDATE', $where)or die($db->errorMsg());

}


function add_missing_files(){
	global $db;
	
	$x=0;
	
	$sql= 'select distinct Source_Details from supporter where Source = "File"';
	$S = $db->Execute($sql) or die($db->errorMsg());
	$s = $S->GetArray();
	
	foreach ($s as $i){

		if (!in_source_map($i['Source_Details']) && ($i['Source_Details'] != '')){
			$d['source']= $i['Source_Details'];
			$d['new'] = 1;
			$d['source_type'] = 'File';
			$db->AutoExecute('source_code_map',$d, 'INSERT')or die($db->errorMsg());
			$x++;
		}
	}
	echo $x.' new files<br>';
}


function referral_flag(){
	global $db;

	$sql = 'select * from referral_map';
	$S = $db->Execute($sql) or die($db->errorMsg());
	$w = $S->GetArray();

	foreach ($w as $i){
		$sql = ' update source_code_map set referral_code 	="1" where source="'.$i['referral_code'].'" ';
		$db->Execute($sql) or die($db->errorMsg());
	}	
}


function in_source_map($source){
	global $db;
	$sql ="select source from source_code_map where source = '".$source."' ";
	$S = $db->Execute($sql) or die($db->errorMsg());
	$s = $S->FetchRow();
	
	if ($s['source']){
		return TRUE;
	}
}

function source_ct(){
	global $db;
	
	$sql ="select id, source from source_code_map where  source_type != 'File' ";
	$S = $db->Execute($sql) or die($db->errorMsg());
	$w = $S->GetArray();

	foreach ($w as $i){
	
		$sql ="select count(supporter_KEY) as count from supporter where Source_Tracking_Code = '".$i['source']."' ";
		$S = $db->Execute($sql) or die($db->errorMsg());
		$s = $S->FetchRow()	;
		$d['ct'] = $s['count'];
		$db->AutoExecute('source_code_map',$d, 'UPDATE', 'id='.$i['id'])or die($db->errorMsg());

	}

	
	$sql ="select id, source from source_code_map where  source_type = 'File' ";
	$S = $db->Execute($sql) or die($db->errorMsg());
	$f = $S->GetArray();
	foreach ($f as $i){
	
		$sql ="select  count(supporter_KEY) as count from supporter where Source_Details = '".$i['source']."' ";
		$S = $db->Execute($sql) or die($db->errorMsg());
		$s = $S->FetchRow()	;
		$d['ct'] = $s['count'];
		$db->AutoExecute('source_code_map',$d, 'UPDATE', 'id='.$i['id'])or die($db->errorMsg());
	}

}


function source_type_options($value=null){

	$type_array = array(
		'',
		'Action',
		'Web',
		'Donation',
		'Field',
		'Acquisition',
		'Devo',
		'Data',

	);
	
	foreach($type_array as $i){
		if ($value == $i) {$selected = ' selected ';} else {$selected = ' ';}
		$out .= '<option '.$selected.'>'.$i.'</option>';
	
	}
	return $out;
}



?>
