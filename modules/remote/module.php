<?php
$Module = array( 'name' => 'Admin Remote' );

$ViewList = array();
$ViewList['content'] = array(
    'script' => 'content.php',
    'params' => array( 'Type', 'NodeID' ) );
$ViewList['loadpage'] = array(
    'script' => 'loadpage.php',
    'params' => array() );
$ViewList['scaffold'] = array(
    'script' => 'scaffold.php',
    'params' => array( 'ViewMode', 'NodeID' ),
    'unordered_params' => array( 'language' => 'Language',
                                 'offset' => 'Offset',
                                 'year' => 'Year',
                                 'month' => 'Month',
                                 'day' => 'Day' ));