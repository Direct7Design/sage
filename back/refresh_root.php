<?php

// ######################################################################
// #                                                                    #
// #   refresh_root.php                                                 #
// #                                                                    #
// #   Last Modified: 02/15/2005                                        #
// #                                                                    #
// #   Sage version 0.01                                                #
// #                                                                    #
// ######################################################################
// #                                                                    #
// #   Refreshes the stats database using text files from Stanford's    #
// #   Folding@Home stats servers. Those files are only updated once    #
// #   every three hours, so it is not practical (not to mention it is  #
// #   rude to Stanford) to run this script more than once every        #
// #   three hours.                                                     #
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

$myLink = mysql_connect($dbHost,$dbUser,$dbPass);
mysql_select_db($dbName);

include('date_lib.php');

function refresh_mysql_tables_list()
{
  global $allTables;
  global $userTableExists;
  $userTableExists = 0;
  global $teamTableExists;
  $teamTableExists = 0;
  global $rankedUsersTableExists;
  $rankedUsersTableExists = 0;
  $tableCount = 0;
  $tablesResult = mysql_query("SHOW TABLES");
  while($currentTable = mysql_fetch_row($tablesResult))
  {
    $allTables[$tableCount] = $currentTable[0];
    $tableCount++;
  }
  global $allTablesLength;
  $allTablesLength = count($allTables);
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

refresh_mysql_tables_list();

include_once('refresh_home_users.php');

include_once('refresh_visitor_users.php');

refresh_mysql_tables_list();

include_once('process_root.php');

//
// close mysql link
//

mysql_close($myLink);

?>
