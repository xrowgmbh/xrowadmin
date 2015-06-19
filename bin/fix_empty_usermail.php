<?php

/*
    this script checks if every contentoject has a main node
    /usr/local/zend/bin/php -d memory_limit=2048M bin/php/ezexec.php extension/xrowadmin/bin/fix_empty_usermail.php
*/

$cli = eZCLI::instance();
$cli->output( "The script has started" );
$db = eZDB::instance();
$users_corrected = 0;
$users_not_corrected = array();
$default_mail = "mail@example.com";

$user_without_mail = $db->arrayQuery('SELECT * FROM ezuser where email = "" AND login != "";');

$cli->output( count($user_without_mail) . " users without mail address found!" );

foreach ( $user_without_mail as $entry )
{
    $old_data = $db->arrayQuery('SELECT * FROM ezuser_restore where contentobject_id = '. $entry["contentobject_id"] . ';');

    if(count($old_data) >= 1)
    {
        #$db->begin();
        #$db->arrayQuery("UPDATE ezuser SET email = '" . $old_data[0]["email"] ."' WHERE contentobject_id = " . $entry["contentobject_id"] . ";");
        #$db->commit();
        $users_corrected++;
    }
    else
    {
        #$db->begin();
        #$db->arrayQuery("UPDATE ezuser SET email = '" . $default_mail ."' WHERE contentobject_id = " . $entry["contentobject_id"] . ";");
        #$db->commit();
        $users_not_corrected[] =  $entry["contentobject_id"];
    }
}

$cli->output( $users_corrected . " user have been corrected." );

if ( count($users_not_corrected) >= 1 )
{
    $cli->output(  count($users_not_corrected) . " user were not corrected:" );
    foreach ( $users_not_corrected as $user_uncorrect )
    {
        $user = eZContentObject::fetch($user_uncorrect);
        $cli->output( "User created at " . date('m/d/Y', $user->Published) . ": " . $user->attribute("name") .  " ( ID: " . $user_uncorrect . " )" );
    }
}

$cli->output( "The script has finished" );

?>