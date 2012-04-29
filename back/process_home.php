<?php

/*
 * process_home.php                                                     
 *                                                                      
 * Last modified 03/18/2005 by hpxchan                                  
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

// $type is 0 for user stats, 1 for team stats

function process_home($insert_table, $team_number, $type, $name, $rank, $trank_users, $wus, $points, $tables, $tables_last, $db_object)
{
    $categories_array = array('rank', 'trankusers', 'wus', 'points');
    $time_periods_array = array('update', 'day', 'week', 'month', 'year');
    $initial_stats_array = array(); // holds stuff for processing *_last_update for the current table
    $main_stats_array = array('team_number' => $team_number, 'row_type' => $type, 'name' => $name, 'rank' => $rank, 'trankusers' => $trank_users, 'wus' => $wus, 'points' => $points); // holds stuff we're going to put into the current table

    $process_insert_query = 'INSERT INTO `' . $insert_table . '` VALUES ';

    $per_update_queries = 2920; // max number of *_last_update stats to average; 56 is a week's worth
    $per_update_sum = 0; // sum of *_last_update stats, before we average
    $per_update_to_year = 2920 / $per_update_queries; // number to multiply $per_update_queries by to get *_per_year stats
                                                      // 2920 == [days in a year (365)] * [updates in a day (8)]
    $year_divide_by_array = array(8760, 2920, 365, 52, 12, 1); // divide *_per_year to get *_per_{hour, update, day, week, month, year}

    if($tables_last == 0) { // if the current table is the only table
    } else { // more than one table
        if($type == 0) { // for team stats
            $last_table_select = "SELECT `rank`, `trankusers`, `wus`, `points` FROM " . $tables[($tables_last - 1)] . " WHERE `row_type` = 0 AND `team_number` = $team_number ;";
        } elseif($type == 1) { // for user stats
        $last_table_select = "SELECT `rank`, `trankusers`, `wus`, `points` FROM " . $tables[($tables_last - 1)] . " WHERE `row_type` = 1 AND `name` = " . $name . " ;";
        }
        $db_object->sql_query($last_table_select) or die('Cannot select from last table ' . $tables[($tables_last - 1)] . '<br />' . $db_object->sql_error());
        for($i = $tables_last - 1; $i >= 0; $i--) { // main loop parses tables, since database queries are resource hogs
                                                      // skip the last table; it is the one we are processing right now
            $this_table_process_select = 'SELECT '; // this table's process database query
        }
    }
    // $db_object->sql_query($process_insert_query);
}

?>
