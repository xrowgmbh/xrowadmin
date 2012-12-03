<?php
$Module =& $Params['Module'];
include_once( 'kernel/common/template.php' );
$tpl = templateInit();
$Result = array();
$Result['left_menu'] = 'design:parts/ezadmin/menu.tpl';
$Result['content'] = $tpl->fetch( 'design:ezadmin/menu.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => 'Menu' ) );
?>