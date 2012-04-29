<?php

// ######################################################################
// #                                                                    #
// #   date_lib.php                                                     #
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

function month_to_number($monthAbbrev)
{
  if ($monthAbbrev == 'Jan')
  {
    return 1;
  }
  else if ($monthAbbrev == 'Feb')
  {
    return 2;
  }
  else if ($monthAbbrev == 'Mar')
  {
    return 3;
  }
  else if ($monthAbbrev == 'Apr')
  {
    return 4;
  }
  else if ($monthAbbrev == 'May')
  {
    return 5;
  }
  else if ($monthAbbrev == 'Jun')
  {
    return 6;
  }
  else if ($monthAbbrev == 'Jul')
  {
    return 7;
  }
  else if ($monthAbbrev == 'Aug')
  {
    return 8;
  }
  else if ($monthAbbrev == 'Sep')
  {
    return 9;
  }
  else if ($monthAbbrev == 'Oct')
  {
    return 10;
  }
  else if ($monthAbbrev == 'Nov')
  {
    return 11;
  }
  else if ($monthAbbrev == 'Dec')
  {
    return 12;
  }
  else
  {
    return 0;
  }
}

function number_to_month($monthNumber)
{
  if ($monthNumber == 1)
  {
    return 'Jan';
  }
  else if ($monthNumber == 2)
  {
    return 'Feb';
  }
  else if ($monthNumber == 3)
  {
    return 'Mar';
  }
  else if ($monthNumber == 4)
  {
    return 'Apr';
  }
  else if ($monthNumber == 5)
  {
    return 'May';
  }
  else if ($monthNumber == 6)
  {
    return 'Jun';
  }
  else if ($monthNumber == 7)
  {
    return 'Jul';
  }
  else if ($monthNumber == 8)
  {
    return 'Aug';
  }
  else if ($monthNumber == 9)
  {
    return 'Sep';
  }
  else if ($monthNumber == 10)
  {
    return 'Oct';
  }
  else if ($monthNumber == 11)
  {
    return 'Nov';
  }
  else if ($monthNumber == 12)
  {
    return 'Dec';
  }
  else
  {
    return 0;
  }
}

function abbrev_to_month($monthAbbrev)
{
  if ($monthAbbrev = 'Jan')
  {
    return 'January';
  }
  else if ($monthAbbrev = 'Feb')
  {
    return 'February';
  }
  else if ($monthAbbrev = 'Mar')
  {
    return 'March';
  }
  else if ($monthAbbrev = 'Apr')
  {
    return 'April';
  }
  else if ($monthAbbrev = 'May')
  {
    return 'May';
  }
  else if ($monthAbbrev = 'Jun')
  {
    return 'June';
  }
  else if ($monthAbbrev = 'Jul')
  {
    return 'July';
  }
  else if ($monthAbbrev = 'Aug')
  {
    return 'August';
  }
  else if ($monthAbbrev = 'Sep')
  {
    return 'September';
  }
  else if ($monthAbbrev = 'Oct')
  {
    return 'October';
  }
  else if ($monthAbbrev = 'Nov')
  {
    return 'November';
  }
  else if ($monthAbbrev = 'Dec')
  {
    return 'December';
  }
  else
  {
    return 0;
  }
}

function hour_24_to_12($inHour)
{
  $inHour = floor($inHour);
  $outHour = array();
  if($inHour == 0)
  {
    $outHour[0] = 12;
    $outHour[1] = 'AM';
    return $outHour;
  }
  else if($inHour == 24)
  {
    $outHour[0] = 12;
    $outHour[1] = 'AM';
    return $outHour;
  }
  else if(($inHour <= 11) && ($inHour >= 1))
  {
    $outHour[0] = $inHour;
    $outHour[1] = 'AM';
    return $outHour;
  }
  else if(($inHour >= 13) && ($inHour <= 23))
  {
    $outHour[0] = $inHour - 12;
    $outHour[1] = 'PM';
    return $outHour;
  }
  else if($inHour == 12)
  {
    $outHour[0] = $inHour;
    $outHour[1] = 'PM';
    return $outHour;
  }
  else
  {
    return 0;
  }
}

function add_to_hour_24($baseHour,$addHour)
{
  $baseHour = floor($baseHour);
  $addHour = floor($addHour);
  if($baseHour == 24)
  {
    $baseHour = 0;
  }
  else if(($baseHour > 24) || ($baseHour < 0))
  {
    return 0;
  }
  $baseHour += $addHour;
  while($baseHour > 23)
  {
    $baseHour -= 24;
  }
  return $baseHour;
}

?>
