<?php

/*
 * footer.php
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

$html_out .= '</td></tr></table>' . "\n";
$html_out .= '<p class="fineprint">Powered by <a href="http://sage-stats.sourceforge.net">Sage</a> &copy; 2005 <a href="http://samuraidev.com">SamuraiDev</a></p>' . "\n";
$html_out .= '</div></body></html>';

print( $html_out );

?>
