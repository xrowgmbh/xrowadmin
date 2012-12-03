<?php
/**
 * File extension2tgz.php
 *
 * @package ezadmin
 * @version //autogentag//
 * @copyright Copyright (C) 2007 xrow. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl.txt GPL License
 */
include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );
$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "Extension to .tgz\n" .
                                                         "Packs an extension as a tgz file.\n" .
                                                         "\n" .
                                                         "./extension/ezadmin/bin/extension2tgz.php --name=ezdhtml" ),
                                      'use-session' => true,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[name:]",
                                "",
                                array( 'name' => 'Fodler name of the extension' ) );
$script->initialize();

$sys =& eZSys::instance();

if ( !$options['name'] )
{    
    $script->showHelp();
    $script->shutdown();
}
$filename = $options['name'].".tgz";
$path = "extension/".$options['name']."/";
if ( !file_exists( $path ) or !is_dir( $path ) or !is_readable( $path ) )
{
    $cli->output( "Wasn't able to create package for ". $path );
    return $script->shutdown();
}

include_once( 'lib/ezfile/classes/ezarchivehandler.php' );
$archive = eZArchiveHandler::instance( 'tar', 'gzip', $filename );
$fileList[]= $path;
$fileList = array_unique( $fileList );

$archive->addModify( $fileList, '', '' );

$cli->output( 'A new package "' . $filename . '" has been created in your ez publish root directory.' );

return $script->shutdown();

?>