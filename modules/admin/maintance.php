<?php
/**
 * File maintance.php
 *
 * @package ezadmin
 * @version //autogentag//
 * @copyright Copyright (C) 2007 xrow. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl.txt GPL License
 */
$Result = array();
$Module =& $Params['Module'];
include_once( 'kernel/common/template.php' );

$Module->setTitle( "Website down for maintance" );

$tpl = templateInit();

if ( $Params['date'] )
{
    if ( is_numeric( $Params['date'] ) )
        $tpl->setVariable( "time", $Params['date'] );
    else
    {
        if ( $Params['time'] )
            $time = $Params['date'] . ' ' . $Params['time'];
        else
            $time = $Params['date'];
        $tpl->setVariable( "time", strtotime( $time ) + date('%Z') );
    }
}
else
    $tpl->setVariable( "time", 0 );

$LayoutStyle="Maintance";

$layoutINI =& eZINI::instance( 'layout.ini' );

$Result['pagelayout'] = $layoutINI->variable( $LayoutStyle, 'PageLayout' );


if ( $layoutINI->hasGroup( $LayoutStyle ) )
{
    $Result['pagelayout'] = $layoutINI->variable( $LayoutStyle, 'PageLayout' );
}
$Result['left_menu'] = "design:parts/ezadmin/menu.tpl";
$Result['content'] = $tpl->fetch( "design:ezadmin/maintance.tpl" );
$Result['path'] = array( array( 'url' => false,
                        'text' => 'Website down for maintance' ) );
?>