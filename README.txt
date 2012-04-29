/*
 * README                                                     
 *                                                                      
 * Read me first for installation help, notes, and references
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

/******************************
 * I.    Table of Contents    
 ******************************/

I.    Table of Contents

II.   Project Files List
    A.    README.txt
    B.    CHANGES.txt
    C.    CONTRIB.txt
    D.    LICENSE.txt
    E.    config.php
    F.    date_lib.php
    G.    refresh.php
    H.    extract_home.php
    I.    process_home.php
    J.    display.php
    K.    list.php
    L.    header.php
    M.    footer.php
    N.    default.css

III.  System Requirements
    A.    Server programming languages
    B.    Server database system
    C.    Client web browser

IV.   Installation and Operation
    A.    General installation directions
    B.    General operation directions
    C.    Unix-specific additional information

V.    Notes and Warnings

VI.   Project Management
   
/******************************
 * II.   Project Files List
 ******************************/

 * A.    README.txt

Contains general project information, installation and operating guides,
system requirements, project management information (including contact
information).

 * B.    CHANGES.txt

A log of all significant changes made directly to the Sage project, organized
by release.

 * C.    CONTRIB.txt

A list of all project contributors, including developers, beta-testers,
financial donors, particularly useful feedback-giving users, etc.

 * D.    LICENSE.txt

The full license under which the Sage Folding@Home Stats System is made
available to you, whoever you are. 

 * E.    config.php

A php file consisting of a number of program-critical variable definitions
used as configuration options, included at the top of the refresh.php driver
script.

 * F.    date_lib.php

A general (not Sage-specific) library of functions dealing with date and
time issues (obviously, ones that have not already been covered in the 
official PHP function set).

 * G.    refresh.php

The driver script for the Sage backend (the processing that occurs
periodically to update the stats on your server). Includes date_lib.php,
config.php, and extract_home.php.

 * H.    extract_home.php

Helper script included by refresh.php. Extracts user and team information
from Stanford's Folding@Home stats text files, sorts the relevant values
into variables, and passes those variables into functions from
process_home.php. Includes process_home.php.

 * I.    process_home.php

Library of Sage-specific stats-processing functions included by other scripts
in Sage to process relevant information and insert it into the database.

 * J.    display.php

Primary display script for the Sage frontend. Handles team and user summaries.

 * K.    list.php

Secondary display script for the Sage frontend. Displays lists of users, teams.

 * L.    header.php

Universal header for the Sage frontend; required by all frontend driver scripts
(like display.php and list.php).

 * M.    footer.php

Universal footer for the Sage frontend; required by all frontend driver scripts
(like display.php and list.php).

 * N.    default.css

Default external stylesheet for the Sage frontend pages.
   

/******************************
 * III.  System Requirements
 ******************************/

         THIS SECTION NEEDS TO BE UPDATED!!

 * A.    Server programming languages
         *       PHP 4.? or later

 * B.    Server database system
         *       Database system with one of the following SQL-based database APIs:
                 *       MySQL 3.? or later
                 *       PostgreSQL 7.? or later
                 *       MSSQL ? or later
                 *       MS Access ? or later
                 *       Oracle ? or later
                 *       DB2 ? or later

 * C.    Client web browser
         *       Ummm... general W3C-compliant browser... preferably not IE...
   
/******************************
 * IV.   Installation and Operation
 ******************************/

 * A.    General installation directions

Download the latest Sage sources (see the section entitled ``Project Management''
for information on how to get them). If you use Windows, it would probably be
easiest to download them in ZIP format (indicated by the .zip at the end of the
package name). If you use Unix, just download it in whatever compression method
you're most comfortable with expanding.

Once you've downloaded the sources, you can either expand them locally and send
them to the webserver (most likely via FTP) afterwards, or send the archive to 
your webserver and expand them there. I usually expand first and send later, but
that's just because it seems to take less keystrokes than the second method.

Preferrably, put the Sage sources in an isolated directory, but one that isn't too
hard to access. It would be messy to put them in the root directory for your
domain, but your users might not like it if they have to type in a 50-character
long URL to access their stats. Whatever you do, make sure to keep all of the Sage
files where they are (relative to each other). Everything in the back/ directory
should stay there; everything else should stay out of it.

Make sure your server meets the requirements listed in the section entitled
``System Requirements''... and that's really it. As far as installation goes, you're
done.

 * B.    General operation directions

refresh.php is the stats-refreshing driver script, and it is what you should run
every three hours to refresh the stats (obviously). It must be run with a GET-style
name/value pair, like so:
http://yourdomain.com/sage-stats/back/refresh.php?lockhole=lockkey
Make sure to substitute yourdomain.com for your domain, sage-stats for the path to
the Sage directory, and lockhole/lockkey for the values of the $lock_hole and
$lock_key variables defined in config.php.

Unless you want to go to your computer every three hours to refresh the stats, set
up your server to automatically load refresh.php, as described above, every three
hours. A walk-through for the typical Unix-based server is included below; if you're
on another platform, you're on your own (though we'd love you to write up a
walk-through for us, for running Sage on your platform).

 * C.    Unix-specific additional information
   
To run Sage's refresh.php driver script every three hours on a Unix-based system,
you should use Cron (unless you don't want to, in which case you're on your own).
Make sure lynx (or another browser... but you'll have to do a little modification
of our example below) is installed on your system.

Once you have logged on to your webserver, run `crontab -e`, and edit the crontab
appropriately:

# crontab -e
0 */3 * * * lynx -dump http://yourdomain.com/sage-stats/back/refresh.php?lockhole=lockkey

Then, of course, save and quit. If you've set everything up right, Sage should refresh
its stats database every three hours, on the hour. Remember to substitute the important
information in the example above for your own. I believe there is a way to use this with
the php executable... but I'm not ready to provide help for that method at the moment.

/******************************
 * V.    Notes and Warnings
 ******************************/

Please make sure to customize config.php, especially $lock_hole and $lock_key.
These two fields are used to prevent robots (and malicious humanoids) from
running the stats-refreshing script when it shouldn't be run. Safeguards have
been included in Sage so that it should be impossible to generate two stats tables
for the same update, but you can never be too sure.

Note that Sage depends on Stanford's team stats text pages at:
http://vspx27.stanford.edu/teamstats/team37941.txt
Where 37941 is replaced by the team number of the team your copy of Sage monitors.
If that page does not exist, the desired team is probably too low in the rankings,
and Sage will /not/ work for that team.

Do /NOT/ run the Sage stats-refreshing script (refresh.php, unless something changes)
more than once every three hours. Stanford updates approximately every three hours, so
it would be (a) pointless, (b) hopefully resultless, and (c) rude to Stanford to try
and update your stats more often than the source the stats come from (Stanford).

Want to help with Sage? Contact us through one of the methods listed in ``Project
Management''. All contributions are appreciated!

Future features (tentative, of course... just cool things we'd like to see):
 * Graphical stats signatures, like those of LiquidNinjas and DinoSig
 * Dynamic graphs for the stats pages, like those of EOC (we can't stand to use
   a trial version of a commercial dynamic image-generation program, so we're
   holding graphs off until we (a) develop our own dynamic image-generation program,
   or (b) find an available open-source one).
 * Tetris. Probably based on Flash or Java. Can't wait for the next update, when your
   team takes the number one spot from those sneaky Australians? Don't! Play Tetris!
 * Global stats (for all teams). This feature will be integrated into the primary
   Sage release, and one will be able to toggle on or off through the configuration
   file.
 * More stats configuration. For example, the ability to toggle how long to keep stats
   tables, or maybe even a conditional trigger system.
 * An optional XML-based stats feed. I think EOC has already implemented something
   like this.
 * Pre-built style templates
 * Graphical installer

/******************************
 * VI.   Project Management
 ******************************/
   
As of the last update of this file, Sage Folding@Home Stats System is
managed by the ``SamuraiDev'' development team, and headed by SamuraiDev
member ``hpxchan''. Sage is also registered as a project at the SourceForge
open-source software project database.

If you have any questions, suggestions, comments, requests, etc. please
let us know. We can be reached through the following:

SamuraiDev website:
    http://samuraidev.com

Sage at SourceForge:
    http://sourceforge.net/projects/sage-stats

Project leader (hpxchan) email:
    hpxchan [at] gmail [dot] com
