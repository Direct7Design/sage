<?php

/*
 * index.php
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

require('header.php');

$big_select;
$big_title;
$second_subtitle;

if( strlen( $user ) == 32 ) {
    $big_select = "SELECT * FROM `$date` WHERE `rid` = '" . $user . "';";
    $big_title = 'User Summary: ' . htmlspecialchars( $age_array[1] );
    $second_subtitle = 'Team Rank';
} else {
    $big_select = 'SELECT * FROM `' . $date . '` WHERE `row_type` = 0 AND `team_number` = ' . $team_number . ';';
    $big_title = 'Team Summary: ' . htmlspecialchars( $teams_index_array[0] );
    $second_subtitle = 'Users';
}

$big_select_handle = $db->sql_query( $big_select );
$big_array = array();
$big_array = $db->sql_fetchrow( $big_select_handle );

$cats_array = array();

$cats_array['Total'] = array( $big_array[4], $big_array[16], $big_array[28], $big_array[40] );
$cats_array['Empty1'] = 1;
$cats_array['Last Update'] = array( $big_array[5], $big_array[17], $big_array[29], $big_array[41] );
$cats_array['Last Day'] = array( $big_array[6], $big_array[18], $big_array[30], $big_array[42] );
$cats_array['Last Week'] = array( $big_array[7], $big_array[19], $big_array[31], $big_array[43] );
$cats_array['Last Month'] = array( $big_array[8], $big_array[20], $big_array[32], $big_array[44] );
$cats_array['Last Year'] = array( $big_array[9], $big_array[21], $big_array[33], $big_array[45] );
$cats_array['Empty2'] = 1;
$cats_array['Per Hour'] = array( $big_array[10], $big_array[22], $big_array[34], $big_array[46] );
$cats_array['Per Update'] = array( $big_array[11], $big_array[23], $big_array[35], $big_array[47] );
$cats_array['Per Day'] = array( $big_array[12], $big_array[24], $big_array[36], $big_array[48] );
$cats_array['Per Week'] = array( $big_array[13], $big_array[25], $big_array[37], $big_array[49] );
$cats_array['Per Month'] = array( $big_array[14], $big_array[26], $big_array[38], $big_array[50] );
$cats_array['Per Year'] = array( $big_array[15], $big_array[27], $big_array[39], $big_array[51] );
$cats_array['Empty3'] = 1;
$cats_array['Points Per Work Unit'] = 2;
$cats_array['Empty4'] = 1;

$html_out .= '<h2>' . $big_title . '</h2>';
$html_out .= '<table rules="all" frame="box" class="righttable" cellpadding="4"><colgroup><col class="righttablecol" /><col class="righttablecol" /><col class="righttablecol" /><col class="righttablecol" /><col class="righttablecol" /></colgroup>' . "\n";
$html_out .= '<tr><th class="topbth"></th><th class="topbth">Overall Rank</th><th class="topbth">' . $second_subtitle . '</th><th class="topbth">Work Units</th><th class="topbth">Points</th></tr>' . "\n";

$k = 2;

// for each time period
foreach( $cats_array as $key => $value ) {
    if( $value == 1 ) {
        $html_out .= '<tr><td colspan="5" class="emptyrow">&nbsp;</td></tr>' . "\n";
    } elseif( $value == 2 ) {
        if( $k % 2 == 0 ) {
            $html_out .= '<tr><th class="leftbth1">' . $key . '</th><td class="bodytd1" colspan="4">' . $big_array[52] . '</td></tr>' . "\n";
        } else {
            $html_out .= '<tr><th class="leftbth2">' . $key . '</th><td class="bodytd2" colspan="4">' . $big_array[52] . '</td></tr>' . "\n";
        }
    } else {
        if( $k % 2 == 0 ) {
            $html_out .= '<tr><th class="leftbth1">' . $key . '</th>';
        } else {
            $html_out .= '<tr><th class="leftbth2">' . $key . '</th>';
        }
        // for each stats category
        foreach( $value as $nkey => $nvalue ) {
            if( $nvalue != '&nbsp;' ) {
                $nvalue = number_format( $nvalue );
            }
            if( substr( $key, 0, 4 ) == 'Last' || substr( $key, 0, 4 ) == 'Per ' ) {
                if( $nvalue > 0 ) {
                    $nvalue = '+' . $nvalue;
                }
            }
            if( $k % 2 == 0 ) {
                $html_out .= '<td class="bodytd1">' . $nvalue . '</td>';
            } else {
                $html_out .= '<td class="bodytd2">' . $nvalue . '</td>';
            }
        }
        $html_out .= '</tr>' . "\n";
    }
    $k++;
}
$html_out .= '</table>' . "\n";

require('footer.php');

?>
