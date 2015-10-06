#!/usr/bin/env php
<?php

//you can call the script like: /usr/local/zend/bin/php bin/php/ezexec.php extension/xrowadmin/bin/check_language_problems.php

$cli = eZCLI::instance();
$script = eZScript::instance( array( 
    'description' => ( "\n" . "This script will analyse the DB for problems with language codes.\n" ) , 
    'use-session' => false , 
    'use-modules' => true , 
    'use-extensions' => true 
) );
$script->startup();
$script->initialize();
$db = eZDB::instance();
$ini = eZINI::instance();

$language_map = $bad_objects = $skip_list = array();
$limit_per_fetch = 1000;
$offset = $bad_data_rows = $count = 0;
$dry_run = false;

//TODO: can be made flexible propably (importent for other customers!)
$language_map["eng"] = array(2);
$language_map["esl"] = array(4);
$language_map["fre"] = array(8);
$language_map["ger"] = array(16);
$language_map["heb-IL"] = array(32);
$language_map["ita"] = array(64);
$language_map["jpn-JP"] = array(128);
$language_map["eng-US"] = array(256);
$language_map["fre-CH"] = array(512);
$language_map["ger-DE"] = array(1024);
$language_map["ita-CH"] = array(2048);
$language_map["ita-IT"] = array(4096);
$language_map["ger-CH"] = array(8192);
$language_map["dut"] = array(16384);
$language_map["eng-GB"] = array(32768);
$language_map["eng-AU"] = array(65536);
$language_map["chi-CN"] = array(131072);
$language_map["other"] = array(262144);
$language_map["por"] = array(524288);
$language_map["por-BR"] = array(1048576);
$language_map["por-PT"] = array(2097152);
$language_map["rus-RU"] = array(4194304);
$language_map["fre-FR"] = array(8388608);
$language_map["esl-ES"] = array(16777216);
$language_map["esl-LM"] = array(33554432);
$language_map["tur-TR"] = array(67108864);
$language_map["ger-AT"] = array(134217728);

//now add all conditions for "always available" 1 bit
foreach($language_map as $locale => $map)
{
    $language_map[$locale][] = $map[0]+1;
}

$cli->output( "Start Fetching all Attributes" );

//only objects younger than 17.08.2011
$special_condition = " AND obj.published < 1313550000";

do {
    echo "\n status fetching: " . $offset . "\n";
    $query = "SELECT attr.data_type_string, attr.contentobject_id, attr.id, attr.language_code, attr.language_id, attr.version, FROM_UNIXTIME(obj.published) as 'created', obj.name, obj.contentclass_id
              FROM ezcontentobject_attribute AS attr, ezcontentobject AS obj
              WHERE attr.contentobject_id = obj.id
              AND obj.contentclass_id = 4 
              $special_condition
              LIMIT $limit_per_fetch
              OFFSET $offset;";

    $resultSet = $db->arrayQuery( $query );
    $count = count($resultSet);
    $offset = $offset+$count;

    foreach ( $resultSet as $result )
    {
        if (in_array($result["language_id"], $language_map[$result["language_code"]]))  
        {
            //echo "+";
        }
        else
        {
            //echo "-";
            $bad_data_rows++;
            $bad_objects[$result["contentobject_id"]] = $result["created"];

            //correcting data
            if ( !in_array( $result["version"] . "_" . $result["contentobject_id"] . "_" . $result["language_code"], $skip_list) AND $dry_run != true )
            {
                $db->begin();
                $db->arrayQuery( 'UPDATE ezcontentobject_attribute as attr SET language_id = ' . $language_map[$result["language_code"]][1] . '
                                  WHERE attr.version = ' . $result["version"] . ' 
                                  AND attr.contentobject_id = ' . $result["contentobject_id"] . '
                                  AND attr.language_code = "' . $result["language_code"] . '";' );
                $db->commit();
                echo "updating: " . $result["version"] . "_" . $result["contentobject_id"] . "_" . $result["language_code"] . " // ";
                //add combination to skip list so that we do not update the same thing more than once
                $skip_list[] = $result["version"] . "_" . $result["contentobject_id"] . "_" . $result["language_code"]; 
            }
        }
    }

    //clearing every now and then to keep array short
    $skip_list = array();

    //have a break of 118 seconds every 75.000 elements
    if( $offset % 75000 == 0)
    {
        echo "sleeping to cool down the database";
        sleep(118);
    }
    if( $offset % 500000 == 0)
    {
        echo "sleeping to extra cool down the database";
        sleep(118);
    }

} while ($count == $limit_per_fetch);

$cli->output( "Done." );

$cli->output( "=========================================================" );
$cli->output( "Total Result" );
$cli->output( "Bad data rows: " . $bad_data_rows );
$cli->output( "Amount of broken objects: " . count($bad_objects) );

$script->shutdown();

?>