<?php

//you can call the script like: /usr/local/zend/bin/php bin/php/ezexec.php extension/xrowadmin/bin/change_attribute_value.php

$cli = eZCLI::instance();
$script = eZScript::instance( array( 
    'description' => ( "\n" . "This can modify attributes content on certain conditions.\n" ) , 
    'use-session' => false , 
    'use-modules' => true , 
    'use-extensions' => true 
) );

$script->startup();
$script->initialize();
$db = eZDB::instance();
$ini = eZINI::instance();
$now_ts = time();
#$time = strtotime("-6 month", $now_ts );
#01.06.2015
#h,m,s,m,d,y
$time = mktime(0, 0, 1, 6, 1, 2015);

$attribute_list = array();

//settings for folder class
$attribute_list["folder/show_modified_time"]["attr_id"] = 1006;
$attribute_list["folder/show_modified_time"]["type"] = "checkbox";
$attribute_list["folder/show_modified_time"]["params"] = array();
$attribute_list["folder/show_modified_time"]["params"]["AttributeFilter"] = array( array('modified', '<=', $time ) );
$attribute_list["folder/show_modified_time"]["change_to"] = 0;

//settings for article class
$attribute_list["article/show_modified_time"]["attr_id"] = 1005;
$attribute_list["article/show_modified_time"]["type"] = "checkbox";
$attribute_list["article/show_modified_time"]["params"] = array();
$attribute_list["article/show_modified_time"]["params"]["AttributeFilter"] = array( array('modified', '<=', $time ) );
$attribute_list["article/show_modified_time"]["change_to"] = 0;

foreach ( $attribute_list as $identifier => $conditions )
{
    $identifier_information = explode("/", $identifier);
    $class_identifier = $identifier_information[0];
    $attribute_identifier = $identifier_information[1];
    $conditions["params"]["ClassFilterType"] = "include";        
    $conditions["params"]["ClassFilterArray"] = array( $class_identifier );
    $conditions["params"]["Depth"] = "9999";
    $conditions["params"]["MainNodeOnly"] = true;

    echo "Start Fetching '$class_identifier' elements..\n";
    $nodes = eZContentObjectTreeNode::subTreeByNodeID( $conditions["params"], 1 );

    echo "Found " . count($nodes) . " '$class_identifier' elements.. changing attribute now\n";
    if ( $conditions["type"] === "checkbox" )
    {
        foreach( $nodes as $i => $node )
        {
            $object = $node->object();
            $objectDataMap = $object->dataMap();
            $objectAttribute = $objectDataMap[$attribute_identifier];
            $objectAttribute->setContent( $conditions["change_to"] );
            $objectAttribute->setAttribute( "data_int", $conditions["change_to"] );
            $objectAttribute->store();
            $object->store();
            
            if( $i % 100 == 0)
            {
                echo "\n" . $i . " objects done";
            }
            
        }
    }
    
    echo "\n\n'$class_identifier' class finished..\n\n";
}

$cli->output( "Script completely done.\n" );
$script->shutdown();

?>