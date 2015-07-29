<?php
$http = eZHTTPTool::instance();
$Module = $Params['Module'];
$tpl = eZTemplate::factory();
$db = eZDB::instance();

if ($http->hasVariable('findFileSearchButton')) {
    $tpl->setVariable('formType', 'findFile');
    if ($http->variable('fileName') != "") {
        $fileName = $http->variable('fileName');
        
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
                    WHERE ezbinaryfile.filename =\'' . $fileName . '\'
                    ORDER BY ezcontentobject_attribute.version DESC
                    LIMIT 1;';
        $rows = $db->arrayQuery($query);
        if (isset($rows[0])) {
            $contentObjectID = $rows[0]['contentobject_id'];
            if (isset($contentObjectID) && ! empty($contentObjectID)) {
                $tpl->setVariable('contentObjectID', $contentObjectID);
            }
            $getStatus = eZContentObject::fetch($contentObjectID);
            $status = $getStatus->Status;
            if ($status == '1') {
                $node = eZContentObjectTreeNode::fetchByContentObjectID($contentObjectID);
                if ($node[0] instanceof eZContentObjectTreeNode) {
                    if (isset($node) && ! empty($node)) {
                        $tpl->setVariable('objectName', $node[0]->getName());
                        if ($node[0]->urlAlias() != "") {
                            $tpl->setVariable('urlAlias', $node[0]->urlAlias());
                        }
                    }
                    $nodeID = $node[0]->attribute('node_id');
                    if (isset($nodeID) && ! empty($nodeID)) {
                        $tpl->setVariable('nodeID', $nodeID);
                    }
                }
            } else {
                $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", "The file was found, but the details can not be displayed. Maybe is the file in the trash box."));
            }
            $tpl->setVariable('fileName', $fileName);
        } else {
            $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", 'This filename %filename was not found', '', array(
                '%filename' => $filename
            )));
        }
    } else {
        $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", "Please fill in the textbox"));
    }
}

if ($http->hasVariable('findAttributeID')) {
    $tpl->setVariable('formType', 'findAttribute');
    if ($http->variable('attributeID') != "") {
        $attributeID = $http->variable('attributeID');
        if (is_numeric($attributeID)) {
            $query = 'Select contentobject_id
                        FROM ezcontentobject ezcontentobject
                        LEFT JOIN
                        ezcontentobject_attribute ezcontentobject_attribute ON ezcontentobject_attribute.contentobject_id = ezcontentobject.id
                        Where ezcontentobject_attribute.id =' . $attributeID . ';';
            $rows = $db->arrayQuery($query);
            if (isset($rows[0])) {
                $tpl->setVariable('attributeID', $attributeID);
                $contentObjectID = $rows[0]['contentobject_id'];
                if (isset($contentObjectID) && ! empty($contentObjectID)) {
                    $tpl->setVariable('contentObjectID', $contentObjectID);
                }
                $node = eZContentObjectTreeNode::fetchByContentObjectID($contentObjectID);
                if ($node[0] instanceof eZContentObjectTreeNode) {
                    if (isset($node) && ! empty($node)) {
                        if ($node[0]->getName() != "") {
                            $tpl->setVariable('objectName', $node[0]->getName());
                            if ($node[0]->urlAlias() != "") {
                                $tpl->setVariable('urlAlias', $node[0]->urlAlias());
                            }
                        } else {
                            $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", "No objectname found"));
                        }
                    }
                    $nodeID = $node[0]->attribute('node_id');
                    if (isset($nodeID) && ! empty($nodeID)) {
                        $tpl->setVariable('nodeID', $nodeID);
                    }
                }
            } else {
                $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", 'This contentobject attribute ID %attribute_id was not found', '', array(
                    '%attribute_id' => $attribute_id
                )));
            }
        } else {
            $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", "Is not a number"));
        }
    } else {
        $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", "Please fill in the textbox"));
    }
}

if ($http->hasVariable('findBlockID')) {
    $tpl->setVariable('formType', 'findBlock');
    if ($http->variable('blockID') != "") {
        $blockID = $http->variable('blockID');
        
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
        WHERE data_text LIKE (\'%' . $blockID . '%\')
        ;';
        $rows = $db->arrayQuery($query);
        if (isset($rows[0])) {
            $datatext = $rows[0]["data_text"];
            $xml = simplexml_load_string($datatext);
            
            $zone = $xml->xpath("/page/zone[block[@id='id_" . $blockID . "']]");
            $block = $xml->xpath("/page/zone/block[@id='id_" . $blockID . "']");
            $tpl->setVariable('zoneID', $block[0]->zone_id[0]);
            $tpl->setVariable('blockType', $block[0]->type[0]);
            if ($block[0]->name[0] != "") {
                $tpl->setVariable('blockName', $block[0]->name[0]);
            }
            $tpl->setVariable('zoneLayout', $xml->zone_layout[0]);
            $tpl->setVariable('zoneIdentifier', $zone[0]->zone_identifier[0]);
            
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
            
            $contentObjectID = $rows[0]['id'];
            $tpl->setVariable('contentObjectID', $contentObjectID);
            $node = eZContentObjectTreeNode::fetchByContentObjectID($contentObjectID);
            if ($node[0] instanceof eZContentObjectTreeNode) {
                $tpl->setVariable('objectName', $node[0]->getName());
                if ($node[0]->urlAlias() != "") {
                    $tpl->setVariable('urlAlias', $node[0]->urlAlias());
                }
                $nodeID = $node[0]->attribute('node_id');
                $tpl->setVariable('nodeID', $nodeID);
            }
            $tpl->setVariable('blockID', $blockID);
        } else {
            $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", 'This block ID %blockid was not found', '', array(
                '%blockid' => $blockID
            )));
        }
    } else {
        $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", "Please fill in the textbox"));
    }
}

$timeStamp = time();

// $jacextensionINI = eZINI::instance( 'helptools.ini' );

// $tollerText = $jacextensionINI->variable( 'lastmodified','query' );

// if( $tollerText == 'Smarties' )
// {
//     echo 'SMARTIES :D';
// }
// last 10 modified objects

$inputInformation["lastmodified"]["query"] = 'SELECT
                                                id , modified , published
                                                FROM ezcontentobject
                                                WHERE modified < ' . $timeStamp . '
                                                AND status = 1
                                                ORDER by modified DESC
                                                LIMIT 10
                                                ;';
$inputInformation["lastmodified"]["headline"] = "Last 10 modified objects";

// last 10 published objects

$inputInformation["lastpublished"]["query"] = 'SELECT
                                                id , modified , published, current_version
                                                FROM ezcontentobject
                                                WHERE published < ' . $timeStamp . '
                                                AND status = 1
                                                ORDER by published DESC
                                                LIMIT 10
                                                ;';
$inputInformation["lastpublished"]["headline"] = "Last 10 published objects";

$tpl->setVariable('outputInformation', getQueryInformation($inputInformation));

function getQueryInformation($inputInformation)
{
    $db = eZDB::instance();
    foreach ($inputInformation as $value => $inputInformation) {
        $outputInformation[$value]['headline'] = $inputInformation['headline'];
        $rows = $db->arrayQuery($inputInformation['query']);
        foreach ($rows as $count => $row) {
            $contentObjectID = $row['id'];
            $object = eZContentObject::fetch($contentObjectID);
            if ($object instanceof eZContentObject) {
                $outputInformation[$value][$count]['ID'] = $contentObjectID;
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
                }
                $node = eZContentObjectTreeNode::fetchByContentObjectID($contentObjectID);
                if ($node[0] instanceof eZContentObjectTreeNode) {
                    $creatorContentObjectID = $node[0]->creator()->ID;
                    $creatorNode = eZContentObjectTreeNode::fetchByContentObjectID($creatorContentObjectID);
                    $modifierUrlAlias = $creatorNode[0]->urlAlias();
                    if (isset($modifierUrlAlias) && ! empty($modifierUrlAlias)) {
                        $outputInformation[$value][$count]['modifierUrl'] = $modifierUrlAlias;
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
                    }
                    $nodeID = $node[0]->attribute('node_id');
                    if (isset($nodeID) && ! empty($nodeID)) {
                        $outputInformation[$value][$count]['nodeID'] = $nodeID;
                    } else {
                        $outputInformation[$value][$count]['nodeID'] = "Not found nodeID";
                    }
                } else {
                    $outputInformation[$value][$count]['error'] = "No published node found";
                }
            } else {
                $outputInformation[$value][$count]['error'] = "No published object found";
            }
        }
    }
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