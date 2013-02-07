#!/usr/bin/env php
<?php

//@TODO Translate into english

// script initializing
require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 
    'description' => ( "\n" . "This script will analyse the DB.\n" ) , 
    'use-session' => false , 
    'use-modules' => true , 
    'use-extensions' => true 
) );
$script->startup();
$script->initialize();

$ini = eZINI::instance();
$userCreatorID = 14;
$user = eZUser::fetch( $userCreatorID );
eZUser::setCurrentlyLoggedInUser( $user, $userCreatorID );

$db = eZDB::instance();
$sqlArray = array( 
    "Test::ezorder: Doppelte Order Nummern?" => array( 
        "details" => false ,
        "sql" => "SELECT COUNT(`created`) as count FROM `ezorder` GROUP BY `order_nr` HAVING ( COUNT(`order_nr`) > 1 );" 
    ) , 
    "Test::ezorder: Erfolgreiche Orders innerhalb eine woche" => array( 
        "details" => true , 
        "fields" => array( 
            "created" => "date",
            "data_text_1" => "xml" , 
            "order_nr" => "string"
        ) ,
		//@TODO make it abstrakt sometime...
        "sql" => "SELECT data_text_1, created, email, order_nr, status_id FROM `ezorder` WHERE  created >= 1352667600 AND created <=1352718000 AND status_id !=0;" 
    ) , 
    "Test::ezcontentobject_tree: NodeID's doppelt?" => array( 
        "details" => false , 
        "sql" => "SELECT COUNT(*) as count FROM `ezcontentobject_tree` GROUP BY `node_id` HAVING ( COUNT(`node_id`) > 1 );" 
    ) , 
    "Test::ezcontentobject_tree: Doppelte ObjectID's unter demselben Parent?" => array( 
        "details" => false , 
        "sql" => "SELECT COUNT(`contentobject_id`) as count, parent_node_id, contentobject_id FROM `ezcontentobject_tree` GROUP BY `contentobject_id` HAVING ( COUNT(`contentobject_id`) > 1 AND COUNT(`parent_node_id`) = 1);" 
    ) , 
    "Test::ezcontentobject_tree: Doppelter path_string||path_identification_string " => array( 
        "details" => false , 
        "sql" => "SELECT COUNT(`contentobject_id`) as count, path_string, path_identification_string, contentobject_id FROM `ezcontentobject_tree` WHERE path_string != '/TEMPPATH' AND path_identification_string != '' GROUP BY `path_string` HAVING ( COUNT(`path_string`) > 1 AND COUNT(`path_identification_string`) > 1);" 
    ) , 
    "Test::ezcontentobject_tree|ezcontentobject: Objekte ohne NodeID && status = 1" => array( 
        "details" => false , 
        "sql" => "SELECT * FROM `ezcontentobject` co, `ezcontentobject_tree` cot WHERE (cot.node_id = 0 OR cot.node_id = null OR cot.main_node_id = 0 OR  cot.main_node_id = null ) AND cot.contentobject_id = co.id and co.status=1;" 
    ) , 
    "Test::ezdfsfile: name_hash doppelt" => array( 
        "details" => false , 
        "sql" => "SELECT count(name_hash) FROM `ezdfsfile` GROUP BY `name_hash` HAVING ( COUNT(`name_hash`) > 1 );" 
    ) , 
    "Test::ezbinary_file:attribute_id && version && filename doppelt" => array( 
        "details" => false , 
        "sql" => "SELECT count(filename), contentobject_attribute_id, version, filename FROM `ezbinaryfile` GROUP BY  `contentobject_attribute_id`, version, filename HAVING ( COUNT(`version`) > 1 AND COUNT(`filename`) > 1 AND COUNT(`contentobject_attribute_id`) > 1 );" 
    ) , 
    "Test::ezuser:contentobject_id doppelt" => array( 
        "details" => false , 
        "sql" => "SELECT * FROM `ezuser` GROUP BY  `contentobject_id` HAVING ( COUNT(`contentobject_id`) > 1 );" 
    ) , 
    "Test::ezcontentobject_version: mehrere gleiche versionennummern eines objects" => array( 
        "details" => false , 
        "sql" => "SELECT *, count(contentobject_id ) as count FROM `ezcontentobject_version` GROUP BY `contentobject_id`, version HAVING (  COUNT(`version`) > 1);" 
    ) , 
    "Test::ezontentobject: mehrere versionen eines objects die veröffentlicht sind" => array( 
        "details" => false , 
        "sql" => "SELECT *, count(id) as count FROM `ezcontentobject` where status = 1 AND (modified >= 1352667600 AND modified <=1352718000) GROUP BY `id`, current_version HAVING (  COUNT(`current_version`) > 1);" 
    ) , 
    "Test::ezcontentobject_attribute: Gibt es jedes Attribut nur ein mal pro version, sprache und object?" => array( 
        "details" => false , 
        "sql" => "SELECT * FROM `ezcontentobject_attribute` GROUP BY `contentobject_id`, 'language_code', 'version' HAVING (  COUNT(`language_code`) > 1 AND COUNT(`version`) = 1);" 
    ) , 
    "Test::ezcontentobject_name: Gibt jede pro object_id nur ein mal pro sprache und version?" => array( 
        "details" => false , 
        "sql" => "SELECT * FROM `ezcontentobject_name` GROUP BY `contentobject_id`, 'name', 'content_translation', content_version HAVING (  COUNT(`content_translation`) > 1 AND COUNT(`content_version`) = 1);" 
    ) , 
    "Test::ezcontentobject_tree: Gibt es immer genau eine übereinstimmung für eine veröffentlichten Knoten, ein Object, eine Version?" => array( 
        "details" => false , 
        "sql" => "SELECT * FROM `ezcontentobject_tree` GROUP BY `contentobject_id`, `contentobject_version`, `node_id` HAVING (  COUNT(`node_id`) > 1 AND COUNT(`contentobject_version`) > 1 AND COUNT(`contentobject_id`) > 1);" 
    ) 
);

foreach ( $sqlArray as $index => $sql )
{
    $resultSet = $db->arrayQuery( $sql['sql'] );
    $cli->output( "\n######################" );
    $cli->output( "######################" );
    $cli->output( "\n{$index}" );
    $cli->output( "Query: {$sql['sql']}" );
    
    if ( empty( $resultSet ) )
    {
        $result = "Ergebnis okay!";
    }
    else
    {
        $result = "Bitte Testfall prüfen!";
        
        if ( true === $sql['details'] )
        {
            $result .= "\n";
            $result .= "Details:\n";
            
            foreach ( $resultSet as $item )
            {
                foreach ( $item as $key1 => $entry )
                {
                    switch ( $sql['fields'][$key1] )
                    {
                        case 'xml':
                            $data = simplexml_load_string( $entry );
                            $data = toArray( $data );
                            $itemSet .= "Order Details:";
                            foreach ( $data as $key => $dat )
                            {
                                $itemSet .= "\n{$key}: {$dat}";
                            }
                            break;
                        case 'date':
                            $date = date( 'D, d M Y h:i:s O', $entry );
                            $itemSet .= "\nOrder Datum: {$date}";
                            break;
                        default:
                            $itemSet .= "\n{$key1}: {$entry}";
                            break;
                    }
                }
                $itemSet .= "\n######################\n";
            }
            $result .= $itemSet;
        }
    }
    $cli->output( "Ergebnis: {$result}" );
    $cli->output( "\n######################" );
    $cli->output( "######################" );

}
$cli->output( "Done." );
$script->shutdown();

function toArray( $xml )
{
    $array = json_decode( json_encode( $xml ), TRUE );
    
    foreach ( array_slice( $array, 0 ) as $key => $value )
    {
        if ( empty( $value ) )
            $array[$key] = NULL;
        elseif ( is_array( $value ) )
            $array[$key] = toArray( $value );
    }
    
    return $array;
}

?>