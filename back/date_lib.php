<?php

/*
 * date_lib.php                                                         
 *                                                                      
 * Last modified 04/16/2005 by hpxchan                                  
 *                                                                      
 * Sage Folding@Home Stats System, version 1.0.7                         
 *                                                                      
 * Copyright (C) 2005 SamuraiDev                                        
 *                                                                      
 * This program is free software; you can redistribute it and/or    
 * modify it under the terms of the GNU General Public License      
 * as published by the Free Software Foundation; either version 2   
 * of the License, or (at your option) any later version.           
 */

function add_leading_zeros($base_number, $digits)
{
    $is_negative = 0;
    if($base_number < 0) {
        $base_number *= -1;
        $is_negative = 1;
    }
    $digits = floor($digits);
    if($digits < 1) {
        return 0;
    }
    $number_out = $base_number;
    $digits_base = pow(10, ($digits - 1));
    for($dynamic_base = $digits_base; $base_number < $dynamic_base; $dynamic_base /= 10) {
        $number_out = '0' . $number_out;
    }
    if($is_negative) {
        $number_out = '-' . $number_out;
    }
    return $number_out;
}

function day_to_number($day_abbrev)
{
    $day_abbrev = substr($day_abbrev,0,2);
    $number_out = 0;

    switch($day_abbrev) {
    
    case 'Su':
        $number_out = 1;
        break;

    case 'Mo':
        $number_out = 2;
        break;

    case 'Tu':
        $number_out = 3;
        break;

    case 'We':
        $number_out = 4;
        break;

    case 'Th':
        $number_out = 5;
        break;

    case 'Fr':
        $number_out = 6;
        break;

    case 'Sa':
        $number_out = 7;
        break;

    default:
        break;
    }

    return $number_out;
}

function abbrev_to_day($day_abbrev)
{
    $day_abbrev = substr($day_abbrev,0,2);
    $day_full = 0;

    switch($day_abbrev) {

    case 'Su':
        $day_full = 'Sunday';
        break;

    case 'Mo':
        $day_full = 'Monday';
        break;

    case 'Tu':
        $day_full = 'Tuesday';
        break;

    case 'We':
        $day_full = 'Wednesday';
        break;

    case 'Th':
        $day_full = 'Thursday';
        break;

    case 'Fr':
        $day_full = 'Friday';
        break;

    case 'Sa':
        $day_full = 'Saturday';
        break;

    default:
        break;
    }

    return $day_full;
}

function number_to_day($day_number)
{
    $day_full = 0;
    switch($day_number) {

    case 1:
        $day_full = 'Sunday';
        break;

    case 2:
        $day_full = 'Monday';
        break;

    case 3:
        $day_full = 'Tuesday';
        break;

    case 4:
        $day_full = 'Wednesday';
        break;

    case 5:
        $day_full = 'Thursday';
        break;

    case 6:
        $day_full = 'Friday';
        break;

    case 7:
        $day_full = 'Saturday';
        break;

    default:
        break;
    }

    return $day_full;
}

function month_to_number($month_abbrev)
{
    $month_abbrev = substr($month_abbrev,0,3);
    $number_out = 0;

    switch($month_abbrev) {

    case 'Jan':
        $number_out = 1;
        break;

    case 'Feb':
        $number_out = 2;
        break;

    case 'Mar':
        $number_out = 3;
        break;

    case 'Apr':
        $number_out = 4;
        break;

    case 'May':
        $number_out = 5;
        break;

    case 'Jun':
        $number_out = 6;
        break;

    case 'Jul':
        $number_out = 7;
        break;

    case 'Aug':
        $number_out = 8;
        break;

    case 'Sep':
        $number_out = 9;
        break;

    case 'Oct':
        $number_out = 10;
        break;

    case 'Nov':
        $number_out = 11;
        break;

    case 'Dec':
        $number_out = 12;
        break;

    default:
        break;
    }

    return $number_out;
}

function number_to_month($month_number)
{
    $month_full = 0;
    
    switch($month_number) {

    case 1:
        $month_full = 'January';
        break;

    case 2:
        $month_full = 'February';
        break;

    case 3:
        $month_full = 'March';
        break;

    case 4:
        $month_full = 'April';
        break;

    case 5:
        $month_full = 'May';
        break;

    case 6:
        $month_full = 'June';
        break;

    case 7:
        $month_full = 'July';
        break;

    case 8:
        $month_full = 'August';
        break;

    case 9:
        $month_full = 'September';
        break;

    case 10:
        $month_full = 'October';
        break;

    case 11:
        $month_full = 'November';
        break;

    case 12:
        $month_full = 'December';
        break;

    default:
        break;
    }

    return $month_full;
}

function abbrev_to_month($month_abbrev)
{
    $month_abbrev = substr($month_abbrev,0,3);
    $month_full = 0;

    switch($month_abbrev) {

    case 'Jan':
        $month_full = 'January';
        break;

    case 'Feb':
        $month_full = 'February';
        break;

    case 'Mar':
        $month_full = 'March';
        break;

    case 'Apr':
        $month_full = 'April';
        break;

    case 'May':
        $month_full = 'May';
        break;

    case 'Jun':
        $month_full = 'June';
        break;

    case 'Jul':
        $month_full = 'July';
        break;

    case 'Aug':
        $month_full = 'August';
        break;

    case 'Sep':
        $month_full = 'September';
        break;

    case 'Oct':
        $month_full = 'October';
        break;

    case 'Nov':
        $month_full = 'November';
        break;

    case 'Dec':
        $month_full = 'December';
        break;

    default:
        break;
    }

    return $month_full;
}

function hour_24_to_12($in_hour)
{
    $in_hour = floor($in_hour);
    $out_hour = array();

    if($in_hour == 0) {
        $out_hour[0] = 12;
        $out_hour[1] = 'AM';
        return $out_hour;
    } elseif($in_hour == 24) {
        $out_hour[0] = 12;
        $out_hour[1] = 'AM';
        return $out_hour;
    } elseif(($in_hour <= 11) && ($in_hour >= 1)) {
        $out_hour[0] = $in_hour;
        $out_hour[1] = 'AM';
        return $out_hour;
    } elseif(($in_hour >= 13) && ($in_hour <= 23)) {
        $out_hour[0] = $in_hour - 12;
        $out_hour[1] = 'PM';
        return $out_hour;
    } elseif($in_hour == 12) {
        $out_hour[0] = $in_hour;
        $out_hour[1] = 'PM';
        return $out_hour;
    } else {
        return 0;
    }
}

function add_to_hour_24($base_hour, $add_hour)
{
    $base_hour = floor($base_hour);
    $add_hour = floor($add_hour);

    if($base_hour == 24) {
        $base_hour = 0;
    } elseif(($base_hour > 24) || ($base_hour < 0)) {
        return 0;
    }

    $base_hour += $add_hour;

    while($base_hour > 23) {
        $base_hour -= 24;
    }

    return $base_hour;
}

?>
