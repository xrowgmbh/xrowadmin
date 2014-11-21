<?php

/*
    this script checks if every contentoject has a main node
    php -d memory_limit=2048M bin/php/ezexec.php extension/xrowadmin/bin/check_mainnode.php
*/

$cli = eZCLI::instance();
$cli->output( "The script has started" );

$db = eZDB::instance();

$real_bad_items = 0;
$corrected_items = 0;
$corrected_items_node_id = 0;

$potential_bad_items = $db->arrayQuery("SELECT contentobject_id, node_id, path_identification_string FROM ezcontentobject_tree where main_node_id = 0 group by contentobject_id;");

$cli->output( count($potential_bad_items ) . " possible threats found!" );
foreach ( $potential_bad_items as $item )
{
    $fetchedObjectTreeNode = eZContentObjectTreeNode::fetch( $item['node_id'] );
    if ( $fetchedObjectTreeNode instanceof eZContentObjectTreeNode )
    {
        $real_bad_items++;
        $correctMainNodeID = 0;
        if ( $fetchedObjectTreeNode->MainNodeID == 0 )
        {
            $nodeList = eZContentObjectTreeNode::fetchByContentObjectID( $fetchedObjectTreeNode->ContentObjectID );
            foreach ( $nodeList as $node )
            {
                if ( $node->MainNodeID != 0 )
                {
                    $correctMainNodeID = $node->MainNodeID;
                    break;
                }
            }
            if ( $correctMainNodeID > 0 )
            {
                #activate the next 2 lines if you like to auto correct the missing main node
                #$corrected_items++;
                #eZContentObjectTreeNode::updateMainNodeID($correctMainNodeID, $fetchedObjectTreeNode->ContentObjectID, false, $fetchedObjectTreeNode->ParentNodeID, true);
            }
            else
            {
                #activate the next 2 lines if you like to replace the missing main node with their own NodeID
                #$corrected_items_node_id++;
                #eZContentObjectTreeNode::updateMainNodeID($fetchedObjectTreeNode->NodeID, $fetchedObjectTreeNode->ContentObjectID, false, $fetchedObjectTreeNode->ParentNodeID, true);
            }
        }
        $cli->output( "Object " . $item["contentobject_id"]  . " has no main node id( " . $item["path_identification_string"] . " )\nCorrect MainNode: " . $correctMainNodeID );
    }
}

$cli->output( $real_bad_items . " real threats found!" );
$cli->output( $corrected_items . " Corrections with correct mainnode_id." );
$cli->output( $corrected_items_node_id . " replacements with node_id." );
$cli->output( "The script has finished!" );

?>