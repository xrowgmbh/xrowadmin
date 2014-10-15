<?php

header( 'X-Robots-Tag: noindex, nofollow' );

$http = eZHTTPTool::instance();

$tpl = eZTemplate::factory();

$ViewMode = $Params['ViewMode'];
$NodeID = $Params['NodeID'];
$Module = $Params['Module'];
$LanguageCode = $Params['Language'];
$Offset = $Params['Offset'];
$Year = $Params['Year'];
$Month = $Params['Month'];
$Day = $Params['Day'];

// Check if we should switch access mode (http/https) for this node.
if( !isset( $Params['InternalRedirect'] ) )
    eZSSLZone::checkNodeID( 'content', 'view', $NodeID );

if ( isset( $Params['UserParameters'] ) )
{
    $UserParameters = $Params['UserParameters'];
}
else
{
    $UserParameters = array();
}

if ( $Offset )
    $Offset = (int) $Offset;
if ( $Year )
    $Year = (int) $Year;
if ( $Month )
    $Month = (int) $Month;
if ( $Day )
    $Day = (int) $Day;

$NodeID = (int) $NodeID;

if ( $NodeID < 2 )
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

$ini = eZINI::instance();

// Be able to filter node id for general use
$NodeID = ezpEvent::getInstance()->filter( 'content/view', $NodeID, $ini );

$testingHandler = new ezpMultivariateTest( ezpMultivariateTest::getHandler() );

if ( $testingHandler->isEnabled() )
    $NodeID = $testingHandler->execute( $NodeID );

$viewCacheEnabled = ( $ini->variable( 'ContentSettings', 'ViewCaching' ) == 'enabled' );

if ( isset( $Params['ViewCache'] ) )
{
    $viewCacheEnabled = $Params['ViewCache'];
}
elseif ( $viewCacheEnabled && !in_array( $ViewMode, $ini->variableArray( 'ContentSettings', 'CachedViewModes' ) ) )
{
    $viewCacheEnabled = false;
}

if ( $viewCacheEnabled && $ini->hasVariable( 'ContentSettings', 'ViewCacheTweaks' ) )
{
    $viewCacheTweaks = $ini->variable( 'ContentSettings', 'ViewCacheTweaks' );
    if ( isset( $viewCacheTweaks[$NodeID] ) && strpos( $viewCacheTweaks[$NodeID], 'disabled' ) !== false )
    {
        $viewCacheEnabled = false;
    }
}

$collectionAttributes = false;
if ( isset( $Params['CollectionAttributes'] ) )
    $collectionAttributes = $Params['CollectionAttributes'];

$validation = array( 'processed' => false,
    'attributes' => array() );
if ( isset( $Params['AttributeValidation'] ) )
    $validation = $Params['AttributeValidation'];

$res = eZTemplateDesignResource::instance();
$keys = $res->keys();
if ( isset( $keys['layout'] ) )
    $layout = $keys['layout'];
else
    $layout = false;

$viewParameters = array( 'offset' => $Offset,
    'year' => $Year,
    'month' => $Month,
    'day' => $Day,
    'namefilter' => false );
$viewParameters = array_merge( $viewParameters, $UserParameters );

$user = eZUser::currentUser();

eZDebugSetting::addTimingPoint( 'kernel-content-view', 'Operation start' );


$operationResult = array();

if ( eZOperationHandler::operationIsAvailable( 'content_read' ) )
{
    $operationResult = eZOperationHandler::execute( 'content', 'read', array( 'node_id' => $NodeID,
        'user_id' => $user->id(),
        'language_code' => $LanguageCode ), null, true );
}

if ( ( isset( $operationResult['status'] ) && $operationResult['status'] != eZModuleOperationInfo::STATUS_CONTINUE ) )
{
    switch( $operationResult['status'] )
    {
        case eZModuleOperationInfo::STATUS_HALTED:
        case eZModuleOperationInfo::STATUS_REPEAT:
            {
                if ( isset( $operationResult['redirect_url'] ) )
                {
                    $Module->redirectTo( $operationResult['redirect_url'] );
                    return;
                }
                else if ( isset( $operationResult['result'] ) )
                {
                    $result = $operationResult['result'];
                    $resultContent = false;
                    if ( is_array( $result ) )
                    {
                        if ( isset( $result['content'] ) )
                        {
                            $resultContent = $result['content'];
                        }
                        if ( isset( $result['path'] ) )
                        {
                            $Result['path'] = $result['path'];
                        }
                    }
                    else
                    {
                        $resultContent = $result;
                    }
                    $Result['content'] = $resultContent;
                }
            } break;
        case eZModuleOperationInfo::STATUS_CANCELLED:
            {
                $Result = array();
                $Result['content'] = "Content view cancelled<br/>";
            } break;
    }
    return $Result;
}
else
{
    //fix potencial security issue for anonymous user, here it returns KERNEL_NOT_AVAILABLE for anonymous instead of KERNEL_ACCESS_DENIED
    if( eZUser::currentUser()->isAnonymous() )
    {
        $node = eZContentObjectTreeNode::fetch( $NodeID );
        if( !empty( $node ) )
        {
            if( !$node->canRead() )
            {
                return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
            }

            if( $node->attribute( 'is_invisible' ) && !eZContentObjectTreeNode::showInvisibleNodes() )
            {
                return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
            }
        }
    }
    $args = compact(
        array(
            "NodeID", "Module", "tpl", "LanguageCode", "ViewMode", "Offset", "ini", "viewParameters", "collectionAttributes", "validation"
        )
    );
    $data = eZNodeviewfunctions::contentViewGenerate( false, $args ); // the false parameter will disable generation of the 'binarydata' entry
    $remote_ini = eZINI::instance( 'remotecontent.ini' );
    if( $remote_ini->hasVariable( 'Settings', 'ContentMarker' ) )
    {
        $content_devider = $remote_ini->variable( 'Settings', 'ContentMarker' );
        $data['content']['content'] = $content_devider;
    }
    else
    {
        $data['content']['content'] = "<!--CONTENT-->";
    }
    return $data['content']; // Return the $Result array
}