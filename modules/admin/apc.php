<?php
/**
 * File apc.php
 *
 * @package xrowadmin
 * @version //autogentag//
 * @copyright Copyright (C) 2007 xrow. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl.txt GPL License
 */
$Module =& $Params['Module'];
$http =& eZHTTPTool::instance();

eZDebug::writeNotice( "Starting APC SCRIPT" );

ob_end_clean();
$_SERVER['PHP_SELF'] = eZSys::requestURI();

define( 'USE_AUTHENTIFICATION', 0 );	

include_once( 'extension/xrowadmin/src/apc/'.'/apc.php');

eZExecution::cleanExit();

?>