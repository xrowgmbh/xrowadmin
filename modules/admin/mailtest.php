<?php

$Module = $Params['Module'];
$tpl = eZTemplate::factory();
$ini = eZINI::instance();
$http = eZHTTPTool::instance();

$output = '';
if ( $Module->isCurrentAction( 'Cancel' ) )
{
    $Module->redirectTo( 'admin/menu' );
}

if ( $http->hasPostVariable( 'Run' ) )
{
    $time =  new eZDateTime();
    $subject = "Test mail " . $time->toString();

    if( $http->hasPostVariable( 'transporttype' ) && $http->postVariable( 'transporttype' ) == 'eZMail' )
    {
        $mail = new eZMail();

        // Sender might not be given by default settings
        if ( $ini->variable( 'MailSettings', 'EmailSender' ) )
            $mail->setSender( $ini->variable( 'MailSettings', 'EmailSender' ) );
        else
            $mail->setSender( $ini->variable( 'MailSettings', 'AdminEmail' ) );

        $mail->setReceiver( $http->postVariable( 'To' ) );
        $mail->setBody( $subject."\n\nNew line test. Sending with eZMail.\nMessage End." );
        $mail->setSubject( $subject );

        $response = eZMailTransport::send( $mail );
    }
    else
    {
        $mail = new ezcMail();
        if ( $ini->variable( 'MailSettings', 'EmailSender' ) )
            $mail->from = new ezcMailAddress( $ini->variable( 'MailSettings', 'EmailSender' ), $ini->variable( 'SiteSettings', 'SiteName' ) );
        else
            $mail->from = new ezcMailAddress( $ini->variable( 'MailSettings', 'AdminEmail' ), $ini->variable( 'SiteSettings', 'SiteName' ) );
        $mail->addTo( new ezcMailAddress( $http->postVariable( 'To' ) ) );
        $mail->subject = $subject;
        $mail->body = new ezcMailText( $subject."\n\nNew line test. Sending with ezcMail.\nMessage End." );
        $transport = new ezcMailMtaTransport();
        $response = $transport->send( $mail );
    }
   // check if the server returned a fault, if not print out the result
   if ($response === true || $response === null )
   {
       $output = "Success when sending on " . $time->toString();
   }
   else
   {
       $output = "Not success when sending on " . $time->toString() .". Please see debug output.";
   }
}

$tpl->setVariable( 'Output' , $output );

if ( $http->hasPostVariable( 'To' ) )
    $tpl->setVariable( 'To' , $http->postVariable( 'To' ) );
else
    $tpl->setVariable( 'To' , $ini->variable( 'MailSettings', 'AdminEmail' ) );

$Result = array();
$Result['left_menu'] = "design:parts/ezadmin/menu.tpl";
$Result['content'] = $tpl->fetch( "design:ezadmin/mailtest.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'extension/admin', 'Mail Test' ) ) );
