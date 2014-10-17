#!/bin/php
<?php
require_once 'autoload.php';
$eztar = 'http://ez.no/content/download/322726/3215350/version/2/file/ezpublish-4.3.0-full-with_ezc-gpl.tar.gz';
$source = 'ezpublish.tar.gz';
$builddir = '.tmp';
$patchbin = 'patch.exe';
if ( ! file_exists( $source ) )
{
    file_put_contents( $source, file_get_contents( $eztar ) );
}
#ezcFile::removeRecursive( $builddir );
#mkdir( $builddir );
if ( ! is_dir( $builddir . DIRECTORY_SEPARATOR . 'src' ) )
{
    echo "Extract\n";
    $tar = ezcArchive::open( $source );
    foreach ( $tar as $entry )
    {
        if ( ! isset( $ezdir ) )
        {
            $dir = dirname( $entry->getPath() );
            list ( $ezdir, $trash ) = explode( '/', $dir, 2 );
        }
        $tar->extractCurrent( $builddir . DIRECTORY_SEPARATOR . 'src' );
    }

}

if ( ! is_dir( $builddir . DIRECTORY_SEPARATOR . 'build' ) )
{
	echo "Copy\n";
    ezcFile::copyRecursive( $builddir . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . $ezdir, $builddir . DIRECTORY_SEPARATOR . 'build' );
}
echo "Patching\n";
$patches = ezcBaseFile::findRecursive( "patch", array( '@\.diff$@' ) );
foreach ( $patches as $patch )
{
	$cwd = getcwd();
	system( 'patch --dry-run --verbose -p0 -t -d .tmp' . DIRECTORY_SEPARATOR . 'build -i ' .  realpath( $patch ) );
    chdir( $cwd );
}

echo "Build\n";

 class ArchiveContext extends ezcBaseFileFindContext
 {
 public $archive;
 public $prefix;
 }

 function findRecursiveCallback( ezcBaseFileFindContext $context, $sourceDir, $fileName, $fileInfo )
 {

 $path = "{$sourceDir}/{$fileName}";

 if ( is_dir( $path ) )
 {
 	   
 	 #die($path);
  $path .= '/';
 }
 $path = str_ireplace( '\\', "/", $path);
echo $path . "\n";
 $context->archive->append( array( $path ), $context->prefix );
 }

function appendRecursive( $archive, $sourceDir, $prefix )
{
 $context = new ArchiveContext();
 $context->archive = $archive;
 $context->prefix = $prefix;
 ezcBaseFile::walkRecursive( $sourceDir, array(), array(), 'findRecursiveCallback', $context );
}
unlink("ezpublish_patched.tar.gz");
$archive = ezcArchive::open( "ezpublish_patched.tar.gz", ezcArchive::TAR );
$archive->truncate();

// the 2nd parameter is the directory, the 3rd parameter is the prefix
appendRecursive( $archive, $builddir . '/' . 'build', $builddir . '/' . 'build') ; 

$archive->close();
#ezcFile::removeRecursive( $builddir . DIRECTORY_SEPARATOR . 'build' );
echo "done\n";
?>