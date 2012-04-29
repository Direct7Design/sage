<?php

/*
 * db.php                                                          
 *                                                                      
 * Last modified 04/16/2005 by hpxchan                                  
 *                                                                      
 * Sage Folding@Home Stats System, version 1.0.7                         
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
        include('db/mysql.php');
        break;

    case 'mysql4':
        include('db/mysql4.php');
        break;

    case 'postgres':
        include('db/postgres7.php');
        break;

    case 'mssql':
        include('db/mssql.php');
        break;

    case 'oracle':
        include('db/oracle.php');
        break;

    case 'msaccess':
        include('db/msaccess.php');
        break;

    case 'mssql-odbc':
        include('db/mssql-odbc.php');
        break;
}

// Make the database connection.
$db = new sql_db($db_host, $db_user, $db_pass, $db_name, false);
if(!$db->db_connect_id)
{
   die('Could not connect to the database');
}

?>
