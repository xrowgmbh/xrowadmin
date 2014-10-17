<?php

class SSLJavascriptHelper
{
    public static function getJavaScript( )
    {
        $ini = eZINI::instance();
        $returnModules = array();
        if( trim( $ini->variable( 'SSLZoneSettings', 'SSLZones' ) ) != 'enabled' )
        {
            return 'var sslviewaccess = ' . json_encode( array() ) . ';
            ';
        }
        else
        {
            $moduleViewAccess = $ini->variable( 'SSLZoneSettings', 'ModuleViewAccessMode' );
            foreach ( $moduleViewAccess as $mods => $item )
            {
                if( trim( $item ) == 'ssl' )
                {
                    $replacers = '/' . str_replace( '*', '', $mods );
                    $returnModules[] = $replacers;
                    $replacers = '';
                }
            }
            $return = '$(document).ready(function(){'."\n";
            $return .= 'var sslviewaccess = ' . json_encode( $returnModules ) . ';'."\n";
            $return .= <<<EOD
    //replace all relative post-actions in form with ssl action
    if(typeof(sslviewaccess) !== 'undefined') {
        if(sslviewaccess.length > 0) {
            $.each($('form'), function(index ,element) {
                if($(element).attr('method') == "post") {
                    $.each(sslviewaccess , function(index, replace) {
                        if($(element).attr('action').indexOf(replace) >= 0 && $(element).attr('action').indexOf("/") == 0 ) {
                        	
                        	if( $(element).attr('action').indexOf("//") == 0 ) {
                        		var actionurl = $(element).attr('action');
                                actionurl = "https:" + $(element).attr('action');
                                $(element).attr('action', actionurl);
                        	}
                        	else
                        	{
                                var actionurl = $(element).attr('action');
                                actionurl = "https://" + window.location.hostname + $(element).attr('action');
                                $(element).attr('action', actionurl);
                        	}
                        }
                    });
                }
            });
        }
    }
EOD;
            $return .= '});'."\n";
            return $return;
        }
    }
}