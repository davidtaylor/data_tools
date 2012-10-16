<?php
require_once('_header.php');


#defualt setup for a new action


#5896

#DEFINE TEMPLATES
$action_KEY_temp = '5896';
$action_email_trigger_KEY_temp= '';
$action_email_trigger_KEY_welcome_temp= '';

$donate_page_KEY_temp = '';
$tell_a_friend_KEY_temp = '';
$email_blast_KEY_temp = '';
$donation_email_trigger_KEY_temp= '';


function populate_new_from_template($new,$temp){
	foreach ($temp as  $key => $value) {
		if ($new[$key]) {
			$temp[$key] = $new[$key];
		}
	}
	return $temp;
}






if ($_REQUEST['new']){
	
	$Reference_Name = $_REQUEST['Reference_Name'];
	$campaign = $_REQUEST['campaign'];
	$campaign_slug = $_REQUEST['campaign_slug'];
	

	
	##create the inital objects
	
	$cam = array(
		'Reference_Name' =>  $campaign.': '.$Reference_Name,
		'campaign' =>  $campaign,
		'campaign_slug' =>  $campaign_slug,
		);
	$c = $DIA->save( 'campaign', $cam);
	
	
	if ($_REQUEST['action']){
		$a = $DIA->save( 'action', $options);
	}
	if ($_REQUEST['tell_a_friend']){
		$t = $DIA->save( 'tell_a_friend', $options);
	}
	if ($_REQUEST['donate_page']){
		$d = $DIA->save( 'donate_page', $options);
	}
	if ($_REQUEST['email_blast1']){
		$e1 = $DIA->save( 'email_blast', $options);
	}
	if ($_REQUEST['email_blast2']){
		$e2 = $DIA->save( 'email_blast', $options);
	}
	if ($_REQUEST['email_blast3']){
		$e3 = $DIA->save( 'email_blast', $options);
	}
	
	if ($_REQUEST['email_triger_donate']){
		$ed = $DIA->save( 'email_trigger', $options);
	}
	
	if ($_REQUEST['email_trigger_action']){
		$ea = $DIA->save( 'email_trigger', $options);
	}
	
	#get the templates
	
	$options = array('condition' => array('action_KEY='.$action_KEY_temp));
	$out = $DIA->get( 'action', $options);
	$a_t = $out[0];
	
	$options = array('condition' => array('donate_page_KEY='.$donate_page_KEY_temp));
	$out = $DIA->get( 'donate_page', $options);
	$a_d = $out[0];
	
	$options = array('condition' => array('tell_a_friend_KEY='.$tell_a_friend_KEY_temp));
	$out = $DIA->get( 'tell_a_friend', $options);
	$t_t = $out[0];
	
	$options = array('condition' => array('email_blast_KEY='.$email_blast_KEY_temp));
	$out = $DIA->get( 'email_blast', $options);
	$e_t = $out[0];
	
	$options = array('condition' => array('_KEY='.$action_email_trigger_KEY_temp));
	$out = $DIA->get( 'email_trigger', $options);
	$tra_t = $out[0];
	
	$options = array('condition' => array('_KEY='.$donation_email_trigger_KEY_temp));
	$out = $DIA->get( 'email_trigger', $options);
	$trd_t = $out[0];
	
	#update the new objects with the template data
	
	
	
	
	$action = array(
		'key' => $a ,
		'Reference_Name' =>  $campaign.': '.$Reference_Name,
		'campaign' =>  $campaign,
		'campaign_slug' =>  $campaign_slug,
		'email_trigger_KEYS' =>  '0,'.$ea,
		'tell_a_friend_KEY' =>  $t,
		'campaign_KEY' => $c,   
		'Style' => 'Targeted'
	);
	
	$action = populate_new_from_template($action,$a_t);
	
	
	$tell_a_friend = array(  #182
		'key' => $t ,
		'Reference_Name' =>  $campaign.': '.$Reference_Name,
		'campaign' =>  $campaign,
		'donation_page_KEY' => $d,
		'action_KEY' => $a,
		'campaign_KEY' => $c
		);
	$tell_a_friend = populate_new_from_template($tell_a_friend,$t_t);
	
	
	$email_trigger_action = array(  #59
		'key' => $ea ,
		'Reference_Name' =>  'Action: '. $campaign.': '.$Reference_Name,
		'campaign' =>  $campaign,
		'action_KEY' => $a,
		'campaign_KEY' => $c
		);
	$email_trigger_action = populate_new_from_template($email_trigger_action,$_t);
	
	
	
	$email_trigger_donate= array(  #59
		'key' => $ea ,
		'Reference_Name' =>  'Donation: '. $campaign.': '.$Reference_Name,
		'campaign' =>  $campaign,
		'donation_page_KEY' => $d,
		'campaign_KEY' => $c
		);
	$email_trigger_donate = populate_new_from_template($email_trigger_donate,$trd_t);
	
	
	$donate_page = array(
		'key' => $d ,
		'Reference_Name' =>  'Action: '.$campaign.': '.$Reference_Name,
		'campaign' =>  $campaign,  # enum values 
		'donate_page_KEY' => $d,
		'action_KEY' => $a,
		'email_trigger_KEYS' =>  '0,'.$ed,
		'campaign_KEY' => $c
		);
	$donate_page = populate_new_from_template($donate_page,$d_t);
	
	
	$email_blast1 = array(  #52
		'key' => $e1 ,
		'Reference_Name' =>  $campaign.': '.$Reference_Name,
		'campaign' =>  $campaign,
		'donation_page_KEY' => $d,
		'action_KEY' => $a,
		'campaign_KEY' => $c,
		'tell_a_friend_KEY' =>  $t
		);
	$email_blast1 = populate_new_from_template($email_blast1,$e_t);
	
	
	$email_blast1 = array(  
		'key' => $e2 ,
		'Reference_Name' =>  $campaign.': '.$Reference_Name.' #2',
		'campaign' =>  $campaign,
		'donation_page_KEY' => $d,
		'action_KEY' => $a,
		'campaign_KEY' => $c,
		'tell_a_friend_KEY' =>  $t
		);
	$email_blast1 = populate_new_from_template(email_blast1,$e_t);
	
	
	$email_blast3 = array(  
		'key' => $e3 ,
		'Reference_Name' =>  $campaign.': '.$Reference_Name.' #3',
		'campaign' =>  $campaign,
		'donation_page_KEY' => $d,
		'action_KEY' => $a,
		'campaign_KEY' => $c,
		'tell_a_friend_KEY' =>  $t
		);
	$email_blast3 = populate_new_from_template($email_blast3,$e_t);
	
	
		
	if ($_REQUEST['action']){
		$a = $DIA->save( 'action', $action);
		echo 'a: '.$a .'<br>';
	}
	if ($_REQUEST['tell_a_friend']){
		$t = $DIA->save( 'tell_a_friend', $tell_a_friend);
		echo 't: '.$t .'<br>';
	
	}
	if ($_REQUEST['donate_page']){
		$d = $DIA->save( 'donate_page', $donate_page);
		echo 'd: '.$d .'<br>';
	
	}
	if ($_REQUEST['email_blast1']){
		$e = $DIA->save( 'email_blast', $email_blast1);
		echo 'em 1: '.$e .'<br>';
	
	}
	if ($_REQUEST['email_blast2']){
		$e = $DIA->save( 'email_blast', $email_blast2);
		echo 'em 2: '.$e .'<br>';
	
	}
	if ($_REQUEST['email_blast1']){
		$e = $DIA->save( 'email_blast', $email_blas3);
		echo 'em 3: '.$e .'<br>';
	
	}
	
	if ($_REQUEST['email_triger_donate']){
		$ed = $DIA->save( 'email_trigger', $email_triger_donate);
	}
	
	if ($_REQUEST['email_trigger_action']){
		$ea = $DIA->save( 'email_trigger', $email_trigger_action);
	}
	

}

?>

<h1>Make a new campaign</h1>
<form method="post" action="campaigner.php">
<input type="hidden" name="new" value='1'/>
<label for="Reference_Name">Reference Name</label><br>
<input id="Reference_Name" name="Reference_Name" type="text" /><br>

<label for="campaign">Internal Campaign Code</label><br>
<select name="campaign">
	<option></option>
	<option>GFC</option>
	<option>RFP</option>
	<option>RAG</option>
	<option>Online</option>
	<option>Devo</option>

</select>

<br>

<label for="campaign_slug">Campaign Slug (no spaces)</label><br>
<input id="campaign_slug" name="campaign_slug" type="text" /><br>
<br>
This campaign has:<br>
<input name="action" type="checkbox" value="action" checked />Action<br>
<input name="email_trigger_action" type="checkbox" value="action" checked />New Action Email Trigger<br>
<input name="tell_a_friend" type="checkbox" value="action" checked />Action Tell A Friend<br>
<input name="donate_page" type="checkbox" value="action"  />Donation Page<br>
 <input name="email_trigger_donate" type="checkbox" value="action"  />Donation Email Trigger<br>
<input name="email_blast1" type="checkbox" value="action" checked />Email #1<br>
<input name="email_blast2" type="checkbox" value="action"  />Email #2<br>
<input name="email_blast3" type="checkbox" value="action"  />Email #3
<br>
<input name="Submit" type="submit" value="Submit">
</form>
