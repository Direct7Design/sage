<?php

// ######################################################################
// #                                                                    #
// #   refresh_home_users.php                                           #
// #                                                                    #
// #   Last Modified: 02/15/2005                                        #
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

if($lockHole != $lockKey)
{
  die('Killed: Unauthorized Connection');
}

if(!$myLink)
{
  die('Killed: No Database Link');
}

$stanfordUsersHandle = 'http://vspx27.stanford.edu/teamstats/team' . $teamNumber . '.txt';

$teamUsersCount = 0;

$allUsersCount = 0;

$allUsersHandle = fopen($stanfordUsersHandle,"r");

while (!feof($allUsersHandle))
{
  if (($allUsersCount <= 2) || ($allUsersCount == 4) || (($allUsersCount >=7) && ($allUsersCount <=9)))
  {
    $userLine = fgets($allUsersHandle,4096);
    $allUsersCount++;
    continue;
  }
  else if ($allUsersCount == 3)
  {
    $dateLine = Trim(fgets($allUsersHandle,4096));
    preg_match("/[0-9]+$/",$dateLine,$yearInteger);
    $yearInteger = $yearInteger[0];
    $yearString = (string)$yearInteger;
    $yearStringLength = strlen($yearString);
    $yearStringPosition = 0 - $yearStringLength;
    $dateLine = Trim(substr_replace($dateLine,'',$yearStringPosition,$yearStringLength));
    preg_match("/^(\S)+/",$dateLine,$dayString);
    $dayStringLength = strlen($dayString[0]);
    $dateLine = Trim(substr_replace($dateLine,'',0,$dayStringLength));
    preg_match("/^(\S)+/",$dateLine,$monthString);
    $monthStringLength = strlen($monthString[0]);
    $monthInteger = month_to_number(substr($monthString[0],0,3));
    $dateLine = Trim(substr_replace($dateLine,'',0,$monthStringLength));
    preg_match("/^[0-9]{1,2}/",$dateLine,$dayNumberInteger);
    $dayNumberInteger = $dayNumberInteger[0];
    $dayNumberString = (string)$dayNumberInteger;
    $dayNumberStringLength = strlen($dayNumberInteger);
    $dateLine = Trim(substr_replace($dateLine,'',0,$dayNumberStringLength));
    preg_match("/^[0-9]{2,2}/",$dateLine,$hourInteger);
    $hourInteger = $hourInteger[0];
    $hourString = (string)$hourInteger;
    $allUsersCount++;
    continue;
  }
  else if ($allUsersCount == 5)
  {
    $teamLine = Trim(fgets($allUsersHandle,4096));
    preg_match("/[0-9]+$/",$teamLine,$teamWusInteger);
    $teamWusString = (string)$teamWusInteger[0];
    $teamWusStringLength = strlen($teamWusString);
    $teamWusStringPosition = 0 - $teamWusStringLength;
    $teamLine = Trim(substr_replace($teamLine,'',$teamWusStringPosition,$teamWusStringLength));
    preg_match("/[0-9]+(\.)?[0-9]*$/",$teamLine,$teamScoreFloat);
    $teamScoreString = (string)$teamScoreFloat[0];
    $teamScoreInteger = (int)$teamScoreFloat[0];
    $teamScoreStringLength = strlen($teamScoreString);
    $teamScoreStringPosition = 0 - $teamScoreStringLength;
    $teamLine = Trim(substr_replace($teamLine,'',$teamScoreStringPosition,$teamScoreStringLength));
    preg_match("/^[0-9]+/",$teamLine,$teamNumberInteger);
    $teamNumberString = (string)$teamNumberInteger[0];
    $teamNumberStringLength = strlen($teamNumberString);
    $teamNameString = Trim(substr_replace($teamLine,'',0,$teamNumberStringLength));
    $allUsersCount++;
    continue;
  }
  else if ($allUsersCount == 6)
  {
    $teamLine = Trim(fgets($allUsersHandle,4096));
    preg_match("/[0-9]+$/",$teamLine,$teamRank);
    $allUsersCount++;
    continue;
  }
  else
  {
    $userLine = Trim(fgets($allUsersHandle,4096));
    preg_match("/[0-9]+$/",$userLine,$userTeamNumberInteger);
    if(!$userTeamNumberInteger)
    {
      continue;
    }
    $userTeamNumberString = (string)$userTeamNumberInteger[0];
    $userTeamNumberStringLength = strlen($userTeamNumberString);
    $userTeamNumberStringPosition = 0 - $userTeamNumberStringLength;
    $userLine = Trim(substr_replace($userLine,'',$userTeamNumberStringPosition,$userTeamNumberStringLength));
    preg_match("/[0-9]+$/",$userLine,$userWusInteger);
    $userWusString = (string)$userWusInteger[0];
    $userWusStringLength = strlen($userWusString);
    $userWusStringPosition = 0 - $userWusStringLength;
    $userLine = Trim(substr_replace($userLine,'',$userWusStringPosition,$userWusStringLength));
    preg_match("/[0-9]+(\.)?[0-9]*$/",$userLine,$userScoreFloat);
    $userScoreString = (string)$userScoreFloat[0];
    $userScoreStringLength = strlen($userScoreString);
    $userScoreStringPosition = 0 - $userScoreStringLength;
    $userScoreInteger = (int)$userScoreFloat[0];
    $userLine = Trim(substr_replace($userLine,'',$userScoreStringPosition,$userScoreStringLength));
    preg_match("/^[0-9]+/",$userLine,$userOverallRankInteger);
    $userOverallRankString = (string)$userOverallRankInteger[0];
    $userOverallRankStringLength = strlen($userOverallRankString);
    $userLine = Trim(substr_replace($userLine,'',0,$userOverallRankStringLength));
    preg_match("/^[0-9]+/",$userLine,$userTeamRankInteger);
    $userTeamRankString = (string)$userTeamRankInteger[0];
    $userTeamRankStringLength = strlen($userTeamRankString);
    $userNameString = Trim(substr_replace($userLine,'',0,$userTeamRankStringLength));
    $userTableName = '' . $userTablePrefix . $userNameString;
    $userTableExists = 0;
    if($allTables)
    {
      $userTableExists = array_search($userTableName,$allTables);
    }
    if($userTableExists == 0)
    {
      mysql_query("CREATE TABLE $userTableName (rid MEDIUMINT AUTO_INCREMENT PRIMARY KEY NOT NULL, year MEDIUMINT, month SMALLINT, day SMALLINT, hour SMALLINT, team_rank MEDIUMINT, team_rank_update MEDIUMINT, team_rank_last_day MEDIUMINT, team_rank_last_week MEDIUMINT, team_rank_last_month MEDIUMINT, team_rank_last_year MEDIUMINT, team_rank_per_hour MEDIUMINT, team_rank_per_update MEDIUMINT, team_rank_per_day MEDIUMINT, team_rank_per_week MEDIUMINT, team_rank_per_month MEDIUMINT, team_rank_per_year MEDIUMINT, overall_rank INT, overall_rank_update MEDIUMINT, overall_rank_last_day MEDIUMINT, overall_rank_last_week MEDIUMINT, overall_rank_last_month INT, overall_rank_last_year INT, overall_rank_per_hour MEDIUMINT, overall_rank_per_update MEDIUMINT, overall_rank_per_day MEDIUMINT, overall_rank_per_week MEDIUMINT, overall_rank_per_month INT, overall_rank_per_year INT, wus BIGINT, wus_update MEDIUMINT, wus_last_day MEDIUMINT, wus_last_week INT, wus_last_month INT, wus_last_year INT, wus_per_hour MEDIUMINT, wus_per_update MEDIUMINT, wus_per_day MEDIUMINT, wus_per_week INT, wus_per_month INT, wus_per_year INT, points BIGINT, points_update INT, points_last_day INT, points_last_week INT, points_last_month BIGINT, points_last_year BIGINT, points_per_hour MEDIUMINT, points_per_update INT, points_per_day INT, points_per_week INT, points_per_month BIGINT, points_per_year BIGINT, points_per_wu MEDIUMINT)");
      $allTablesLength = count($allTables);
      $allTables[$allTablesLength] = $userTableName;
    }
    mysql_query("INSERT INTO $userTableName (year, month, day, hour, team_rank, overall_rank, wus, points) VALUES($yearInteger, $monthInteger, $dayNumberInteger, $hourInteger, $userTeamRankInteger[0], $userOverallRankInteger[0], $userWusInteger[0], $userScoreInteger)");
    $allUsersCount++;
    $teamUsersCount++;
    continue;
  }
}
if($allTables)
{
  $teamTableExists = array_search($teamTableName,$allTables);
}
if($teamTableExists == 0)
{
  mysql_query("CREATE TABLE $teamTableName (rid MEDIUMINT AUTO_INCREMENT PRIMARY KEY NOT NULL, year MEDIUMINT, month SMALLINT, day SMALLINT, hour SMALLINT, name VARCHAR(75), rank MEDIUMINT, rank_update MEDIUMINT, rank_last_day MEDIUMINT, rank_last_week MEDIUMINT, rank_last_month MEDIUMINT, rank_last_year MEDIUMINT, rank_per_hour MEDIUMINT, rank_per_update MEDIUMINT, rank_per_day MEDIUMINT, rank_per_week MEDIUMINT, rank_per_month MEDIUMINT, rank_per_year MEDIUMINT, users INT, users_update MEDIUMINT, users_last_day MEDIUMINT, users_last_week MEDIUMINT, users_last_month MEDIUMINT, users_last_year INT, users_per_hour MEDIUMINT, users_per_update MEDIUMINT, users_per_day MEDIUMINT, users_per_week MEDIUMINT, users_per_month MEDIUMINT, users_per_year INT, wus BIGINT, wus_update MEDIUMINT, wus_last_day MEDIUMINT, wus_last_week INT, wus_last_month INT, wus_last_year INT, wus_per_hour MEDIUMINT, wus_per_update MEDIUMINT, wus_per_day MEDIUMINT, wus_per_week INT, wus_per_month INT, wus_per_year INT, points BIGINT, points_update INT, points_last_day INT, points_last_week INT, points_last_month BIGINT, points_last_year BIGINT, points_per_hour MEDIUMINT, points_per_update INT, points_per_day INT, points_per_week INT, points_per_month BIGINT, points_per_year BIGINT, points_per_wu MEDIUMINT)");
}
mysql_query("INSERT INTO $teamTableName (year, month, day, hour, name, rank, users, wus, points) VALUES($yearInteger, $monthInteger, $dayNumberInteger, $hourInteger, \"$teamNameString\", $teamRank[0], $teamUsersCount, $teamWusInteger[0], $teamScoreInteger)");

fclose($allUsersHandle);

?>
