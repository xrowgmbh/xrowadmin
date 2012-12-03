<?php
/**
 * File containing the systemcheck
 *
 * @package ezadmin
 * @version //autogentag//
 * @copyright Copyright (C) 2009 xrow. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl.txt GPL License
 */

include_once ( 'autoload.php' );
include_once ( 'kernel/common/template.php' );
require_once( 'kernel/common/i18n.php' );

$Module = $Params['Module'];
$tpl = templateInit();
$http = eZHTTPTool::instance();
$db = eZDB::instance();

if ( $Module->isCurrentAction( 'Cancel' ) )
{
    $Module->redirectTo( 'admin/menu' );
}

if ( $http->hasPostVariable( 'Run' ) )
{
    $type = 'select';
    if( $http->hasPostVariable( 'data' ) )
    {
        $data = trim( $http->postVariable( 'data' ) );
        if( substr( $data, 0, 5 ) == 'array' )
        {
            $pos = strpos( $data, '{' );
            if( $pos !== false )
            {
                $data = substr( $data, $pos + 1 );
            }
            
            if( substr( $data, -1 ) == '}' )
            {
                $data = substr( $data, 0, -1 );
            }
            $data = trim( $data );
        }
        // if we get right data
        if( substr( $data, 0, 5 ) == '["q"]' )
        {
            $params = print_r_reverse( $data );
            $findINI = eZINI::instance( 'ezfind.ini' );
            $fullSolrURI = false;
            if ( $findINI->variable( 'LanguageSearch', 'MultiCore' ) == 'enabled' )
            {
               $solrINI = eZINI::instance( 'solr.ini' );
               $siteINI = eZINI::instance( 'site.ini' );
               $currentLanguage = $siteINI->variable( 'RegionalSettings', 'Locale');
               $languageMapping = $findINI->variable( 'LanguageSearch','LanguagesCoresMap');
               $shardMapping = $solrINI->variable ('SolrBase', 'Shards');
               $fullSolrURI = $shardMapping[$languageMapping[$currentLanguage]];
            }

            $solrBase = new eZSolrBase( $fullSolrURI );
            $wt = $params['wt'];
            if( $wt == '' )
            {
                $wt = 'php';
            }
            $result = $solrBase->rawSolrRequest( '/'.$type, $params, $wt );
            if( $wt == 'standard' )
            {
                $result_tmp = explode( "\n", $result[0] );
                $result = array();
                foreach( $result_tmp as $result_item )
                {
                    if( trim( $result_item ) != '' )
                    {
                        if( preg_match( "/<str>/", $result_item, $matches ) )
                        {
                            $result[] = str_replace( '&amp;lt;b&amp;gt;', '<b>', str_replace( '&amp;lt;/b&amp;gt;', '</b>', htmlspecialchars( $result_item ) ) );
                        }
                        else
                        {
                            $result[] = htmlspecialchars( $result_item );
                        }
                    }
                }
            }
            $tpl->setVariable( 'result', $result );
            $tpl->setVariable( 'wt', $wt );
            $tpl->setVariable( 'solr_uri', $solrBase->SearchServerURI );
        }
        else
        {
            $tpl->setVariable( 'error', 'Data has to start either with "array(10){" or with "["q"]=>".' );
        }
        $tpl->setVariable( 'data', $data );
    }
    else
    {
        $tpl->setVariable( 'error', 'Data is empty' );
    }
}

$Result = array();
$Result['left_menu'] = 'design:parts/ezadmin/menu.tpl';
$Result['content'] = $tpl->fetch( 'design:ezadmin/solrcheck.tpl' );
$Result['path'] = array( 
    array( 
        'url' => false , 
        'text' => ezpI18n::tr( 'extension/admin', 'SOLR Test' ) 
    ) 
);

function print_r_reverse( &$output )
{
    $lines = explode( "\n", $output );
    $topArray = null;
    $matches = null;
    $sub_matches = null;
    $index = '';
    while ( !empty( $lines ) )
    {
        $line = array_shift( $lines );

        $trim = trim($line);
        if( preg_match( "/\[\"(.*)\"]/", $trim, $matches ) )
        {
            $topArray[$matches[1]] = array();
        }
        else
        {
            if( !preg_match( "/\[\d\]\=\>/", $trim, $sub_matches ) )
            {
                $pos_string = strpos( $trim, 'string(' );
                if( $pos_string !== false )
                {
                    $explode_char = ' "';
                    $last_char = '"';
                }
                $pos_int = strpos( $trim, 'int(' );
                if( $pos_int !== false )
                {
                    $explode_char = '(';
                    $last_char = ')';
                }
                if( $explode_char != '' )
                {
                    $array_value = explode( $explode_char, $trim );
                    if( isset( $array_value[1] ) )
                    {
                        $last_char_found = substr( $array_value[1], -1 );
                        if( $last_char_found == $last_char )
                        {
                           $value = substr( $array_value[1], 0, -1 );
                        }
                        else
                        {
                           $value = $array_value[1];
                        }
                    }
                }

                if( isset( $value ) && $value != '' )
                {
                    if( $topArray !== null )
                    {
                        $keys = array_keys( $topArray );
                        $last_key = array_pop( $keys );
                        #echo $last_key.' :: <pre>'.$value.'</pre>';
                        if( $value != '' )
                        {
                            if( $index != ''  && $index >= 0 )
                            {
                                $topArray[$last_key][$index] = $value;
                                $index = '';
                                $value = '';
                            }
                            else
                            {
                                $topArray[$last_key] = $value;
                            }
                            $value = '';
                        }
                    }
                }
            }
            else
            {
                $index_array = preg_split('/[[^\]]/i', $sub_matches[0], -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
                $index = $index_array[0];
            }
        }
    }
    return $topArray;
}
?>