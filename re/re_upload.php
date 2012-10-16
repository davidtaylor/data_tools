<?php
require_once('../_header.php');



if (!empty($_FILES)){
	

	$fp = fopen($_FILES['file']['tmp_name'], 'r');       
	while (($data = fgetcsv($fp, 0, "\r", '"', "\r")) !== FALSE) {
	    $num = count($data);
	    for ($c=0; $c < $num; $c++) {
	        $up[]=str_getcsv($data[$c]);
	    }
	}


	$num = count($up); 
	for ($c=0; $c < $num; $c++) {

		$data['id'] = $up[$c][0];
		$data['First_Name'] = $up[$c][1];
		$data['Last_Name'] = $up[$c][2];
		$data['Organization'] = $up[$c][3];
		$data['Street'] = $up[$c][4];
		$data['City'] = $up[$c][5];
		$data['State'] = $up[$c][6];
		$data['Zip'] = $up[$c][7];
		$data['Country'] = $up[$c][8];
		$data['Edge_Email'] = $up[$c][9];
		$data['uid'] = $up[$c][10];
		$data['Update_Date'] = $up[$c][11];
		$data['done'] = $up[$c][12];
		$data['new'] = $up[$c][13];
		$data['salsa'] = $up[$c][14];

		#echo '<pre>';
		#print_r($data);
		#echo '</pre>';
		if ($c != 0){
			$db->AutoExecute('re_data', $data, 'INSERT'); 
		}

	}	
	echo $c . ' lines inserted<br>'; 	
	#match_emails();
	

} 
	
	
	


?>

<table width="600">
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">

<tr>
<td width="20%">Select file</td>
<td width="80%"><input type="file" name="file" id="file" /></td>
</tr>

<tr>
<td>Submit</td>
<td><input type="submit" name="submit" /></td>
</tr>

</form>
</table>
<?php require_once('_footer.php');?>
