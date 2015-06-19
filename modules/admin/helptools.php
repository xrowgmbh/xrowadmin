<?php
$http = eZHTTPTool::instance();
$Module = $Params['Module'];
$tpl = eZTemplate::factory();

if ($http->hasVariable('findfilesearchbutton')) {
    $tpl->setVariable('formtype', 'findfile');
    if ($http->variable('filename') != "") {
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
            FROM ezbinaryfile ezbinaryfile
            LEFT JOIN
                ezcontentobject_attribute ezcontentobject_attribute ON ezcontentobject_attribute.id = ezbinaryfile.contentobject_attribute_id
            LEFT JOIN 
                ezcontentobject ezcontentobject ON ezcontentobject.id = ezcontentobject_attribute.contentobject_id
            WHERE ezbinaryfile.filename =\'' . $filename . '\'
            ORDER BY ezcontentobject_attribute.version DESC
            LIMIT 1;';
        $rows = $db->arrayQuery($query);
        if (isset($rows[0])) {
            $contentobject_id = $rows[0]['contentobject_id'];
            $tpl->setVariable('contentobject_id', $contentobject_id);
            $node = eZContentObjectTreeNode::fetchByContentObjectID($contentobject_id);
            $tpl->setVariable('objectname', $node[0]->getName());
            $tpl->setVariable('urlAlias', $node[0]->urlAlias());
            $nodeId = $node[0]->attribute('node_id');
            $tpl->setVariable('node_id', $nodeId);
            $tpl->setVariable('filename', $filename);
        } else {
            $tpl->setVariable('errormessage', ezpI18n::tr("admin/helptools", $filename));
        }
    } else {
        $tpl->setVariable('errormessage', ezpI18n::tr("admin/helptools", "No entry in the search box"));
    }
}

if ($http->hasVariable('findblockid')) {
    $tpl->setVariable('formtype', 'findblock');
    if ($http->variable('blockid') != "") {
        $blockid = $http->variable('blockid');
        $db = eZDB::instance();
        
        $query = 'SELECT * FROM
        (
            SELECT
            ezcontentobject.id, ezcontentobject.name, ezcontentobject_attribute.data_text
            FROM ezcontentobject ezcontentobject
            LEFT JOIN
            ezcontentobject_attribute ezcontentobject_attribute ON ezcontentobject_attribute.contentobject_id = ezcontentobject.id
            WHERE contentclass_id = \'23\'
            AND ezcontentobject_attribute.data_type_string = \'ezpage\'
            GROUP BY ezcontentobject_attribute.contentobject_id
            ORDER BY ezcontentobject_attribute.version DESC
        ) as subtable
        WHERE data_text LIKE (\'%' . $blockid . '%\')
        ;';
        $rows = $db->arrayQuery($query);
        if (isset($rows[0])) {
            $datatext = $rows[0]["data_text"];
            $xml = simplexml_load_string($datatext);
            
            $zone = $xml->xpath("/page/zone[block[@id='id_" . $blockid . "']]");
            $block = $xml->xpath("/page/zone/block[@id='id_" . $blockid . "']");
            $tpl->setVariable('zone_id', $block[0]->zone_id[0]);
            $tpl->setVariable('block_type', $block[0]->type[0]);
            if ($block[0]->name[0] != "") {
                $tpl->setVariable('block_name', $block[0]->name[0]);
            }
            $tpl->setVariable('zone_layout', $xml->zone_layout[0]);
            $tpl->setVariable('zone_identifier', $zone[0]->zone_identifier[0]);
            
            // alternative to the xpath method
            // foreach ($xml->zone as $zone)
            // {
            // foreach ($zone->block as $block)
            // {
            // if ($block->attributes() == 'id_' . $blockid)
            // {
            // $tpl->setVariable('zone_id', $block->zone_id[0] );
            // $tpl->setVariable('block_type', $block->type[0] );
            // if ($block->name[0]!= "")
            // {
            // $tpl->setVariable('block_name', $block->name[0] );
            // }
            // $tpl->setVariable('zone_layout', $xml->zone_layout[0] );
            // $tpl->setVariable('zone_identifier', $zone->zone_identifier[0] );
            // }
            // }
            // }
            
            $contentobject_id = $rows[0]['id'];
            $tpl->setVariable('contentobject_id', $contentobject_id);
            $node = eZContentObjectTreeNode::fetchByContentObjectID($contentobject_id);
            $tpl->setVariable('objectname', $node[0]->getName());
            $tpl->setVariable('urlAlias', $node[0]->urlAlias());
            $nodeId = $node[0]->attribute('node_id');
            $tpl->setVariable('node_id', $nodeId);
            $tpl->setVariable('block_id', $blockid);
        } else {
            $tpl->setVariable('errormessage', ezpI18n::tr("admin/helptools", $blockid));
        }
    } else {
        $tpl->setVariable('errormessage', ezpI18n::tr("admin/helptools", "No entry in the search box"));
    }
}

$timestamp = time();

// last 10 modified objects

$inputInformation["lastmodified"]["query"] = 'SELECT
										        id , modified , published
										        FROM ezcontentobject
										        WHERE modified < ' . $timestamp . '
										        AND status = 1
										        ORDER by modified DESC
										        LIMIT 10
										        ;';
$inputInformation["lastmodified"]["headline"] = "Last 10 modified objects";

// last 10 published objects

$inputInformation["lastpublished"]["query"] = 'SELECT
										        id , modified , published, current_version
										        FROM ezcontentobject
										        WHERE published < ' . $timestamp . '
										        AND status = 1
										        ORDER by published DESC
										        LIMIT 10
										        ;';
$inputInformation["lastpublished"]["headline"] = "Last 10 published objects";

// var_dump(getQueryInformation($inputInformation));
// test
$tpl->setVariable('outputInformation', getQueryInformation($inputInformation));

function getQueryInformation($inputInformation)
{
    foreach ($inputInformation as $value => $inputInformation) {
        $db = eZDB::instance();
        $rows = $db->arrayQuery($inputInformation['query']);
        foreach ($rows as $count => $row) {
            $contentobject_id = $row['id'];
            $object = eZContentObject::fetch($contentobject_id);
            if ($object instanceof eZContentObject) {
                $outputInformation[$value][$count]['id'] = $contentobject_id;
                if (isset($object->owner()->Name) && ! empty($object->owner()->Name)) {
                    $outputInformation[$value][$count]['publisher'] = $object->owner()->Name;
                } else {
                    $outputInformation[$value][$count]['publisher'] = "Not found publisher";
                }
                $ownerContentObjectID = $object->owner()->ID;
                $ownerNode = eZContentObjectTreeNode::fetchByContentObjectID($ownerContentObjectID);
                $publisherUrl = $ownerNode[0]->urlAlias();
                if (isset($publisherUrl) && ! empty($publisherUrl)) {
                    $outputInformation[$value][$count]['publisherUrl'] = $publisherUrl;
                } else {
                    $outputInformation[$value][$count]['publisherUrl'] = "Not found publisherUrl";
                }
                $node = eZContentObjectTreeNode::fetchByContentObjectID($contentobject_id);
                if ($node[0] instanceof eZContentObjectTreeNode) {
                    $creatorContentObjectID = $node[0]->creator()->ID;
                    $creatorNode = eZContentObjectTreeNode::fetchByContentObjectID($creatorContentObjectID);
                    $modifierUrlAlias = $creatorNode[0]->urlAlias();
                    if (isset($modifierUrlAlias) && ! empty($modifierUrlAlias)) {
                        $outputInformation[$value][$count]['modifierUrl'] = $modifierUrlAlias;
                    } else {
                        $outputInformation[$value][$count]['modifierUrl'] = "Not found modifierUrl";
                    }
                    if (isset($node[0]->creator()->Name) && ! empty($node[0]->creator()->Name)) {
                        $outputInformation[$value][$count]['modifier'] = $node[0]->creator()->Name;
                    } else {
                        $outputInformation[$value][$count]['modifier'] = "Not found modifier";
                    }
                    $getName = $node[0]->getName();
                    if (isset($getName) && ! empty($getName)) {
                        $outputInformation[$value][$count]['name'] = $getName;
                    } else {
                        $outputInformation[$value][$count]['name'] = "Not found name";
                    }
                    $urlAlias = $node[0]->urlAlias();
                    if (isset($urlAlias) && ! empty($urlAlias)) {
                        $outputInformation[$value][$count]['url'] = $urlAlias;
                    } else {
                        $outputInformation[$value][$count]['url'] = "Not found url";
                    }
                    $nodeId = $node[0]->attribute('node_id');
                    if (isset($nodeId) && ! empty($nodeId)) {
                        $outputInformation[$value][$count]['nodeId'] = $nodeId;
                    } else {
                        $outputInformation[$value][$count]['nodeId'] = "Not found nodeId";
                    }
                } else {
                    $outputInformation[$value][$count]['error'] = "No published node found";
                }
            } else {
                $outputInformation[$value][$count]['error'] = "No published object found";
            }
        }
    }
    $outputInformation[$value]['headline'] = $inputInformation['headline'];
    return $outputInformation;
}
$Result = array();
$Result['left_menu'] = "design:parts/xrowadmin/menu.tpl";
$Result['content'] = $tpl->fetch("design:xrowadmin/helptools.tpl");
$Result['path'] = array(
    array(
        'url' => false,
        'text' => 'helptools'
    )
);
?>