<?php
/**
 * File containing the systemcheck
 *
 * @package ezadmin
 * @version //autogentag//
 * @copyright Copyright (C) 2009 xrow. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl.txt GPL License
 */

include_once ( 'autoload.php' );
include_once ( 'kernel/setup/ezsetuptests.php' );
include_once ( 'kernel/setup/steps/ezstep_system_check.php' );
include_once ( 'kernel/common/template.php' );
require_once( 'kernel/common/i18n.php' );

$Module = $Params['Module'];
$tpl = templateInit();
$ini = eZINI::instance( 'systemcheck.ini' );
$http = eZHTTPTool::instance();
$db = eZDB::instance();

if ( $Module->isCurrentAction( 'Cancel' ) )
{
    $Module->redirectTo( 'admin/menu' );
}

if ( $http->hasPostVariable( 'Run' ) )
{
    $phptestarray = array();
    $testlist = array();
    $phptestoutputarray = array();
    $customtestoutputarray = array();
    $dbarray = array();
    $dbconnect = false;
    $phptest = $ini->variable( 'SystemCheckSettings', 'PHPTest' );
    $customtests = array();
    if( $ini->hasVariable( 'SystemCheckSettings', 'CustomTests' ) )
        $customtests = $ini->variable( 'SystemCheckSettings', 'CustomTests' );

    if ( $phptest == 'enabled' )
    {
        $phptestarray = $ini->variable( 'PHPTestSettings', 'PHPTests' );
        foreach ( $phptestarray as $phptestkey => $phptestitems )
        {
            $testlist[$phptestkey] = $phptestitems;
        }
        
        $phptestoutputarray = eZSetupRunTests( $testlist, 'eZSetup:init:system_check', $PersistenceList );

        if ( $db->isConnected() )
        {
            $dbconnect = true;
        }
        $dbarray[1] = 'database_connect';
        $dbarray[2] = array(
            'persistent_data' => array(
                'result' => array(
                    'value' => $dbconnect
                )
            )
        );
        array_push( $phptestoutputarray['results'], $dbarray );

        // create headlinearray
        $phptestoutputarray['headlines'] = array( 
            'phpversion' => 'PHP Version',
            'variables_order' => 'Environment Variables',
            'php_session' => 'PHP Session',
            'directory_permissions' => 'Directory Permissions',
            'settings_permission' => 'Settings Permission',
            'database_extensions' => 'Database Handler',
            'php_magicquotes' => 'Magic Quotes',
            'magic_quotes_runtime' => 'Magic Quotes Runtime',
            'php_register_globals' => 'Register Globals',
            'mbstring_extension' => 'MBString Extension',
            'curl_extension' => 'cURL Extension',
            'zlib_extension' => 'zlib Extension',
            'dom_extension' => 'DOM Extension',
            'iconv_extension' => 'iconv Extension',
            'file_upload' => 'File Uploading',
            'open_basedir' => 'open_basedir',
            'safe_mode' => 'Safe Mode',
            'image_conversion' => 'Image Conversion',
            'texttoimage_functions' => 'Text Creation Functions',
            'memory_limit' => 'Memory Limit',
            'execution_time' => 'Execution Time',
            'allow_url_fopen' => 'allow_url_fopen',
            'accept_path_info' => 'AcceptPathInfo',
            'timezone' => 'Timezone',
            'ezcversion' => 'eZ Components Version',
            'database_connect' => 'Database Connection'
        );

        $phptestoutputarray['testtitle'] = 'PHP Tests';
        $tpl->setVariable( 'phptest', $phptestoutputarray );
    }
    
    /* if you created a custom check we will get it here */
    if ( is_array( $customtests ) && count( $customtests ) > 0)
    {
        foreach ($customtests as $customtest)
        {
            $optionArray = array( 
                'iniFile' => 'systemcheck.ini',
                'iniSection' => $customtest,
                'iniVariable' => 'CustomHandlerName'
            );
            $options = new ezpExtensionOptions( $optionArray );
            $CustomSysCheckHandler = eZExtension::getHandlerClass( $options );
            
            $customtestarray = $ini->variable( $customtest, 'CustomTests' );
            $customtesttitle = $ini->variable( $customtest, 'CustomTestTitle' );
            $customtestoutputarray[$customtest] = $CustomSysCheckHandler->runTests( $customtestarray );
            $customtestoutputarray[$customtest]['headlines'] = $CustomSysCheckHandler->getHeadlines();
            $customtestoutputarray[$customtest]['testtitle'] = $customtesttitle;
        }
        $tpl->setVariable( 'customtests', $customtestoutputarray );
    }
}

$Result = array();
$Result['left_menu'] = 'design:parts/ezadmin/menu.tpl';
$Result['content'] = $tpl->fetch( 'design:ezadmin/systemcheck.tpl' );
$Result['path'] = array( 
    array( 
        'url' => false , 
        'text' => ezpI18n::tr( 'extension/admin', 'System Check' ) 
    ) 
);
?>