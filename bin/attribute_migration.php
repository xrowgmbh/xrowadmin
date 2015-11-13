<?php
/*
    This script migrates the content of an ezobjectrelation to an ezobjectrelationlist attribute
    php -d memory_limit=2048M bin/php/ezexec.php extension/xrowadmin/bin/attribute_migration.php
*/

$cli = eZCLI::instance();
$cli->output( "The script has started" );

$db = eZDB::instance();
$ini = eZINI::instance();
$options = eZCLI::getOptions( $config, $argumentConfig, $arguments = false);

$contentClassID = $options["arguments"][1];
$sourceAttributeID = $options["arguments"][2];
$destinationAttributeID = $options["arguments"][3];
$sourceAttributeExsists = false;
$destinationAttributeExsists = false;

$processedAttributes = 0;
$emptyAttributes = 0;
$duplicateAttributes = 0;
$NaNAttributes = 0;
$migratedAttributes = 0;
$noContentObj = 0;
$processedObj = 0;
$changedObj = 0;

$contentClass = eZContentClass::fetch($contentClassID, true);

// Check if attributes exist in the selected content class
$contentClassAttributes = $contentClass->fetchAttributes();
foreach ($contentClassAttributes as $attribute)
{
    if($attribute->ID == $sourceAttributeID)
    {
        $sourceAttributeExsists = true;
    }
    if($attribute->ID == $destinationAttributeID)
    {
        $destinationAttributeExsists = true;
    }
}

if (($sourceAttributeExsists && $destinationAttributeExsists) == true)
{
    $contentObjects = eZContentObject::fetchList(true, array( 'contentclass_id' => $contentClassID ), false, false);
    foreach($contentObjects as $contentObject)
    {
        $processedObj++;
        $objChanged = false;
        $cli->output("////// " . $contentObject->ID . " ///////");
        $attributeDestinations = eZContentObjectAttribute::fetchSameClassAttributeIDList($destinationAttributeID, $asObject = true, $version = false, $contentObject->ID );
        foreach ($attributeDestinations as $attributeDestination)
        {
            $processedAttributes++;
            $duplicateItem = false;
            $list_content = $attributeDestination->content();
            $attributeSource = eZContentObjectAttribute::fetchSameClassAttributeIDList($sourceAttributeID,
                                                                                        $asObject = true,
                                                                                        $version = $attributeDestination->Version,
                                                                                        $contentObject->ID );
           if($attributeSource[0]->hasContent())
           {
                foreach ($list_content['relation_list'] as $listItem)
                {
                    if($listItem['contentobject_id'] == $attributeSource[0]->DataInt)
                    {
                        $duplicateItem = true;
                        break;
                    }
                }

                if($duplicateItem == false)
                {
                    if(is_numeric($attributeSource[0]->DataInt))
                    {
                        $object = eZContentObject::fetch($attributeSource[0]->DataInt);
                        if ($object instanceof eZContentObject)
                        {
                            $list_content['relation_list'][] = eZObjectRelationListType::appendObject( $attributeSource[0]->DataInt, 1, $attributeDestination );
                            $attributeDestination->setContent( $list_content );
                            $attributeDestination->store();
                            $contentObject->store();

                            $migratedAttributes++;
                            $objChanged = true;
                        }
                        else
                        {
                            $noContentObj++;
                            $cli->output("Not a eZContentObject, related contentobject could not be found");
                        }
                    }
                    else
                    {
                        $NaNAttributes++;
                        $cli->output("Content object ID is not a number");
                    }
                }
                else
                {
                    $duplicateAttributes++;
                    $cli->output("duplicate item");
                }
           }
           else
           {
               $emptyAttributes++;
               $cli->output("Nothing to migrate");
           }
        }
        if($objChanged == true)
        {
            $changedObj++;
        }
    }
    eZContentObject::clearCache();
    eZContentCacheManager::clearAllContentCache();
}
else
{
    if ($sourceAttributeExsists == false)
    {
        $cli->output("The source attribute with ID " . $sourceAttributeID . " does not exsist in the content class with ID ". $contentClassID ."!");
    }
    if ($destinationAttributeExsists == false)
    {
        $cli->output("The destination attribute with ID " . $destinationAttributeID . " does not exsist in the content class with ID ". $contentClassID ."!");
    }
    $cli->output("Aborting Script!");
}

$cli->output("\nOBJECTS:");
$cli->output("processed: " . $processedObj);
$cli->output("changed: " . $changedObj . "\n");
$cli->output("NOT MIGRATED ATTRIBUTES:");
$cli->output("Empty: " . $emptyAttributes);
$cli->output("NaN: " . $NaNAttributes);
$cli->output("Duplicates: " . $duplicateAttributes);
$cli->output($noContentObj . " cases where the related content object could not be found");
$cli->output("\nPROCESSED ATTRIBUTES: " . $processedAttributes);
$cli->output("MIGRATED ATTRIBUTES: " . $migratedAttributes);
$cli->output("\nThe script has finished!"); 
?>