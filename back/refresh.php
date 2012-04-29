<?php

// ######################################################################
// #                                                                    #
// #   refresh.php                                                      #
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

include('config.php');

if($_GET[$lockHole] != $lockKey)
{
	die('Killed: Unauthorized Connection');
}
else
{
	$lockHole = $lockKey;
}

$myLink = mysql_connect($dbHost,$dbUser,$dbPass) or die('No connection to MySQL database');
mysql_select_db($dbName);

mysql_query('DROP TABLE `2005022112`;');
mysql_query('DROP TABLE `tables_index`;');

include('date_lib.php');

function get_index_table($mode = 1)
{
	$indexTableSelect;
	if($mode == 0)
	{
		$indexTableSelect = 'SELECT `name` FROM `tables_index` WHERE `rid` = 1;';
	}
	else
	{
		$mode = 1;
		$indexTableSelect = 'SELECT * FROM `tables_index`;';
	}
	$indexTableResult = mysql_query($indexTableSelect);
	if(!$indexTableResult)
	{
		return 0;
	}
	if($mode == 1)
	{
		$indexTableArray = array();
		$i = 0;
		while($currentRow = mysql_fetch_row($indexTableResult))
		{
			$indexTableArray[$i] = $currentRow;
			$i++;
		}
		return $indexTableArray;
	}
	elseif($mode == 0)
	{
		$currentRow = mysql_fetch_row($indexTableResult);
		return $currentRow[0];
	}
}

// Stanford team stats: http://vspx27.stanford.edu/daily_team_summary.txt
// Stanford specific team stats: http://vspx27.stanford.edu/teamstats/team37941.txt

$teamNumberString = (string)$teamNumber;
$teamTablePrefix = 'team_' . $teamNumberString;
$teamTableName = 'team_' . $teamNumberString . '_main';
$teamTableNameLength = strlen($teamTableName);
$userTablePrefix = $teamTablePrefix . '_user_';
$userTablePrefixLength = strlen($userTablePrefix);
$rankedUsersTableName = 'team_' . $teamNumberString . '_ranked_users';

$teamRank;
$teamScoreInteger;
$teamWusInteger;
$teamNameString;

$monthInteger;
$hourInteger;
$dayNumberInteger;
$yearInteger;

include_once('extract_home.php');

//
// close mysql link
//

mysql_close($myLink);

?>
