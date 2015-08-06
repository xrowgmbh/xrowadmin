<?php
$http = eZHTTPTool::instance();
$Module = $Params['Module'];
$tpl = eZTemplate::factory();
$db = eZDB::instance();

if ($http->hasVariable('findFileSearchButton')) {
    $tpl->setVariable('formType', 'findFile');
    if ($http->variable('fileName') != "") {
        $fileName = $db->escapeString($http->variable('fileName'));
        $tpl->setVariable('fileName', $fileName);
        if (! preg_match("#^[a-zA-Z0-9äöüÄÖÜ \.]+$#", $fileName)) {
            $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", "Please check your input, if you use only the following character: a-z, A-Z, 0-9, ., äüö, ÄÖÜ"));
        } else {
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
                $fileContentObjectID = $rows[0]['contentobject_id'];
                if (isset($fileContentObjectID) && ! empty($fileContentObjectID)) {
                    $tpl->setVariable('fileContentObjectID', $fileContentObjectID);
                }
                $getStatus = eZContentObject::fetch($fileContentObjectID);
                $status = $getStatus->Status;
                if ($status == '1') {
                    $fileNode = eZContentObjectTreeNode::fetchByContentObjectID($fileContentObjectID);
                    if ($fileNode[0] instanceof eZContentObjectTreeNode) {
                        if (isset($fileNode) && ! empty($fileNode)) {
                            $tpl->setVariable('objectName', $fileNode[0]->getName());
                            if ($fileNode[0]->urlAlias() != "") {
                                $tpl->setVariable('urlAlias', $fileNode[0]->urlAlias());
                            }
                        }
                        $fileNodeID = $fileNode[0]->attribute('node_id');
                        if (isset($fileNodeID) && ! empty($fileNodeID)) {
                            $tpl->setVariable('fileNodeID', $fileNodeID);
                        }
                    }
                } elseif ($status == '2') {
                    $trashNode = eZContentObject::fetch($fileContentObjectID);
                    $trashNodeObjectID = $trashNode->ID;
                    $trashNodeObjectName = $trashNode->Name;
                    $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", 'The file was found, but the details can not be displayed. The file is in the trash box(object ID: %trashNodeObjectID and object name: %trashNodeObjectName).', '', array(
                        '%trashNodeObjectID' => $trashNodeObjectID,
                        '%trashNodeObjectName' => $trashNodeObjectName
                    )));
                }
            } else {
                $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", 'This filename %filename was not found.', '', array(
                    '%filename' => $fileName
                )));
            }
        }
    } else {
        $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", "Please fill in the textbox"));
    }
}

if ($http->hasVariable('findAttributeID')) {
    $tpl->setVariable('formType', 'findAttribute');
    if ($http->variable('attributeID') != "") {
        $attributeID = $db->escapeString($http->variable('attributeID'));
        $tpl->setVariable('attributeID', $attributeID);
        if (is_numeric($attributeID)) {
            $query = 'Select contentobject_id
                        FROM ezcontentobject ezcontentobject
                        LEFT JOIN
                        ezcontentobject_attribute ezcontentobject_attribute ON ezcontentobject_attribute.contentobject_id = ezcontentobject.id
                        Where ezcontentobject_attribute.id =' . $attributeID . ';';
            $rows = $db->arrayQuery($query);
            if (isset($rows[0])) {
                $attributeContentObjectID = $rows[0]['contentobject_id'];
                if (isset($attributeContentObjectID) && ! empty($attributeContentObjectID)) {
                    $tpl->setVariable('attributeContentObjectID', $attributeContentObjectID);
                }
                $attributeNode = eZContentObjectTreeNode::fetchByContentObjectID($attributeContentObjectID);
                if ($attributeNode[0] instanceof eZContentObjectTreeNode) {
                    if (isset($attributeNode) && ! empty($attributeNode)) {
                        if ($attributeNode[0]->getName() != "") {
                            $tpl->setVariable('objectName', $attributeNode[0]->getName());
                            if ($attributeNode[0]->urlAlias() != "") {
                                $tpl->setVariable('urlAlias', $attributeNode[0]->urlAlias());
                            }
                        } else {
                            $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", "No object name found"));
                        }
                    }
                    $attributeNodeID = $attributeNode[0]->attribute('node_id');
                    if (isset($attributeNodeID) && ! empty($attributeNodeID)) {
                        $tpl->setVariable('attributeNodeID', $attributeNodeID);
                    }
                }
            } else {
                $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", 'This contentobject attribute ID %attribute_id was not found.', '', array(
                    '%attribute_id' => $attributeID
                )));
            }
        } else {
            $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", "It is not a number"));
        }
    } else {
        $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", "Please fill in the textbox"));
    }
}
if ($http->hasVariable('findBlockID')) {
    $tpl->setVariable('formType', 'findBlock');
    if ($http->variable('blockID') != "") {
        $blockID = $db->escapeString($http->variable('blockID'));
        $tpl->setVariable('blockID', $blockID);
        
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
            
            $blockContentObjectID = $rows[0]['id'];
            $tpl->setVariable('blockContentObjectID', $blockContentObjectID);
            $blockNode = eZContentObjectTreeNode::fetchByContentObjectID($blockContentObjectID);
            if ($blockNode[0] instanceof eZContentObjectTreeNode) {
                $tpl->setVariable('objectName', $blockNode[0]->getName());
                if ($blockNode[0]->urlAlias() != "") {
                    $tpl->setVariable('urlAlias', $blockNode[0]->urlAlias());
                }
                $blockNodeID = $blockNode[0]->attribute('node_id');
                $tpl->setVariable('blockNodeID', $blockNodeID);
            }
        } else {
            $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", 'This block ID %blockid was not found.', '', array(
                '%blockid' => $blockID
            )));
        }
    } else {
        $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", "Please fill in the textbox"));
    }
}

$timeStamp = time();

$helpToolsINI = eZINI::instance('helptools.ini');

foreach ($helpToolsINI->variable('activelist', 'active') as $output) {
    $inputInformation[$output]["query"] = str_replace('$$timeStamp$$', $timeStamp, $helpToolsINI->variable($output, 'query'));
    $inputInformation[$output]["headline"] = $helpToolsINI->variable($output, 'headline');
}

$tpl->setVariable('outputInformation', getQueryInformation($inputInformation));

function getQueryInformation($inputInformation)
{
    $db = eZDB::instance();
    foreach ($inputInformation as $value => $inputInformation) {
        $outputInformation[$value]['headline'] = $inputInformation['headline'];
        $rows = $db->arrayQuery($inputInformation['query']);
        foreach ($rows as $count => $row) {
            $resultContentObjectID = $row['id'];
            $object = eZContentObject::fetch($resultContentObjectID);
            if ($object instanceof eZContentObject) {
                $outputInformation[$value][$count]['ID'] = $resultContentObjectID;
                if (isset($object->owner()->Name) && ! empty($object->owner()->Name)) {
                    $outputInformation[$value][$count]['publisher'] = $object->owner()->Name;
                } else {
                    $outputInformation[$value][$count]['publisher'] = ezpI18n::tr("admin/helptools", "Publisher could not be found");
                }
                $ownerContentObjectID = $object->owner()->ID;
                $ownerNode = eZContentObjectTreeNode::fetchByContentObjectID($ownerContentObjectID);
                $publisherUrl = $ownerNode[0]->urlAlias();
                if (isset($publisherUrl) && ! empty($publisherUrl)) {
                    $outputInformation[$value][$count]['publisherUrl'] = $publisherUrl;
                }
                $resultNode = eZContentObjectTreeNode::fetchByContentObjectID($resultContentObjectID);
                if ($resultNode[0] instanceof eZContentObjectTreeNode) {
                    $creatorContentObjectID = $resultNode[0]->creator()->ID;
                    $creatorNode = eZContentObjectTreeNode::fetchByContentObjectID($creatorContentObjectID);
                    $modifierUrlAlias = $creatorNode[0]->urlAlias();
                    if (isset($modifierUrlAlias) && ! empty($modifierUrlAlias)) {
                        $outputInformation[$value][$count]['modifierUrl'] = $modifierUrlAlias;
                    }
                    if (isset($resultNode[0]->creator()->Name) && ! empty($resultNode[0]->creator()->Name)) {
                        $outputInformation[$value][$count]['modifier'] = $resultNode[0]->creator()->Name;
                    } else {
                        $outputInformation[$value][$count]['modifier'] = ezpI18n::tr("admin/helptools", "Modifier could not be found");
                    }
                    $getName = $resultNode[0]->getName();
                    if (isset($getName) && ! empty($getName)) {
                        $outputInformation[$value][$count]['name'] = $getName;
                    } else {
                        $outputInformation[$value][$count]['name'] = ezpI18n::tr("admin/helptools", "Name could not be found");
                    }
                    $urlAlias = $resultNode[0]->urlAlias();
                    if (isset($urlAlias) && ! empty($urlAlias)) {
                        $outputInformation[$value][$count]['url'] = $urlAlias;
                    }
                    $resultNodeID = $resultNode[0]->attribute('node_id');
                    if (isset($resultNodeID) && ! empty($resultNodeID)) {
                        $outputInformation[$value][$count]['nodeID'] = $resultNodeID;
                    } else {
                        $outputInformation[$value][$count]['nodeID'] = ezpI18n::tr("admin/helptools", "nodeID could not be found");
                    }
                } else {
                    $outputInformation[$value][$count]['error'] = ezpI18n::tr("admin/helptools", "No published node found");
                }
            } else {
                $outputInformation[$value][$count]['error'] = ezpI18n::tr("admin/helptools", "No published object found");
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