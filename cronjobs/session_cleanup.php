<?php
/**
 * File session_cleanup.php
 *
 * @package ezadmin
 * @version //autogentag//
 * @copyright Copyright (C) 2007 xrow. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl.txt GPL License
 */

    include_once( 'lib/classes/ezsession.php' );
    
    if( !$isQuiet )
        $cli->output( 'Removing expired sessions from database...' );
    
    eZSessionGarbageCollector();

    if( !$isQuiet )
        $cli->output( 'Finished.' );
?>
