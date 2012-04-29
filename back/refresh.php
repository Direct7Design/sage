<?php

/*
 * refresh.php                                                          
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

require( 'config.php' );

if( $_GET[$lock_hole] != $lock_key ) {
    die( 'Killed: Unauthorized Connection' );
} else {
    define( 'IN_SAGE', true );
}

require( 'date_lib.php' );

require( 'db.php' );

function get_index_table( $db_object, $index_table_name, $mode = 1 )
{

    $index_table_select;

    if( $mode == 0 ) {
        $index_table_select = 'SELECT `rid` FROM `' . $index_table_name . '` WHERE `rid` = 1;';
    } elseif( $mode == 1 ) {
        $index_table_select = 'SELECT * FROM `' . $index_table_name . '`;';
    }

    $index_table_result = $db_object->sql_query( $index_table_select );

    if( !$index_table_result ) {
        return 0;
    }

    if( $mode == 1 ) {
        $index_table_array = array();
        $i = 0;
        while( $current_row = $db_object->sql_fetchrow( $index_table_result ) )
        {
            $index_table_array[$i] = $current_row;
            $i++;
        }
        return $index_table_array;
    } elseif( $mode == 0 ) {
        return 1;
    }

}

// Stanford team stats: http://vspx27.stanford.edu/daily_team_summary.txt
// Stanford specific team stats: http://vspx27.stanford.edu/teamstats/team37941.txt

require_once( 'extract_home.php' );

//
// close mysql link
//

$db->sql_close();

?>
