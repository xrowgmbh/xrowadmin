<?php

$Module = $Params['Module'];
$namedParameters = $Module->NamedParameters;
$remote_ini = eZINI::instance( 'remotecontent.ini' );
if( $remote_ini->hasVariable( 'Settings', 'ContentDevider' ) )
{
    $content_devider = $remote_ini->variable( 'Settings', 'ContentDevider' );
    $Result['content'] = $content_devider;
}
$Result['pagelayout'] = 'design:pagelayout.tpl';