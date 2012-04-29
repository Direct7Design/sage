<?php

/*
 * header.php
 *
 * Last modified 04/20/2005 by hpxchan                                                           
 *                                                                      
 * Copyright (C) 2005 SamuraiDev                                        
 *                                                                      
 * This program is free software; you can redistribute it and/or    
 * modify it under the terms of the GNU General Public License      
 * as published by the Free Software Foundation; either version 2   
 * of the License, or (at your option) any later version.           
 */

define( 'IN_SAGE', true );

require( 'back/config.php' );

require( 'back/db.php' );

require( 'back/date_lib.php' );

$date = 0;
$user = 0;
$team = 0;

$html_out = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . "\n";
$html_out .= '<html xmlns="http://www.w3.org/1999/xhtml">' . "\n";
$html_out .= '<head><title>Folding@Home Stats</title><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" /><link type="text/css" rel="stylesheet" href="default.css" /></head>' . "\n";
$html_out .= '<body><div class="center">' . "\n";

$html_out .= '<table rules="none" frame="void" class="maintable" cellpadding="5"><colgroup><col class="leftcol" /><col class="rightcol" /></colgroup><tr><td class="lefttd">' . "\n";

$teams_index_select = 'SELECT `name` FROM `stats_index` WHERE `row_type` = 0 AND `team_number` = ' . $team_number . ';';
$teams_index_handle = $db->sql_query( $teams_index_select );
$teams_index_array = array();
$teams_index_array  = $db->sql_fetchrow( $teams_index_handle );
$html_out .= '<h1>Sage<br />Folding@Home<br />Stats</h1><h2>' . htmlspecialchars( $teams_index_array[0] ) . '</h2><h2>' . $team_number . '</h2>' . "\n";

$html_out .= '<form method="get" action="display.php"><fieldset>' . "\n";

$tables_index_array = array_reverse( get_index_table( $db, 'tables_index', 1 ) );

$select_age;
if( strlen( $_GET['user'] ) == 32 ) {
    $user = $_GET['user'];
    $html_out .= '<input type="hidden" name="user" value="' . $user . '" />';
    $select_age = "SELECT `age`, `name` FROM `stats_index` WHERE `rid` = '" . $_GET['user'] . "';";
} else {
    $select_age = "SELECT `age` FROM `stats_index` WHERE `row_type` = 0 AND `team_number` = " . $team_number . ";";
}
$age_handle = $db->sql_query( $select_age );
$age_array = $db->sql_fetchrow( $age_handle );
$age = $age_array[0];
$tables_index_array = array_slice( $tables_index_array, ( 0 - $age ), $age );

if( strlen( $_GET['date'] ) == 10 ) {
    $date = $_GET['date'];
} else {
    $date = $tables_index_array[0][1];
}

$html_out .= '<h3>Date:</h3><p><select name="date">' . "\n";
foreach( $tables_index_array as $key => $value ) {
    if( $value[1] == $date ) {
        $html_out .= '<option value="' . $value[1] . '" selected="selected">' . extend_numeric_date( $value[1] ) . '</option>' . "\n";
    } else {
        $html_out .= '<option value="' . $value[1] . '">' . extend_numeric_date( $value[1] ) . '</option>' . "\n";
    }
}

$html_out .= '</select></p><p>' . "\n";
$html_out .= '<input type="submit" value="Change" /></p></fieldset></form>' . "\n";
$html_out .= '<h3><a class="ah3" href="display.php?date=' . $date . '">Team Summary</a></h3>' . "\n";
$html_out .= '<h3><a class="ah3" href="list.php?date=' . $date . '">User List</a></h3>' . "\n";
$html_out .= '</td><td class="righttd">' . "\n";

?>
