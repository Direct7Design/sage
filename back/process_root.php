<?php

// ######################################################################
// #                                                                    #
// #   process_root.php                                                 #
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
  die('Killed: No Database Connection');
}

$dbUsersInfo = array();
$dbRankedUsers = array();
$dbCount = 0;
$dbTeamInfo = array();
foreach($allTables as $tableName)
{
  $dbCount = 0;
  $tablesResult = mysql_query("SELECT * FROM $tableName");
  if($tableName == $rankedUsersTableName)
  {
    $rankedUsersTableExists = 1;
  }
  else if(substr($tableName,0,$userTablePrefixLength) == $userTablePrefix) // if the current table is a user's stats table
  {
    while($currentRow = mysql_fetch_row($tablesResult))
    {
      $dbUsersInfo[$dbCount] = $currentRow;
      $dbCount++;
    }
    $dbUsersInfoLength = count($dbUsersInfo);
    $dbUsersInfoLast = $dbUsersInfoLength - 1;
    $dbUsersInfoLess = $dbUsersInfoLast - 1;
    $dbUserRid = $dbUsersInfo[$dbUsersInfoLast][0];
    $dbUsersInfo[$dbUsersInfoLast][53] = (int)($dbUsersInfo[$dbUsersInfoLast][41] / $dbUsersInfo[$dbUsersInfoLast][29]);
    $dbUsersInfo[$dbUsersInfoLast][6] = $dbUsersInfo[$dbUsersInfoLast][5] - $dbUsersInfo[$dbUsersInfoLess][5];
    $dbUsersInfo[$dbUsersInfoLast][18] = $dbUsersInfo[$dbUsersInfoLast][17] - $dbUsersInfo[$dbUsersInfoLess][17];
    $dbUsersInfo[$dbUsersInfoLast][30] = $dbUsersInfo[$dbUsersInfoLast][29] - $dbUsersInfo[$dbUsersInfoLess][29];
    $dbUsersInfo[$dbUsersInfoLast][42] = $dbUsersInfo[$dbUsersInfoLast][41] - $dbUsersInfo[$dbUsersInfoLess][41];  
    $minRows = array(0 => 8, 1 => 56, 2 => 240, 3 => 2912); // minimum number of rows required for a day, week, month, year (respectively)
    $rowIncrement = array(0 => 1, 1 => 8, 2 => 8, 3 => 56); // number of rows we should increment by for a day, week, month, year (respectively)
    $storeToFirst = array(0 => 7, 1 => 8, 2 => 9, 3 => 10); // first table column index to store last day, week, month, year stuff (respectively)
    $storeFromFirst = array(0 => 6, 1 => 7, 2 => 7, 3 => 8); // first table column index to extract stuff to process last day, week, month, year (respectively)
    $storeToPerFirst = array(0 => 11, 1 => 12, 2 => 13, 3 => 14, 4 => 15, 5 => 16); // first table column index to store per hour, update, day, week, month, year stuff (respectively)
    $storeFromPerFirst = 6; // first table column index to extract stuff to process per * stuff
    $multiplyBy = array(0 => (1/3), 1 => 1, 2 => 8, 3 => 56, 4 => 240, 5 => 2912); // number to multiply the value in table column index $storeFromPerFirst by to produce per hour, update, day, week, month, year stats (respectively)
    $dbRankedUsers[$dbUsersInfo[$dbUsersInfoLast][5] - 1] = substr($tableName,$userTablePrefixLength);
    for($m = 0; $m <= 4; $m++) // step through four types {day,week,month,year} for last, then do per
    {
      if($m <= 3) // if we're working on last_* fields
      {
        if($dbUsersInfoLength == 1) // if it is the first row
        {
          for($k = 0; $k <= 4; $k++)
          {
              $dbUsersInfo[$dbUsersInfoLast][6 + $k] = 0;
              $dbUsersInfo[$dbUsersInfoLast][18 + $k] = 0;
              $dbUsersInfo[$dbUsersInfoLast][30 + $k] = 0;
              $dbUsersInfo[$dbUsersInfoLast][42 + $k] = 0;
          }
        }
        else if(($dbUsersInfoLength < $minRows[$m]) && $dbUsersInfoLength > 1) // if database doesn't span a {day,week,month,year}
        {
          for($k = 0; $k < $dbUsersInfoLength; $k += $rowIncrement[$m])
          {
            for($n = 0; $n <= 3; $n++)
            {
              $alignedStoreTo = $storeToFirst[$m] + ($n * 12);
              $alignedStoreFrom = $storeFromFirst[$m] + ($n * 12);
              $dbUsersInfo[$dbUsersInfoLast][$alignedStoreTo] += $dbUsersInfo[$k][$alignedStoreFrom];
            }
          }
        }
        else // if database spans more than or equal to {day,week,month,year}
        {
          for($k = $dbUsersInfoLength - $minRows[$m]; $k < $dbUsersInfoLength; $k += $rowIncrement[$m])
          {
            for($n = 0; $n <= 3; $n++) // step through different types {day,week,month,year}
            {
              $alignedStoreTo = $storeToFirst[$m] + ($n * 12);
              $alignedStoreFrom = $storeFromFirst[$m] + ($n * 12);
              $dbUsersInfo[$dbUsersInfoLast][$alignedStoreTo] += $dbUsersInfo[$k][$alignedStoreFrom];
            }
          }
        }
      }
      else // if we're working on per_* fields
      {
        // get per_update info
        for($k = 0; $k < $dbUsersInfoLength; $k++) // step through table rows
        {
          for($n = 0; $n <= 3; $n++) // step through types {team_rank,overall_rank,wus,points}
          {
            $alignedStoreToPer = $storeToPerFirst[1] + ($n * 12);
            $alignedStoreFromPer = $storeFromPerFirst + ($n * 12);
            $dbUsersInfo[$dbUsersInfoLast][$alignedStoreToPer] += $dbUsersInfo[$k][$alignedStoreFromPer];
            if($k == $dbUsersInfoLast) // and dbUsersInfoLast not zero?
            {
              $dbUsersInfo[$dbUsersInfoLast][$alignedStoreToPer] = (int)($dbUsersInfo[$dbUsersInfoLast][$alignedStoreToPer] / $dbUsersInfoLength);
            }
          }
        }
        // get per_[else] info
        for($p = 0; $p <= 5; $p++) // step through different types {hour,update,day,week,month,year}
        {
          for($q = 0; $q <= 3; $q++) // step through different types {team_rank,overall_rank,wus,points}
          {
            $alignedStoreToPer = $storeToPerFirst[$p] + ($q * 12);
            $alignedStoreFromPer = $storeToPerFirst[1] + ($q * 12);
            $dbUsersInfo[$dbUsersInfoLast][$alignedStoreToPer] = (int)($dbUsersInfo[$dbUsersInfoLast][$alignedStoreFromPer] * $multiplyBy[$p]);
          }
        }
      }
    }
    $userSQLQuery = 'UPDATE ' . $tableName . ' SET ';
    $userSQLQuery = $userSQLQuery . 'team_rank_update=' . $dbUsersInfo[$dbUsersInfoLast][6] . ', team_rank_last_day=' . $dbUsersInfo[$dbUsersInfoLast][7] . ', team_rank_last_week=' . $dbUsersInfo[$dbUsersInfoLast][8] . ', team_rank_last_month=' . $dbUsersInfo[$dbUsersInfoLast][9] . ', team_rank_last_year=' . $dbUsersInfo[$dbUsersInfoLast][10] . ', team_rank_per_hour=' . $dbUsersInfo[$dbUsersInfoLast][11] . ', team_rank_per_update=' . $dbUsersInfo[$dbUsersInfoLast][12] . ', team_rank_per_day=' . $dbUsersInfo[$dbUsersInfoLast][13] . ', team_rank_per_week=' . $dbUsersInfo[$dbUsersInfoLast][14] . ', team_rank_per_month=' . $dbUsersInfo[$dbUsersInfoLast][15] . ', team_rank_per_year=' . $dbUsersInfo[$dbUsersInfoLast][16] . ', ';
    $userSQLQuery = $userSQLQuery . 'overall_rank_update=' . $dbUsersInfo[$dbUsersInfoLast][18] . ', overall_rank_last_day=' . $dbUsersInfo[$dbUsersInfoLast][19] . ', overall_rank_last_week=' . $dbUsersInfo[$dbUsersInfoLast][20] . ', overall_rank_last_month=' . $dbUsersInfo[$dbUsersInfoLast][21] . ', overall_rank_last_year=' . $dbUsersInfo[$dbUsersInfoLast][22] . ', overall_rank_per_hour=' . $dbUsersInfo[$dbUsersInfoLast][23] . ', overall_rank_per_update=' . $dbUsersInfo[$dbUsersInfoLast][24] . ', overall_rank_per_day=' . $dbUsersInfo[$dbUsersInfoLast][25] . ', overall_rank_per_week=' . $dbUsersInfo[$dbUsersInfoLast][26] . ', overall_rank_per_month=' . $dbUsersInfo[$dbUsersInfoLast][27] . ', overall_rank_per_year=' . $dbUsersInfo[$dbUsersInfoLast][28] . ', ';
    $userSQLQuery = $userSQLQuery . 'wus_update=' . $dbUsersInfo[$dbUsersInfoLast][30] . ', wus_last_day=' . $dbUsersInfo[$dbUsersInfoLast][31] . ', wus_last_week=' . $dbUsersInfo[$dbUsersInfoLast][32] . ', wus_last_month=' . $dbUsersInfo[$dbUsersInfoLast][33] . ', wus_last_year=' . $dbUsersInfo[$dbUsersInfoLast][34] . ', wus_per_hour=' . $dbUsersInfo[$dbUsersInfoLast][35] . ', wus_per_update=' . $dbUsersInfo[$dbUsersInfoLast][36] . ', wus_per_day=' . $dbUsersInfo[$dbUsersInfoLast][37] . ', wus_per_week=' . $dbUsersInfo[$dbUsersInfoLast][38] . ', wus_per_month=' . $dbUsersInfo[$dbUsersInfoLast][39] . ', wus_per_year=' . $dbUsersInfo[$dbUsersInfoLast][40] . ', ';
    $userSQLQuery = $userSQLQuery . 'points_update=' . $dbUsersInfo[$dbUsersInfoLast][42] . ', points_last_day=' . $dbUsersInfo[$dbUsersInfoLast][43] . ', points_last_week=' . $dbUsersInfo[$dbUsersInfoLast][44] . ', points_last_month=' . $dbUsersInfo[$dbUsersInfoLast][45] . ', points_last_year=' . $dbUsersInfo[$dbUsersInfoLast][46] . ', points_per_hour=' . $dbUsersInfo[$dbUsersInfoLast][47] . ', points_per_update=' . $dbUsersInfo[$dbUsersInfoLast][48] . ', points_per_day=' . $dbUsersInfo[$dbUsersInfoLast][49] . ', points_per_week=' . $dbUsersInfo[$dbUsersInfoLast][50] . ', points_per_month=' . $dbUsersInfo[$dbUsersInfoLast][51] . ', points_per_year=' . $dbUsersInfo[$dbUsersInfoLast][52] . ', points_per_wu=' . $dbUsersInfo[$dbUsersInfoLast][53] . ' ';
    $userSQLQuery = $userSQLQuery . 'WHERE rid=' . $dbUserRid;
    mysql_query($userSQLQuery);
  }
  else if($tableName == $teamTableName) // if the current table is the team's main stats table
  {
    while($currentRow = mysql_fetch_row($tablesResult))
    {
      $dbTeamInfo[$dbCount] = $currentRow;
      $dbCount++;
    }
    $dbTeamInfoLength = count($dbTeamInfo);
    $dbTeamInfoLast = $dbTeamInfoLength - 1;
    $dbTeamInfoLess = $dbTeamInfoLast - 1;
    $dbTeamRid = $dbTeamInfo[$dbTeamInfoLast][0];
    $dbTeamInfo[$dbTeamInfoLast][54] = (int)($dbTeamInfo[$dbTeamInfoLast][42] / $dbTeamInfo[$dbTeamInfoLast][30]);
    $dbTeamInfo[$dbTeamInfoLast][7] = $dbTeamInfo[$dbTeamInfoLast][6] - $dbTeamInfo[$dbTeamInfoLess][6];
    $dbTeamInfo[$dbTeamInfoLast][19] = $dbTeamInfo[$dbTeamInfoLast][18] - $dbTeamInfo[$dbTeamInfoLess][18];
    $dbTeamInfo[$dbTeamInfoLast][31] = $dbTeamInfo[$dbTeamInfoLast][30] - $dbTeamInfo[$dbTeamInfoLess][30];
    $dbTeamInfo[$dbTeamInfoLast][43] = $dbTeamInfo[$dbTeamInfoLast][42] - $dbTeamInfo[$dbTeamInfoLess][42];  
    $minRows = array(0 => 8, 1 => 56, 2 => 240, 3 => 2912);
    $rowIncrement = array(0 => 1, 1 => 8, 2 => 8, 3 => 56);
    $storeToFirst = array(0 => 8, 1 => 9, 2 => 10, 3 => 11);
    $storeFromFirst = array(0 => 7, 1 => 8, 2 => 8, 3 => 9);
    $storeToPerFirst = array(0 => 12, 1 => 13, 2 => 14, 3 => 15, 4 => 16, 5 => 17);
    $storeFromPerFirst = 7;
    $multiplyBy = array(0 => (1/3), 1 => 1, 2 => 8, 3 => 56, 4 => 240, 5 => 2912);
    for($m = 0; $m <= 4; $m++) // step through four types {day,week,month,year} for last, then do per
    {
      if($m <= 3) // if we're working on last_* fields
      {
        if($dbTeamInfoLength == 1) // if it is the first row
        {
          for($k = 0; $k <= 4; $k++)
          {
              $dbTeamInfo[$dbTeamInfoLast][7 + $k] = 0;
              $dbTeamInfo[$dbTeamInfoLast][19 + $k] = 0;
              $dbTeamInfo[$dbTeamInfoLast][31 + $k] = 0;
              $dbTeamInfo[$dbTeamInfoLast][43 + $k] = 0;
          }
        }
        else if(($dbTeamInfoLength < $minRows[$m]) && $dbTeamInfoLength > 1) // if database doesn't span a {day,week,month,year}
        {
          for($k = 0; $k < $dbTeamInfoLength; $k += $rowIncrement[$m])
          {
            for($n = 0; $n <= 3; $n++)
            {
              $alignedStoreTo = $storeToFirst[$m] + ($n * 12);
              $alignedStoreFrom = $storeFromFirst[$m] + ($n * 12);
              $dbTeamInfo[$dbTeamInfoLast][$alignedStoreTo] += $dbTeamInfo[$k][$alignedStoreFrom];
            }
          }
        }
        else // if database spans more than or equal to {day,week,month,year}
        {
          for($k = $dbTeamInfoLength - $minRows[$m]; $k < $dbTeamInfoLength; $k += $rowIncrement[$m])
          {
            for($n = 0; $n <= 3; $n++) // step through different types {day,week,month,year}
            {
              $alignedStoreTo = $storeToFirst[$m] + ($n * 12);
              $alignedStoreFrom = $storeFromFirst[$m] + ($n * 12);
              $dbTeamInfo[$dbTeamInfoLast][$alignedStoreTo] += $dbTeamInfo[$k][$alignedStoreFrom];
            }
          }
        }
      }
      else // if we're working on per_* fields
      {
        // get per_update info
        for($k = 0; $k < $dbTeamInfoLength; $k++) // step through table rows
        {
          for($n = 0; $n <= 3; $n++) // step through types {team_rank,overall_rank,wus,points}
          {
            $alignedStoreToPer = $storeToPerFirst[1] + ($n * 12);
            $alignedStoreFromPer = $storeFromPerFirst + ($n * 12);
            $dbTeamInfo[$dbTeamInfoLast][$alignedStoreToPer] += $dbTeamInfo[$k][$alignedStoreFromPer];
            if(($k == $dbTeamInfoLast) && ($dbTeamInfoLast != 0))
            {
              $dbTeamInfo[$dbTeamInfoLast][$alignedStoreToPer] = (int)($dbTeamInfo[$dbTeamInfoLast][$alignedStoreToPer] / $dbTeamInfoLength);
            }
          }
        }
        // get per_[else] info
        for($p = 0; $p <= 5; $p++) // step through different types {hour,update,day,week,month,year}
        {
          for($q = 0; $q <= 3; $q++) // step through different types {team_rank,overall_rank,wus,points}
          {
            $alignedStoreToPer = $storeToPerFirst[$p] + ($q * 12);
            $alignedStoreFromPer = $storeToPerFirst[1] + ($q * 12);
            $dbTeamInfo[$dbTeamInfoLast][$alignedStoreToPer] = (int)($dbTeamInfo[$dbTeamInfoLast][$alignedStoreFromPer] * $multiplyBy[$p]);
          }
        }
      }
    }
    $teamSQLQuery = 'UPDATE ' . $tableName . ' SET ';
    $teamSQLQuery = $teamSQLQuery . 'rank_update=' . $dbTeamInfo[$dbTeamInfoLast][7] . ', rank_last_day=' . $dbTeamInfo[$dbTeamInfoLast][8] . ', rank_last_week=' . $dbTeamInfo[$dbTeamInfoLast][9] . ', rank_last_month=' . $dbTeamInfo[$dbTeamInfoLast][10] . ', rank_last_year=' . $dbTeamInfo[$dbTeamInfoLast][11] . ', rank_per_hour=' . $dbTeamInfo[$dbTeamInfoLast][12] . ', rank_per_update=' . $dbTeamInfo[$dbTeamInfoLast][13] . ', rank_per_day=' . $dbTeamInfo[$dbTeamInfoLast][14] . ', rank_per_week=' . $dbTeamInfo[$dbTeamInfoLast][15] . ', rank_per_month=' . $dbTeamInfo[$dbTeamInfoLast][16] . ', rank_per_year=' . $dbTeamInfo[$dbTeamInfoLast][17] . ', ';
    $teamSQLQuery = $teamSQLQuery . 'users_update=' . $dbTeamInfo[$dbTeamInfoLast][19] . ', users_last_day=' . $dbTeamInfo[$dbTeamInfoLast][20] . ', users_last_week=' . $dbTeamInfo[$dbTeamInfoLast][21] . ', users_last_month=' . $dbTeamInfo[$dbTeamInfoLast][22] . ', users_last_year=' . $dbTeamInfo[$dbTeamInfoLast][23] . ', users_per_hour=' . $dbTeamInfo[$dbTeamInfoLast][24] . ', users_per_update=' . $dbTeamInfo[$dbTeamInfoLast][25] . ', users_per_day=' . $dbTeamInfo[$dbTeamInfoLast][26] . ', users_per_week=' . $dbTeamInfo[$dbTeamInfoLast][27] . ', users_per_month=' . $dbTeamInfo[$dbTeamInfoLast][28] . ', users_per_year=' . $dbTeamInfo[$dbTeamInfoLast][29] . ', ';
    $teamSQLQuery = $teamSQLQuery . 'wus_update=' . $dbTeamInfo[$dbTeamInfoLast][31] . ', wus_last_day=' . $dbTeamInfo[$dbTeamInfoLast][32] . ', wus_last_week=' . $dbTeamInfo[$dbTeamInfoLast][33] . ', wus_last_month=' . $dbTeamInfo[$dbTeamInfoLast][34] . ', wus_last_year=' . $dbTeamInfo[$dbTeamInfoLast][35] . ', wus_per_hour=' . $dbTeamInfo[$dbTeamInfoLast][36] . ', wus_per_update=' . $dbTeamInfo[$dbTeamInfoLast][37] . ', wus_per_day=' . $dbTeamInfo[$dbTeamInfoLast][38] . ', wus_per_week=' . $dbTeamInfo[$dbTeamInfoLast][39] . ', wus_per_month=' . $dbTeamInfo[$dbTeamInfoLast][40] . ', wus_per_year=' . $dbTeamInfo[$dbTeamInfoLast][41] . ', ';
    $teamSQLQuery = $teamSQLQuery . 'points_update=' . $dbTeamInfo[$dbTeamInfoLast][43] . ', points_last_day=' . $dbTeamInfo[$dbTeamInfoLast][44] . ', points_last_week=' . $dbTeamInfo[$dbTeamInfoLast][45] . ', points_last_month=' . $dbTeamInfo[$dbTeamInfoLast][46] . ', points_last_year=' . $dbTeamInfo[$dbTeamInfoLast][47] . ', points_per_hour=' . $dbTeamInfo[$dbTeamInfoLast][48] . ', points_per_update=' . $dbTeamInfo[$dbTeamInfoLast][49] . ', points_per_day=' . $dbTeamInfo[$dbTeamInfoLast][50] . ', points_per_week=' . $dbTeamInfo[$dbTeamInfoLast][51] . ', points_per_month=' . $dbTeamInfo[$dbTeamInfoLast][52] . ', points_per_year=' . $dbTeamInfo[$dbTeamInfoLast][53] . ', points_per_wu=' . $dbTeamInfo[$dbTeamInfoLast][54] . ' ';
    $teamSQLQuery = $teamSQLQuery . 'WHERE rid=' . $dbTeamRid;
    mysql_query($teamSQLQuery);
  }
}

if($rankedUsersTableExists == 0)
{
  mysql_query("CREATE TABLE $rankedUsersTableName (rank MEDIUMINT PRIMARY KEY NOT NULL AUTO_INCREMENT, user VARCHAR(75))");
}
else
{
  mysql_query("TRUNCATE $rankedUsersTableName");
}

$dbRankedUsersLength = count($dbRankedUsers);
for($t = 0; $t < $dbRankedUsersLength; $t++)
{
  $rankedUsersSQLQuery = 'INSERT INTO ' . $rankedUsersTableName . ' (user) VALUES (\'' . $dbRankedUsers[$t] . '\')';
  mysql_query($rankedUsersSQLQuery);
}

?>
