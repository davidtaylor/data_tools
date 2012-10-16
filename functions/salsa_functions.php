<?php


function salsa_add_groups($supporter_KEY,$groups_KEY){
	global $DIA;
	global $db;

	$data['supporter_KEY'] = $R->Fields("supporter_KEY");
	$data['groups_KEY'] = $group;

	$db->AutoExecute('re_update_salsa', $data, 'UPDATE'); 


}

function salsa_remove_groups($supporter_KEY,$groups_KEY){
	global $DIA;
	global $db;


#needs to cycle and get the key to do the delete
	$data['supporter_KEY'] = $R->Fields("supporter_KEY");
	$data['groups_KEY'] = $group;

				
	$DIA->delete('supporter_groups', $data);
			
	$sql = 'delete from groups where supporter_KEY ="'.$supporter_KEY.'"  and groups_KEY = "'.$groups_KEY.'"';
	$db->Execute($sql); 

}


##### supporter functions  #####

function count_helper($sql){
	global $db;

	$R = $db->Execute($sql) or die($db->errorMsg());
	$r = $S->FetchRow();

	return $r['count'];
}

function count_emails($s){

	count_helper($sql);
}

#### campaign functions #######


function campaign_options($value=null){

	$campaign_array = array(
		'',
		'RAG',
		'GFC',
		'RFP',
		'CVX',
		'Online',
		'Forests',
		'RAN',
		'',
		'PAA',
		'Tiki',
		'RYSE',
		'RH',
		'',
		'FFO',
		'JSF',
		'OG',
		'TAZ',
		'EF',
		'GO',
		'Kids',
		'RFA',
		'WRW'
	);
	
	foreach($campaign_array as $i){
		if ($value == $i) {$selected = ' selected ';} else {$selected = ' ';}
		$out .= '<option '.$selected.'>'.$i.'</option>';
	
	}
	return $out;
}



?>