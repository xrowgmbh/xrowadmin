<?php
/**
 * File module.php
 *
 * @package ezadmin
 * @version //autogentag//
 * @copyright Copyright (C) 2007 xrow. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl.txt GPL License
 */
$Module = array( 'name' => 'Test' );

$ViewList = array();
$ViewList['html'] = array(
    'default_navigation_part' => 'ezadmin',
    'script' => 'html.php',
    'params' => array( 'DefaultPageLayout' ) );