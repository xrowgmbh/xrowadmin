<?php
$Module =& $Params['Module'];
$http = eZHTTPTool::instance();
if ( $http->hasPostVariable('download') or $http->hasGetVariable('download') )
{
	include( 'extension/xrowadmin/classes/ezbackup.php' );
	$backup = new eZBackup();
	$backup->backup();
	$backup->download(); 
}

$tpl = eZTemplate::factory();
$Result = array();
$Result['left_menu'] = "design:parts/xrowadmin/menu.tpl";
$Result['content'] = $tpl->fetch( "design:xrowadmin/backup.tpl" );
$Result['path'] = array( array( 'url' => false,
                        'text' => 'Backup' ) );
?>