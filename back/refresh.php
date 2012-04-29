<?php

/*
 * refresh.php                                                          
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

require('config.php');

if($_GET[$lock_hole] != $lock_key) {
    die('Killed: Unauthorized Connection');
} else {
    define('IN_SAGE', true);
}

require('date_lib.php');

require('db.php');

$db->sql_query('DROP TABLE `2005022112`;');
$db->sql_query('DROP TABLE `tables_index`;');

function get_index_table($db_object, $mode = 1)
{
    $index_table_select;
    if($mode == 0) {
        $index_table_select = 'SELECT `name` FROM `tables_index` WHERE `rid` = 1;';
    } else {
        $mode = 1;
        $index_table_select = 'SELECT * FROM `tables_index`;';
    }
    $index_table_result = $db_object->sql_query($index_table_select);
    if(!$index_table_result) {
        return 0;
    }
    if($mode == 1) {
        $index_table_array = array();
        $i = 0;
        while($current_row = $db_object->sql_fetchrow($index_table_result))
        {
            $index_table_array[$i] = $current_row;
            $i++;
        }
        return $index_table_array;
    } elseif($mode == 0) {
        $current_row = $db_object->sql_fetchrow($index_table_result);
        return $current_row[0];
    }
}

// Stanford team stats: http://vspx27.stanford.edu/daily_team_summary.txt
// Stanford specific team stats: http://vspx27.stanford.edu/teamstats/team37941.txt

require_once('extract_home.php');

//
// close mysql link
//

$db->sql_close();

?>
