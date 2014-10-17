<?php

$Module = $Params['Module'];
$namedParameters = $Module->NamedParameters;

$Result['content'] =  RemoteContent::getMarker();

$Result['pagelayout'] = 'design:pagelayout.tpl';