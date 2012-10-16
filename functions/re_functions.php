<?php
/*

This script starts by uplaoding to re_data 
We then add

look to see if mapped items have a change
	if they do declare winners
	if they do not mark as done

Look to see if non mapped items have and email match
	map
	
Look to see if names 


*/



#TO DO
#test the adding to salsa with a small subset of data
#verify that there are no orphans from the process
#build uploader



#main hand syncing function

function check_names($search = 'name'){
	global $db;
	$sql="select * from re_data where done = 0  and Edge_Email != '' and First_Name != '' and Last_Name != ''  ";
	$RE = $db->Execute($sql) or die($db->errorMsg());	

	echo "<table>";
	echo '<tr><td>Salsa</td><td>Salsa Email </td><td>RE Data </td><td></td></tr>';
	$x=0;
	while (!$RE->EOF){
	
		if ($search == 'id'){
			$sql="select * from supporter WHERE supporter_KEY = '".$RE->Fields('uid')."' ";
		} else if ($search == 'name') {
			$sql="select * from supporter WHERE First_Name = '".$RE->Fields('First_Name')."'AND Last_Name = '".$RE->Fields('Last_Name')."' ";
		}

		$S = $db->Execute($sql) or die($db->errorMsg());	
		while (!$S->EOF){
			if ($S->Fields("Email")){
				$x++;
				echo '<tr><td>'.$S->Fields("First_Name") .' '. $S->Fields("Last_Name") . '<br>'.$S->Fields("Street").'<br>'.$S->Fields("City").'  '.$S->Fields("State").' '.$S->Fields("Zip").'<br>'.$S->Fields("Source_Details").'<br>SID:'.$S->Fields("supporter_KEY").' RE_ID:'.$S->Fields("edge_id").'</td>';
				echo '<td>'.code_email($S->Fields("Email"),$RE->Fields("Edge_Email"),$S->Fields("Receive_Email"),$S->Fields("supporter_KEY"),$RE->Fields("id")) .' </td>';
				#echo '<td>'.$RE->Fields("uid").'</td>';
				echo '<td>'. $RE->Fields("Edge_Email") . '<br>'.	$RE->Fields("Update_Date").' </td><td>' .$RE->Fields("First_Name") .' ' .$RE->Fields("Last_Name") .'<br>'.$RE->Fields("Street").'<br>'.$RE->Fields("City").' ' .$RE->Fields("State").' '.$RE->Fields("Zip").'<br>SID:'.$RE->Fields("uid").' RE_ID:'.$RE->Fields("id").'</tr>';
			}
			
			$S->MoveNext();
			echo '<tr><td> <br><td></tr>';
		}
		
		$RE->MoveNext();
	}
	echo '</table>'.$x;
}

#checks to see if someone is on a list over the api, not currently used due to slowness of api
function check_list($sid){
	global $DIA,$db;
	#$options = array('condition' => 'supporter_KEY='.$sid, 'condition' => 'groups_KEY=54801');
	#$s = $DIA->get('supporter_groups',$options);
	
	$sql="select * from supporter_groups where supporter_KEY ='".$sid."' and groups_KEY=54801";
	$R = $db->Execute($sql) or die($db->errorMsg());
	$s = $R->FetchRow();
	
	if ($s['supporter_KEY']){
		return TRUE;
	}
}

function re_sync_status(){
	global $db;

	$sql='select count(id) as count from re_data ';
	$R = $db->Execute($sql) or die($db->errorMsg());
	$total = $R->FetchRow();
	
	$sql='select count(id) as count from re_data where done =1';
	$R = $db->Execute($sql) or die($db->errorMsg());
	$done = $R->FetchRow();
	
	$sql='select count(id) as count from re_data where new =1';
	$R = $db->Execute($sql) or die($db->errorMsg());
	$new = $R->FetchRow();
	
	$sql='select count(id) as count from re_data where salsa =1';
	$R = $db->Execute($sql) or die($db->errorMsg());
	$salsa = $R->FetchRow();

	$sql='select count(edge_id) as count from re_update ';
	$R = $db->Execute($sql) or die($db->errorMsg());
	$re = $R->FetchRow();
	
	$sql='select count(edge_id) as count from re_update_salsa ';
	$R = $db->Execute($sql) or die($db->errorMsg());
	$salsa_update = $R->FetchRow();	

	$sql='select count(edge_id) as count from re_update_salsa where salsa=1';
	$R = $db->Execute($sql) or die($db->errorMsg());
	$salsa_update_done = $R->FetchRow();	

	$o['total'] = $total['count'];
	$o['done'] = $done['count'];
	$o['salsa'] = $salsa['count'];
	$o['new'] = $new['count'];
	$o['re'] = $done['count'];
	$o['salsa_update_done'] = $salsa_update_done['count'];
	$o['salsa_update'] = $salsa_update['count'];

	return $o;
}


#creates the email block in the check names section
function code_email($salsa_email,$re_email,$code,$sid,$rid){
	global $db;
	$actions = '<br><a href="'.$_SERVER['PHP_SELF'].'?re='.$rid.'&s='.$sid.'&salsa_email='.$salsa_email.'&re_email='.$re_email.'&Receive_Email='.$code.'&winner=salsa">Salsa Wins</a>';
	$actions .= ' | <a href="'.$_SERVER['PHP_SELF'].'?re='.$rid.'&s='.$sid.'&salsa_email='.$salsa_email.'&re_email='.$re_email.'&Receive_Email='.$code.'&winner=re">RE Wins</a>';
	$actions .= ' | <a href="'.$_SERVER['PHP_SELF'].'?re='.$rid.'&s='.$sid.'&salsa_email='.$salsa_email.'&re_email='.$re_email.'&Receive_Email='.$code.'&winner=new">New</a>';
$actions .= ' | <a href="'.$_SERVER['PHP_SELF'].'?re='.$rid.'&s='.$sid.'&salsa_email='.$salsa_email.'&re_email='.$re_email.'&Receive_Email='.$code.'&winner=ignore">Ignore</a>';

	$salsa = '    <br><a href="https://hq.salsalabs.com/salsa/hq/p/salsa/supporter/common/hq/edit?table=supporter&key='.$sid.'#category-ContactHistory" target="_blank">view in salsa</a>';

	$sql="select * from unsubscribe WHERE supporter_KEY = '".$sid."' Order by unsubscribe_KEY DESC limit 1 ";
	$U = $db->Execute($sql) or die($db->errorMsg());	
	while (!$U->EOF){
		$date= '<br>'.$U->Fields('Unsubscribe_Date');
		$U->MoveNext();
	}


	if ($code > 0) {
		#$color ='green';
		if (check_list($sid) == TRUE) {$color='green';}
		return '<span class="'.$color.'">'.$salsa_email.'</span>'.$salsa .$date.$actions;
	}
	if ($code < 0) {
			return '<span class="red">'.$salsa_email.'</span> '.$code.$salsa. $date.$actions;
	}	

}

#saves the best item to the db

function winner($edge_id,$sid,$email,$Receive_Email=NULL){
	global $db;
	
	$data['Email'] = $email;
	$data['edge_id'] = $edge_id;
	$data['supporter_KEY'] = $sid;
	$data['Receive_Email'] = $Receive_Email;
	
	$db->AutoExecute('re_update_salsa', $data, 'INSERT'); 
	$db->AutoExecute('re_update', $data, 'INSERT'); 
	

	$data['done'] = 1;
	$where ='id = '. $edge_id;
	$db->AutoExecute('re_data', $data, 'UPDATE', $where); 

	
}


#mark everyone left that we have not matched as a new salsa record
function they_are_all_new(){
	global $db;
	$x=0;
	$sql= "select * from re_data where done =0";
	$R = $db->Execute($sql) or die($db->errorMsg());	
	while (!$R->EOF){
		new_salsa($R->Fields("id"));
		$x++;
		$R->MoveNext();
	}
	echo $x .'new people ready to add';
}



#marks people as new to salsa
function new_salsa($edge_id){
	global $db;
	$data['done'] = 1;
	$data['new'] = 1;
	$where ='id = '. $edge_id;
	$db->AutoExecute('re_data', $data, 'UPDATE', $where); 
	
}

#ignore a record for import
function ignore($edge_id){
	global $db;
	$data['done'] = 1;
	$where ='id = '. $edge_id;
	$db->AutoExecute('re_data', $data, 'UPDATE', $where); 
}


# adds everyone who is marked as new to salsa to salsa
function salsa_new_supporter(){
	global $db;
	global $DIA;
	$x=0;
	$sql='select * from re_data where Edge_Email != "" and new =1 and salsa=0';
	$R = $db->Execute($sql) or die($db->errorMsg());	
	while (!$R->EOF){
	
		#save new person
		$add = array(
		  'First_Name' => $R->Fields('First_Name'),
		  'Last_Name' => $R->Fields('Last_Name'),
		  'Organization' => $R->Fields('Organization'),
		  'Street' => $R->Fields('Street'),
		  'City' => $R->Fields('City'),
		  'State' => $R->Fields('State'),
		  'Email' => $R->Fields('Edge_Email'),
		  'edge_id' => $R->Fields('id'),
		  'Tracking_Code' => 'RE Import: '.date("m/d/Y"),
		  'link' => array('custom','groups'),
		  'linkKey'=>array(0,43071)
		);
		
		$plusfour = zipplusfour($R->Fields('Zip'));
		if ($plusfour){
			$add['Zip'] = $plusfour[0];
			$add['PRIVATE_Zip_Plus_4'] = $plusfour[1];
		} else {
			$add['Zip']; 
		}		
		$add['Country'] = convert_country($R->Fields('Country'));

		
		$supporter_key = $DIA->save( 'supporter', $add);
		$x++;
		#save the person to the edge export
		
		$data['supporter_KEY'] = $supporter_key;
		$data['edge_id'] = $R->Fields('id');
		$db->AutoExecute('re_sync', $data, 'INSERT'); 
		
		
		#mark as done
		$data['salsa'] = 1;
		$where ='id = '. $R->Fields('id');
		$db->AutoExecute('re_data', $data, 'UPDATE', $where); 
	
		$R->MoveNext();
	}
	echo $x. ' new people added to salsa<br>';
}

# updates everyone in the update salsa table in salsa

function salsa_update_supporter(){
	global $db;
	global $DIA;
	$x=0;
	$sql='select * from re_update_salsa where salsa=0';
	$R = $db->Execute($sql) or die($db->errorMsg());	
	while (!$R->EOF){
		$update['key'] = $supporter_key;
		$update['edge_id'] = $R->Fields('id');
		
		if ($R->Fields('Email')){
			$update['Email'] = $R->Fields('Edge_Email');
		}
		if($R->Fields('Receive_Email')){
			$update['Receive_Email'] = $R->Fields('Receive_Email');
		}
		
		$supporter_key = $DIA->save( 'supporter', $update);
		$x++;

		#mark as done
		$data['salsa'] = 1;
		$where ='supporter_KEY = '. $supporter_key;
		$db->AutoExecute('re_update_salsa', $data, 'UPDATE', $where); 
		
		$R->MoveNext();
	}
	echo $x. '  people updated in salsa<br>';

}



#check to see is a person has an email address that is already in salsa and adds them to the mapping tables
function match_emails(){
	global $db;
	$x=0;
	$c = 0;
	$sql= 'select * from re_data where done=0 and Edge_Email != "" ' ;
	$R = $db->Execute($sql) or die($db->errorMsg());	
	while (!$R->EOF){
		$sql = 'select Email, supporter_KEY, Receive_Email from supporter where Email = "'.$R->Fields("Edge_Email").'" and edge_id = "" ';
		$S = $db->Execute($sql) or die($db->errorMsg());	
		$c++;
		while (!$S->EOF){
			#echo $S->Fields("supporter_KEY").'<br>';
			add_re_table($R->Fields("id"), $S->Fields("supporter_KEY"), NULL, $S->Fields("Receive_Email"));
			add_salsa_table($R->Fields("id"), $S->Fields("supporter_KEY"), NULL, $S->Fields("Receive_Email"));
			$x++;
			$S->MoveNext();
		}
		$R->MoveNext();
	}
	echo $x .' emails matched of '.$c;
}


#add to salsa add table

function add_salsa_table($edge_id, $supporter_KEY, $Email=NULL,$Receive_Email = NULL) {
	global $db;
	
	if ($Email){
		$data['Email'] = $Email;
	}
	$data['edge_id'] = $edge_id;
	$data['supporter_KEY'] = $supporter_KEY;
	if ($Receive_Email){
		$data['Receive_Email'] = $Receive_Email;
	}
	
	$db->AutoExecute('re_update_salsa', $data, 'INSERT'); 
	

	$data['done'] = 1;
	
	$where ='id = '. $edge_id;
	$db->AutoExecute('re_data', $data, 'UPDATE', $where); 



}

#add to RE output table

function add_re_table($edge_id, $supporter_KEY, $Email = NULL, $Receive_Email = NULL) {
	global $db;
	
	if ($Email){
		$data['Email'] = $Email;
	}
	$data['edge_id'] = $edge_id;
	$data['supporter_KEY'] = $supporter_KEY;
	if ($Receive_Email){
		$data['Receive_Email'] = $Receive_Email;
	}
	$db->AutoExecute('re_update', $data, 'INSERT'); 
	
	$data['done'] = 1;
	
	$where ='id = '. $edge_id;
	$db->AutoExecute('re_data', $data, 'UPDATE', $where); 

}


#batch function not used in this script
function sync_re_ids(){
	global $db;
	$sql ="select Email, edge_id from re_all";
	$R = $db->Execute($sql) or die($db->errorMsg());	
	while (!$R->EOF){
	
		$sql = 'select * from supporter where Email= "'.$R->Fields('Email').'"'	;
		$S = $db->Execute($sql) or die($db->errorMsg());	
		while (!$S->EOF){
	
			$data['supporter_KEY'] = $S->Fields("supporter_KEY");
			$data['edge_id'] = $R->Fields("edge_id");
			$data['Email'] = $S->Fields("Email");
			$data['Edge_Email'] = $R->Fields("Email");
	
		    $db->AutoExecute('re_sync', $data, 'INSERT'); 
	
			$S->MoveNext();
		}
		$R->MoveNext();
	}
	echo 'done';
}


function delete_everything(){
	global $db;

	$sql ="delete from re_data";
	$R = $db->Execute($sql) or die($db->errorMsg());

	$sql ="delete from re_update";
	$R = $db->Execute($sql) or die($db->errorMsg());	

	$sql ="delete from re_update_salsa";
	$R = $db->Execute($sql) or die($db->errorMsg());	
	
	echo 'all deleted, hope you meant to click that button';
}


function convert_country($country = NULL){
	global $db;
	if ($country){
		$sql = "select iso_code from country where name like '%".$country."%'";
		$R = $db->Execute($sql) or die($db->errorMsg());
			while (!$S->EOF){
			$out= $R->Fields("iso_code");
			$R->MoveNext();
		}
	}
	return $out;
}

function zipplusfour($zip){
	
	if( preg_match('/\d{5}-\d{4}/', $zip ) ) { 
	  $zip_ary = split('-', $zip);
	  return $zip_ary;
	}
	
	else{
		 return false;
	}
}



?>