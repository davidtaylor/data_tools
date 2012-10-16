<?php
require_once("connect.php");


$fields= array(
"RespondentID"=>"INT(11),",
"CollectorID"=>"INT(11),",
"StartDate"=>"VARCHAR(255),",
"EndDate"=>"VARCHAR(255),",
"IP_Address"=>"VARCHAR(255),",
"Custom_Data"=>"INT(11),",
"Work_Protecting_rainforests"=>"VARCHAR(255),",
"Work_Supporting_sustainable_agriculture"=>"VARCHAR(255),",
"Work_Stopping_climate_change"=>"VARCHAR(255),",
"Work_Promoting_clean_energy_solutions"=>"VARCHAR(255),",
"Work_Defending_Indigenous_rights"=>"VARCHAR(255),",
"Work_Fighting_for_corporate_accountability"=>"VARCHAR(255),",
"Work_Saving_endangered_species"=>"VARCHAR(255),",
"Work_Opposing_coal_and_oil_extraction"=>"VARCHAR(255),",
"Work_Supporting_non_violent_direct_actions"=>"VARCHAR(255),",
"Work_Other"=>"TEXT,",
"Describe_Street_activist_rabble_rouser"=>"VARCHAR(255),",
"Describe_Member_of_RAN_chapter"=>"VARCHAR(255),",
"Describe_Online_activist_advocate"=>"VARCHAR(255),",
"Describe_Frontline_impacted_community_member"=>"VARCHAR(255),",
"Describe_Involved_with_other_non_profits"=>"VARCHAR(255),",
"Describe_Business_person"=>"VARCHAR(255),",
"Describe_Policy_wonk_researcher_academic"=>"VARCHAR(255),",
"Describe_Journalist_blogger_member_of_the_media"=>"VARCHAR(255),",
"Describe_Legislator_public_figure"=>"VARCHAR(255),",
"Describe_Just_a_bit_curious"=>"VARCHAR(255),",
"Describe_Other"=>"TEXT,",
"Groups_Sierra_Club"=>"VARCHAR(255),",
"Groups_WWF"=>"VARCHAR(255),",
"Groups_Greenpeace"=>"VARCHAR(255),",
"Groups_Center_for_Biological_Diversity"=>"VARCHAR(255),",
"Groups_Credo_Action"=>"VARCHAR(255),",
"Groups_MoveOn"=>"VARCHAR(255),",
"Groups_Rising_Tide"=>"VARCHAR(255),",
"Groups_Earth_First"=>"VARCHAR(255),",
"Info_Email_list"=>"VARCHAR(255),",
"Info_Facebook"=>"VARCHAR(255),",
"Info_Twitter"=>"VARCHAR(255),",
"Info_The_Understory_Blog"=>"VARCHAR(255),",
"Info_Events"=>"VARCHAR(255),",
"Info_The_RAN_website"=>"VARCHAR(255),",
"Info_Panther_print_newsletter"=>"VARCHAR(255),",
"Info_Phone_calls"=>"VARCHAR(255),",
"Info_Other"=>"TEXT,",
"What_do_you_think_about_the_amount_of_email_you_receive_from_RAN"=>"VARCHAR(255),",
"Any_other_thoughts_on_RANs_email_communications"=>"VARCHAR(255),",
"Involved_Taking_online_actionsign_a_petition_send_an_email"=>"VARCHAR(255),",
"Involved_Making_phone_calls_to_targets"=>"VARCHAR(255),",
"Involved_social_media"=>"VARCHAR(255),",
"Involved_Helping_to_create_visual_or_written_or_multimedias"=>"VARCHAR(255),",
"Involved_Attending_a_protest_or_event_in_my_community"=>"VARCHAR(255),",
"Involved_Co_hosting_an_event"=>"VARCHAR(255),",
"Involved_Joining_a_RAN_book_club_movie_discussion_group"=>"VARCHAR(255),",
"Involved_Attending_a_webinar_or_conference_call_with_RAN"=>"VARCHAR(255),",
"Involved_Raise_money_for_RAN"=>"VARCHAR(255),",
"Involved_Donating_money_to_RAN"=>"VARCHAR(255),",
"Work_Working_at_the_intersection_of_forest_destruction_climate_"=>"VARCHAR(255),",
"Work_Confronting_the_root_causes_of_environmental_destruction"=>"VARCHAR(255),",
"Work_Using_a_savvy_blend_of_inside_outside_strategys"=>"VARCHAR(255),",
"Work_Transforming_the_marketplace_by_focusing_on_changing"=>"VARCHAR(255),",
"Work_Working_in_solidarity_with_Indigenous_and_frontline"=>"VARCHAR(255),",
"Work_Creating_precedent_setting_victories"=>"VARCHAR(255),",
"Why_do_you_support_RAN___Open_Ended_Response"=>"TEXT,",
"Connect_Quarterly_update_letters_from_the_Executive_Director"=>"VARCHAR(255),",
"Connect_Occasional_emails_from_the_Executive_Director"=>"VARCHAR(255),",
"Connect_Quarterly_Panther_newsletters"=>"VARCHAR(255),",
"Connect_I_dont_receive_all_of_these_please_put_me_back"=>"VARCHAR(255),",
"Connect_House_parties_in_my_area"=>"VARCHAR(255),",
"Connect_REVEL"=>"VARCHAR(255),",
"Connect_Individual_visits_with_RAN_campaign_leaders"=>"VARCHAR(255),",
"Connect_Visits_with_RAN_board_members"=>"VARCHAR(255),",
"Connect_Other"=>"TEXT,",
"Is_there_anything_else_you_would_like_to_share_with_us"=>"TEXT,",
"First_Name"=>"VARCHAR(255),",
"Last_Name"=>"VARCHAR(255),",
"Twitter_Nameif_you_have_one"=>"VARCHAR(255),",
"Address"=>"VARCHAR(255),",
"City_Town"=>"VARCHAR(255),",
"State_Province"=>"VARCHAR(255),",
"ZIP_Postal_Code"=>"VARCHAR(255),",
"Country"=>"VARCHAR(255),",
"Email_Address"=>"VARCHAR(255),",
"Phone_Number"=>"VARCHAR(255)"

);


function create_table($fields){
	global $db;
	$sql= 'create TABLE  results ( ';
	foreach ( $fields as $i => $type) {
		$field .= $i.' '. $type;
	}
	$sql = $sql . $field. ' ) TYPE=MyISAM;';
	echo $sql;
	
	$db->Execute($sql) or die($db->errorMsg());

}


function merge_databases($fields,$table){
	global $db;
	
	$sql = 'select * from '.$table.' ';
	$R = $db->Execute($sql) or die($db->errorMsg());
	$r = $R->GetArray();
	$x=0;
	foreach ($r as $i){
		foreach ($fields as $f=>$v){
			$d[$f] = $i[$f];
		}
	$x++;
	$db->AutoExecute('results',$d,'INSERT');
	}
	echo $x;
}


merge_databases($fields,'online');
#create_table($fields);

