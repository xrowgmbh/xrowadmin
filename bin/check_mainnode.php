<?php

/*
    this script checks if every contentoject has a main node
    php -d memory_limit=2048M bin/php/ezexec.php extension/xrowadmin/bin/check_mainnode.php
*/

$cli = eZCLI::instance();
$cli->output( "The script has started" );

$db = eZDB::instance();

$real_bad_items = 0;

$potential_bad_items = $db->arrayQuery("SELECT contentobject_id, node_id, path_identification_string FROM ezcontentobject_tree where main_node_id = 0 group by contentobject_id;");

$cli->output( count($potential_bad_items) . " possible threats found!" );

foreach ( $potential_bad_items as $item )
{
    $tmp_obj = eZContentObject::fetch($item["contentobject_id"]);
    if ( $tmp_obj->MainNodeID() === NULL )
    {
        $real_bad_items++;
        $tmp_node = eZContentObjectTreeNode::fetch( $item["node_id"] );

        if ( $tmp_node instanceOf eZContentObjectTreeNode )
        {
            #activate the next line if you like to auto correct the missing main node
            #eZContentObjectTreeNode::updateMainNodeID($item["node_id"], $item["contentobject_id"], false, $tmp_node->ParentNodeID );
        }

        $cli->output( "Object " . $item["contentobject_id"]  . " has no main node id( " . $item["path_identification_string"] . " )" );
    }
}

$cli->output( $real_bad_items . " real threats found!" );

$cli->output( "The script has finished" );

?>