<?php
$Module = array( 'name' => 'Admin Remote' );

$ViewList = array();
$ViewList['content'] = array(
    'script' => 'content.php',
    'params' => array( 'Type' ) );
$ViewList['loadpage'] = array(
    'script' => 'loadpage.php',
    'params' => array() );