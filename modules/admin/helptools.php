<?php

$http = eZHTTPTool::instance();
$Module = $Params['Module'];
$tpl = eZTemplate::factory();

if ($http->hasVariable('findfilesearchbutton'))
{
    $tpl->setVariable('formtype' , 'findfile' );
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

if ($http->hasVariable('findblockid'))
{
    $tpl->setVariable('formtype' , 'findblock' );
    if ($http->variable('blockid') != "")
    {
        $blockid = $http->variable('blockid');
        $db = eZDB::instance();
        
        $query = 'SELECT * FROM
        (
            SELECT
            ezcontentobject.id, ezcontentobject.name, ezcontentobject_attribute.data_text
            FROM ezp_hann_live_db.ezcontentobject ezcontentobject
            LEFT JOIN
            ezp_hann_live_db.ezcontentobject_attribute ezcontentobject_attribute ON ezcontentobject_attribute.contentobject_id = ezcontentobject.id
            WHERE contentclass_id = \'23\'
            AND ezcontentobject_attribute.data_type_string = \'ezpage\'
            GROUP BY ezcontentobject_attribute.contentobject_id
            ORDER BY ezcontentobject_attribute.version DESC
        ) as subtable
        WHERE data_text LIKE (\'%' . $blockid . '%\')
        ;';
        $rows = $db -> arrayQuery( $query );
        if (isset($rows[0]))
        {   
            $datatext = $rows[0]["data_text"];
            $xml = simplexml_load_string( $datatext );
            
            $zone = $xml->xpath("/page/zone[block[@id='id_".$blockid."']]");
            $block = $xml->xpath("/page/zone/block[@id='id_".$blockid."']");

            $tpl->setVariable('zone_id', $block[0]->zone_id[0] );
            $tpl->setVariable('block_type', $block[0]->type[0] );
            if ($block[0]->name[0]!= "")
            {
                $tpl->setVariable('block_name', $block[0]->name[0] );
            }
            $tpl->setVariable('zone_layout', $xml->zone_layout[0] );
            $tpl->setVariable('zone_identifier', $zone[0]->zone_identifier[0] );
// alternative to the xpath method
//             foreach ($xml->zone as $zone)
//             {
//                 foreach ($zone->block as $block)
//                 {
//                     if ($block->attributes() == 'id_' . $blockid)
//                     {
//                         $tpl->setVariable('zone_id', $block->zone_id[0] );
//                         $tpl->setVariable('block_type', $block->type[0] );
//                         if ($block->name[0]!= "")
//                         {
//                             $tpl->setVariable('block_name', $block->name[0] );
//                         }
//                         $tpl->setVariable('zone_layout', $xml->zone_layout[0] );
//                         $tpl->setVariable('zone_identifier', $zone->zone_identifier[0] );
//                     }
//                 }
//             }

            $contentobject_id = $rows[0]['id'];
            $tpl->setVariable('contentobject_id' , $contentobject_id );
            $node = eZContentObjectTreeNode::fetchByContentObjectID( $contentobject_id);
            $tpl->setVariable('objectname', $node[0]->getName());
            $tpl->setVariable('urlAlias', $node[0]->urlAlias());
            $nodeId = $node[0]->attribute( 'node_id' );
            $tpl->setVariable('node_id', $nodeId);
            $tpl->setVariable('block_id', $blockid);
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