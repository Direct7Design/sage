<?php

/*
 * display.php
 *
 * Last modified 04/16/2005 by hpxchan                                                           
 *                                                                      
 * Copyright (C) 2005 SamuraiDev                                        
 *                                                                      
 * This program is free software; you can redistribute it and/or    
 * modify it under the terms of the GNU General Public License      
 * as published by the Free Software Foundation; either version 2   
 * of the License, or (at your option) any later version.           
 */

require('header.php');

$html_out .= '<h2>User List: ' . htmlspecialchars( $teams_index_array[0] ) . '</h2>' . "\n";

if( $_GET['page'] < 1 ) {
    $page = 1;
} else {
    $page = $_GET['page'];
}

$users_count_select = 'SELECT COUNT(*) FROM `' . $date . '` WHERE `row_type` = 1;';
$users_count_handle = $db->sql_query( $users_count_select );
$users_count_array = array();
$users_count_array = $db->sql_fetchrow( $users_count_handle );
$users_count = $users_count_array[0];

$total_pages = ceil( $users_count / 100 );
if( $page > $total_pages ) {
    $page = $total_pages;
}
$list_offset = ( $page - 1 ) * 100;

$html_out .= '<form action="list.php" method="get"><fieldset><h3>' . "\n";
if( $page > 1 ) {
    $html_out .= '<a class="ah3" href="list.php?date=' . $date . '&page=1">&lt;&lt; First</a>';
    $html_out .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    $html_out .= '<a class="ah3" href="list.php?date=' . $date . '&page=' . ( $page - 1 ) . '">&lt; Previous</a>' . "\n";
}
$html_out .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$html_out .= '<input type="hidden" name="date" value="' . $date . '" />' . "\n";
$html_out .= 'Page:<select name="page">' . "\n";
for( $i = 1; $i <= $total_pages; $i++ ) {
    if( $i == $page ) {
        $html_out .= '<option value="' . $i . '" selected="selected">' . $i . '</option>' . "\n";
    } else {
        $html_out .= '<option value="' . $i . '">' . $i . '</option>' . "\n";
    }
}
$html_out .= '</select><input type="submit" value="Change" />' . "\n";
$html_out .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
if( $page < $total_pages ) {
    $html_out .= '<a class="ah3" href="list.php?date=' . $date . '&page=' . ( $page + 1 ) . '">Next &gt;</a>';
    $html_out .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    $html_out .= '<a class="ah3" href="list.php?date=' . $date . '&page=' . $total_pages . '">Last &gt;&gt;</a>' . "\n";
}
$html_out .= '</h3></fieldset></form>' . "\n";

$html_out .= '<table rules="all" frame="box" class="righttable" cellpadding="4"><colgroup><col class="righttablecol" /><col class="righttablecol" /><col class="righttablecol" /><col class="righttablecol" /><col class="righttablecol" /></colgroup>' . "\n";
$html_out .= '<tr><th class="topbth">User</th><th class="topbth">Overall Rank</th><th class="topbth">Team Rank</th><th class="topbth">Work Units</th><th class="topbth">Points</th></tr>' . "\n";

$users_index_select = 'SELECT `rid`, `name`, `rank`, `trankusers`, `wus`, `points` FROM `' . $date . '` WHERE `row_type` = 1 ORDER BY `rank` LIMIT 100 OFFSET ' . $list_offset . ';';
$users_index_handle = $db->sql_query( $users_index_select );
$cats_array = array();
for( $i = 0; $current_row  = $db->sql_fetchrow( $users_index_handle ); $i++ ) {
    if( $i == 25 || $i == 50 || $i == 75 ) {
        $cats_array[( 'Meta' . $i )] = 2;
    }
    $cats_array[ htmlspecialchars( $current_row[0] ) ] = array( $current_row[1], $current_row[2], $current_row[3], $current_row[4], $current_row[5] );
}
$cats_array['Empty1'] = 1;

$k = 2;
foreach( $cats_array as $key => $value ) {
    if( $value == 1 ) {
        $html_out .= '<tr><td colspan="5" class="emptyrow">&nbsp;</td></tr>' . "\n";
    } elseif( $value == 2 ) {
        $html_out .= '<tr><th class="topbth">User</th><th class="topbth">Overall Rank</th><th class="topbth">Team Rank</th><th class="topbth">Work Units</th><th class="topbth">Points</th></tr>' . "\n";
    } else {
        if( $k % 2 == 0 ) {
            $html_out .= '<tr><th class="leftbth1"><a class="ah3" href="display.php?date=' . $date . '&user=' . $key . '">';
        } else {
            $html_out .= '<tr><th class="leftbth2"><a class="ah3" href="display.php?date=' . $date . '&user=' . $key . '">';
        }
        foreach( $value as $nkey => $nvalue ) {
            if( $nkey == 0 ) {
                $html_out .= $nvalue . '</a></th>';
            } else {
                if( $nvalue != '&nbsp;' ) {
                    $nvalue = number_format( $nvalue );
                }
                if( $k % 2 == 0 ) {
                    $html_out .= '<td class="bodytd1">' . $nvalue . '</td>';
                } else {
                    $html_out .= '<td class="bodytd2">' . $nvalue . '</td>';
                }
            }
        }
        $html_out .= '</tr>' . "\n";
    }
    $k++;
}

$html_out .= '</table><p>&nbsp;</p>' . "\n";

$html_out .= '<form action="list.php" method="get"><fieldset><h3>' . "\n";
if( $page > 1 ) {
    $html_out .= '<a class="ah3" href="list.php?date=' . $date . '&page=1">&lt;&lt; First</a>';
    $html_out .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    $html_out .= '<a class="ah3" href="list.php?date=' . $date . '&page=' . ( $page - 1 ) . '">&lt; Previous</a>' . "\n";
}
$html_out .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$html_out .= '<input type="hidden" name="date" value="' . $date . '" />' . "\n";
$html_out .= 'Page:<select name="page">' . "\n";
for( $i = 1; $i <= $total_pages; $i++ ) {
    if( $i == $page ) {
        $html_out .= '<option value="' . $i . '" selected="selected">' . $i . '</option>' . "\n";
    } else {
        $html_out .= '<option value="' . $i . '">' . $i . '</option>' . "\n";
    }
}
$html_out .= '</select><input type="submit" value="Change" />' . "\n";
$html_out .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
if( $page < $total_pages ) {
    $html_out .= '<a class="ah3" href="list.php?date=' . $date . '&page=' . ( $page + 1 ) . '">Next &gt;</a>';
    $html_out .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    $html_out .= '<a class="ah3" href="list.php?date=' . $date . '&page=' . $total_pages . '">Last &gt;&gt;</a>' . "\n";
}
$html_out .= '</h3></fieldset></form>' . "\n";

require('footer.php');

?>
