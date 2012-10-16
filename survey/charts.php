<?php

require_once('HighRoller/HighRoller.php');
require_once('HighRoller/HighRollerSeriesData.php');
require_once('HighRoller/HighRollerBarChart.php');
require_once('HighRoller/HighRollerLineChart.php');

require_once('../_header.php');


function add_data_count($field, $where=NULL){
	global $db;
	$sql = 'SELECT count(Custom_Data) as count  FROM results WHERE '.$field.' != "" '.$where;
	$S = $db->Execute($sql) or die($db->errorMsg());
	$s = $S->FetchRow();
	return intval($s['count']);
}

function add_data_percent($field,$where=NULL){
	global $db;
	$sql = 'SELECT count(Custom_Data) as count  FROM results WHERE '.$field.' != "" '.$where;
	$S = $db->Execute($sql) or die($db->errorMsg());
	$s = $S->FetchRow();

	if ($where) { $where = substr($where, 3);
	$where = 'where '.$where;
	}
	

	$sql = 'SELECT count(Custom_Data) as count  FROM results '.$where;
	$C = $db->Execute($sql) or die($sql.$db->errorMsg());
	$c = $C->FetchRow();

	$out= ($s['count'] / $c['count']) * 100;

	return $out;
}


$work_array =array(
	"Work_Protecting_rainforests"=>"Protecting rainforests",
	"Work_Supporting_sustainable_agriculture"=>"Supporting sustainable agriculture",
	"Work_Stopping_climate_change"=>"Stopping climate change",
	"Work_Promoting_clean_energy_solutions"=>"Promoting clean energy solutions",
	"Work_Defending_Indigenous_rights"=>"Defending Indigenous rights",
	"Work_Fighting_for_corporate_accountability"=>"Fighting for corporate accountability",
	"Work_Saving_endangered_species"=>"Saving endangered species",
	"Work_Opposing_coal_and_oil_extraction"=>"Opposing coal and oil extraction",
	"Work_Supporting_non_violent_direct_actions"=>"Supporting non-violent direct actions"
);


$involved_array =array(
	"Involved_Taking_online_actionsign_a_petition_send_an_email"=>"Taking online action (sign a petition, send an email)",
	"Involved_Making_phone_calls_to_targets"=>"Making phone calls to targets",
	"Involved_social_media"=>"Share RAN campaigns and messages to your friends using social media",
	"Involved_Helping_to_create_visual_or_written_or_multimedias"=>"Helping to create visual or written or multimedia content for RAN's campaigns",
	"Involved_Attending_a_protest_or_event_in_my_community"=>"Attending a protest or event in my community",
	"Involved_Co_hosting_an_event"=>"Co-hosting an event",
	"Involved_Joining_a_RAN_book_club_movie_discussion_group"=>"Joining a RAN book club/movie discussion group focused on environmental topics",
	"Involved_Attending_a_webinar_or_conference_call_with_RAN"=>"Attending a webinar or conference call with RAN issue experts",
	"Involved_Raise_money_for_RAN"=>"Raise money for RAN",
	"Involved_Donating_money_to_RAN"=>"Donating money to RAN"
);	
	
$brand_array =array(
	"Work_Working_at_the_intersection_of_forest_destruction_climate_"=>"Working at the intersection of forest destruction, climate change, and human right",
	"Work_Confronting_the_root_causes_of_environmental_destruction"=>"Confronting the root causes of environmental destruction",
	"Work_Using_a_savvy_blend_of_inside_outside_strategys"=>"Using a savvy blend of inside-outside strategy (banner hangs + boardroom negotiations)",
	"Work_Transforming_the_marketplace_by_focusing_on_changing"=>"Transforming the marketplace by focusing on changing the behavior of industry leaders",
	"Work_Working_in_solidarity_with_Indigenous_and_frontline"=>"Working in solidarity with Indigenous and frontline communities and other allies",
	"Work_Creating_precedent_setting_victories"=>"Creating precedent setting victories"
);

$groups_array =array(
	"Groups_Sierra_Club"=>"Sierra Club",
	"Groups_WWF"=>"WWF",
	"Groups_Greenpeace"=>"Greenpeace",
	"Groups_Center_for_Biological_Diversity"=>"Center for Biological Diversity",
	"Groups_Credo_Action"=>"Credo Action",
	"Groups_MoveOn"=>"MoveOn",
	"Groups_Rising_Tide"=>"Rising Tide",
	"Groups_Earth_First"=>"Earth First!"
);

$type_array =array(
	"Describe_Street_activist_rabble_rouser"=>"Street activist/rabble rouser",
	"Describe_Member_of_RAN_chapter"=>"Member of RAN chapter",
	"Describe_Online_activist_advocate"=>"Online activist/advocate",
	"Describe_Frontline_impacted_community_member"=>"Frontline impacted community member",
	"Describe_Involved_with_other_non_profits"=>"Involved with other non-profits",
	"Describe_Business_person"=>"Business person",
	"Describe_Policy_wonk_researcher_academic"=>"Policy wonk/researcher/academic",
	"Describe_Journalist_blogger_member_of_the_media"=>"Journalist/blogger/member of the media",
	"Describe_Legislator_public_figure"=>"Legislator/public figure",
	"Describe_Just_a_bit_curious"=>"Just a bit curious"
);


function make_chart($div_id,$fields_array,$title){

	$where_online = 'and CollectorID = "28933038" ';
	$where_donors = 'and CollectorID = "28941647" ';
	$where_all = 'and CollectorID != "0" ';
	$where_np = 'and CollectorID != "0" and Describe_Involved_with_other_non_profits != "" ';
	$where_rabble= 'and CollectorID != "0" and Describe_Street_activist_rabble_rouser != "" ';
	$where_frontline= 'and CollectorID != "0" and Describe_Frontline_impacted_community_member != "" ';
	$where_busines= 'and CollectorID != "0" and Describe_Business_person != "" ';
	$where_md= 'and CollectorID != "0" and MajorD =1 ';
	$where_new= 'and CollectorID != "0" and Date_Created > "2012-01-01" ';
	$where_old= 'and CollectorID != "0" and Date_Created < "2010-01-01"';


	
	foreach ($fields_array as $k => $v){
		$work_labels[]=$v;
		$donors[] = add_data_percent($k,$where_donors);
		$online[] = add_data_percent($k,$where_online);
		$all[] = add_data_percent($k,$where_all);
		$np[] = add_data_percent($k,$where_np);
		$rabble[] = add_data_percent($k,$where_rabble);
		$frontline[] = add_data_percent($k,$where_frontline);
		$business[] = add_data_percent($k,$where_busines);
		$old[] = add_data_percent($k,$where_old);
		$new[] = add_data_percent($k,$where_new);
		$md[] = add_data_percent($k,$where_md);

	
	}
	
	$work = new HighRollerBarChart();
	$work->chart->renderTo = $div_id;
	$work->title->text = $title;
	$work->plotOptions->bar->pointWidth=8;
	$work->plotOptions->bar->groupPadding=.1;
	
	
	$work->xAxis->categories= $work_labels;
	




	
	$series7 = new HighRollerSeriesData();
	$series7->addName('non-profits')->addData($np);
	$work->addSeries($series7);	

	$series6 = new HighRollerSeriesData();
	$series6->addName('Frontline')->addData($frontline);
	$work->addSeries($series6);	

	$series5 = new HighRollerSeriesData();
	$series5->addName('Rabble rouser')->addData($rabble);
	$work->addSeries($series5);	

	#$series4 = new HighRollerSeriesData();
	#$series4->addName('Business person')->addData($business);
	#$work->addSeries($series4);	

	$series10 = new HighRollerSeriesData();
	$series10->addName('New')->addData($new);
	$work->addSeries($series10);	
	
	
	$series9 = new HighRollerSeriesData();
	$series9->addName('Old')->addData($old);
	$work->addSeries($series9);	
	

	$series2 = new HighRollerSeriesData();
	$series2->addName('Non donors')->addData($online);
	$work->addSeries($series2);
	
	$series1 = new HighRollerSeriesData();
	$series1->addName('Donors')->addData($donors);
	$work->addSeries($series1);
	
	$series8 = new HighRollerSeriesData();
	$series8->addName('Major Donor')->addData($md);
	$work->addSeries($series8);	
		
	$series3 = new HighRollerSeriesData();
	$series3->addName('All')->addData($all);
	$work->addSeries($series3);
	
	return $work;
}

$work = make_chart('work',$work_array,'What part of RANs work resonates the most with you?');
$groups = make_chart('groups',$groups_array,'Are you a member or supporter of any of the following groups?');
$brand = make_chart('brand',$brand_array,'Which best describes RANs work to you?');
$involved = make_chart('involved',$involved_array,'How would you prefer to get more involved with RAN?');
$type = make_chart('type',$type_array,'How would you describe yourself?');



?>

<head>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
 <script type='text/javascript' src='Highcharts/js/highcharts.js'></script>
 <script src="Highcharts/js/modules/exporting.js"></script>
 
 
 
</head>

<body>
<div id="work" style="height:900"></div>
<a href="answers.php?q=work"></a>
<div id="type" style="height:900"></div>
<div id="brand" style="height:900"></div>
<div id="groups" style="height:900"></div>
<div id="involved" style="height:900"></div>

<script type="text/javascript">
<?php 

echo $work->renderChart();
echo $type->renderChart();
echo $brand->renderChart();
echo $groups->renderChart();
echo $involved->renderChart();  
  
  
  ?>
</script>
</body>