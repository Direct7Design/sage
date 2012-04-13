<?php

/*
 * process_home.php                                                     
 *                                                                      
 * Last modified 04/24/2005 by hpxchan                                  
 *                                                                      
 * Copyright (C) 2005 SamuraiDev                                        
 *                                                                      
 * This program is free software; you can redistribute it and/or    
 * modify it under the terms of the GNU General Public License      
 * as published by the Free Software Foundation; either version 2   
 * of the License, or (at your option) any later version.           
 */

if( !defined('IN_SAGE') ) {
    die('Killed: Unauthorized Connection');
}

// $type is 1 for user stats, 0 for team stats

$this_where_clause = "WHERE `rid` = '$rid';";

// $main_stats_array holds stuff we're going to put into the current table
// we'll add more to it later
$main_stats_array = array( 'rid' => $rid, 'team_number' => $team_number, 'row_type' => $type, 'name' => $name, 'rank' => $rank, 'trankusers' => $trankusers, 'wus' => $wus, 'points' => $points );
if( $wus >= 1 ) {
    $main_stats_array['points_per_wu'] = floor( $points / $wus );
} else {
    $main_stats_array['points_per_wu'] = 0;
}

$age = $stats_index_array[$rid][1];

// if the current table is the only table (THIS PART IS NOT FUN)
if( $tables_last == 0 || ( ! $age ) ) {

    $update_stats_index = "INSERT INTO `stats_index` (`rid`, `row_type`, `name`, `team_number`, `age`, `first_table`) VALUES ('$rid', $type, '$name', $team_number, 1, '$current_table_name');";
    $db->sql_query( $update_stats_index ) or die( 'Could not update stats_index for ' . $name . '.<br />' . $update_stats_index . '<br />' . $db->sql_error() . '<br />' );

    $main_stats_array['rank_last_update'] = 0;
    $main_stats_array['trankusers_last_update'] = 0;
    $main_stats_array['wus_last_update'] = 0;
    $main_stats_array['points_last_update'] = 0;

    $main_stats_array['rank_last_day'] = 0;
    $main_stats_array['trankusers_last_day'] = 0;
    $main_stats_array['wus_last_day'] = 0;
    $main_stats_array['points_last_day'] = 0;

    $main_stats_array['rank_last_week'] = 0;
    $main_stats_array['trankusers_last_week'] = 0;
    $main_stats_array['wus_last_week'] = 0;
    $main_stats_array['points_last_week'] = 0;

    $main_stats_array['rank_last_month'] = 0;
    $main_stats_array['trankusers_last_month'] = 0;
    $main_stats_array['wus_last_month'] = 0;
    $main_stats_array['points_last_month'] = 0;

    $main_stats_array['rank_last_year'] = 0;
    $main_stats_array['trankusers_last_year'] = 0;
    $main_stats_array['wus_last_year'] = 0;
    $main_stats_array['points_last_year'] = 0;

    $main_stats_array['rank_per_hour'] = 0;
    $main_stats_array['trankusers_per_hour'] = 0;
    $main_stats_array['wus_per_hour'] = 0;
    $main_stats_array['points_per_hour'] = 0;

    $main_stats_array['rank_per_update'] = 0;
    $main_stats_array['trankusers_per_update'] = 0;
    $main_stats_array['wus_per_update'] = 0;
    $main_stats_array['points_per_update'] = 0;

    $main_stats_array['rank_per_year'] = 0;
    $main_stats_array['trankusers_per_year'] = 0;
    $main_stats_array['wus_per_year'] = 0;
    $main_stats_array['points_per_year'] = 0;

    $main_stats_array['rank_per_day'] = 0;
    $main_stats_array['trankusers_per_day'] = 0;
    $main_stats_array['wus_per_day'] = 0;
    $main_stats_array['points_per_day'] = 0;

    $main_stats_array['rank_per_week'] = 0;
    $main_stats_array['trankusers_per_week'] = 0;
    $main_stats_array['wus_per_week'] = 0;
    $main_stats_array['points_per_week'] = 0;

    $main_stats_array['rank_per_month'] = 0;
    $main_stats_array['trankusers_per_month'] = 0;
    $main_stats_array['wus_per_month'] = 0;
    $main_stats_array['points_per_month'] = 0;
    
// more than one table
} else {
       
    // *_last_* stuff

    $first_table = $stats_index_array[$rid][2];

    // increment age and update the table
    $age++;
    $stats_index_update = "UPDATE `stats_index` SET `age`=" . $age . ' ' . $this_where_clause;
    $db->sql_query( $stats_index_update ) or die( 'Could not update stats_index<br />' . $stats_index_update . '<br />' . $db->sql_error() . '<br />' );

    // * last update
    $rank_last_update = $rank - $last_table_array[$rid][4];
    $trankusers_last_update = $trankusers - $last_table_array[$rid][16];
    $wus_last_update = $wus - $last_table_array[$rid][28];
    $points_last_update = $points - $last_table_array[$rid][40];

    $main_stats_array['rank_last_update'] = $rank_last_update;
    $main_stats_array['trankusers_last_update'] = $trankusers_last_update;
    $main_stats_array['wus_last_update'] = $wus_last_update;
    $main_stats_array['points_last_update'] = $points_last_update;

    // * {last,per} year
    if( $age <= 2920 ) {
        $main_stats_array['rank_last_year'] = $last_table_array[$rid][9] + $rank_last_update;
        $main_stats_array['trankusers_last_year'] = $last_table_array[$rid][21] + $trankusers_last_update;
        $main_stats_array['wus_last_year'] = $last_table_array[$rid][33] + $wus_last_update;
        $main_stats_array['points_last_year'] = $last_table_array[$rid][45] + $points_last_update;

        $main_stats_array['rank_per_year'] = floor( $main_stats_array['rank_last_year'] * ( 2920 / $age ) );
        $main_stats_array['trankusers_per_year'] = floor( $main_stats_array['trankusers_last_year'] * ( 2920 / $age ) );
        $main_stats_array['wus_per_year'] = floor( $main_stats_array['wus_last_year'] * ( 2920 / $age ) );
        $main_stats_array['points_per_year'] = floor( $main_stats_array['points_last_year'] * ( 2920 / $age ) );
    } else {
        $main_stats_array['rank_last_year'] = $last_table_array[$rid][9] + $rank_last_update - $last_year_array[$rid][0];
        $main_stats_array['trankusers_last_year'] = $last_table_array[$rid][21] + $trankusers_last_update - $last_year_array[$rid][1];
        $main_stats_array['wus_last_year'] = $last_table_array[$rid][33] + $wus_last_update - $last_year_array[$rid][2];
        $main_stats_array['points_last_year'] = $last_table_array[$rid][45] + $points_last_update - $last_year_array[$rid][3];

        $main_stats_array['rank_per_year'] = floor( ( $last_table_array[$rid][15] + $rank_last_update ) * ( 2920 / 2921 ) );
        $main_stats_array['trankusers_per_year'] = floor( ( $last_table_array[$rid][27] + $trankusers_last_update ) * ( 2920 / 2921 ) );
        $main_stats_array['wus_per_year'] = floor( ( $last_table_array[$rid][39] + $wus_last_update ) * ( 2920 / 2921 ) );
        $main_stats_array['points_per_year'] = floor( ( $last_table_array[$rid][51] + $points_last_update ) * ( 2920 / 2921 ) );
    }

    // * last month
    if( $age <= 240 ) {
        $main_stats_array['rank_last_month'] = $last_table_array[$rid][8] + $rank_last_update;
        $main_stats_array['trankusers_last_month'] = $last_table_array[$rid][20] + $trankusers_last_update;
        $main_stats_array['wus_last_month'] = $last_table_array[$rid][32] + $wus_last_update;
        $main_stats_array['points_last_month'] = $last_table_array[$rid][44] + $points_last_update;
    } else {
        $main_stats_array['rank_last_month'] = $last_table_array[$rid][8] + $rank_last_update - $last_month_array[$rid][0];
        $main_stats_array['trankusers_last_month'] = $last_table_array[$rid][20] + $trankusers_last_update - $last_month_array[$rid][1];
        $main_stats_array['wus_last_month'] = $last_table_array[$rid][32] + $wus_last_update - $last_month_array[$rid][2];
        $main_stats_array['points_last_month'] = $last_table_array[$rid][44] + $points_last_update - $last_month_array[$rid][3];
    }

    // * last week
    if( $age <= 56 ) {
        $main_stats_array['rank_last_week'] = $last_table_array[$rid][7] + $rank_last_update;
        $main_stats_array['trankusers_last_week'] = $last_table_array[$rid][19] + $trankusers_last_update;
        $main_stats_array['wus_last_week'] = $last_table_array[$rid][31] + $wus_last_update;
        $main_stats_array['points_last_week'] = $last_table_array[$rid][43] + $points_last_update;
    } else {
        $main_stats_array['rank_last_week'] = $last_table_array[$rid][7] + $rank_last_update - $last_week_array[$rid][0];
        $main_stats_array['trankusers_last_week'] = $last_table_array[$rid][19] + $trankusers_last_update - $last_week_array[$rid][1];
        $main_stats_array['wus_last_week'] = $last_table_array[$rid][31] + $wus_last_update - $last_week_array[$rid][2];
        $main_stats_array['points_last_week'] = $last_table_array[$rid][43] + $points_last_update - $last_week_array[$rid][3];
    }

    // * last day
    if( $age <= 8 ) {
        $main_stats_array['rank_last_day'] = $last_table_array[$rid][6] + $rank_last_update;
        $main_stats_array['trankusers_last_day'] = $last_table_array[$rid][18] + $trankusers_last_update;
        $main_stats_array['wus_last_day'] = $last_table_array[$rid][30] + $wus_last_update;
        $main_stats_array['points_last_day'] = $last_table_array[$rid][42] + $points_last_update;
    } else {
        $main_stats_array['rank_last_day'] = $last_table_array[$rid][6] + $rank_last_update - $last_day_array[$rid][0];
        $main_stats_array['trankusers_last_day'] = $last_table_array[$rid][18] + $trankusers_last_update - $last_day_array[$rid][1];
        $main_stats_array['wus_last_day'] = $last_table_array[$rid][30] + $wus_last_update - $last_day_array[$rid][2];
        $main_stats_array['points_last_day'] = $last_table_array[$rid][42] + $points_last_update - $last_day_array[$rid][3];
    }

    // * per month
    $main_stats_array['rank_per_month'] = floor( $main_stats_array['rank_per_year'] / 12 );
    $main_stats_array['trankusers_per_month'] = floor( $main_stats_array['trankusers_per_year'] / 12 );
    $main_stats_array['wus_per_month'] = floor( $main_stats_array['wus_per_year'] / 12 );
    $main_stats_array['points_per_month'] = floor( $main_stats_array['points_per_year'] / 12 );

    // * per week
    $main_stats_array['rank_per_week'] = floor( $main_stats_array['rank_per_year'] / 52 );
    $main_stats_array['trankusers_per_week'] = floor( $main_stats_array['trankusers_per_year'] / 52 );
    $main_stats_array['wus_per_week'] = floor( $main_stats_array['wus_per_year'] / 52 );
    $main_stats_array['points_per_week'] = floor( $main_stats_array['points_per_year'] / 52 );

    // * per day
    $main_stats_array['rank_per_day'] = floor( $main_stats_array['rank_per_year'] / 365 );
    $main_stats_array['trankusers_per_day'] = floor( $main_stats_array['trankusers_per_year'] / 365 );
    $main_stats_array['wus_per_day'] = floor( $main_stats_array['wus_per_year'] / 365 );
    $main_stats_array['points_per_day'] = floor( $main_stats_array['points_per_year'] / 365 );

    // * per update
    $main_stats_array['rank_per_update'] = floor( $main_stats_array['rank_per_year'] / 2920 );
    $main_stats_array['trankusers_per_update'] = floor( $main_stats_array['trankusers_per_year'] / 2920 );
    $main_stats_array['wus_per_update'] = floor( $main_stats_array['wus_per_year'] / 2920 );
    $main_stats_array['points_per_update'] = floor( $main_stats_array['points_per_year'] / 2920 );

    // * per hour
    $main_stats_array['rank_per_hour'] = floor( $main_stats_array['rank_per_year'] / 8760 );
    $main_stats_array['trankusers_per_hour'] = floor( $main_stats_array['trankusers_per_year'] / 8760 );
    $main_stats_array['wus_per_hour'] = floor( $main_stats_array['wus_per_year'] / 8760 );
    $main_stats_array['points_per_hour'] = floor( $main_stats_array['points_per_year'] / 8760 );

}

// start making big insert query in $process_insert_query ...	
$process_insert_query = 'INSERT INTO `' . $current_table_name . '` (';

$main_stats_array_length = count( $main_stats_array );
$i = 0;

// add field names to $process_insert_query
foreach( $main_stats_array as $key => $value ) {
    $process_insert_query .= '`' . $key . '`';
    if ( ( $i + 1 ) < $main_stats_array_length ) {
        $process_insert_query .= ', ';
    }
    $i++;
}

// transition ...
$process_insert_query .= ') VALUES (';
$i = 0;

// add field values to $process_insert_query
foreach( $main_stats_array as $key => $value ) {
    if ( $key == 'name' || $key == 'rid' ) {
        $process_insert_query .= "'$value'";
    } else {
        $process_insert_query .= $value;
    }
    if ( ( $i + 1 ) < $main_stats_array_length ) {
        $process_insert_query .= ', ';
    }
    $i++;
}

// finish off $process_insert_query
$process_insert_query .= ');';

// execute the big, bad $process_insert_query! woohoo!
$db->sql_query( $process_insert_query ) or die( 'Could not insert data for ' . $name . '.<br />' . $process_insert_query . '<br />' . $db->sql_error() . '<br />' );

?>
