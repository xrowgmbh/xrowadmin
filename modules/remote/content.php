<?php

$http = eZHTTPTool::instance();
$tpl = eZTemplate::factory();
$Module = $Params['Module'];
$namedParameters = $Module->getNamedParameters();

if( isset( $namedParameters['Type'] ) )
{
    $remote_ini = eZINI::instance( 'remotecontent.ini' );
    if( $remote_ini->hasVariable( 'Settings', 'RemoteURL' ) )
    {
        $remote_url = $remote_ini->variable( 'Settings', 'RemoteURL' );
        if( isset( $remote_url['host'] ) && isset( $remote_url['path'] ) )
        {
            if( strpos( $remote_url['host'], 'http' ) === false )
            {
                $remote_url['host'] = 'http://' . $remote_url['host'];
            }
            $url = $remote_url['host'] . '/' . $remote_url['path'];
        }
        if( $remote_ini->hasVariable( 'Settings', 'ContentDevider' ) )
        {
            $content_devider = $remote_ini->variable( 'Settings', 'ContentDevider' );
        }
        
        if( isset( $url ) )
        {
            if ( function_exists( 'curl_init' ) )
            {
                $curl_is_set = true;
                $ch = curl_init();
                curl_setopt( $ch, CURLOPT_URL, $url );
                curl_setopt( $ch, CURLOPT_HEADER, 0 );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
                curl_setopt( $ch, CURLOPT_TIMEOUT, 40 );

                $remote_content = curl_exec( $ch );
                $info = curl_getinfo( $ch );
                if ( $info['http_code'] != 200 || $remote_content === false )
                {
                    $remote_content = false;
                    eZDebug::writeError( "URL ($url) is not avialable ", __METHOD__ );
                }
                curl_close( $ch );
                eZDebug::writeDebug( "URL ($url) included", __METHOD__ );
            }
            else
            {
                $remote_content = file_get_contents( $url );
                
                if( strlen( trim( $remote_content ) ) == 0 )
                {
                    $remote_content = false;
                    eZDebug::writeError( "URL ($url) doesn't returned content", __METHOD__ );
                }
                eZDebug::writeDebug( "URL ($url) included", __METHOD__ );
            }
            if( $remote_content )
            {
                $remote_content = preg_replace( '#(href|src)="([^:"]*)("|(?:(?:%20|\s|\+)[^"]*"))#', '$1="' . $remote_url['host'] . '$2$3', $remote_content );
                switch ( $namedParameters['Type'] )
                {
                    case 'head':
                        if( isset( $content_devider ) )
                        {
                            $pos = strpos( $remote_content, $content_devider );
                            if ($pos !== false)
                            {
                                $array_snippet_start = explode( $content_devider, $remote_content );
                                $remote_content = $array_snippet_start[0];
                            }
                            else
                            {
                                eZDebug::writeError( "Snippet start wasn't found in remote content. URL ($url), snippet start ".$content_devider, __METHOD__ );
                            }
                        }
                        else
                        {
                            eZDebug::writeError( "Snippet start wasn't found in remotecontent.ini ".$content_devider, __METHOD__ );
                        }
                        break;
                    case 'footer':
                        if( isset( $content_devider ) )
                        {
                            $pos = strpos( $remote_content, $content_devider );
                            if ($pos !== false)
                            {
                                $array_snippet_start = explode( $content_devider, $remote_content );
                                $remote_content = $array_snippet_start[1];
                            }
                            else
                            {
                                eZDebug::writeError( "Snippet start wasn't found in remote content. URL ($url), snippet start ".$content_devider, __METHOD__ );
                            }
                        }
                        else
                        {
                            eZDebug::writeError( "Snippet start wasn't found in remotecontent.ini ".$content_devider, __METHOD__ );
                        }
                        break;
                }
            }
            else
            {
                eZDebug::writeError( "The url $url does not return content.", __METHOD__ );
            }
        }
        else
        {
            eZDebug::writeError( "The url is not set.", __METHOD__ );
        }
    }
}
if( isset( $remote_content ) )
{
    $lastModified = gmdate( 'D, d M Y H:i(worry)', time() ) . ' GMT';
    $expires = gmdate( 'D, d M Y H:i(worry)', time() + 600 ) . ' GMT';
    $httpCharset = eZTextCodec::httpCharset();
    header( 'Cache-Control: max-age=600, public, must-revalidate' );
    header( 'Expires:' . $expires );
    header( 'Pragma:' );
    header( 'Last-Modified: ' . $lastModified );
    header( 'Content-Type: text/html; charset=' . $httpCharset );
    header( 'Content-Length: ' . strlen( $remote_content ) );

    while ( @ob_end_clean() );

    echo $remote_content;

    eZExecution::cleanExit();
}
else
{
    eZDebug::writeError( "An error has accured.", __METHOD__ );
}