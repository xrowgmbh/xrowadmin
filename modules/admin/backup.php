<?php
$Module =& $Params['Module'];
$http = eZHTTPTool::instance();
if ( $http->hasPostVariable('download') or $http->hasGetVariable('download') )
{
	include( 'extension/ezadmin/classes/ezbackup.php' );
	$backup = new eZBackup();
	$backup->backup();
	$backup->download(); 
}

$tpl = eZTemplate::factory();
$Result = array();
$Result['left_menu'] = "design:parts/ezadmin/menu.tpl";
$Result['content'] = $tpl->fetch( "design:ezadmin/backup.tpl" );
$Result['path'] = array( array( 'url' => false,
                        'text' => 'Backup' ) );
?>