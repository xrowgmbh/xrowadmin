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

include_once( 'kernel/common/template.php' );
$tpl = templateInit();
$Result = array();
$Result['left_menu'] = "design:parts/ezadmin/menu.tpl";
$Result['content'] = $tpl->fetch( "design:ezadmin/backup.tpl" );
$Result['path'] = array( array( 'url' => false,
                        'text' => 'Backup' ) );
?>