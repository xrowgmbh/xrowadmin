<?php
/**
 * File eaccelerator.php
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

include_once( 'extension/xrowadmin/src/eaccelerator'.'/eaccelerator.php');

eZExecution::cleanExit();

?>