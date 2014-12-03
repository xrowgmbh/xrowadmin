<?php

/*
    this script removes the given object array
    php -d memory_limit=2048M bin/php/ezexec.php extension/xrowadmin/bin/remove_user.php
*/

$cli = eZCLI::instance();
$db = eZDB::instance();

$cli->output( "The script has started" );

$objects_to_delete = array();
#$objects_to_delete = array(347847,347947,347953,347963,347981,347995,348065,348067,348101,348133,348137,348147,348209,348217,348225,348233,348237,348241,348261,348315,348327,348329,348339,348341,348355,348381,348433,348447,348455,348505,348519,348527,348533,348577,348583,348591,348597,348641,348643,348739,348805,349223,349225,349247,349277,349333,349399,349409,349419,349435,349459,349603,349619,349621,349643,349677,349689,349699,349801,349821,349835,349863,349903,349919,349943,350043,350103,350111,350131,350751,350765,350795,350849,350851);
#$objects_to_delete = $db->arrayQuery("");
$objects_skipped = array();

$cli->output( count($objects_to_delete) . " elements found" );

$cli->output( "====================================" );

$db->begin();
foreach ( $objects_to_delete as $object_id )
{
    $object = eZContentObject::fetch( $object_id );
    $user = eZUser::fetch( $object_id );

    if ( $user instanceof eZUser && $object instanceof eZContentObject )
    {
        //remove object
        $object->removeThis();
        //remove user data
        eZUser::removeUser( $object_id );
        $cli->output( $user->Email . "(" . $user->ContentObjectID . ")" . " removed." );
    }
    else
    {
        $objects_skipped[] = $object_id;
    }
}
$db->commit();

$cli->output( "====================================" );

if (count($objects_skipped) >= 1)
{
    $cli->output( count($objects_skipped) . " objects couldnt be fetched: " );
    foreach( $objects_skipped as $id )
    {
        $cli->output( $id );
    }
}
else
{
    $cli->output( "All items removed." );
}

$cli->output( "The script has finished!" );

?>