<?php

// ######################################################################
// #                                                                    #
// #   header_template.php                                              #
// #                                                                    #
// #   Last Modified: 02/16/2005                                        #
// #                                                                    #
// #   Sage version 0.01                                                #
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

include('back/config.php');

$myLink = mysql_connect($dbHost,$dbUser,$dbPass);

mysql_select_db($dbName);

include('back/date_lib.php');

$teamNumberString = (string)$teamNumber;
$teamTablePrefix = 'team_' . $teamNumberString;
$teamTableName = 'team_' . $teamNumberString . '_main';
$teamTableNameLength = strlen($teamTableName);
$userTablePrefix = $teamTablePrefix . '_user_';
$userTablePrefixLength = strlen($userTablePrefix);
$rankedUsersTableName = 'team_' . $teamNumberString . '_ranked_users';

$dbCount = 0;
$dbTeamInfo = array();
$dbRankedUsers = array();

$rankedUsersResult = mysql_query("SELECT * FROM $rankedUsersTableName");
while($currentRow = mysql_fetch_row($rankedUsersResult))
{
  $dbRankedUsers[$dbCount] = $currentRow[1];
  $dbCount++;
}

$dbRankedUsersLength = count($dbRankedUsers);

$dbCount = 0;

$teamTableResult = mysql_query("SELECT * FROM $teamTableName");
while($currentRow = mysql_fetch_row($teamTableResult))
{
  $dbTeamInfo[$dbCount] = $currentRow;
  $dbCount++;
}

$dbTeamInfoLength = count($dbTeamInfo);
$dbTeamInfoLast = $dbTeamInfoLength - 1;
$dbTeamInfoLastLength = count($dbTeamInfo[$dbTeamInfoLast]);

if(($statsee == 'team') || ($statsee == 'users'))
{
  $ostatsee = $statsee;
  $statsee = $dbTeamInfo[$dbTeamInfoLast][5];
  if($ostatsee == 'users')
  {
    $statsee .= '\'s Users';
  }
}

$htmlOut = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';

$htmlOut .= '<html xmlns="http://www.w3.org/1999/xhtml"><head><title>Sage Folding@Home Stats for ';

$htmlOut .= $statsee . '</title><link rel="stylesheet" href="default.css" type="text/css" /></head><body>';

$htmlOut .= '<table width="100%" border="0" frame="void" rules="none" cellpadding="5">';

$htmlOut .= '<colgroup><col width="175" /><col width="*" /></colgroup>'; 

$htmlOut .= '<tr><td width="100%" colspan="2"><h1>Sage Folding@Home Stats for ' . $statsee . '</h1></td></tr>';

$localTime = array();
$localTime = getdate();
$CSTTime = add_to_hour_24($localTime['hours'],2);

$currentHour = hour_24_to_12($CSTTime);
$currentMinutes;
if($localTime['minutes'] < 10)
{
  $currentMinutes = '0' . $localTime['minutes'];
}
else
{
  $currentMinutes = $localTime['minutes'];
}

$currentDate = $currentHour[0] . ':' . $currentMinutes . ' ' . $currentHour[1];

$lastUpdate = $dbTeamInfo[$dbTeamInfoLast][4];
if(($lastUpdate % 3) != 0)
{
  $lastUpdate -= 1;
  if(($lastUpdate % 3) != 0)
  {
    $lastUpdate += 2;
  }
}
$lastUpdate = add_to_hour_24($lastUpdate,3);

$lastUpdate12 = hour_24_to_12($lastUpdate);

$htmlOut .= '<tr><td valign="top" height="100%">';

$htmlOut .= '<p>Team ' . $teamNumberString . ':<br />' . $dbTeamInfo[$dbTeamInfoLast][5] . '</p>';

$htmlOut .= '<p>Last update: ' . $lastUpdate12[0] . ' ' . $lastUpdate12[1];

$nextUpdate = add_to_hour_24($lastUpdate,3);
$nextUpdate12 = hour_24_to_12($nextUpdate);

$htmlOut .= '<br />Next update: ' . $nextUpdate12[0] . ' ' . $nextUpdate12[1];

$htmlOut .= '<br />Time: ' . $currentDate . '</p>';

$htmlOut .= '<p><a href="display_root.php">Team Stats: Main</a></p>';

$htmlOut .= '<p><a href="display_user.php">User Stats: All</a></p>';

$htmlOut .= '<p>User Stats:</p><form action="display_user.php" method="get"><select name="user" size="10">';

for($v = 0; $v < $dbRankedUsersLength; $v++)
{
  $htmlOut .= '<option class="sp" value="' . $dbRankedUsers[$v] . '">' . ($v + 1) . ': ' . $dbRankedUsers[$v] . '</option>';
}

$htmlOut .= '</select><br /><br /><input type="submit" /></form></td><td valign="top" height="100%">';

?>
