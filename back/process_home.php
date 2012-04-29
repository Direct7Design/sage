<?php

/*
 * process_home.php                                                     
 *                                                                      
 * Last modified 03/28/2005 by hpxchan                                  
 *                                                                      
 * Sage Folding@Home Stats System, version 0.02                         
 *                                                                      
 * Copyright (C) 2005 SamuraiDev                                        
 *                                                                      
 * This program is free software; you can redistribute it and/or    
 * modify it under the terms of the GNU General Public License      
 * as published by the Free Software Foundation; either version 2   
 * of the License, or (at your option) any later version.           
 */

// $type is 1 for user stats, 0 for team stats

// NOTE, if there is not enough data to find *_last_* (for example, if we have not yet processed a day, and we are trying to find points_last_day),
//   then it will not be set in the database (it will be left set to the default value of NULL).

function process_home($insert_table, $team_number, $type, $name, $rank, $trankusers, $wus, $points, $tables, $tables_last, $db_object)
{
    $categories_array = array('rank', 'trankusers', 'wus', 'points');
    $time_periods_array = array('update', 'day', 'week', 'month', 'year');
    $big_time_periods_array = array('hour', 'update', 'day', 'week', 'month', 'year');
    $initial_stats_array = array(); // temporary stats array; holds table stats until we're ready to process them
    // $main_stats_array holds stuff we're going to put into the current table
    // we'll add more to it later
    $main_stats_array = array('team_number' => $team_number, 'row_type' => $type, 'name' => $name, 'rank' => $rank, 'trankusers' => $trankusers, 'wus' => $wus, 'points' => $points); 

    $per_update_queries = 2920; // max number of *_last_update stats to average; 56 is a week's worth
    $per_update_sum = 0; // sum of *_last_update stats, before we average
    $per_update_to_year = 2920 / $per_update_queries; // number to multiply $per_update_queries by to get *_per_year stats
                                                      // 2920 == [days in a year (365)] * [updates in a day (8)]
    $year_divide_by_array = array(8760, 2920, 365, 52, 12, 1); // divide *_per_year to get *_per_{hour, update, day, week, month, year}

    if($tables_last == 0) { // if the current table is the only table (THIS PART IS NOT FUN)
        $main_stats_array['rank_last_update'] = 0;
        $main_stats_array['trankusers_last_update'] = 0;
        $main_stats_array['wus_last_update'] = 0;
        $main_stats_array['points_last_update'] = 0;

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
    
        $main_stats_array['points_per_wu'] = floor($points / $wus);
    } else { // more than one table;
       
        // *_last_* stuff

        $full_last_update = 1;
        $full_last_day = 1;
        $full_last_week = 1;
        $full_last_month = 1;
        $full_last_year = 1;

        if ($tables_last < 7) { // not a full day's info
            $full_last_day = 0;
        }
        if ($tables_last < 55) { // not a full week's info
            $full_last_week = 0;
        }
        if ($tables_last < 239) { // not a full month's info
            $full_last_month = 0;
        }
        if ($tables_last < 2919) { // not a full year's info
            $full_last_year = 0;
        }

        $user_exists_at_this_point = 1; // whether or not the user exists at this point - when we run into a database query error, this will be set to 0 ...

        // this stuff is for *_last_update

        $last_table_select;
        if ($type == 0) { // for team stats
            $last_table_select = "SELECT `rank`, `trankusers`, `wus`, `points` FROM `" . $tables[($tables_last - 1)]['name'] . "` WHERE `row_type` = 0 AND `team_number` = $team_number;";
        } elseif ($type == 1) { // for user stats
            $last_table_select = "SELECT `rank`, `trankusers`, `wus`, `points` FROM `" . $tables[($tables_last - 1)]['name'] . "` WHERE `row_type` = 1 AND `name` = '" . $name . "';";
        }

        $last_table_array = array();
        $last_table_handle = $db_object->sql_query($last_table_select) or die('Cannot select from last table ' . $tables[($tables_last - 1)]['name'] . '<br />' . $last_table_select . '<br />' . $db_object->sql_error() . '<br />');
        $last_table_array = $db_object->sql_fetchrow($last_table_handle);

        $initial_stats_array[0]['rank'] = $rank;
        $initial_stats_array[0]['trankusers'] = $trankusers;
        $initial_stats_array[0]['wus'] = $wus;
        $initial_stats_array[0]['points'] = $points;

        $initial_stats_array[1]['rank'] = $last_table_array[0];
        $initial_stats_array[1]['trankusers'] = $last_table_array[1];
        $initial_stats_array[1]['wus'] = $last_table_array[2];
        $initial_stats_array[1]['points'] = $last_table_array[3];

        $main_stats_array['rank_last_update'] = $initial_stats_array[0]['rank'] - $initial_stats_array[1]['rank'];
        $main_stats_array['trankusers_last_update'] = $initial_stats_array[0]['trankusers'] - $initial_stats_array[1]['trankusers'];
        $main_stats_array['wus_last_update'] = $initial_stats_array[0]['wus'] - $initial_stats_array[1]['wus'];
        $main_stats_array['points_last_update'] = $initial_stats_array[0]['points'] - $initial_stats_array[1]['points'];

        // now on to *_last_{everything except update}
        // not done yet

        // now on to *_per_*
        // not done yet
    }
	
    $process_insert_query = 'INSERT INTO `' . $insert_table . '` (';

    $main_stats_array_length = count($main_stats_array);
    $i = 0;

    foreach($main_stats_array as $key => $value) {
        $process_insert_query .= '`' . $key . '`';
        if (($i + 1) < $main_stats_array_length) {
            $process_insert_query .= ', ';
        }
        $i++;
    }

    $process_insert_query .= ') VALUES (';
    $i = 0;

    foreach($main_stats_array as $key => $value) {
        if ($key == 'name') {
            $process_insert_query .= "'$value'";
        } else {
            $process_insert_query .= $value;
        }
        if (($i + 1) < $main_stats_array_length) {
            $process_insert_query .= ', ';
        }
        $i++;
    }

    $process_insert_query .= ');';
    $db_object->sql_query($process_insert_query) or die('Could not insert data for ' . $name . '.<br />' . $process_insert_query . '<br />' . $db_object->sql_error() . '<br />');
}

?>
