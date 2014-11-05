<?php
$Module =& $Params['Module'];

$tpl = eZTemplate::factory();
$Result = array();

$Result['content'] = $tpl->fetch( 'design:xrowadmin/test/html.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => 'Test HTML' ) );
