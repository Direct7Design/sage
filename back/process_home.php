<?php

/*
 * process_home.php                                                     
 *                                                                      
 * Last modified 04/14/2005 by hpxchan                                  
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

function process_home( $insert_table, $team_number, $type, $name, $rank, $trankusers, $wus, $points, $tables, $tables_last, $db_object )
{

    // temporary stats array; holds table stats until we're ready to process them
    $initial_stats_array = array();

    // $main_stats_array holds stuff we're going to put into the current table
    // we'll add more to it later
    $main_stats_array = array( 'team_number' => $team_number, 'row_type' => $type, 'name' => $name, 'rank' => $rank, 'trankusers' => $trankusers, 'wus' => $wus, 'points' => $points );

    // if the current table is the only table (THIS PART IS NOT FUN)
    if( $tables_last == 0 ) {

        $update_stats_index = "INSERT INTO `stats_index` (`row_type`, `name`, `team_number`, `age`, `first_table`) VALUES ($type, '$name', $team_number, 1, '$insert_table');";
        $db_object->sql_query( $update_stats_index ) or die( 'Could not update stats_index for ' . $name . '.<br />' . $update_stats_index . '<br />' . $db_object->sql_error() . '<br />' );

        $main_stats_array['rank_last_update'] = $rank;
        $main_stats_array['trankusers_last_update'] = $trankusers;
        $main_stats_array['wus_last_update'] = $wus;
        $main_stats_array['points_last_update'] = $points;

        $main_stats_array['rank_last_day'] = $rank;
        $main_stats_array['trankusers_last_day'] = $trankusers;
        $main_stats_array['wus_last_day'] = $wus;
        $main_stats_array['points_last_day'] = $points;

        $main_stats_array['rank_last_week'] = $rank;
        $main_stats_array['trankusers_last_week'] = $trankusers;
        $main_stats_array['wus_last_week'] = $wus;
        $main_stats_array['points_last_week'] = $points;

        $main_stats_array['rank_last_month'] = $rank;
        $main_stats_array['trankusers_last_month'] = $trankusers;
        $main_stats_array['wus_last_month'] = $wus;
        $main_stats_array['points_last_month'] = $points;

        $main_stats_array['rank_last_year'] = $rank;
        $main_stats_array['trankusers_last_year'] = $trankusers;
        $main_stats_array['wus_last_year'] = $wus;
        $main_stats_array['points_last_year'] = $points;

        $main_stats_array['rank_per_hour'] = $rank;
        $main_stats_array['trankusers_per_hour'] = $trankusers;
        $main_stats_array['wus_per_hour'] = $wus;
        $main_stats_array['points_per_hour'] = $points;

        $main_stats_array['rank_per_update'] = $rank;
        $main_stats_array['trankusers_per_update'] = $trankusers;
        $main_stats_array['wus_per_update'] = $wus;
        $main_stats_array['points_per_update'] = $points;

        $main_stats_array['rank_per_year'] = $rank;
        $main_stats_array['trankusers_per_year'] = $trankusers;
        $main_stats_array['wus_per_year'] = $wus;
        $main_stats_array['points_per_year'] = $points;

        $main_stats_array['rank_per_day'] = $rank;
        $main_stats_array['trankusers_per_day'] = $trankusers;
        $main_stats_array['wus_per_day'] = $wus;
        $main_stats_array['points_per_day'] = $points;

        $main_stats_array['rank_per_week'] = $rank;
        $main_stats_array['trankusers_per_week'] = $trankusers;
        $main_stats_array['wus_per_week'] = $wus;
        $main_stats_array['points_per_week'] = $points;

        $main_stats_array['rank_per_month'] = $rank;
        $main_stats_array['trankusers_per_month'] = $trankusers;
        $main_stats_array['wus_per_month'] = $wus;
        $main_stats_array['points_per_month'] = $points;
    
        $main_stats_array['points_per_wu'] = floor( $points / $wus );

    // more than one table
    } else {
       
        // *_last_* stuff

        // get info from the stats table submitted last update

        $last_table_select = "SELECT * FROM `" . $tables[( $tables_last - 1 )]['name'] . "` ";
        $stats_index_select = "SELECT * FROM `stats_index` ";
        $this_where_clause;

        // for team stats
        if( $type == 0 ) {

            $last_table_select .= "WHERE `row_type` = 0 AND `team_number` = $team_number;";
            $this_where_clause = "WHERE `row_type` = 0 AND `team_number` = $team_number;";
            $stats_index_select .= $this_where_clause;

        // for user stats
        } elseif( $type == 1 ) {

            $last_table_select .= "WHERE `row_type` = 1 AND `name` = '" . $name . "';";
            $this_where_clause .= "WHERE `row_type` = 1 AND `name` = '" . $name . "';";
            $stats_index_select .= $this_where_clause;

        }

        // get info from last table
        $last_table_array = array();
        $last_table_handle = $db_object->sql_query( $last_table_select ) or die( 'Cannot select from last table ' . $tables[( $tables_last - 1 )]['name'] . '<br />' . $last_table_select . '<br />' . $db_object->sql_error() . '<br />' );
        $last_table_array = $db_object->sql_fetchrow( $last_table_handle );

        // get info from stats_index table
        $stats_index_array = array();
        $stats_index_handle = $db_object->sql_query( $stats_index_select ) or die( 'Cannot select from stats_index<br />' . $stats_index_select . '<br />' . $db_object->sql_error() . '<br />' );
        $stats_index_array = $db_object->sql_fetchrow( $stats_index_handle );
        // increment age and update the table
        $stats_index_array[4]++;
        $stats_index_update = "UPDATE `stats_index` SET `age`=" . $stats_index_array[4] . ' ' . $this_where_clause;
        $db_object->sql_query( $stats_index_update ) or die( 'Could not update stats_index<br />' . $stats_index_update . '<br />' . $db_object->sql_error() . '<br />' );
        $age = $stats_index_array[4];
        $first_table = $stats_index_array[5];

        // * last update
        $rank_last_update = $rank - $last_table_array[4];
        $trankusers_last_update = $trankusers - $last_table_array[16];
        $wus_last_update = $wus - $last_table_array[28];
        $points_last_update = $points - $last_table_array[40];

        $main_stats_array['rank_last_update'] = $rank_last_update;
        $main_stats_array['trankusers_last_update'] = $trankusers_last_update;
        $main_stats_array['wus_last_update'] = $wus_last_update;
        $main_stats_array['points_last_update'] = $points_last_update;

        // * {last,per} year
        if( $age < 2920 ) {
            $main_stats_array['rank_last_year'] = $last_table_array[9] + $rank_last_update;
            $main_stats_array['trankusers_last_year'] = $last_table_array[21] + $trankusers_last_update;
            $main_stats_array['wus_last_year'] = $last_table_array[33] + $wus_last_update;
            $main_stats_array['points_last_year'] = $last_table_array[45] + $points_last_update;

            $main_stats_array['rank_per_year'] = floor( $main_stats_array['rank_last_year'] * ( 2920 / $age ) );
            $main_stats_array['trankusers_per_year'] = floor( $main_stats_array['trankusers_last_year'] * ( 2920 / $age ) );
            $main_stats_array['wus_per_year'] = floor( $main_stats_array['wus_last_year'] * ( 2920 / $age ) );
            $main_stats_array['points_per_year'] = floor( $main_stats_array['points_last_year'] * ( 2920 / $age ) );
        } elseif( $age >= 2920 ) {
            $old_last_update_array = array();
            $old_last_update_select = "SELECT `rank_last_update`, `trankusers_last_update`, `wus_last_update`, `points_last_update` FROM " . $tables[( $tables_last - 2920 )] . ' ' . $this_where_clause . ';';
            $old_last_update_handle = $db_object->sql_query( $old_last_update_select ) or die( 'Cannot select from ' . $tables[( $tables_last - 2920 )] . '<br />' . $old_last_update_select . '<br />' . $db_object->sql_error() . '<br />' );
            $old_last_update_array = $db_object->sql_fetchrow( $old_last_update_handle );

            $main_stats_array['rank_last_year'] = $last_table_array[9] + $rank_last_update - $old_last_update_array[0];
            $main_stats_array['trankusers_last_year'] = $last_table_array[21] + $trankusers_last_update - $old_last_update_array[1];
            $main_stats_array['wus_last_year'] = $last_table_array[33] + $wus_last_update - $old_last_update_array[2];
            $main_stats_array['points_last_year'] = $last_table_array[45] + $points_last_update - $old_last_update_array[3];

            $main_stats_array['rank_per_year'] = floor( ( $last_table_array[15] + $rank_last_update ) * ( 2920 / 2921 ) );
            $main_stats_array['trankusers_per_year'] = floor( ( $last_table_array[27] + $trankusers_last_update ) * ( 2920 / 2921 ) );
            $main_stats_array['wus_per_year'] = floor( ( $last_table_array[39] + $wus_last_update ) * ( 2920 / 2921 ) );
            $main_stats_array['points_per_year'] = floor( ( $last_table_array[51] + $points_last_update ) * ( 2920 / 2921 ) );
        }

        // * last month
        if( $age < 240 ) {
            $main_stats_array['rank_last_month'] = $last_table_array[8] + $rank_last_update;
            $main_stats_array['trankusers_last_month'] = $last_table_array[20] + $trankusers_last_update;
            $main_stats_array['wus_last_month'] = $last_table_array[32] + $wus_last_update;
            $main_stats_array['points_last_month'] = $last_table_array[44] + $points_last_update;
        } elseif( $age >= 240 ) {
            $old_last_update_array = array();
            $old_last_update_select = "SELECT `rank_last_update`, `trankusers_last_update`, `wus_last_update`, `points_last_update` FROM " . $tables[( $tables_last - 240 )] . ' ' . $this_where_clause . ';';
            $old_last_update_handle = $db_object->sql_query( $old_last_update_select ) or die( 'Cannot select from ' . $tables[( $tables_last - 240 )] . '<br />' . $old_last_update_select . '<br />' . $db_object->sql_error() . '<br />' );
            $old_last_update_array = $db_object->sql_fetchrow( $old_last_update_handle );

            $main_stats_array['rank_last_month'] = $last_table_array[8] + $rank_last_update - $old_last_update_array[0];
            $main_stats_array['trankusers_last_month'] = $last_table_array[20] + $trankusers_last_update - $old_last_update_array[1];
            $main_stats_array['wus_last_month'] = $last_table_array[32] + $wus_last_update - $old_last_update_array[2];
            $main_stats_array['points_last_month'] = $last_table_array[44] + $points_last_update - $old_last_update_array[3];
        }

        // * last week
        if( $age < 56 ) {
            $main_stats_array['rank_last_week'] = $last_table_array[7] + $rank_last_update;
            $main_stats_array['trankusers_last_week'] = $last_table_array[19] + $trankusers_last_update;
            $main_stats_array['wus_last_week'] = $last_table_array[31] + $wus_last_update;
            $main_stats_array['points_last_week'] = $last_table_array[43] + $points_last_update;
        } elseif( $age >= 56 ) {
            $old_last_update_array = array();
            $old_last_update_select = "SELECT `rank_last_update`, `trankusers_last_update`, `wus_last_update`, `points_last_update` FROM " . $tables[( $tables_last - 56 )] . ' ' . $this_where_clause . ';';
            $old_last_update_handle = $db_object->sql_query( $old_last_update_select ) or die( 'Cannot select from ' . $tables[( $tables_last - 56 )] . '<br />' . $old_last_update_select . '<br />' . $db_object->sql_error() . '<br />' );
            $old_last_update_array = $db_object->sql_fetchrow( $old_last_update_handle );

            $main_stats_array['rank_last_week'] = $last_table_array[7] + $rank_last_update - $old_last_update_array[0];
            $main_stats_array['trankusers_last_week'] = $last_table_array[19] + $trankusers_last_update - $old_last_update_array[1];
            $main_stats_array['wus_last_week'] = $last_table_array[31] + $wus_last_update - $old_last_update_array[2];
            $main_stats_array['points_last_week'] = $last_table_array[43] + $points_last_update - $old_last_update_array[3];
        }

        // * last day
        if( $age < 8 ) {
            $main_stats_array['rank_last_day'] = $last_table_array[6] + $rank_last_update;
            $main_stats_array['trankusers_last_day'] = $last_table_array[18] + $trankusers_last_update;
            $main_stats_array['wus_last_day'] = $last_table_array[30] + $wus_last_update;
            $main_stats_array['points_last_day'] = $last_table_array[42] + $points_last_update;
        } elseif( $age >= 8 ) {
            $old_last_update_array = array();
            $old_last_update_select = "SELECT `rank_last_update`, `trankusers_last_update`, `wus_last_update`, `points_last_update` FROM " . $tables[( $tables_last - 8 )] . ' ' . $this_where_clause . ';';
            $old_last_update_handle = $db_object->sql_query( $old_last_update_select ) or die( 'Cannot select from ' . $tables[( $tables_last - 8 )] . '<br />' . $old_last_update_select . '<br />' . $db_object->sql_error() . '<br />' );
            $old_last_update_array = $db_object->sql_fetchrow( $old_last_update_handle );

            $main_stats_array['rank_last_day'] = $last_table_array[6] + $rank_last_update - $old_last_update_array[0];
            $main_stats_array['trankusers_last_day'] = $last_table_array[18] + $trankusers_last_update - $old_last_update_array[1];
            $main_stats_array['wus_last_day'] = $last_table_array[30] + $wus_last_update - $old_last_update_array[2];
            $main_stats_array['points_last_day'] = $last_table_array[42] + $points_last_update - $old_last_update_array[3];
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

        // points per wu
        if( $main_stats_array['wus'] != 0 ) {
            $main_stats_array['points_per_wu'] = floor( $main_stats_array['points'] / $main_stats_array['wus'] );
        } else {
            $main_stats_array['points_per_wu'] = 0;
        }
    }

    // start making big insert query in $process_insert_query ...	
    $process_insert_query = 'INSERT INTO `' . $insert_table . '` (';

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
        if ( $key == 'name' ) {
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

    // execute the big, bad insert query! woohoo!
    $db_object->sql_query( $process_insert_query ) or die( 'Could not insert data for ' . $name . '.<br />' . $process_insert_query . '<br />' . $db_object->sql_error() . '<br />' );

}

?>
