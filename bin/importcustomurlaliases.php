#!/usr/bin/env php
<?php

require "autoload.php";

$cli = eZCLI::instance();
$script = eZScript::instance(
    array(
        "description" => (
            "\nThis script will import custom URL aliases from the specified database table.\n" .
            "\nNote that paths of custom URL aliases cannot be unambiguously resolved." .
            "\nTherefore they will be resolved for given siteaccess."
        ),
        "use-session" => false,
        "use-modules" => true,
        "use-extensions" => true
    )
);

$script->startup();

$optionHelp = array(
    "n" => "Do not wait 30 seconds before starting",
    "dry-run" => "Test mode, output the list of aliases to import without actually importing them",
    "source-table" => "Name of the database table to import custom aliases from"
);
$options = $script->getOptions( "[n][dry-run][source-table:]", "", $optionHelp );

if ( !$options["source-table"] )
{
    $cli->error( "Source table must be specified" );
    $script->shutdown( 2 );
}
$optDryRun = (boolean)$options["dry-run"];
$optSourceTable = $options["source-table"];

$script->initialize();

$cli->warning( "This script will import custom URL aliases from the specified database table." );
$cli->output();
$cli->warning( "Note that paths of custom URL aliases cannot be unambiguously resolved." );
$cli->warning( "Therefore they will be resolved for given siteaccess." );
$cli->output();

if ( !isset( $options["n"] ) )
{
    $cli->warning( "You have 30 seconds to break the script before actual processing starts (press Ctrl-C)" );
    $cli->warning( "(execute the script with '-n' switch to skip this delay)" );
    sleep( 30 );
    $cli->output();
}

/**
 * @param int $pass
 *
 * @return \eZPathElement[]
 */
function loadCustomAliases( $pass )
{
    global $optSourceTable;

    $limit = 200;
    $offset = $pass * $limit;
    $langMask = trim( eZContentLanguage::languagesSQLFilter( $optSourceTable, "lang_mask" ) );
    $query = "SELECT * FROM {$optSourceTable} WHERE ({$langMask}) AND is_original = 1 AND is_alias = 1 ORDER BY id";
    $params = array(
        "offset" => $offset,
        "limit"  => $limit,
    );

    $db = eZDB::instance();
    $rows = $db->arrayQuery( $query, $params );

    if ( count( $rows ) == 0 )
    {
        return array();
    }

    return eZURLAliasQuery::makeList( $rows );
}

function getPath( $alias )
{
    global $optSourceTable;

    if ( $alias->Path !== null )
        return $alias->Path;

    // Fetch path 'text' elements of correct parent path
    $path = array( $alias->Text );
    $id = (int)$alias->Parent;
    $db = eZDB::instance();
    while ( $id != 0 )
    {
        $query = "SELECT parent, lang_mask, text FROM {$optSourceTable} WHERE id={$id}";
        $rows = $db->arrayQuery( $query );
        if ( count( $rows ) == 0 )
        {
            break;
        }
        $result = eZURLAliasML::choosePrioritizedRow( $rows );
        if ( !$result )
        {
            $result = $rows[0];
        }
        $id = (int)$result['parent'];
        array_unshift( $path, $result['text'] );
    }
    $alias->Path = implode( '/', $path );
    return $alias->Path;
}

function storeAlias( eZPathElement $alias )
{
    global $cli, $optDryRun;

    $language = $alias->getLanguage();
    $path = getPath( $alias );

    $cli->output( "Importing custom alias " . $cli->stylize( "dark-red", "#" . $alias->ID ) );
    $cli->output( " - path: " . $cli->stylize( "dark-green", $path ) );
    $cli->output( " - action: " . $alias->Action );
    $cli->output( " - language: " . $language->Name . " (ID: {$language->ID}, Code: {$language->Locale})" );
    $cli->output( " - always available: " . ( $alias->alwaysAvailable() ? "yes" : "no" ) );
    $cli->output( " - redirects: " . ( $alias->AliasRedirects ? "yes" : "no" ) );

    if ( $optDryRun )
    {
        $status = $cli->stylize( "red-bg", " skipped " );
    }
    else
    {
        $result = eZURLAliasML::storePath(
            $path,
            $alias->Action,
            $language,
            true,
            $alias->AlwaysAvailable,
            false,
            true,
            false,
            false,
            $alias->AliasRedirects
        );

        if ( $result["status"] === true )
        {
            $status = $cli->stylize( "green-bg", " imported #" . $result["element"]->ID . " " );
        }
        else
        {
            $status = $cli->stylize( "red-bg", " error " . $result["status"] . " " );
        }
    }

    $cli->output( " - import status: " . $status );
    $cli->output();
}

function migrateAliases()
{
    global $cli, $script;

    $pass = 0;
    $customAliases = loadCustomAliases( $pass );

    if ( count( $customAliases ) == 0 )
    {
        $cli->output( "Nothing to process, exiting" );
        $script->shutdown( 0 );
    }

    while ( count( $customAliases ) )
    {
        foreach ( $customAliases as $alias )
        {
            storeAlias( $alias );
        }

        $customAliases = loadCustomAliases( ++$pass );
    }
}

migrateAliases();

$script->shutdown();
