<?php

// ######################################################################
// #                                                                    #
// #   refresh_visitor_users.php                                        #
// #                                                                    #
// #   Last Modified: 02/13/2005                                        #
// #                                                                    #
// #   Sage version 0.01                                                #
// #                                                                    #
// ######################################################################
// #                                                                    #
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

$stanfordTeamsHandle = 'http://vspx27.stanford.edu/daily_team_summary.txt';

// The 100 teams above and below the home team will have their information processed. You can change this as needed by changing the numbers below.
// $teamsMinimumRank is the rank of the lowest team whose information will be processed, or, if the home team's rank is less than 104,
// then $teamsMinimumRank is 4, because the first five lines must be skipped (they are the headers and aggregate teams). $teamsMaximumRank
// is the rank of the highest team whose information will be processed, plus 5, taking into account the four irrelevant lines at the top.
// You will notice that 5 lines are skipped, not 4. I don't understand why it works like this, but it just does. Trust me.

/* for now teams will not be counted, because i'm lazy
$teamsMinimumRank = $myTeamRank - 100;
if ($teamsMinimumRank < 4)
{
  $teamsMinimumRank = 4;
}
$teamsMaximumRank = $myTeamRank + 105;

// $allTeamsCount increments by 1 each cycle through the following while loop, counting the teams,
// so we can regulate how many are parsed

$allTeamsCount = 0;

// $allTeamsHandle holds handle for remote stanford stats page

$allTeamsHandle = fopen($stanfordTeamsHandle,"r");

// begin universal teams list parsing

while ($allTeamsCount < $teamsMaximumRank)
{
  if (($allTeamsCount < $teamsMinimumRank))
  {
    $teamLine = fgets($allTeamsHandle,4096);
    $allTeamsCount++;
    continue;
  }
  $teamLine = Trim(fgets($allTeamsHandle,4096));
  print($teamLine . '<br />');
  preg_match("/^[0-9]+/",$teamLine,$teamNumberInteger);
  $teamNumberString = (string)$teamNumberInteger[0];
  $myTeamNumberTableName = 'team_' . $teamNumberString;
  $teamNumberStringLength = strlen($teamNumberString);
  $teamLine = Trim(substr_replace($teamLine,'',0,$teamNumberStringLength));
  preg_match("/[0-9]+$/",$teamLine,$teamWusInteger);
  $teamWusString = (string)$teamWusInteger[0];
  $teamWusStringLength = strlen($teamWusString);
  $teamWusStringPosition = 0 - $teamWusStringLength;
  $teamLine = Trim(substr_replace($teamLine,'',$teamWusStringPosition,$teamWusStringLength));
  preg_match("/[0-9]+(\.)?[0-9]*$/",$teamLine,$teamScoreFloat);
  $teamScoreString = (string)$teamScoreFloat[0];
  $teamScoreStringLength = strlen($teamScoreString);
  $teamScoreStringPosition = 0 - $teamScoreStringLength;
  $teamNameString = Trim(substr_replace($teamLine,'',$teamScoreStringPosition,$teamScoreStringLength));
  $teamScoreInteger = (int)$teamScoreFloat[0];
//  mysql_query("INSERT INTO teams_main(name, tnumber, wus, points) VALUES(\"$teamNameString\", $teamNumberInteger[0], $teamWusInteger[0], $teamScoreInteger)");
//  mysql_query("CREATE TABLE IF NOT EXISTS `$myTeamNumberTableName` (name VARCHAR(75) PRIMARY KEY, wus BIGINT, points BIGINT)");
  $allTeamsCount++;
}

// close the $allTeamsHandle handle

fclose($allTeamsHandle);

end not searching teams ;-) */

?>
