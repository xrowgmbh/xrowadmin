#!/usr/bin/env php
<?php

$cli = eZCLI::instance();
$script = eZScript::instance( array( 
    'description' => ( "\n" . "This script will analyse the DB for missing images.\n" ) , 
    'use-session' => false , 
    'use-modules' => true , 
    'use-extensions' => true 
) );
$script->startup();
$script->initialize();

$ini = eZINI::instance();
$limit_per_fetch = 1000;
$invalid_entries = 0;
$missing_completely = 0;
$badly_expired = 0;

$offset = 0;
#$userCreatorID = 14;
#$user = eZUser::fetch( $userCreatorID );
#eZUser::setCurrentlyLoggedInUser( $user, $userCreatorID );

$db = eZDB::instance();
$count = 0;

$cli->output( "Start Fetching Image Attributes" );

do {
	$query = "SELECT ezcontentobject_attribute.data_text, ezcontentobject.id, ezcontentobject_attribute.language_code FROM ezcontentobject_attribute, ezcontentobject
		WHERE ezcontentobject_attribute.contentobject_id = ezcontentobject.id
		AND ezcontentobject_attribute.data_type_string = 'ezimage'
		AND ezcontentobject_attribute.version = ezcontentobject.current_version
		LIMIT $limit_per_fetch
		OFFSET $offset;";

	$resultSet = $db->arrayQuery( $query );
	$count = count($resultSet);
	$offset = $offset+$count;
	$cli->output( "############# Checking the next " . $limit_per_fetch . " Elements(" . $offset . ") ##############" );
	foreach ( $resultSet as $result )
	{

		$dom = new DOMDocument;
		$dom->loadXML($result["data_text"]);
		$images = $dom->getElementsByTagName('ezimage');
		foreach ($images as $image)
		{
			//image path can be empty when image is not translatable or when its not required i guess
			$image_path = $image->getAttribute('url');
			if ($image_path != "" )
			{
				$dfs_entry = $db->arrayQuery( 'SELECT * FROM ezdfsfile where name_trunk = "' . $image_path . '"; ');
				//echo $troll->c14N(false,true);
				if ( count( $dfs_entry ) == 0)
				{
					$cli->output( "(Broken)Problem in Object " . $result["id"] . "(lang:" . $result["language_code"] . ") on image:" . $image_path );
					$missing_completely++;
				}
				else if ( $dfs_entry[0]["expired"] == 1 OR $dfs_entry[0]["mtime"] <= 0 )
				{
					$cli->output( "(Expired)Problem in Object " . $result["id"] . "(lang:" . $result["language_code"] . ") on image:" . $image_path );
					$badly_expired++;
				}
			}
		}
	}

} while ($count == $limit_per_fetch);

$cli->output( "Done." );

$cli->output( "=========================================================" );
$cli->output( "Total Result" );
$cli->output( "Missing Images: " . $missing_completely );
$cli->output( "Expired Images: " . $badly_expired );

$script->shutdown();

?>