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

		$data['Email'] = $up[$c][0];
		$data['source'] = $_REQUEST['source'];


		#echo '<pre>';
		#print_r($data);
		#echo '</pre>';
		$db->AutoExecute('suppression_file', $data, 'INSERT'); 

	}	
	echo $c . ' lines inserted'; 	
	

} 
	
	
	


?> 

<h2>Suppression File Upload <small>Upload a file that only has emails and no header line.</small></h2>

<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">

<label for="file">Select file</label>
<input type="file" name="file" id="file" />

<label for="source">Source</label>
<input name="source">
<br>
<input type="submit" name="submit" value="Upload File" />
</form>
 <?php require_once('_footer.php');?>

