<?php

/**
 * 
 * @author kristina
 *
 * Class creates customize system tests, e.g. for varnish
 *
 * For the output there is a customize template in ezadmin/design/standard/templates/ezadmin/systemcheck/custom_{function_name}.tpl
 * {function_name} comes from the array in the function getHeadlines
 */
class checkUrlTest
{

    /**
     * Customize tests, which check the url with an authentication
     * 
     * @return array
     */
    private function setupTestTable()
    {
        return array( 
            'check_url' => array( 
                'checkUrl' 
            ) 
        );
    }

    /**
     * Return all called functions defined in the systemcheck.ini
     * 
     * @return array
     */
    public function runTests( $testList )
    {
        $testTable = $this->setupTestTable();
        
        $testResultArray = array();
        foreach ( $testList as $testItem )
        {
            $testName = $testItem;
            $testElement = array();
            if ( ! isset( $testTable[$testItem] ) )
            {
                eZDebug::writeError( "The setup test '$testName' is not defined" );
                continue;
            }
            $testInfo = $testTable[$testItem];
            $testFunction = $testInfo[0];
            
            if ( ! $this->$testFunction() )
            {
                continue;
            }
            $testResultArray[$testItem] = $this->$testFunction();
        }
        return $testResultArray;
    }

    /**
     * Customize tests headlines
     * 
     * @return array
     */
    public function getHeadlines()
    {
    	
        $ini = eZINI::instance( 'test.ini' );
        if ( $gujINI->hasVariable( 'UrlSettings', 'NotifyURLAdvanced' ) )
        {
            return array( 
                'check_url' => 'Details of the check' 
            );
        }
        else
        {
            eZDebug::writeError( 'You have to fill in a url in the test.ini which you would like to check.' );
        }
    }

    function checkUrl()
    {
        $gujINI = eZINI::instance( 'test.ini' );
        $url = $gujINI->variable( 'UrlSettings', 'NotifyURLAdvanced' );
        $authUser = $gujINI->variable( 'UrlSettings', 'NotifyUser' );
        $authPwd = $gujINI->variable( 'UrlSettings', 'NotifyPassword' );
        
        if ( $url != '' )
        {
            $planeUrl = $url;
            if ( $authUser != '' && $authPwd != '' )
            {
                $urlArray = parse_url( $planeUrl );
                $urlArray['host'] = $planeUrl;
                $urlArray['path'] = '/';
                if ( $authUser != '' )
                {
                    $urlArray['user'] = $authUser;
                }
                if ( $authPwd != '' )
                {
                    $urlArray['pass'] = $authPwd;
                }
                
                $url = http_build_url( $urlArray );
            }
            
            $options = array(
	            CURLOPT_RETURNTRANSFER => true,     // return web page
	            CURLOPT_HEADER         => false,    // don't return headers
	            //CURLOPT_FOLLOWLOCATION => true,     // follow redirects
	            CURLOPT_ENCODING       => "",       // handle all encodings
	            CURLOPT_USERAGENT      => "eZ Publish Notify Advanced", // who am i
	            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
	            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
	            CURLOPT_TIMEOUT        => 120,      // timeout on response
	            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
	            CURLOPT_SSL_VERIFYHOST => 0,            // don't verify ssl
	            CURLOPT_POST => true,
	        );
	        $ch = curl_init( $url );
	        curl_setopt_array( $ch, $options );
			
	        if ( $authUser != '' && $authPwd != '' )
	        {
	            curl_setopt( $ch, CURLOPT_USERPWD, $authUser . ":" . $authPwd );
	            curl_setopt( $ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	        }
	        $content = curl_exec( $ch );
	        $err     = curl_errno( $ch );
	        $errmsg  = curl_error( $ch );
	        $header  = curl_getinfo( $ch );
            $intReturnCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
            curl_close( $ch );
            
            if ( $intReturnCode == 200 || $intReturnCode == 302 )
            {
                return array( 
                    'result' => true , 
                    'true_text' => 'The url ' . $planeUrl . ' is reachable with HTTP STATUS CODE ' . $intReturnCode . '.' , 
                    'false_text' => '' 
                );
            }
            else
            {
	            if ( $intReturnCode == 403 )
	            {
	                return array( 
	                    'result' => false , 
	                    'true_text' => '' , 
	                    'false_text' => 'The url ' . $planeUrl . ' is unreachable with HTTP STATUS CODE ' . $intReturnCode . '. Your login is wrong.' 
	                );
	            }
                elseif ( $intReturnCode >= 400 && $intReturnCode < 500 )
                {
                    return array( 
                        'result' => false , 
                        'true_text' => '' , 
                        'false_text' => 'The url ' . $planeUrl . ' is unreachable. Client error with HTTP STATUS CODE ' . $intReturnCode . '.' 
                    );
                }
                elseif ( $intReturnCode <= 500 )
                {
                    return array( 
                        'result' => false , 
                        'true_text' => '' , 
                        'false_text' => 'The url ' . $planeUrl . ' is unreachable. Server error with HTTP STATUS CODE ' . $intReturnCode . '.' 
                    );
                }
                else 
                {
                	return array( 
                        'result' => false , 
                        'true_text' => '' , 
                        'false_text' => 'The url ' . $planeUrl . ' is unreachable.' 
                    );
                }
            }
        
        }
        else
        {
            return array( 
                'result' => false , 
                'true_text' => '' , 
                'false_text' => 'You have to fill in a url in the test.ini which you would like to check.' 
            );
        }
    }
}
?>