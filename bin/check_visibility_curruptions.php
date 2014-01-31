#!/usr/bin/env php
<?php

#run script on windows: php bin\php\ezexec.php extension\xrowadmin\bin\check_visibility_curruptions.php
#run script on linux: php bin/php/ezexec.php extension/xrowadmin/bin/check_visibility_curruptions.php

$cli = eZCLI::instance();
$script = eZScript::instance( array( 
    'description' => ( "\n" . "This script will analyse the DB for bad visibility states.\n" ) , 
    'use-session' => false , 
    'use-modules' => true , 
    'use-extensions' => true 
) );
$script->startup();
$script->initialize();
$bad_visible_state = 0;
$hidden_object_array = array();
$db = eZDB::instance();

$cli->output( ".. fetching database information" );

$visible_objects = $db->arrayQuery("SELECT * FROM ezcontentobject_tree where is_invisible = 0 group by parent_node_id;");
$hidden_objects = $db->arrayQuery("SELECT node_id FROM ezcontentobject_tree where is_invisible = 1;");

$cli->output( ".. preparing the data" );

foreach ( $hidden_objects as $hidden_obj )
{
    //create an array of all hidden nodes - setting key so that we can use the quick "isset"
    $hidden_object_array[$hidden_obj["node_id"]] = $hidden_obj["node_id"];
}

$cli->output( ".. comparing the data" );

foreach ( $visible_objects as $item )
{
    if ( isset($hidden_object_array[$item["parent_node_id"]]))
    {
        //increase affected objects count and output the bad state objects
        $bad_visible_state++;
        $cli->output( "node " . $item["node_id"] . " should be hidden because the parent node " . $item["parent_node_id"] . " is hidden." );
    }
}

$cli->output( "Done." );

$cli->output( "=========================================================" );
$cli->output( "Total Result" );
$cli->output( "Amount of hidden parents with visible children: " . $bad_visible_state );

$script->shutdown();

?>