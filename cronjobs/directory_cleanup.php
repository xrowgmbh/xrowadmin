<?php
//
// Definition of  class
//
// Created on: <31-Jul-2009>
//
// COPYRIGHT NOTICE: Copyright (C) 2009 xrow GmbH


$fileIni = eZINI::instance( 'file.ini' );
$iniCheckValue = $fileIni->variable( 'ClusteringSettings', 'FileHandler' );
$MountPointPath = $fileIni->variable( 'eZDFSClusteringSettings', 'MountPointPath' );
if($iniCheckValue == 'ezdfs' and $MountPointPath)
{
	echo 'Start of removing empty folders in ' . $MountPointPath . "\n";
	$fileSPLObjects =  new RecursiveIteratorIterator(
                   new RecursiveDirectoryIterator( $MountPointPath ),
                                                    RecursiveIteratorIterator::CHILD_FIRST
	);
	
	try {
	    foreach( $fileSPLObjects as $fullFileName => $fileSPLObject )
	    {
	        if( $fileSPLObject->isDir() and is_empty_folder( $fullFileName ) )
	        {
	            echo 'Deleting: ' . $fullFileName . "\n";
	            rmdir( $fullFileName );
	        }
	    }
	}
	catch (UnexpectedValueException $e) {
	    printf("Directory [%s] contained a directory we can not recurse into", $directory);
	}
	
	echo 'End of removing empty folders in ' . $MountPointPath . "\n";
}

$fileSPLObjects =  new RecursiveIteratorIterator(
                   new RecursiveDirectoryIterator( eZSys::storageDirectory() ),
                                                   RecursiveIteratorIterator::CHILD_FIRST
);

echo 'Start of removing empty folders in ' . eZSys::storageDirectory() . "\n";

try {
    foreach( $fileSPLObjects as $fullFileName => $fileSPLObject )
    {
        if( $fileSPLObject->isDir() and is_empty_folder( $fullFileName ) )
        {
            echo 'Deleting: ' . $fullFileName . "\n";
            rmdir( $fullFileName );
        }
    }
}
catch (UnexpectedValueException $e) {
    printf("Directory [%s] contained a directory we can not recurse into", $directory);
}

function is_empty_folder($folder){
    $c=0;
    if(is_dir($folder) ){
        $files = opendir($folder);
        while ($file=readdir($files)){$c++;}
        if ($c>2){
            return false;
        }else{
            return true;
        }
    }       
}

echo 'End of removing empty folders in ' . eZSys::storageDirectory() . "\n";

?>