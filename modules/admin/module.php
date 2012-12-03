<?php
/**
 * File module.php
 *
 * @package ezadmin
 * @version //autogentag//
 * @copyright Copyright (C) 2007 xrow. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl.txt GPL License
 */
$Module = array( 'name' => 'Admin' );

$ViewList = array();
$ViewList['setowner'] = array(
    'functions' => array( 'setowner' ),
    'default_navigation_part' => 'ezadmin',
    'script' => 'setowner.php',
    'params' => array( 'ObjectID' ) );
$ViewList['changeuser'] = array(
    'functions' => array( 'changeuser' ),
    'default_navigation_part' => 'ezadmin',
    'script' => 'changeuser.php',
    'params' => array( 'ObjectID' ) );
$ViewList['changeuserview'] = array(
	'functions' => array( 'changeuserview' ),
    'default_navigation_part' => 'ezadmin',
    'script' => 'changeuserview.php',
    'params' => array( ) );
$ViewList['recalluser'] = array(
	'functions' => array( 'recalluser' ),
    'default_navigation_part' => 'ezadmin',
    'script' => 'recalluser.php',
    'params' => array( ) );
$ViewList['backup'] = array(
    'default_navigation_part' => 'backup',
    'functions' => array( 'backup' ),
    'script' => 'backup.php',
    'params' => array(  ) );
$ViewList['phpinfo'] = array(
    'functions' => array( 'phpinfo' ),
    'default_navigation_part' => 'ezadmin',
    'script' => 'phpinfo.php',
    'params' => array( 'phpinfo' ) );
$ViewList['menu'] = array(
	'script' => 'menu.php',
    'default_navigation_part' => 'ezadmin',
    'functions' => array( 'menu' ) );
$ViewList['sqlquery'] = array(
    'functions' => array( 'sqlquery' ),
    'default_navigation_part' => 'ezadmin',
    'script' => 'sqlquery.php',
    'params' => array( 'sql' ) );
$ViewList['phpmyadmin'] = array(
    'functions' => array( 'phpmyadmin' ),
    'default_navigation_part' => 'ezadmin',
    'script' => 'phpmyadmin.php',
    'params' => array( ) );
$ViewList['frame'] = array(
    'script' => 'frame.php',
    'default_navigation_part' => 'ezadmin',
    'ui_context' => 'edit',
    'ui_component' => 'content',
    'single_post_actions' => array( 'Exit' => 'Exit' ),
    'params' => array( 'modulename', 'view' ) );
$ViewList['apc'] = array(
    'default_navigation_part' => 'ezadmin',
    'functions' => array( 'apc' ),
    'script' => 'apc.php',
    'params' => array( ) );
$ViewList['eaccelerator'] = array(
    'default_navigation_part' => 'ezadmin',
    'functions' => array( 'eaccelerator' ),
    'script' => 'eaccelerator.php',
    'params' => array( ) );
$ViewList['maintance'] = array(
    'script' => 'maintance.php',
    'params' => array( 'date', 'time' ) );
$ViewList['client'] = array(
	'script' => 'client.php',
	'single_post_actions' => array( 'Cancel' => 'Cancel' ),
    'default_navigation_part' => 'ezadmin',
    'functions' => array( 'client' ) );
$ViewList['mailtest'] = array(
    'script' => 'mailtest.php',
    'default_navigation_part' => 'ezadmin',
    'functions' => array( 'mailtest' ),
    'single_post_actions' => array( 'Cancel' => 'Cancel' ) );
$ViewList['systemcheck'] = array(
    'script' => 'systemcheck.php',
    'ui_context' => 'systemcheck',
    'default_navigation_part' => 'ezadmin',
    'functions' => array( 'systemcheck' ),
    'single_post_actions' => array( 'Cancel' => 'Cancel' ),
    'post_action_parameters' => array( 'Cancel' => array(  ) ),
    'params' => array( ),
    'unordered_params' => array(  ) );
$ViewList['solrcheck'] = array(
    'script' => 'solrcheck.php',
	'single_post_actions' => array( 'Cancel' => 'Cancel' ),
    'default_navigation_part' => 'ezadmin',
    'functions' => array( 'solrcheck' ) );
$ViewList['migration'] = array(
    'script' => 'migration.php',
	'single_post_actions' => array( 'Cancel' => 'Cancel' ),
    'default_navigation_part' => 'ezadmin',
    'functions' => array( 'migration' ) );

$FunctionList['setowner'] = array( );
$FunctionList['changeuser'] = array( );
$FunctionList['changeuserview'] = array( );
$FunctionList['recalluser'] = array( );
$FunctionList['backup'] = array( );
$FunctionList['menu'] = array( );
$FunctionList['phpinfo'] = array( );
$FunctionList['migration'] = array( );
$FunctionList['sqlquery'] = array( );
$FunctionList['phpmyadmin'] = array( );
$FunctionList['apc'] = array( );
$FunctionList['accelerator'] = array( );
$FunctionList['maintance'] = array( );
$FunctionList['client'] = array( );
$FunctionList['mailtest'] = array( );
$FunctionList['systemtesting'] = array( );
$FunctionList['systemcheck'] = array( );
$FunctionList['solrcheck'] = array( );
