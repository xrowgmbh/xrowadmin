<?php
$Module = & $Params['Module'];
$http = eZHTTPTool::instance();

if ( $http->hasPostVariable( 'Skip' ) )
{
    $Module->redirectToView( 'menu' );
}

$tpl = eZTemplate::factory();

if ( $http->hasPostVariable( 'Execute' ) )
{
    $source = (int) $http->postVariable( 'source_id' );
    $target = (int) $http->postVariable( 'target_id' );
    
    if ( empty( $source ) || empty( $target ) || ! eZContentObjectTreeNode::fetch( $target ) instanceof eZContentObjectTreeNode || ! eZContentObjectTreeNode::fetch( $source ) instanceof eZContentObjectTreeNode )
    {
        $error = true;
        $operation = ezpI18n::tr( "admin/migration", "Please provide valid NodeID's for the choosen operation!" );
    }
    else
    
    {
        $db = eZDB::instance();
        
        switch ( $http->postVariable( 'operation' ) )
        {
            case '1':
                if ( eZContentObjectTreeNodeOperations::move( $source, $target ) !== true )
                {
                    $error = true;
                }
                else
                {
                    $success = true;
                }
                $operation = ezpI18n::tr( 'admin/migration', 'Moving Node %1 to %2', null, array( 
                    $source , 
                    $target 
                ) );
                break;
            case '2':
                $params = array( 
                    'AsObject' => true , 
                    'Depth' => 1 
                );
                
                $nodes = eZContentObjectTreeNode::subTreeByNodeID( $params, $source );

                if ( ! $nodes )
                {
                    $error = true;
                }
                else
                {
                    foreach ( $nodes as $key => $node )
                    {
                        $db->begin();
                        
                        if ( eZContentObjectTreeNodeOperations::move( $node->attribute('node_id'), $target ) !== true )
                        {
                            $error = true;
                        }
                        else
                        {
                            $success = true;
                        }
                        $db->commit();
                        eZContentObject::clearCache();
                    }
                    $operation = ezpI18n::tr( 'admin/migration', 'Moving Children from Node %1 to %2', null, array( 
                        $source , 
                        $target 
                    ) );
                }
                break;
            case '3':
                $db->begin();
                $return = ezadminSwapNode::swapNode( $source, $target, array( 
                    $source , 
                    $target 
                ) );
                $db->commit();
                ( ! isset( $return['status'] ) ) ? $error = true : $success = true;
                $operation = ezpI18n::tr( 'admin/migration', 'Swapping Node %1 with %2', null, array( 
                    $source , 
                    $target 
                ) );
                break;
        }
    }
}
$tpl->setVariable( 'operation', $operation );

( $error === true ) ? $tpl->setVariable( 'error', true ) : null;
( $success === true ) ? $tpl->setVariable( 'success', true ) : null;

$Result = array();
$Result['left_menu'] = "design:parts/ezadmin/menu.tpl";
$Result['content'] = $tpl->fetch( "design:ezadmin/migration.tpl" );
$Result['path'] = array( 
    array( 
        'url' => false , 
        'text' => 'Migration Manager' 
    ) 
);
