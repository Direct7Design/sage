<?php

/*
 * db.php                                                          
 *                                                                      
 * Last modified 04/24/2005 by hpxchan                                  
 *                                                                      
 * Copyright (C) 2005 SamuraiDev                                        
 *                                                                      
 * This program is free software; you can redistribute it and/or    
 * modify it under the terms of the GNU General Public License      
 * as published by the Free Software Foundation; either version 2   
 * of the License, or (at your option) any later version.
 *
 * This particular script has been borrowed from the awesome
 * folks over at phpBB (http://phpbb.com), and has been modified
 * by the Sage development team for use with Sage. Thanks, phpBB!           
 */

if(!defined('IN_SAGE')) {
    die('Killed: Unauthorized Connection');
}

switch($db_type)
{
    case 'mysql':
        require('db/mysql.php');
        break;

    case 'mysql4':
        require('db/mysql4.php');
        break;

    case 'postgres':
        require('db/postgres7.php');
        break;

    case 'mssql':
        require('db/mssql.php');
        break;

    case 'oracle':
        require('db/oracle.php');
        break;

    case 'msaccess':
        require('db/msaccess.php');
        break;

    case 'mssql-odbc':
        require('db/mssql-odbc.php');
        break;
}

// Make the database connection.
$db = new sql_db($db_host, $db_user, $db_pass, $db_name, false);
if(!$db->db_connect_id)
{
   die('Could not connect to the database');
}

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
        for( $i = 0; $current_row = $db_object->sql_fetchrow( $index_table_result ); $i++ ) {
            $index_table_array[$i] = $current_row;
        }
        return $index_table_array;
    } elseif( $mode == 0 ) {
        return 1;
    }

}

?>
