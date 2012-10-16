<?php

require_once("../connect.php");


function field_to_group($groups_KEY, $field, $table){
	global $db;

	$sql= 'select Custom_Data from '.$table.' where '.$field.' != "" ';
	$S = $db->Execute($sql) or die($db->errorMsg());
	$s = $S->GetArray();
	
	foreach ($s as $i){
		add_to_group($grousp_KEY,$i['Custom_Data']);
	}

}

function field_to_field($supporter_field, $field, $table){
	global $db, $DIA;

	$sql= 'select Custom_Data from '.$table.' where '.$field.' != "" ';
	$S = $db->Execute($sql) or die($db->errorMsg());
	$s = $S->GetArray();
	
	foreach ($s as $i){
		$d[$supporter_field] = TRUE;
		$d['key'] = $supporter_KEY;
		$DIA->save('supporter',$d);
	}

}

function add_to_group($groups_KEY,$supporter_KEY){
	global $DIA;

	$d['groups_KEY'] = $groups_KEY;
	$d['supporter_KEY'] = $supporter_KEY;
	
	$DIA->save('supporter_groups',$d);

}


$process_fields= array(
	"RespondentID"=>"",
	"CollectorID"=>"",
	"StartDate"=>"",
	"EndDate"=>"",
	"IP_Address"=>"",
	"Custom_Data"=>"",
	"Work_Protecting_rainforests"=>"",
	"Work_Supporting_sustainable_agriculture"=>"",
	"Work_Stopping_climate_change"=>"",
	"Work_Promoting_clean_energy_solutions"=>"",
	"Work_Defending_Indigenous_rights"=>"",
	"Work_Fighting_for_corporate_accountability"=>"",
	"Work_Saving_endangered_species"=>"",
	"Work_Opposing_coal_and_oil_extraction"=>"",
	"Work_Supporting_non_violent_direct_actions"=>"",
	"Work_Other"=>"",
	"Describe_Street_activist_rabble_rouser"=>"",
	"Describe_Member_of_RAN_chapter"=>"",
	"Describe_Online_activist_advocate"=>"",
	"Describe_Frontline_impacted_community_member"=>"",
	"Describe_Involved_with_other_non_profits"=>"",
	"Describe_Business_person"=>"",
	"Describe_Policy_wonk_researcher_academic"=>"",
	"Describe_Journalist_blogger_member_of_the_media"=>"",
	"Describe_Legislator_public_figure"=>"",
	"Describe_Just_a_bit_curious"=>"",
	"Describe_Other"=>"",
	#"Groups_Sierra_Club"=>"",
	#"Groups_WWF"=>"",
	#"Groups_Greenpeace"=>"",
	#"Groups_Center_for_Biological_Diversity"=>"",
	#"Groups_Credo_Action"=>"",
	#"Groups_MoveOn"=>"",
	#"Groups_Rising_Tide"=>"",
	#"Groups_Earth_First"=>"",
	#"Info_Email_list"=>"",
	"Info_Facebook"=>"",
	"Info_Twitter"=>"",
	#"Info_The_Understory_Blog"=>"",
	#"Info_Events"=>"",
	#"Info_The_RAN_website"=>"",
	#"Info_Panther_print_newsletter"=>"",
	#"Info_Phone_calls"=>"",
	#"Info_Other"=>"",
	"What_do_you_think_about_the_amount_of_email_you_receive_from_RAN"=>"",
	"Any_other_thoughts_on_RANs_email_communications"=>"",
	"Involved_Taking_online_actionsign_a_petition_send_an_email"=>"",
	"Involved_Making_phone_calls_to_targets"=>"",
	"Involved_social_media"=>"",
	"Involved_Helping_to_create_visual_or_written_or_multimedias"=>"",
	"Involved_Attending_a_protest_or_event_in_my_community"=>"",
	"Involved_Co_hosting_an_event"=>"",
	"Involved_Joining_a_RAN_book_club_movie_discussion_group"=>"",
	"Involved_Attending_a_webinar_or_conference_call_with_RAN"=>"",
	"Involved_Raise_money_for_RAN"=>"",
	"Involved_Donating_money_to_RAN"=>"",
	"Work_Working_at_the_intersection_of_forest_destruction_climate_"=>"",
	"Work_Confronting_the_root_causes_of_environmental_destruction"=>"",
	"Work_Using_a_savvy_blend_of_inside_outside_strategys"=>"",
	"Work_Transforming_the_marketplace_by_focusing_on_changing"=>"",
	"Work_Working_in_solidarity_with_Indigenous_and_frontline"=>"",
	"Work_Creating_precedent_setting_victories"=>"",
	"Why_do_you_support_RAN___Open_Ended_Response"=>"",
	#"Connect_Quarterly_update_letters_from_the_Executive_Director"=>"",
	#"Connect_Occasional_emails_from_the_Executive_Director"=>"",
	#"Connect_Quarterly_Panther_newsletters"=>"",
	#"Connect_I_dont_receive_all_of_these_please_put_me_back"=>"",
	#"Connect_House_parties_in_my_area"=>"",
	#"Connect_REVEL"=>"",
	#"Connect_Individual_visits_with_RAN_campaign_leaders"=>"",
	#"Connect_Visits_with_RAN_board_members"=>"",
	#"Connect_Other"=>"",
	"Is_there_anything_else_you_would_like_to_share_with_us"=>"",
	"First_Name"=>"",
	"Last_Name"=>"",
	"Twitter_Nameif_you_have_one"=>"",
	"Address"=>"",
	"City_Town"=>"",
	"State_Province"=>"",
	"ZIP_Postal_Code"=>"",
	"Country"=>"",
	"Email_Address"=>"",
	"Phone_Number"=>""

);

$process_groups= array(
	"RespondentID"=>"",
	"CollectorID"=>"",
	"StartDate"=>"",
	"EndDate"=>"",
	"IP_Address"=>"",
	"Custom_Data"=>"",
	"Work_Protecting_rainforests"=>"",
	"Work_Supporting_sustainable_agriculture"=>"",
	"Work_Stopping_climate_change"=>"",
	"Work_Promoting_clean_energy_solutions"=>"",
	"Work_Defending_Indigenous_rights"=>"",
	"Work_Fighting_for_corporate_accountability"=>"",
	"Work_Saving_endangered_species"=>"",
	"Work_Opposing_coal_and_oil_extraction"=>"",
	"Work_Supporting_non_violent_direct_actions"=>"",
	"Work_Other"=>"",
	"Describe_Street_activist_rabble_rouser"=>"",
	"Describe_Member_of_RAN_chapter"=>"",
	"Describe_Online_activist_advocate"=>"",
	"Describe_Frontline_impacted_community_member"=>"",
	"Describe_Involved_with_other_non_profits"=>"",
	"Describe_Business_person"=>"",
	"Describe_Policy_wonk_researcher_academic"=>"",
	"Describe_Journalist_blogger_member_of_the_media"=>"",
	"Describe_Legislator_public_figure"=>"",
	"Describe_Just_a_bit_curious"=>"",
	"Describe_Other"=>"",
	"Groups_Sierra_Club"=>"",
	"Groups_WWF"=>"",
	"Groups_Greenpeace"=>"",
	"Groups_Center_for_Biological_Diversity"=>"",
	"Groups_Credo_Action"=>"",
	"Groups_MoveOn"=>"",
	"Groups_Rising_Tide"=>"",
	"Groups_Earth_First"=>"",
	"Info_Email_list"=>"",
	"Info_Facebook"=>"",
	"Info_Twitter"=>"",
	"Info_The_Understory_Blog"=>"",
	"Info_Events"=>"",
	"Info_The_RAN_website"=>"",
	"Info_Panther_print_newsletter"=>"",
	"Info_Phone_calls"=>"",
	"Info_Other"=>"",
	"What_do_you_think_about_the_amount_of_email_you_receive_from_RAN"=>"",
	"Any_other_thoughts_on_RANs_email_communications"=>"",
	"Involved_Taking_online_actionsign_a_petition_send_an_email"=>"",
	"Involved_Making_phone_calls_to_targets"=>"",
	"Involved_social_media"=>"",
	"Involved_Helping_to_create_visual_or_written_or_multimedias"=>"",
	"Involved_Attending_a_protest_or_event_in_my_community"=>"",
	"Involved_Co_hosting_an_event"=>"",
	"Involved_Joining_a_RAN_book_club_movie_discussion_group"=>"",
	"Involved_Attending_a_webinar_or_conference_call_with_RAN"=>"",
	"Involved_Raise_money_for_RAN"=>"",
	"Involved_Donating_money_to_RAN"=>"",
	"Work_Working_at_the_intersection_of_forest_destruction_climate_"=>"",
	"Work_Confronting_the_root_causes_of_environmental_destruction"=>"",
	"Work_Using_a_savvy_blend_of_inside_outside_strategys"=>"",
	"Work_Transforming_the_marketplace_by_focusing_on_changing"=>"",
	"Work_Working_in_solidarity_with_Indigenous_and_frontline"=>"",
	"Work_Creating_precedent_setting_victories"=>"",
	"Why_do_you_support_RAN___Open_Ended_Response"=>"",
	#"Connect_Quarterly_update_letters_from_the_Executive_Director"=>"",
	#"Connect_Occasional_emails_from_the_Executive_Director"=>"",
	#"Connect_Quarterly_Panther_newsletters"=>"",
	#"Connect_I_dont_receive_all_of_these_please_put_me_back"=>"",
	#"Connect_House_parties_in_my_area"=>"",
	#"Connect_REVEL"=>"",
	#"Connect_Individual_visits_with_RAN_campaign_leaders"=>"",
	#"Connect_Visits_with_RAN_board_members"=>"",
	#"Connect_Other"=>"",
	"Is_there_anything_else_you_would_like_to_share_with_us"=>"",
	"First_Name"=>"",
	"Last_Name"=>"",
	"Twitter_Nameif_you_have_one"=>"",
	"Address"=>"",
	"City_Town"=>"",
	"State_Province"=>"",
	"ZIP_Postal_Code"=>"",
	"Country"=>"",
	"Email_Address"=>"",
	"Phone_Number"=>""

);

?>