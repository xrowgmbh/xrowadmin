<?php

$Module = $Params['Module'];
$namedParameters = $Module->NamedParameters;
$remote_ini = eZINI::instance( 'remotecontent.ini' );
if( $remote_ini->hasVariable( 'Settings', 'ContentMarker' ) )
{
    $content_devider = $remote_ini->variable( 'Settings', 'ContentMarker' );
    $Result['content'] = $content_devider;
}
else
{
    $Result['content'] = "<!--CONTENT-->";
}
$Result['pagelayout'] = 'design:pagelayout.tpl';