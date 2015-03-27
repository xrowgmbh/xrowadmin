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

$db->query( "TRUNCATE TABLE ezurlalias_ml" );
$db->query( "INSERT INTO ezurlalias_ml SELECT * FROM ezurlalias_ml_save" ); # table with data after updateniceurls.php
$db->query( "INSERT INTO ezurlalias_ml SELECT * FROM ezurlalias_ml_old WHERE is_alias = 1" ); # table before niceurludpate.php

function fetchAliasMap( )
{
    $db = eZDB::instance();
    $sql = 'SELECT DISTINCT * FROM ezurlalias_ml_old WHERE is_alias = 1 order by id';
    $rows = $db->arrayQuery( $sql );
    $newarray = array();
    foreach( $rows as $row ){
    	$newarray[$row["id"]][] = $row;
    }
    return $newarray;
}

$map = fetchAliasMap();
$conds = array( "is_alias" => 1, "parent" => array( '>', 0 ) );
$list = eZURLAliasML::fetchObjectList(eZURLAliasML::definition(), null, $conds );

foreach( $list as $item ){
    if ( isset( $map[ $item->attribute( "parent" ) ] ) ){
    	echo "parent #". $item->attribute( "parent" ) ." in map"."\n";
    }
    else{
       parentImport($item);

    }
}
function parentImport( $item ) {

    
    $db = eZDB::instance();
    if(!$item or !$item->attribute("parent") ){return;}
        
            $rows = $db->arrayQuery( "SELECT * FROM ezurlalias_ml_old WHERE id = " . $item->attribute( "parent" ) );
            if ($rows[0] and !$rows[0]["is_original"]){
            	$map[$rows[0]["id"]]= $rows[0];
            	$db->query( "INSERT INTO ezurlalias_ml SELECT * FROM ezurlalias_ml_old WHERE id = " . $item->attribute( "parent" ) );
            	$nextitem = eZURLAliasML::fetchObject(eZURLAliasML::definition(), null, array( "id" => $item->attribute( "parent" ) ) );
            	parentImport( $nextitem );
            }elseif ( empty($rows) ){
                echo("Deleting record. Problem with ". $item->attribute( "parent" ) );
                $item->remove();
            }else{
            	$nextitem = eZURLAliasML::fetchObject(eZURLAliasML::definition(), null, array( "action" => $rows[0]["action"], "text_md5" => $rows[0]["text_md5"] ) );
            	if ( $nextitem ){
            		echo "Found #".$nextitem->attribute("id") . " ".$nextitem->attribute("text")."\n";
            		$item->remove();
            		$item->setAttribute( "parent" , $nextitem->attribute( "id" ) );
            		$item->store();
            	}
            }
}

$script->shutdown();