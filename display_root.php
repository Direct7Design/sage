<?php

// ######################################################################
// #                                                                    #
// #   display_root.php                                                 #
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

$statsee = 'team';

include('header_template.php');

$times = array(0 => 'Current', 1 => 'Update', 2 => 'Day', 3 => 'Week', 4 => 'Month', 5 => 'Year');
$categories = array(0 => 'Rank', 1 => 'Users', 2 => 'Work Units', 3 => 'Points');
$firstCategoryIndex = 6;
$categoryIncrement = 12;
$perOffset = 6;

$currentValue;
$categoriesLength = count($categories);
$categoryColWidth = 100 / $categoriesLength;

$htmlOut .= '<table width="100%" border="0" rules="none" frame="void"><colgroup>';

for($x = 0; $x <= $categoriesLast; $x++)
{
  $htmlOut .= '<col width="' . $categoryColWidth . '%" />';
}

$htmlOut .= '</colgroup>';

for($x = 0; $x < count($times); $x++)
{
  $htmlOut .= '<tr><td colspan="' . $categoriesLength . '"><h2>';
  if($x != 0)
  {
    $htmlOut .= 'Change Per ' . $times[$x] . ':</h2></td></tr>';
    for($z = 0; $z < $categoriesLength; $z++)
    {
      $alignedTeamIndex = $firstCategoryIndex + ($categoryIncrement * $z) + $x + $perOffset;
      $htmlOut .= '<td><h3>' . $categories[$z] . '</h3><p>';
      if(($dbTeamInfo[$dbTeamInfoLast][$alignedTeamIndex] > 0) && ($x != 0))
      {
        $currentValue = '+' . $dbTeamInfo[$dbTeamInfoLast][$alignedTeamIndex];
      }
      else
      {
        $currentValue = $dbTeamInfo[$dbTeamInfoLast][$alignedTeamIndex];
      }
      $htmlOut .= $currentValue;
      $htmlOut .= '</p></td>';
    }
    $htmlOut .= '</tr><tr><td colspan="' . $categoryColWidth . '"><h2>';
    $htmlOut .= 'Change Last ';
  }
  $htmlOut .= $times[$x] . ':</h2>';
  $htmlOut .= '</tr><tr>';
  for($y = 0; $y < $categoriesLength; $y++)
  {
    $alignedTeamIndex = $firstCategoryIndex + ($categoryIncrement * $y) + $x;
    $htmlOut .= '<td><h3>' . $categories[$y] . '</h3><p>';
    if(($dbTeamInfo[$dbTeamInfoLast][$alignedTeamIndex] > 0) && ($x != 0))
    {
      $currentValue = '+' . $dbTeamInfo[$dbTeamInfoLast][$alignedTeamIndex];
    }
    else
    {
      $currentValue = $dbTeamInfo[$dbTeamInfoLast][$alignedTeamIndex];
    }
    $htmlOut .= $currentValue;
    $htmlOut .= '</p></td>';
  }
  $htmlOut .= '</tr>';
  if($x == 0)
  {
    $htmlOut .= '<tr><td colspan="' . $categoriesLength . '"><h3>Current Points Per Work Unit: ';
    $htmlOut .= $dbTeamInfo[$dbTeamInfoLast][$dbTeamInfoLastLength - 1] . '</h3></td></tr>';
  }
}

$htmlOut .= '</table>';

include('footer_template.php');

?>
