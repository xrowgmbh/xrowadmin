<?php
/**
 * File phpmyadmin.php
 *
 * @package xrowadmin
 * @version //autogentag//
 * @copyright Copyright (C) 2007 xrow. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl.txt GPL License
 */
$Module =& $Params['Module'];
$http =& eZHTTPTool::instance();

eZDebug::writeNotice( "Starting PHPMYADMIN" );

ob_end_clean();

$GLOBALS['EZPMA_ORIGDIR'] = "../../../../";

#$GLOBALS['EZPMA_CHDIR'] = "extension/xrowadmin/src/phpmyadmin/";
$GLOBALS['EZPMA_CHDIR'] = "C:\workspace\environment\phpmyadmin";

$GLOBALS['EZPMA_CHDIR']  = realpath( $GLOBALS['EZPMA_CHDIR']  );
print ($GLOBALS['EZPMA_CHDIR'] );
chdir( $GLOBALS['EZPMA_CHDIR'] );

function eZDebugErrorHandler2( $errno, $errstr, $errfile, $errline )
{
    chdir( $GLOBALS['EZPMA_ORIGDIR'] );
 print( $errno .  $errstr . $errfile . $errline);
    #   eZDebugErrorHandler( $errno, $errstr, $errfile, $errline );
    chdir( $GLOBALS['EZPMA_CHDIR'] );
}
function eZFatalError2()
{
    eZDebug::setHandleType( EZ_HANDLE_NONE );
    $debug = eZDebug::instance();
    print( "<b>Fatal error2</b>: eZ publish did not finish its request<br/>" );
    print( "<p>The execution of eZ publish was abruptly ended, the debug output is present below.</p>" );
    print( $debug->printReportInternal() );
    exit();
}
$old_error_reporting = true;
set_error_handler("eZDebugErrorHandler2");
#eZExecution::addFatalErrorHandler( 'eZFatalError2' );
print("here");



#require_once('./libraries/grab_globals.lib.php');
#require_once('./libraries/common.lib.php');

#var_dump( $cfg ); die("bjoern");
$GLOBALS['db']="db_mobotix";
$GLOBALS['charset']="utf-8";
$i=1;
$cfg['Servers'][$i]['host']          = 'localhost'; // MySQL hostname or IP address
$cfg['Servers'][$i]['port']          = '';          // MySQL port - leave blank for default port
$cfg['Servers'][$i]['socket']        = '';          // Path to the socket - leave blank for default socket
$cfg['Servers'][$i]['connect_type']  = 'tcp';       // How to connect to MySQL server ('tcp' or 'socket')
$cfg['Servers'][$i]['extension']     = 'mysql';     // The php MySQL extension to use ('mysql' or 'mysqli')
$cfg['Servers'][$i]['compress']      = FALSE;       // Use compressed protocol for the MySQL connection
$cfg['Servers'][$i]['auth_type']     = 'config';    // Authentication method (config, http or cookie based)?
$cfg['Servers'][$i]['user']          = 'root';      // MySQL user
$cfg['Servers'][$i]['password']      = '';          // MySQL password (only needed
                                                    // with 'config' auth_type)
$cfg['Servers'][$i]['only_db']       = 'db_mobotix';   


$default_server = $cfg['Servers'][1];



#ini_set('include_path', realpath( $include_path ) . eZSys::envSeparator() . ini_get('include_path') );
include_once( $GLOBALS['EZPMA_CHDIR'].'/index.php');

?>