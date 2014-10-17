#!/usr/bin/env php
<?php
/**
 * File containing the updatealwaysavailable.php script.
 */

set_time_limit( 0 );

require_once 'autoload.php';

$cli = eZCLI::instance();

$script = eZScript::instance(
    array(
        'description' => (
            "eZ Publish always-available flag updater.\n\n" .
            "Goes trough all objects and toggles the always-available flag twice" .
            "\n" .
            "updatealwaysavailable.php"
        ),
        'use-session' => true,
        'use-modules' => true,
        'use-extensions' => true
    )
);

$script->startup();

$options = $script->getOptions(
    "[sql]",
    "",
    array(
        'sql' => "Display sql queries",
    )
);
$script->initialize();
$script->setIterationData( '.', '~' );

$showSQL = $options['sql'] ? true : false;
$siteAccess = $options['siteaccess'] ? $options['siteaccess'] : false;

if ( $siteAccess )
{
    if ( in_array( $siteAccess, eZINI::instance()->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' ) ) )
    {
        $cli->output( "Using siteaccess $siteAccess for update" );
    }
    else
    {
        $cli->notice( "Siteaccess $siteAccess does not exist, using default siteaccess" );
    }
}

eZExecution::registerShutdownHandler();
$db = eZDB::instance();

$db->setIsSQLOutputEnabled( $showSQL );

$def = eZContentObject::definition();
$conds = null;
$count = eZPersistentObject::count( $def, $conds, 'id' );

$cli->output( "Number of objects to update: $count" );

$length = 50;
$limit = array(
    'offset' => 0,
    'length' => $length
);

$script->resetIteration( $count );

do
{
    // clear in-memory object cache
    eZContentObject::clearCache();

    $objects = eZPersistentObject::fetchObjectList( $def, null, $conds, null, $limit );

    foreach ( $objects as $object )
    {
        $isAlwaysAvailable = $object->isAlwaysAvailable();
        $initialLanguageId = $object->attribute( 'initial_language_id' );
        if ($object instanceOf eZContentObject && $object->currentVersion())
        {
            $object->setAlwaysAvailableLanguageID( $isAlwaysAvailable ? false : $initialLanguageId );
            $object->setAlwaysAvailableLanguageID( $isAlwaysAvailable ? $initialLanguageId : false );
        }
        else
        {
            var_dump($object->ID);
        }
        $script->iterate( $cli, true );
    }

    $limit['offset'] += $length;
}
while ( count( $objects ) == $length );

$cli->output();
$cli->output( "done." );

$script->shutdown();

?>
