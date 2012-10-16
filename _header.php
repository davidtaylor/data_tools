<?php 
require_once('config.php');
require_once('connect.php');
require_once('functions/re_functions.php');
require_once('functions/salsa_functions.php');


set_time_limit(0);


?>

<html>
<head>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<link rel="stylesheet" href="http://online.ran.org/api/bootstrap/css/bootstrap.css">
<script src="http://online.ran.org/api/bootstrap/js/bootstrap.js"></script>



<style>
.maintable {	
	width:900px;
}

.maintable td {
		vertical-align:top;
}


.headerrow {
	background-color:#390;
	color:#FFF;
}

.redrow {
	
		background-color:red;

}

.green {
 color: green;

}

.red {
 color: red;

}

.yellow {
 color: yellow;

}
</style>
 <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>

</head>
<body>

<div class="navbar navbar-fixed-top ">
	<div class="navbar-inner">
    	<div class="container-fluid">
          <img src="/jaguar/img/jaguar_icon.jpg" style="margin-right:15px; padding:0px; float:left;" >	
          <a class="brand" href="#">RAN's Data Tools</a>

          <div class="nav-collapse">
            <ul class="nav">

              
              <li class="dropdown">
              	<a class="dropdown-toggle" href="#" data-toggle="dropdown">Raiser's Edge Tools<b class="caret"></b></a>
              	<ul class="dropdown-menu">

	              <li><a href="/data/re/re_export_update.php">Download Salsa Donations for RE Import</a></li>
	              <li><a href="/data/re/re_doit.php">Upload RE Data for Salsa Sync</a></a></li>
	            </ul>
              </li>

              <li class="dropdown">
              	<a class="dropdown-toggle" href="#" data-toggle="dropdown">Salsa Reports<b class="caret"></b></a>
              	<ul class="dropdown-menu">
              		<li class><a href="/data/reports/donation_report.php">One Time Donation</a></li>
	              	<li class><a href="/data/reports/donation_report.php?rec=1">Recurring Donations</a></li>
              		<li><a href="/data/reports/report_action.php">Action</a></li>
              		<li><a href="/data/reports/report_new_supporters.php">New Supporters</a></li>

              	</ul>
              </li>
               <li class="dropdown">
			    <a class="dropdown-toggle" href="#" data-toggle="dropdown">Survey Data<b class="caret"></b></a>
              	<ul class="dropdown-menu">
              		<li><a href="/data/survey/charts.php">Graphs</a></li>
              		<li><a href="/data/survey/answers.php">Text Answers</a></li>
              		<li><a href="/data/survey/">Salsa Fixin</a></li>
              	</ul>
              </li>

               <li class="dropdown">
			    <a class="dropdown-toggle" href="#" data-toggle="dropdown">Data Sync<b class="caret"></b></a>
              	<ul class="dropdown-menu">
              		<li><a href="/data/monsterpants/person.php">By Person</a></li>
              		<li><a href="/data/monsterpants/object.php">By Object</a></li>
              		<li><a href="/data/monsterpants/date.php">By Date</a></li>
              	</ul>
              </li>

           <!--   <li >
              	<a class="dropdown-toggle" href="#" data-toggle="dropdown">Salsa Management<b class="caret"></b></a>
              	<ul class="dropdown-menu">
              		<li></li>
              	</ul>
              </li>  -->

              <li class="dropdown">
              	<a class="dropdown-toggle" href="#" data-toggle="dropdown">Salsa Cleaning Tools<b class="caret"></b></a>
              	<ul class="dropdown-menu">
              		<li><a href="/data/tools/map_referral.php">Referral Code Map</a></li>
              		<li><a href="/data/tools/map_source_codes.php">Source Code Map</a></li>
              		<li><a href="/data/tools/clean_orphans.php">Orphan Cleaning Tools</a></li>
              		<li><a href="/data/tools/clean_groups_to_action.php">Move Groups to Actions</a></li>
              		<li><a href="/data/tools/clean_assign_campaign.php">Assign Campaigns to Objects</a></li>              
              		<li><a href="/data/tools/clean_gift_membership.php">Gift Membership Tools</a></li>
              		<li ><a href="/data/tools/tool_suppression.php">Suppression Files</a></li>
              		<li ><a href="/data/tools/screen_scrape.php">Screen Scrape</a></li>

              	</ul>
              </li>
              <li class><a href="/jaguar">Jaguar</a></li>

            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
</div>

<div class="container">


