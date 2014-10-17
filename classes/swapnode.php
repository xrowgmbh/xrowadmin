<?php

/**
 * 
 * @author stephan
 *
 * Class swapnodes is a straight copy to swap nodes with each other
 *
 */
class ezadminSwapNode
{

    static public function swapNode( $nodeID, $selectedNodeID, $nodeIdList = array() )
    {
        $userClassIDArray = eZUser::contentClassIDs();

        $node             = eZContentObjectTreeNode::fetch( $nodeID );
        $selectedNode     = eZContentObjectTreeNode::fetch( $selectedNodeID );
        $object           = $node->object();
        $nodeParentNodeID = $node->attribute( 'parent_node_id' );
        $nodeParent       = $node->attribute( 'parent' );

        $objectID      = $object->attribute( 'id' );
        $objectVersion = $object->attribute( 'current_version' );

        $selectedObject           = $selectedNode->object();
        $selectedObjectID         = $selectedObject->attribute( 'id' );
        $selectedObjectVersion    = $selectedObject->attribute( 'current_version' );
        $selectedNodeParentNodeID = $selectedNode->attribute( 'parent_node_id' );
        $selectedNodeParent       = $selectedNode->attribute( 'parent' );

        $db = eZDB::instance();
        $db->begin();

        $node->setAttribute( 'contentobject_id', $selectedObjectID );
        $node->setAttribute( 'contentobject_version', $selectedObjectVersion );

        $selectedNode->setAttribute( 'contentobject_id', $objectID );
        $selectedNode->setAttribute( 'contentobject_version', $objectVersion );

        // fix main node id
        if ( $node->isMain() && !$selectedNode->isMain() )
        {
            $node->setAttribute( 'main_node_id', $selectedNode->attribute( 'main_node_id' ) );
            $selectedNode->setAttribute( 'main_node_id', $selectedNode->attribute( 'node_id' ) );
        }
        else if ( $selectedNode->isMain() && !$node->isMain() )
        {
            $selectedNode->setAttribute( 'main_node_id', $node->attribute( 'main_node_id' ) );
            $node->setAttribute( 'main_node_id', $node->attribute( 'node_id' ) );
        }

        $node->store();
        $selectedNode->store();

        // clear user policy cache if this was a user object
        if ( in_array( $object->attribute( 'contentclass_id' ), $userClassIDArray ) )
        {
            eZUser::purgeUserCacheByUserId( $object->attribute( 'id' ) );
        }

        if ( in_array( $selectedObject->attribute( 'contentclass_id' ), $userClassIDArray ) )
        {
            eZUser::purgeUserCacheByUserId( $selectedObject->attribute( 'id' ) );
        }

        // modify path string
        $changedOriginalNode = eZContentObjectTreeNode::fetch( $nodeID );
        $changedOriginalNode->updateSubTreePath();
        $changedTargetNode = eZContentObjectTreeNode::fetch( $selectedNodeID );
        $changedTargetNode->updateSubTreePath();

        // modify section
        if ( $changedOriginalNode->isMain() )
        {
            $changedOriginalObject = $changedOriginalNode->object();
            $parentObject = $nodeParent->object();
            if ( $changedOriginalObject->attribute( 'section_id' ) != $parentObject->attribute( 'section_id' ) )
            {

                eZContentObjectTreeNode::assignSectionToSubTree( $changedOriginalNode->attribute( 'main_node_id' ),
                                                                $parentObject->attribute( 'section_id' ),
                                                                $changedOriginalObject->attribute( 'section_id' ) );
            }
        }
        if ( $changedTargetNode->isMain() )
        {
            $changedTargetObject = $changedTargetNode->object();
            $selectedParentObject = $selectedNodeParent->object();
            if ( $changedTargetObject->attribute( 'section_id' ) != $selectedParentObject->attribute( 'section_id' ) )
            {

                eZContentObjectTreeNode::assignSectionToSubTree( $changedTargetNode->attribute( 'main_node_id' ),
                                                                $selectedParentObject->attribute( 'section_id' ),
                                                                $changedTargetObject->attribute( 'section_id' ) );
            }
        }

        eZContentObject::fixReverseRelations( $objectID, 'swap' );
        eZContentObject::fixReverseRelations( $selectedObjectID, 'swap' );

        $db->commit();

        // clear cache for new placement.
        eZContentCacheManager::clearContentCacheIfNeeded( $objectID );

//        eZSearch::swapNode( $nodeID, $selectedNodeID, $nodeIdList = array() );

        return array( 'status' => true );
    }
}
?>