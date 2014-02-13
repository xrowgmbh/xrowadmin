#!/usr/bin/env php
<?php

$cli = eZCLI::instance();
$script = eZScript::instance( array( 
    'description' => ( "\n" . "This script will analyse the DB for missing images.\n" ) , 
    'use-session' => false , 
    'use-modules' => true , 
    'use-extensions' => true 
) );
$script->startup();
$script->initialize();

$ini = eZINI::instance();
$limit_per_fetch = 1000;
$invalid_entries = 0;
$missing_completely = 0;
$badly_expired = 0;
$repaired_objects = array();
$offset = 0;
#$userCreatorID = 14;
#$user = eZUser::fetch( $userCreatorID );
#eZUser::setCurrentlyLoggedInUser( $user, $userCreatorID );

$fileINI = eZINI::instance( 'file.ini' );

$dbParameters = array( 'server' => $fileINI->variable( 'eZDFSClusteringSettings', 'DBHost' ),
                               'port' => $fileINI->variable( 'eZDFSClusteringSettings', 'DBPort' ),
                               'user' => $fileINI->variable( 'eZDFSClusteringSettings', 'DBUser' ),
                               'password' => $fileINI->variable( 'eZDFSClusteringSettings', 'DBPassword' ),
                               'socket' => $fileINI->variable( 'eZDFSClusteringSettings', 'DBSocket' ),
                               'database' => $fileINI->variable( 'eZDFSClusteringSettings', 'DBName' ),
                               'charset' => 'iso-8859-1' );


$db = eZDB::instance();
$cluster_db = eZDB::instance(false, $dbParameters, true);

#check if the the file.ini db is used, if not, use normal db
if ( !is_null($cluster_db->ErrorMessage ) )
{
    $cluster_db = eZDB::instance();
}

$count = 0;

$cli->output( "Start Fetching Image Attributes" );

do {
    $query = "SELECT ezcontentobject_attribute.data_text, ezcontentobject.id, ezcontentobject_attribute.language_code, ezcontentobject_attribute.id as 'cobj_attr' FROM ezcontentobject_attribute, ezcontentobject
        WHERE ezcontentobject_attribute.contentobject_id = ezcontentobject.id
        AND ezcontentobject_attribute.data_type_string = 'ezimage'
        AND ezcontentobject_attribute.version = ezcontentobject.current_version
        LIMIT $limit_per_fetch
        OFFSET $offset;";

    $resultSet = $db->arrayQuery( $query );
    $count = count($resultSet);
    $offset = $offset+$count;
    $cli->output( "############# Checking the next " . $limit_per_fetch . " Elements(" . $offset . ") ##############" );
    foreach ( $resultSet as $result )
    {

        $dom = new DOMDocument;
        $dom->loadXML($result["data_text"]);
        $images = $dom->getElementsByTagName('ezimage');
        foreach ($images as $image)
        {
            //image path can be empty when image is not translatable or when its not required i guess
            $image_path = $image->getAttribute('url');
            if ($image_path != "" && strpos($image_path, '/trashed/') === false )
            {
            
                $dfs_entry = $cluster_db->arrayQuery( 'SELECT * FROM ezdfsfile where name_trunk = "' . str_replace('"', '""', $image_path) . '"; ');
                
                if ( count( $dfs_entry ) === 0)
                {
                    $cli->output( "(Broken)Problem in Object " . $result["id"] . "(lang:" . $result["language_code"] . ") on image:" . $image_path );
                    $missing_completely++;
                }
                else if ( $dfs_entry[0]["expired"] == 1 AND $dfs_entry[0]["mtime"] <= 0 )
                {
                    $cli->output( "(Repaired)Problem Object " . $result["id"] . "(lang:" . $result["language_code"] . ") on image:" . $image_path . "in attribute: " . $result["cobj_attr"] );
                    $repaired_objects[] = array("obj_id" => $result["id"], "lang" => $result["language_code"], "path" => $image_path, "attr_id" => $result["cobj_attr"] );
                    $cluster_db->begin();
                    $cluster_db->arrayQuery( 'UPDATE ezdfsfile SET mtime = ' . substr($dfs_entry[0]["mtime"], 1) . ' WHERE name_trunk = "' . $image_path . '"; ');
                    $cluster_db->commit();
                    $badly_expired++;
                }
            }
        }
    }

} while ($count == $limit_per_fetch);

if ( count($repaired_objects) >= 1)
{
    $tpl = eZTemplate::factory();
    $tpl->setVariable( 'repaired_objects', $repaired_objects );
    $templateResult = $tpl->fetch( 'design:mail/image_missing.tpl' );
    $emailSender = $ini->variable( 'MailSettings', 'EmailSender' );
    if ( !$emailSender )
    {
        $emailSender = $ini->variable( 'MailSettings', 'AdminEmail' );
    }
    
    $xrowAdminINI = eZINI::instance( 'xrowadmin.ini' );
    $cli->output( "Sending notification mails." );
    
    foreach ( $xrowAdminINI->variable( 'CheckMissingImages', 'NotificationReceiver' ) as $receiver )
    {
        $mail = new eZMail();
        $mail->setSender( $emailSender );
        $mail->setReceiver( $receiver );
        $mail->setSubject( "Notification: Images were missing and have been repaired." );
        $mail->setBody( $templateResult );
        $mailResult = eZMailTransport::send( $mail );
    }
}

$cli->output( "Done." );

$cli->output( "=========================================================" );
$cli->output( "Total Result" );
$cli->output( "Missing Images: " . $missing_completely );
$cli->output( "Expired Images: " . $badly_expired );

$script->shutdown();

?>