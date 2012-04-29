<?php

// ######################################################################
// #                                                                    #
// #   footer_template.php                                              #
// #                                                                    #
// #   Last Modified: 02/15/2005                                        #
// #                                                                    #
// #   Sage version 0.01                                                #
// #                                                                    #
// ######################################################################
// #                                                                    #
// ######################################################################
// #                                                                    #
// #   Copyright (C) 2005 SamuraiDev                                    #
// #                                                                    #
// #   This program is free software; you can redistribute it and/or    #
// #   modify it under the terms of the GNU General Public License      #
// #   as published by the Free Software Foundation; either version 2   #
// #   of the License, or (at your option) any later version.           #
// #                                                                    #
// ######################################################################

$htmlOut .= '</td></tr></table>';

$htmlOut .= '</body></html>';

print($htmlOut);

//
// close mysql link
//

mysql_close($myLink);

?>
