#!/usr/bin/env php
<?php

set_time_limit ( 0 );

require 'autoload.php';
$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish url-alias imported and updater.\n\n" .
                                                         "Will import urls from the older (3.9) system into the new, controlled by the --import* options.\n" .
                                                         "Will also update the url-alias entries from the content object nodes in the system, controlled by the --update-nodes option.\n" .
                                                         "The default behaviour is to update urls for content object nodes only\n" .
                                                         "\n" .
                                                         "updateniceurls.php" ),
                                      'use-session' => true,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "",
                                "",
                                array( ) );
$script->initialize();

$siteAccess = $options['siteaccess'] ? $options['siteaccess'] : false;
if ( $siteAccess )
{
    changeSiteAccessSetting( $siteAccess );
}

function changeSiteAccessSetting( $siteAccess )
{
    $cli = eZCLI::instance();
    if ( in_array( $siteAccess, eZINI::instance()->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' ) ) )
    {
        $cli->output( "Using siteaccess $siteAccess for nice url update" );
    }
    else
    {
        $cli->notice( "Siteaccess $siteAccess does not exist, using default siteaccess" );
    }
}

eZContentLanguage::setCronjobMode( true );

$fetchLimit = 200;

$globalStartTime = microtime( true );

$db = eZDB::instance();

$db->query( "DELETE FROM ezurlalias_ml WHERE is_alias = 1;" );
$db->query( "INSERT INTO ezurlalias_ml SELECT * FROM ezurlalias_ml_old WHERE is_alias = 1" );

function fetchAliasMap( )
{
    $db = eZDB::instance();
    $sql = 'SELECT DISTINCT action, id FROM ezurlalias_ml_old WHERE id in ( SELECT DISTINCT parent FROM ezurlalias_ml_old WHERE is_alias = 1 and parent > 0 ) order by id';
    $rows = $db->arrayQuery( $sql );
    $newarray = array();
    foreach( $rows as $row ){
    	$newarray[$row["id"]] = explode( ":", $row["action"], 2 );;
    }
    return $newarray;
}

$map = fetchAliasMap();
$conds = array( "is_alias" => 1, "parent" => array( '>', 0 ) );
$list = eZURLAliasML::fetchObjectList(eZURLAliasML::definition(), null, $conds );
foreach( $list as $item ){
    if ( isset( $map[ $item->attribute( "parent" ) ] ) ){
    	$node = $map[ $item->attribute( "parent" ) ][1];
    	$elements = eZURLAliasML::fetchByAction( 'eznode', $node );
    	$last = null;
    	foreach( $elements as $element ){
    	    if ( $last !== null and $last != $element->ID){
    	    	echo "no match ". $element->ID ."\n";
    	    	$last = false;
    	    	break;
    	    }
    	    $last = $element->ID;
    	}
    	if ($last){
    	    $item->setAttribute( "parent", $last);
    	    $item->store();
    	}
    }
}

$script->shutdown();