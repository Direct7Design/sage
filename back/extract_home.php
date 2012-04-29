<?php

/*
 * extract_home.php                                                     
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

if( !get_index_table( $db, 'tables_index', 0 ) ) {
    $make_index = 'CREATE TABLE `tables_index` (rid MEDIUMINT AUTO_INCREMENT NOT NULL PRIMARY KEY, name CHAR(10));';
    $db->sql_query( $make_index ) or die( 'Could not create index table tables_index <br />' . $make_index . '<br />' . $db->sql_error() );
}

if( !get_index_table( $db, 'stats_index', 0 ) ) {
    $make_index = 'CREATE TABLE `stats_index` (rid VARCHAR(32) NOT NULL PRIMARY KEY, row_type SMALLINT, team_number MEDIUMINT, name VARCHAR(75), age MEDIUMINT, first_table CHAR(10));';
    $db->sql_query( $make_index ) or die( 'Could not create index table stats_index <br />' . $make_index . '<br />' . $db->sql_error() );
}

$stanford_team_url = 'http://vspx27.stanford.edu/teamstats/team' . $team_number . '.txt';

$team_users = 0;
$lines_count = 0;
$current_table_name;
$team_name;
$team_number;
$team_points;
$team_wus;
$team_rank;
$team_rid;

$tables = array();
$tables_last = 0;

$stats_index_array = array();
$last_table_array = array();
$last_day_array = array();
$last_week_array = array();
$last_month_array = array();
$last_year_array = array();

$done = array();

$ch = curl_init();

curl_setopt( $ch, CURLOPT_URL, $stanford_team_url );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

$team_page_handle = explode( "\n", curl_exec( $ch ) );

if( !$team_page_handle ) {
    die('Killed: Invalid Team Page: ' . $stanford_team_url);
}

foreach( $team_page_handle as $lines_count => $current_line ) {
    if( $current_line == '' || $current_line == ' ' || $lines_count <= 2 || $lines_count == 4 || ( $lines_count >= 7 && $lines_count <= 9 ) ) { // if the line sucks, do nothing

    } elseif( $lines_count == 3 ) { // if it is the date line

/* This stuff is from Sage 1.1.1 and earlier... it may be needed later.
        $date_array = explode( " ", $current_line );
        $time_array = explode( ":", $date_array[3] );
        $day_of_week = day_to_number( $date_array[0] );
        $month = month_to_number( $date_array[1] );
        $day_of_month = $date_array[2];
        $hour = $time_array[0];
        $time_zone = $date_array[4];
        $year = Trim( $date_array[5] );

        $current_table_name = '' . $year . add_leading_zeros( $month, 2 ) . add_leading_zeros( $day_of_month, 2 ) . add_leading_zeros( $hour, 2 );
*/

        $date_array = array();
        $date_array = getdate();
        $year = $date_array['year'];
        $month = $date_array['mon'];
        $day_of_month = $date_array['mday'];
        $hour = $date_array['hours'];

        $current_table_name = '' . $year . add_leading_zeros( $month, 2 ) . add_leading_zeros( $day_of_month, 2 ) . add_leading_zeros( $hour, 2 );

        $current_table_name = substr( $current_table_name, 0, 10 );
        $table_create = 'CREATE TABLE `' . $current_table_name . '` (rid VARCHAR(32) PRIMARY KEY NOT NULL, team_number MEDIUMINT, row_type SMALLINT, name VARCHAR(75), rank INT, rank_last_update MEDIUMINT, rank_last_day MEDIUMINT, rank_last_week MEDIUMINT, rank_last_month INT, rank_last_year INT, rank_per_hour MEDIUMINT, rank_per_update MEDIUMINT, rank_per_day MEDIUMINT, rank_per_week MEDIUMINT, rank_per_month INT, rank_per_year INT, trankusers INT, trankusers_last_update MEDIUMINT, trankusers_last_day MEDIUMINT, trankusers_last_week MEDIUMINT, trankusers_last_month MEDIUMINT, trankusers_last_year INT, trankusers_per_hour MEDIUMINT, trankusers_per_update MEDIUMINT, trankusers_per_day MEDIUMINT, trankusers_per_week MEDIUMINT, trankusers_per_month MEDIUMINT, trankusers_per_year INT, wus BIGINT, wus_last_update MEDIUMINT, wus_last_day MEDIUMINT, wus_last_week INT, wus_last_month INT, wus_last_year BIGINT, wus_per_hour MEDIUMINT, wus_per_update MEDIUMINT, wus_per_day MEDIUMINT, wus_per_week INT, wus_per_month INT, wus_per_year BIGINT, points BIGINT, points_last_update INT, points_last_day INT, points_last_week INT, points_last_month BIGINT, points_last_year BIGINT, points_per_hour MEDIUMINT, points_per_update INT, points_per_day INT, points_per_week INT, points_per_month BIGINT, points_per_year BIGINT, points_per_wu MEDIUMINT);';
        $db->sql_query( $table_create ) or die( 'Could not create table ' . $current_table_name . '<br />' . $table_create . '<br />' . $db->sql_error() );

        $tables_update = "INSERT INTO `tables_index` (name) VALUES ('$current_table_name');";
	$db->sql_query( $tables_update ) or die( 'Could not insert row for ' . $current_table_name . ' into tables_index<br />' . $tables_update . '<br />' . mysql_error() );

        $tables = get_index_table( $db, 'tables_index', 1 );
        $tables_last = count( $tables ) - 1;

        if( $tables_last > 0 ) {

            $stats_index_select = "SELECT `rid`, `age`, `first_table` FROM `stats_index`;";
            $stats_index_handle = $db->sql_query( $stats_index_select ) or die( 'Cannot select from stats_index<br />' . $stats_index_select . '<br />' . $db->sql_error() . '<br />' );
            while( $current_row = $db->sql_fetchrow( $stats_index_handle ) ) {
                $stats_index_array[$current_row[0]] = $current_row;
            }

            $last_table_select = "SELECT * FROM `" . $tables[( $tables_last - 1 )]['name'] . "`;";
            $last_table_handle = $db->sql_query( $last_table_select ) or die( 'Cannot select from last table ' . $tables[( $tables_last - 1 )]['name'] . '<br />' . $last_table_select . '<br />' . $db->sql_error() . '<br />' );
            while( $current_row = $db->sql_fetchrow( $last_table_handle ) ) {
                $last_table_array[$current_row[0]] = $current_row;
            }

            if( $tables_last >= 2920 ) {
                $last_year_select = "SELECT `rank_last_update`, `trankusers_last_update`, `wus_last_update`, `points_last_update` FROM `" . $tables[( $tables_last - 2920 )][1] . '`;';
                $last_year_handle = $db->sql_query( $last_year_select ) or die( 'Cannot select from ' . $tables[( $tables_last - 2920 )][1] . '<br />' . $last_year_select . '<br />' . $db->sql_error() . '<br />' );
                while( $current_row = $db->sql_fetchrow( $last_year_handle ) ) {
                    $last_year_array[$current_row[0]] = $current_row;
                }
            } 

            if( $tables_last >= 240 ) {
                $last_month_select = "SELECT `rank_last_update`, `trankusers_last_update`, `wus_last_update`, `points_last_update` FROM `" . $tables[( $tables_last - 240 )][1] . '`;';
                $last_month_handle = $db->sql_query( $last_month_select ) or die( 'Cannot select from ' . $tables[( $tables_last - 240 )][1] . '<br />' . $last_month_select . '<br />' . $db->sql_error() . '<br />' );
                while( $current_row = $db->sql_fetchrow( $last_month_handle ) ) {
                    $last_month_array[$current_row[0]] = $current_row;
                }
            }

            if( $tables_last >= 56 ) {
                $last_week_select = "SELECT `rank_last_update`, `trankusers_last_update`, `wus_last_update`, `points_last_update` FROM `" . $tables[( $tables_last - 56 )][1] . '`;';
                $last_week_handle = $db->sql_query( $last_week_select ) or die( 'Cannot select from ' . $tables[( $tables_last - 56 )][1] . '<br />' . $last_week_select . '<br />' . $db->sql_error() . '<br />' );
                while( $current_row = $db->sql_fetchrow( $last_week_handle ) ) {
                    $last_week_array[$current_row[0]] = $current_row;
                }
            }

            if( $tables_last >= 8 ) {
                $last_day_select = "SELECT `rank_last_update`, `trankusers_last_update`, `wus_last_update`, `points_last_update` FROM `" . $tables[( $tables_last - 8 )][1] . '`;';
                $last_day_handle = $db->sql_query( $last_day_select ) or die( 'Cannot select from ' . $tables[( $tables_last - 8 )][1] . '<br />' . $last_day_select . '<br />' . $db->sql_error() . '<br />' );
                while( $current_row = $db->sql_fetchrow( $last_day_handle ) ) {
                    $last_day_array[$current_row[0]] = $current_row;
                }
            }

        }

    } elseif( $lines_count == 5 ) { // if it is the team info line

        $team_array = explode( "\t", $current_line );
        $team_team_number = $team_array[0];
        $team_name = addslashes( $team_array[1] );
        $team_points = floor( $team_array[2] );
        $team_wus = $team_array[3];
        $team_rid = md5( 't' . $team_number );

    } elseif( $lines_count == 6 ) { // if it is the team rank line

        $team_rank_array = explode( ": ", $current_line );
        $team_rank = $team_rank_array[1];

    } else { // if it is a user info line
        $user_array = explode( "\t", $current_line );
        $name = addslashes( $user_array[2] );
        $rid = md5( $team_number . stripslashes( $name ) );
        if( ! $done[$rid] ) {
            $rank = $user_array[0];
            $trankusers = $user_array[1];
            $points = $user_array[3];
            $wus = $user_array[4];
            $team_number = $user_array[5];
            $team_users = $trankusers;
            $type = 1;
            require( 'process_home.php' );
            $done[$rid] = 1;
        }
    }

}

if( ! $done[$team_rid] ) {
    $rid = $team_rid;
    $team_number = $team_team_number;
    $type = 0;
    $name = $team_name;
    $rank = $team_rank; 
    $trankusers = $team_users;
    $wus = $team_wus;
    $points = $team_points;
    require( 'process_home.php' );
    $done[$team_rid] = 1;
}

curl_close( $ch );

?>
