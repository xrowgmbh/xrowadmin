<?php

$Module = $Params['Module'];
$sys  = eZSys::instance();
$tpl = eZTemplate::factory();
$ini = eZINI::instance();

$http = eZHTTPTool::instance();

$output = "";
if ( $Module->isCurrentAction( 'Cancel' ) )
{
    $Module->redirectTo( 'admin/menu' );
}

if ( $http->hasPostVariable( 'Run' ) )
{
    $parameters = array( 'login' => $http->postVariable( 'Username' ),
                         'password' => $http->postVariable( 'Password' ),
                         'server' => $http->postVariable( 'Server' ),
                         'port' => $http->postVariable( 'Port' ),
                         'function' => $http->postVariable( 'Function' )
                        );

    // include client classes
    include_once( "lib/ezsoap/classes/ezsoapclient.php" );
    include_once( "lib/ezsoap/classes/ezsoaprequest.php" );
    $url = parse_url ( $parameters["server"] );
    if ( !array_key_exists( 'port' ,$parameters) or !is_numeric( $parameters['port'] ) )
        $parameters['port'] = 80;

    // create a new client
    $client = new eZSOAPClient( $url['host'], $url['path'], $parameters['port'] );

    if ( array_key_exists( 'login' ,$parameters ) )
        $client->setLogin( $parameters['login'] );
    if ( array_key_exists( 'password' ,$parameters ) )
        $client->setPassword( $parameters['password'] ); 

    // create the SOAP request object
    if ( array_key_exists( 'function' ,$parameters ) and array_key_exists( 'server' ,$parameters ) )
    $request = new eZSOAPRequest( $parameters["function"], $parameters["server"] );

    // add parameters to the request
    #$request->addParameter( "valueA", 42 );
    #$request->addParameter( "valueB", 17 );

    // send the request to the server and fetch the response
    $response = $client->send( $request );
    if( is_object( $response ) )
    {
       // check if the server returned a fault, if not print out the result
       if ( $response->isFault() )
       {
           $output = "SOAP fault: " . $response->faultCode(). " - " . $response->faultString()."\n";
       }
       else
       {
          $output = print_r( $response->value(), true);
       }
    }
    else
    {
        $output =  "Error: Request returned no response\n";
    }
}

$tpl->setVariable( 'Output' , $output );

if ( $http->hasPostVariable( 'Function' ) )
    $tpl->setVariable( 'Function' , $http->postVariable( 'Function' ) );
else
    $tpl->setVariable( 'RemoteID' , '' );

if (! $http->hasPostVariable( 'Username' ) )
    $tpl->setVariable( 'Username' , '' );
else
    $tpl->setVariable( 'Username' , $http->postVariable( 'Username' ) );

if (! $http->hasPostVariable( 'Password' ) )
    $tpl->setVariable( 'Password' , '' );
else
    $tpl->setVariable( 'Password' , $http->postVariable( 'Password' ) );

if (! $http->hasPostVariable( 'Server' ) )
    $tpl->setVariable( 'Server' , 'http://soap.exmaple.com' );
else
    $tpl->setVariable( 'Server' , $http->postVariable( 'Server' ) );
if (! $http->hasPostVariable( 'Port' ) )
    $tpl->setVariable( 'Port' , 80 );
else
    $tpl->setVariable( 'Port' , $http->postVariable( 'Port' ) );

$Result = array();
$Result['left_menu'] = 'design:parts/xrowadmin/menu.tpl';
$Result['content'] = $tpl->fetch( 'design:xrowadmin/client.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'extension/admin', 'SOAP test webclient' ) ) );

?>
