<?php

// ######################################################################
// #                                                                    #
// #   date_lib.php                                                     #
// #                                                                    #
// #   Last Modified: 03/10/2005                                        #
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

function add_leading_zeros($baseNumber, $digits)
{
	$isNegative = 0;
	if($baseNumber < 0)
	{
		$baseNumber *= -1;
		$isNegative = 1;
	}
	$digits = floor($digits);
	if($digits < 1)
	{
		return 0;
	}
	$numberOut = $baseNumber;
	$digitsBase = pow(10, ($digits - 1));
	for($dynamicBase = $digitsBase; $baseNumber < $dynamicBase; $dynamicBase /= 10)
	{
		$numberOut = '0' . $numberOut;
	}
	if($isNegative)
	{
		$numberOut = '-' . $numberOut;
	}
	return $numberOut;
}

function day_to_number($dayAbbrev)
{
	$dayAbbrev = substr($dayAbbrev,0,2);
	if($dayAbbrev == 'Su')
	{
		return 1;
	}
	elseif($dayAbbrev == 'Mo')
	{
		return 2;
	}
	elseif($dayAbbrev == 'Tu')
	{
		return 3;
	}
	elseif($dayAbbrev == 'We')
	{
		return 4;
	}
	elseif($dayAbbrev == 'Th')
	{
		return 5;
	}
	elseif($dayAbbrev == 'Fr')
	{
		return 6;
	}
	elseif($dayAbbrev == 'Sa')
	{
		return 7;
	}
	else
	{
		return 0;
	}
}

function abbrev_to_day($dayAbbrev)
{
	$dayAbbrev = substr($dayAbbrev,0,2);
	if($dayAbbrev == 'Su')
	{
		return 'Sunday';
	}
	elseif($dayAbbrev == 'Mo')
	{
		return 'Monday';
	}
	elseif($dayAbbrev == 'Tu')
	{
		return 'Tuesday';
	}
	elseif($dayAbbrev == 'We')
	{
		return 'Wednesday';
	}
	elseif($dayAbbrev == 'Th')
	{
		return 'Thursday';
	}
	elseif($dayAbbrev == 'Fr')
	{
		return 'Friday';
	}
	elseif($dayAbbrev == 'Sa')
	{
		return 'Saturday';
	}
	else
	{
		return 0;
	}
}

function number_to_day($dayNumber)
{
	if($dayNumber == 1)
	{
		return 'Sun';
	}
	elseif($dayNumber == 2)
	{
		return 'Mon';
	}
	elseif($dayNumber == 3)
	{
		return 'Tue';
	}
	elseif($dayNumber == 4)
	{
		return 'Wed';
	}
	elseif($dayNumber == 5)
	{
		return 'Thu';
	}
	elseif($dayNumber == 6)
	{
		return 'Fri';
	}
	elseif($dayNumber == 7)
	{
		return 'Sat';
	}
	else
	{
		return 0;
	}
}

function month_to_number($monthAbbrev)
{
	$monthAbbrev = substr($monthAbbrev,0,3);
	if($monthAbbrev == 'Jan')
	{
		return 1;
	}
	elseif($monthAbbrev == 'Feb')
	{
		return 2;
	}
	elseif($monthAbbrev == 'Mar')
	{
		return 3;
	}
	elseif($monthAbbrev == 'Apr')
	{
		return 4;
	}
	elseif($monthAbbrev == 'May')
	{
		return 5;
	}
	elseif($monthAbbrev == 'Jun')
	{
		return 6;
	}
	elseif($monthAbbrev == 'Jul')
	{
		return 7;
	}
	elseif($monthAbbrev == 'Aug')
	{
		return 8;
	}
	elseif($monthAbbrev == 'Sep')
	{
		return 9;
	}
	elseif($monthAbbrev == 'Oct')
	{
		return 10;
	}
	elseif($monthAbbrev == 'Nov')
	{
		return 11;
	}
	elseif($monthAbbrev == 'Dec')
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
	if($monthNumber == 1)
	{
		return 'Jan';
	}
	elseif($monthNumber == 2)
	{
		return 'Feb';
	}
	elseif($monthNumber == 3)
	{
		return 'Mar';
	}
	elseif($monthNumber == 4)
	{
		return 'Apr';
	}
	elseif($monthNumber == 5)
	{
		return 'May';
	}
	elseif($monthNumber == 6)
	{
		return 'Jun';
	}
	elseif($monthNumber == 7)
	{
		return 'Jul';
	}
	elseif($monthNumber == 8)
	{
		return 'Aug';
	}
	elseif($monthNumber == 9)
	{
		return 'Sep';
	}
	elseif($monthNumber == 10)
	{
		return 'Oct';
	}
	elseif($monthNumber == 11)
	{
		return 'Nov';
	}
	elseif($monthNumber == 12)
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
	$monthAbbrev = substr($monthAbbrev,0,3);
	if($monthAbbrev = 'Jan')
	{
		return 'January';
	}
	elseif($monthAbbrev = 'Feb')
	{
		return 'February';
	}
	elseif($monthAbbrev = 'Mar')
	{
		return 'March';
	}
	elseif($monthAbbrev = 'Apr')
	{
		return 'April';
	}
	elseif($monthAbbrev = 'May')
	{
		return 'May';
	}
	elseif($monthAbbrev = 'Jun')
	{
		return 'June';
	}
	elseif($monthAbbrev = 'Jul')
	{
		return 'July';
	}
	elseif($monthAbbrev = 'Aug')
	{
		return 'August';
	}
	elseif($monthAbbrev = 'Sep')
	{
		return 'September';
	}
	elseif($monthAbbrev = 'Oct')
	{
		return 'October';
	}
	elseif($monthAbbrev = 'Nov')
	{
		return 'November';
	}
	elseif($monthAbbrev = 'Dec')
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
	elseif($inHour == 24)
	{
		$outHour[0] = 12;
		$outHour[1] = 'AM';
		return $outHour;
	}
	elseif(($inHour <= 11) && ($inHour >= 1))
	{
		$outHour[0] = $inHour;
		$outHour[1] = 'AM';
		return $outHour;
	}
	elseif(($inHour >= 13) && ($inHour <= 23))
	{
		$outHour[0] = $inHour - 12;
		$outHour[1] = 'PM';
		return $outHour;
	}
	elseif($inHour == 12)
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
	elseif(($baseHour > 24) || ($baseHour < 0))
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
