<?php

// ######################################################################
// #                                                                    #
// #   extract_home.php                                                 #
// #                                                                    #
// #   Last Modified: 03/11/2005                                        #
// #                                                                    #
// #   Sage version 0.02                                                #
// #                                                                    #
// ######################################################################
// #                                                                    #
// ######################################################################
// #                                                                    #
// #   Copyright (C) 2005 SamuraiDev                                    #
// #                                                                    #
// #   This program is free software; you can redistribute it and/or    #
// #   modify it under the terms of the GNU General Public License      #
// #   as published by the Free Software Foundation; either version 2   #
// #   of the License, or (at your option) any later version.           #
// #                                                                    #
// ######################################################################

if($lockHole != $lockKey)
{
	die('Killed: Unauthorized Connection');
}

if(!get_index_table(0))
{
	$makeIndex = 'CREATE TABLE `tables_index` (rid MEDIUMINT AUTO_INCREMENT NOT NULL PRIMARY KEY, name VARCHAR(30)) TYPE=MyISAM;';
	mysql_query($makeIndex) or die('Could not create index table tables_index <br />' . $makeIndex . '<br />' . mysql_error());;
}

include('process_home.php');

//$stanfordTeamHandle = 'http://vspx27.stanford.edu/teamstats/team' . $teamNumber . '.txt';
$stanfordTeamHandle = 'home.txt';

$teamUsers = 0;

$linesCount = 0;

$currentTableName;
$teamName;
$teamNumber;
$teamPoints;
$teamWUs;
$teamRank;

$teamPageHandle = fopen($stanfordTeamHandle,'r');

if(!$teamPageHandle)
{
	die('Killed: Invalid Team Page: ' . $stanfordTeamHandle);
}

while(!feof($teamPageHandle))
{
	$currentLine = fgets($teamPageHandle,4096);

	if($currentLine == '' || $currentLine == ' ' || $linesCount <= 2 || $linesCount == 4 || ($linesCount >= 7 && $linesCount <= 9)) {} // if the line sucks
	elseif($linesCount == 3) // if it is the date line
	{
		$dateArray = explode(" ", $currentLine);
		$timeArray = explode(":", $dateArray[3]);
		$dayOfWeek = day_to_number($dateArray[0]);
		$month = month_to_number($dateArray[1]);
		$dayOfMonth = $dateArray[2];
		$hour = $timeArray[0];
		$timeZone = $dateArray[4];
		$year = Trim($dateArray[5]);
		$currentTableName = '' . $year . add_leading_zeros($month,2) . add_leading_zeros($dayOfMonth,2) . add_leading_zeros($hour,2);
		$tableCreate = 'CREATE TABLE `' . $currentTableName . '` (row_id MEDIUMINT AUTO_INCREMENT PRIMARY KEY NOT NULL, team_number MEDIUMINT, row_type SMALLINT, name VARCHAR(75), rank INT, rank_last_update MEDIUMINT, rank_last_day MEDIUMINT, rank_last_week MEDIUMINT, rank_last_month INT, rank_last_year INT, rank_per_hour MEDIUMINT, rank_per_update MEDIUMINT, rank_per_day MEDIUMINT, rank_per_week MEDIUMINT, rank_per_month INT, rank_per_year INT, trankusers INT, trankusers_last_update MEDIUMINT, trankusers_last_day MEDIUMINT, trankusers_last_week MEDIUMINT, trankusers_last_month MEDIUMINT, trankusers_last_year INT, trankusers_per_hour MEDIUMINT, trankusers_per_update MEDIUMINT, trankusers_per_day MEDIUMINT, trankusers_per_week MEDIUMINT, trankusers_per_month MEDIUMINT, trankusers_per_year INT, wus BIGINT, wus_last_update MEDIUMINT, wus_last_day MEDIUMINT, wus_last_week INT, wus_last_month INT, wus_last_year BIGINT, wus_per_hour MEDIUMINT, wus_per_update MEDIUMINT, wus_per_day MEDIUMINT, wus_per_week INT, wus_per_month INT, wus_per_year BIGINT, points BIGINT, points_last_update INT, points_last_day INT, points_last_week INT, points_last_month BIGINT, points_last_year BIGINT, points_per_hour MEDIUMINT, points_per_update INT, points_per_day INT, points_per_week INT, points_per_month BIGINT, points_per_year BIGINT, points_per_wu MEDIUMINT, occurrences MEDIUMINT) TYPE=MyISAM;';
		mysql_query($tableCreate) or die('Could not create table ' . $currentTableName . '<br />' . $tableCreate . '<br />' . mysql_error());
		$tablesUpdate = "INSERT INTO `tables_index` (name) VALUES ('$currentTableName');";
		mysql_query($tablesUpdate) or die('Could not insert row for ' . $currentTableName . ' into tables_index<br />' . $tablesUpdate . '<br />' . mysql_error());
		print('<br />');	
	}
	elseif($linesCount == 5) // if it is the team info line
	{
		$teamArray = explode("\t", $currentLine);
		$teamNumber = $teamArray[0];
		$teamName = $teamArray[1];
		$teamPoints = floor($teamArray[2]);
		$teamWUs = $teamArray[3];
	}
	elseif($linesCount == 6) // if it is the team rank line
	{
		$teamRankArray = explode(": ", $currentLine);
		$teamRank = $teamRankArray[1];
	}
	else // if it is a user info line
	{
		$userArray = explode("\t", $currentLine);
		$userOverallRank = $userArray[0];
		$userTeamRank = $userArray[1];
		$userName = $userArray[2];
		$userPoints = $userArray[3];
		$userWUs = $userArray[4];
		$userTeamNumber = $userArray[5];
		$teamUsers = $userTeamRank;
		process_home($teamNumber, 1, $userName, $userOverallRank, $userTeamRank, $userWUs, $userPoints);
		$userInsert = "INSERT INTO `$currentTableName` (team_number, row_type, name, rank, trankusers, wus, points) VALUES ($teamNumber, 1, '$userName', $userOverallRank, $userTeamRank, $userWUs, $userPoints);";
		mysql_query($userInsert) or die('Could not insert user row for ' . $userName . ' into table ' . $currentTableName . '<br />' . $userInsert . '<br />' . mysql_error());
	}

	$linesCount++;
}

process_home($teamNumber, 0, $teamName, $teamRank, $teamUsers, $teamWUs, $teamPoints);
$teamInsert = "INSERT INTO `$currentTableName` (team_number, row_type, name, rank, trankusers, wus, points) VALUES ($teamNumber, 0, '$teamName', $teamRank, $teamUsers, $teamWUs, $teamPoints);";
mysql_query($teamInsert) or die('Could not insert team row for ' . $teamName . ' into table ' . $currentTableName . '<br />' . $teamInsert . '<br />' . mysql_error());

fclose($teamPageHandle);

?>
