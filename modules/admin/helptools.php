<?php

$http = eZHTTPTool::instance();
$Module = $Params['Module'];
$tpl = eZTemplate::factory();

if ($http->hasVariable('findfilesearchbutton'))
{
    if ($http->variable('filename') != "") 
    {
        $filename = $http->variable('filename');
        
        $db = eZDB::instance();
        
        $query = 'SELECT 
            ezbinaryfile.filename,
            ezbinaryfile.original_filename,
            ezbinaryfile.contentobject_attribute_id ,
            ezcontentobject_attribute.id,
            ezcontentobject_attribute.version,
            ezcontentobject_attribute.contentobject_id,
            ezcontentobject.id,
            ezcontentobject.name
            FROM ezp_hann_live_db.ezbinaryfile ezbinaryfile
            LEFT JOIN
                ezp_hann_live_db.ezcontentobject_attribute ezcontentobject_attribute ON ezcontentobject_attribute.id = ezbinaryfile.contentobject_attribute_id
            LEFT JOIN 
                ezp_hann_live_db.ezcontentobject ezcontentobject ON ezcontentobject.id = ezcontentobject_attribute.contentobject_id
            WHERE ezbinaryfile.filename =\'' . $filename . '\'
            ORDER BY ezcontentobject_attribute.version DESC
            LIMIT 1;';
        $rows = $db -> arrayQuery( $query );
        if (isset($rows[0]))
        {
            $contentobject_id = $rows[0]['contentobject_id'];
            $tpl->setVariable('contentobject_id' , $contentobject_id );
            $node = eZContentObjectTreeNode::fetchByContentObjectID( $contentobject_id);
            $tpl->setVariable('objectname', $node[0]->getName());
            $tpl->setVariable('urlAlias', $node[0]->urlAlias());
            $nodeId = $node[0]->attribute( 'node_id' );
            $tpl->setVariable('node_id', $nodeId);
            $tpl->setVariable('filename', $filename);
        }
        else
        {
            $tpl->setVariable('errormessage', ezpI18n::tr("admin/helptools" , "file not found"));
        }
    }
    else
    {
        $tpl->setVariable('errormessage', ezpI18n::tr("admin/helptools" , "No entry in the search box"));
    }
}

$Result = array();
$Result['left_menu'] = "design:parts/xrowadmin/menu.tpl";
$Result['content'] = $tpl->fetch( "design:xrowadmin/helptools.tpl" );
$Result['path'] = array( array( 'url' => false, 'text' => 'helptools'));

?>