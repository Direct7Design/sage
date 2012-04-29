<?php

/*
 * refresh.php                                                          
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

require( 'config.php' );

if( $_GET[$lock_hole] != $lock_key ) {
    die( 'Killed: Unauthorized Connection' );
} else {
    define( 'IN_SAGE', true );
}

require( 'date_lib.php' );

require( 'db.php' );

// Stanford team stats: http://vspx27.stanford.edu/daily_team_summary.txt
// Stanford specific team stats: http://vspx27.stanford.edu/teamstats/team37941.txt

set_time_limit(10800);

require_once( 'extract_home.php' );

$db->sql_close();

?>
