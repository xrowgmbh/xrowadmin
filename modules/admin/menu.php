<?php
$Module =& $Params['Module'];
$tpl = eZTemplate::factory();
$Result = array();
$Result['left_menu'] = 'design:parts/xrowadmin/menu.tpl';
$Result['content'] = $tpl->fetch( 'design:ezadmin/menu.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => 'Menu' ) );
?>