<?php
/**
 * File setowner.php
 *
 * @package ezadmin
 * @version //autogentag//
 * @copyright Copyright (C) 2007 xrow. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl.txt GPL License
 */
include_once( 'kernel/common/template.php' );
$tpl = templateInit();
$http = eZHTTPTool::instance();
// Start module definition
$module =& $Params["Module"];
if( !$http->hasPostVariable('BrowseActionName') == 'BrowseNewOwner' )
{
	include_once('kernel/classes/ezcontentbrowse.php');

	$return = eZContentBrowse::browse( array( 'action_name' => 'BrowseNewOwner',
                                    'description_template' => 'design:content/browse_change_owner.tpl',
                                    'persistent_data' => array( 'ObjectID' => $Params["ObjectID"] ),
                                    'content' => array( 'object_id' => $Params["ObjectID"] ),
                                    'from_page' => '/admin/setowner/'.$Params["ObjectID"] ),
                             $module );

    include_once( 'kernel/content/ezcontentoperationcollection.php' );
    eZContentOperationCollection::clearObjectViewCache( $Params['ObjectID'] );
}
else
{
    $id = $http->postVariable('SelectedObjectIDArray');
    $obj = eZContentObject::fetch( $http->postVariable('ObjectID') );
    $obj->setAttribute( "owner_id", $id[0] );
    $obj->store();
    return $module->redirectTo( '/' );
}


?>