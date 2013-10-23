<?php

$Module =& $Params["Module"];
$http = eZHTTPTool::instance();

$ObjectID = &$Params['ObjectID'];
if ( !$ObjectID )
{
    eZDebug::writeError('Missing parameter Object ID.',"xrowadmin change User");
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}
$user = eZUser::fetch($ObjectID);
if ( !$user )
{
    eZDebug::writeError('No such user or missing right "content read"',"xrowadmin change User");
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}
$usercontentobject = $user->attribute( 'contentobject' );
if ( !$usercontentobject->attribute( 'can_edit' ) )
{
    eZDebug::writeError('You need the right "content edit" for this content object',"xrowadmin change User");
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}

eZUserAddition::loginDifferentUser($ObjectID);

$userRedirectURI = $Module->actionParameter( 'UserRedirectURI' );
$ini = eZINI::instance();
if ( trim( $userRedirectURI ) == "" )
{
    // Only use redirection if requireuser login is disabled
    $requireUserLogin = ( $ini->variable( "SiteAccessSettings", "RequireUserLogin" ) == "true" );
    if ( !$requireUserLogin )
    {
	if ( $http->hasSessionVariable( "LastAccessesURI" ) )
	    $userRedirectURI = $http->sessionVariable( "LastAccessesURI" );
    }

    if ( $http->hasSessionVariable( "RedirectAfterLogin" ) )
    {
	$userRedirectURI = $http->sessionVariable( "RedirectAfterLogin" );
    }
}
$redirectionURI = $userRedirectURI;
if ( $redirectionURI == '' )
        $redirectionURI = $ini->variable( 'SiteSettings', 'DefaultPage' );
$_SESSION['eZUserInfoCache_Timestamp'] = 0;
$_SESSION['eZUserGroupsCache_Timestamp'] = 0;
$_SESSION['eZRoleIDList_Timestamp'] = 0;
$_SESSION['eZRoleLimitationValueList_Timestamp'] = 0;
$_SESSION['AccessArrayTimestamp'] = 0;
$_SESSION['eZUserDiscountRulesTimestamp'] = 0;

if ( $http->hasGetVariable( 'RedirectionURI' ) )
{
    $Module->redirectTo( $http->getVariable('RedirectionURI') );
}
else if($redirectionURI)
{
    $Module->redirectTo( $redirectionURI );
}
else
{
    $Module->redirectTo( "/" );
}
