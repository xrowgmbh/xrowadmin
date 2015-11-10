<?php
$http = eZHTTPTool::instance();
$Module = $Params['Module'];
$tpl = eZTemplate::factory();
$db = eZDB::instance();
$helpToolsINI = eZINI::instance('helptools.ini');
$contentclassID = $helpToolsINI->variable('helptools', 'ContentclassID');
$dataTypeIdentifier = $helpToolsINI->variable('helptools', 'DataTypeIdentifier');
$timeStamp = time();

if ($http->hasVariable('findFileSearchButton'))
{
    $tpl->setVariable('formType', 'findFile');
    if ($http->variable('fileName') != "")
    {
        $fileName = $db->escapeString($http->variable('fileName'));
        $tpl->setVariable('fileName', $fileName);

        if (isValid($fileName))
        {
            $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", "Please only use numbers, letters and dots for searching."));
        }
        else
        {
            $query = 'SELECT * FROM (
                    SELECT ezbinaryfile.filename, ezbinaryfile.original_filename, ezbinaryfile.contentobject_attribute_id , ezcontentobject_attribute.id as "attr_id", ezcontentobject_attribute.version, ezcontentobject_attribute.contentobject_id, ezcontentobject.id, ezcontentobject.name as "object_name"
                    FROM ezbinaryfile 
                    LEFT JOIN ezcontentobject_attribute ON ezcontentobject_attribute.id = ezbinaryfile.contentobject_attribute_id 
                    LEFT JOIN ezcontentobject ON ezcontentobject.id = ezcontentobject_attribute.contentobject_id 
                    WHERE ezbinaryfile.filename =\'' . $fileName . '\'
                    ORDER BY ezcontentobject_attribute.version DESC
                    LIMIT 1 ) as result_binary, 
                    cluster_test_db.ezdfsfile as dfs
                    WHERE dfs.name LIKE CONCAT(\'%/\', result_binary.filename);';

            $rows = $db->arrayQuery($query);

            if (isset($rows[0]))
            {
                $fileContentObjectID = $rows[0]['contentobject_id'];

                if (isset($fileContentObjectID) && !empty($fileContentObjectID))
                {
                    $tpl->setVariable('fileContentObjectID', $fileContentObjectID);
                }

                $stateInfo = handleState($fileContentObjectID);

                if ($stateInfo["status"] == '1')
                {
                    $fileNode = eZContentObjectTreeNode::fetchByContentObjectID($fileContentObjectID);
                    if ($fileNode[0] instanceof eZContentObjectTreeNode)
                    {
                        if (isset($fileNode) && !empty($fileNode))
                        {
                            $tpl->setVariable('objectName', $fileNode[0]->getName());
                            if ($fileNode[0]->urlAlias() != "")
                            {
                                $tpl->setVariable('urlAlias', $fileNode[0]->urlAlias());
                            }
                        }
                        $fileNodeID = $fileNode[0]->attribute('node_id');
                        if (isset($fileNodeID) && !empty($fileNodeID))
                        {
                            $tpl->setVariable('fileNodeID', $fileNodeID);
                        }
                    }
                }
                else
                {
                    $tpl->setVariable('errorMessage', $stateInfo["msg"] );
                }

                $tpl->setVariable('filePath', $rows[0]['name']);
            }
            else
            {
                $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", 'The provided filename was not found.', '', array(
                    '%filename' => $fileName
                )));
            }
        }
    }
    else
    {
        $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", "Please fill in the textbox"));
    }
}

if ($http->hasVariable('findAttributeID'))
{
    $tpl->setVariable('formType', 'findAttribute');
    if ($http->variable('attributeID') != "")
    {
        $attributeID = $db->escapeString($http->variable('attributeID'));
        $tpl->setVariable('attributeID', $attributeID);
        if (is_numeric($attributeID))
        {
            $query = 'Select contentobject_id
                        FROM ezcontentobject ezcontentobject
                        LEFT JOIN
                        ezcontentobject_attribute ezcontentobject_attribute ON ezcontentobject_attribute.contentobject_id = ezcontentobject.id
                        Where ezcontentobject_attribute.id =' . $attributeID . ';';
            $rows = $db->arrayQuery($query);

            if (isset($rows[0]))
            {
                $resultContentObjectID = $rows[0]['contentobject_id'];
                if (isset($resultContentObjectID) && !empty($resultContentObjectID))
                {
                    $tpl->setVariable('resultContentObjectID', $resultContentObjectID);
                }

                $stateInfo = handleState($resultContentObjectID);

                if ($stateInfo["status"] == '1')
                {
                    $resultNode = eZContentObjectTreeNode::fetchByContentObjectID($resultContentObjectID);

                    if ($resultNode[0] instanceof eZContentObjectTreeNode)
                    {
                        if (isset($resultNode) && !empty($resultNode))
                        {
                            if ($resultNode[0]->getName() != "")
                            {
                                $tpl->setVariable('objectName', $resultNode[0]->getName());
                                if ($resultNode[0]->urlAlias() != "")
                                {
                                    $tpl->setVariable('urlAlias', $resultNode[0]->urlAlias());
                                }
                            }
                            else
                            {
                                $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", "No object name found"));
                            }
                        }
                        $resultNodeID = $resultNode[0]->attribute('node_id');
                        if (isset($resultNodeID) && !empty($resultNodeID))
                        {
                            $tpl->setVariable('resultNodeID', $resultNodeID);
                        }
                    }
                }
                else
                {
                    $tpl->setVariable('errorMessage', $stateInfo["msg"] );
                }
            }
            else
            {
                $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", 'No contentobject attribute found for the provided attribute ID.', '', array(
                    '%attribute_id' => $attributeID
                )));
            }
        }
        else
        {
            $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", "The given value is not a number, please provide a valid attribute ID"));
        }
    }
    else
    {
        $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", "Please fill in the textbox"));
    }
}

function handleState($contentObjectID)
{
    $handled_Object = eZContentObject::fetch($contentObjectID);
    $status = $handled_Object->Status;

    if ($status == 2)
    {
        $error_msg = ezpI18n::tr("admin/helptools", 'Details can not be displayed, because the linked object is located in the trash box (object ID: %trashNodeObjectID and object name: %trashNodeObjectName).', '', array(
                            '%trashNodeObjectID' => $handled_Object->ID,
                            '%trashNodeObjectName' => $handled_Object->Name
                    ));
    }
    elseif ($status >= 3) 
    {
        $error_msg = ezpI18n::tr("admin/helptools", "Content Object has an unsupported status");
    }

    return array('status' => $status, 'msg' => $error_msg, 'handled_Object' => $handled_Object);
}

function isValid($searchString)
{
    return !preg_match("#^[a-zA-Z0-9äöüÄÖÜ \.]+$#", $searchString);
}

if ($http->hasVariable('findBlockID'))
{
    $tpl->setVariable('formType', 'findBlock');
    if ($http->variable('blockID') != "")
    {
        $blockID = $db->escapeString($http->variable('blockID'));
        $tpl->setVariable('blockID', $blockID);

        if (isValid($blockID))
        {
            $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", "Please only use numbers, letters and dots for searching."));
        }
        else
        {
            $query = 'SELECT * FROM
            (
                SELECT
                ezcontentobject.id, ezcontentobject.name, ezcontentobject_attribute.data_text
                FROM ezcontentobject ezcontentobject
                LEFT JOIN
                ezcontentobject_attribute ezcontentobject_attribute ON ezcontentobject_attribute.contentobject_id = ezcontentobject.id
                WHERE contentclass_id = \'' . $contentclassID . '\'
                AND ezcontentobject_attribute.data_type_string = \'' . $dataTypeIdentifier . '\'
                GROUP BY ezcontentobject_attribute.contentobject_id
                ORDER BY ezcontentobject_attribute.version DESC
            ) as subtable
            WHERE data_text LIKE (\'%' . $blockID . '%\');';

            $rows = $db->arrayQuery($query);

            if (isset($rows[0]))
            {
                $datatext = $rows[0]["data_text"];
                $xml = simplexml_load_string($datatext);

                $zone = $xml->xpath("/page/zone[block[@id='id_" . $blockID . "']]");
                $block = $xml->xpath("/page/zone/block[@id='id_" . $blockID . "']");
                $tpl->setVariable('zoneID', $block[0]->zone_id[0]);
                $tpl->setVariable('blockType', $block[0]->type[0]);
                if ($block[0]->name[0] != "")
                {
                    $tpl->setVariable('blockName', $block[0]->name[0]);
                }

                $tpl->setVariable('zoneLayout', $xml->zone_layout[0]);
                $tpl->setVariable('zoneIdentifier', $zone[0]->zone_identifier[0]);
                $blockContentObjectID = $rows[0]['id'];
                $tpl->setVariable('blockContentObjectID', $blockContentObjectID);
                $blockNode = eZContentObjectTreeNode::fetchByContentObjectID($blockContentObjectID);

                $stateInfo = handleState($blockContentObjectID);

                if ($stateInfo["status"] == '1')
                {
                    if ($blockNode[0] instanceof eZContentObjectTreeNode)
                    {
                        $tpl->setVariable('objectName', $blockNode[0]->getName());
                        if ($blockNode[0]->urlAlias() != "")
                        {
                            $tpl->setVariable('urlAlias', $blockNode[0]->urlAlias());
                        }
                        $blockNodeID = $blockNode[0]->attribute('node_id');
                        $tpl->setVariable('blockNodeID', $blockNodeID);
                    }
                }
                else
                {
                    $tpl->setVariable('errorMessage', $stateInfo["msg"] );
                }
            }
            else
            {
                $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", 'The provided block ID was not found.', '', array(
                        '%blockid' => $blockID
                )));
            }
        }
    }
    else
    {
        $tpl->setVariable('errorMessage', ezpI18n::tr("admin/helptools", "Please fill in the textbox"));
    }
}

foreach ($helpToolsINI->variable('activelist', 'active') as $output)
{
    $placeholderError = false;
    $queryString = $helpToolsINI->variable($output, 'query');
    preg_match_all('/(?s)\$\$.+?\$\$/', $queryString, $hits);

    foreach ($hits[0] as $hit)
    {
        $matchedVariable = ${str_replace("$$","",$hit)};
        if($matchedVariable === NULL)
        {
            eZDebug::writeError("xrowAdmin: " . $hit . " not found in xrowadmin/modules/admin/helptools.php");
            $placeholderError = true;
        }
        $queryString = str_replace($hit, $matchedVariable, $queryString);
    }
    if($placeholderError === false)
    {
        $inputInformation[$output]["query"] = $queryString;
        $inputInformation[$output]["headline"] = $helpToolsINI->variable($output, 'headline');
    }
}

$tpl->setVariable('outputInformation', getQueryInformation($inputInformation));

function getQueryInformation($inputInformation)
{
    $db = eZDB::instance();
    foreach ($inputInformation as $value => $inputInformation)
    {
        $outputInformation[$value]['headline'] = $inputInformation['headline'];
        $rows = $db->arrayQuery($inputInformation['query']);
        foreach ($rows as $count => $row)
        {
            $resultContentObjectID = $row['id'];
            $object = eZContentObject::fetch($resultContentObjectID);
            if ($object instanceof eZContentObject)
            {
                $outputInformation[$value][$count]['ID'] = $resultContentObjectID;
                if (isset($object->owner()->ID))
                {
                    $outputInformation[$value][$count]['publisher'] = $object->owner()->Name;
                    $ownerContentObjectID = $object->owner()->ID;
                    $ownerNode = eZContentObjectTreeNode::fetchByContentObjectID($ownerContentObjectID);
                    $publisherUrl = $ownerNode[0]->urlAlias();
                }
                else
                {
                    $outputInformation[$value][$count]['publisher'] = ezpI18n::tr("admin/helptools", "Publisher could not be found");
                }

                if (isset($publisherUrl) && !empty($publisherUrl))
                {
                    $outputInformation[$value][$count]['publisherUrl'] = $publisherUrl;
                }

                $resultNode = eZContentObjectTreeNode::fetchByContentObjectID($resultContentObjectID);

                if ($resultNode[0] instanceof eZContentObjectTreeNode)
                {
                    if(isset($resultNode[0]->creator()->ID))
                    {
                        $creatorContentObjectID = $resultNode[0]->creator()->ID;
                        $creatorNode= eZContentObjectTreeNode::fetchByContentObjectID($creatorContentObjectID);
                        $modifierUrlAlias = $creatorNode[0]->urlAlias();
                    }

                    if (isset($modifierUrlAlias) && !empty($modifierUrlAlias))
                    {
                        $outputInformation[$value][$count]['modifierUrl'] = $modifierUrlAlias;
                    }

                    if (isset($resultNode[0]->creator()->Name) && !empty($resultNode[0]->creator()->Name))
                    {
                        $outputInformation[$value][$count]['modifier'] = $resultNode[0]->creator()->Name;
                    }
                    else
                    {
                        $outputInformation[$value][$count]['modifier'] = ezpI18n::tr("admin/helptools", "Modifier could not be found");
                    }

                    $getName = $resultNode[0]->getName();

                    if (isset($getName) && !empty($getName))
                    {
                        $outputInformation[$value][$count]['name'] = $getName;
                    }
                    else
                    {
                        $outputInformation[$value][$count]['name'] = ezpI18n::tr("admin/helptools", "Name could not be found");
                    }

                    $urlAlias = $resultNode[0]->urlAlias();

                    if (isset($urlAlias) && !empty($urlAlias))
                    {
                        $outputInformation[$value][$count]['url'] = $urlAlias;
                    }

                    $resultNodeID = $resultNode[0]->attribute('node_id');

                    if (isset($resultNodeID) && !empty($resultNodeID))
                    {
                        $outputInformation[$value][$count]['nodeID'] = $resultNodeID;
                    }
                    else
                    {
                        $outputInformation[$value][$count]['nodeID'] = ezpI18n::tr("admin/helptools", "nodeID could not be found");
                    }
                }
                else
                {
                    $outputInformation[$value][$count]['error'] = ezpI18n::tr("admin/helptools", "No published node found");
                }
            }
            else
            {
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