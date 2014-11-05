<?php

class RemoteContent
{
    const DEFAULT_MARKER = "<!--CONTENT-->";
    static public function getMarker()
    {
        $remote_ini = eZINI::instance( 'remotecontent.ini' );
        if( $remote_ini->hasVariable( 'Settings', 'ContentMarker' ) )
        {
            return $remote_ini->variable( 'Settings', 'ContentMarker' );
        }
        else
        {
            return self::DEFAULT_MARKER;
        }
    }
}
