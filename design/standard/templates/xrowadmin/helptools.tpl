<h1 class="context-title">{'Search for a file location'|i18n('admin/helptools')}</h1>

<div class="box-header"></div>

<p>{'Enter the filename to receive the linked object you are looking for (example: b73edf37e8e85df9ad8a5c4fb4e1d144.mp4).'|i18n('admin/helptools')}</p>
<form name="fileName" method="post" action={"admin/helptools"|ezurl}>
    <input type="text" name="fileName" class="inputfield" />
    <input type="submit" name="findFileSearchButton" value="{'search'|i18n('admin/helptools')}" />
</form>

{if eq($formType , 'findFile')}
    {if is_set($fileName)}
        <p class="italic">{'You have searched for: "%fileName"'|i18n('admin/helptools' , '' , hash('%fileName', $fileName))}</p>
    {/if}
    {if is_set($errorMessage)}
            <p class="errorMessage">{$errorMessage}</p>
    {else}
        <ul>
            <li>{'name of the object'|i18n('admin/helptools')}: 
                {if is_set($urlAlias)}
                    <a href="{$urlAlias|ezurl("no")}">{$objectName}</a> 
                {else}
                    {$objectName}
                {/if}
            </li>
            <li>{'object ID'|i18n('admin/helptools')}: {$fileContentObjectID}</li>
            <li>{'node ID'|i18n('admin/helptools')}: {$fileNodeID}</li>
            <li>{'filename'|i18n('admin/helptools')}: {$fileName}</li>
            <li>{'file path'|i18n('admin/helptools')}: {$filePath}</li>
        </ul>
    {/if} 
{/if}
            
<h1 class="context-title">{'Search for a contentobject attribute'|i18n('admin/helptools')}</h1>

<div class="box-header"></div>

<p>{'Enter the contentobject attribute id to receive information about the use of the attribute (example: 10905831).'|i18n('admin/helptools')}</p>

<form name="attributeID" method="post" action={"admin/helptools"|ezurl}>
    <input type="text" name="attributeID" class="inputfield" />
    <input type="submit" name="findAttributeID" value="{'search'|i18n('admin/helptools')}" />
</form>

{if eq($formType , 'findAttribute')}
    {if is_set($attributeID)}
        <p class="italic">{'You have searched for: "%attributeID"'|i18n('admin/helptools' , '' , hash('%attributeID', $attributeID))}</p>
    {/if}
    {if is_set($errorMessage)}
        <p class="errorMessage">{$errorMessage}</p>
    {else}
        <ul>
            <li>{'name of the object'|i18n('admin/helptools')}:
                 {if and(is_set($objectName) , is_set($urlAlias))}
                     {if is_set($urlAlias)}
                         <a href="{$urlAlias|ezurl("no")}">{$objectName}</a> 
                     {else}
                         {$objectName}
                     {/if}
                 {elseif is_set($errorMessage)}
                    <p class="errorMessage">{$errorMessage}</p>
                 {/if}
            </li>
            <li>{'object ID'|i18n('admin/helptools')}: {$resultContentObjectID}</li>
            <li>{'node ID'|i18n('admin/helptools')}: {$resultNodeID}</li>
            <li>{'contentobject attribute ID'|i18n('admin/helptools')}: {$attributeID}</li>
        </ul>
    {/if}
{/if}

<h1 class="context-title">{'Search for a block'|i18n('admin/helptools')}</h1>

<div class="box-header"></div>

<p>{'Enter the block ID to receive information about the use of the block (example: b4fcd2bc56fa7d5a7b54772de029dadd).'|i18n('admin/helptools')}</p>

<form name="blockID" method="post" action={"admin/helptools"|ezurl}>
    <input type="text" name="blockID" class="inputfield" />
    <input type="submit" name="findBlockID" value="{'search'|i18n('admin/helptools')}" />
</form>

{if eq($formType , 'findBlock')}
    {if is_set($blockID)}
        <p class="italic">{'You have searched for: "%blockID"'|i18n('admin/helptools' , '' , hash('%blockID', $blockID))}</p>
    {/if}
    {if is_set($errorMessage)}
        <p class="errorMessage">{$errorMessage}</p>
    {else}
        <ul>
            <li>{'name of the object'|i18n('admin/helptools')} : 
                {if is_set($urlAlias)}
                    <a href="{$urlAlias|ezurl("no")}">{$objectName}</a> 
                {else}
                    {$objectName}
                {/if}
            </li>
            <li>{'object ID'|i18n('admin/helptools')}: {$blockContentObjectID}</li>
            <li>{'node ID'|i18n('admin/helptools')}: {$blockNodeID}</li>
            <li>{'zone ID'|i18n('admin/helptools')}: {$zoneID}</li>
            <li>{'zone name'|i18n('admin/helptools')}: {$zoneIdentifier}</li>
            <li>{'zone layout'|i18n('admin/helptools')}: {$zoneLayout}</li>
            <li>{'block ID'|i18n('admin/helptools')}: {$blockID}</li>
            <li>{'block type'|i18n('admin/helptools')}: {$blockType}</li>
            {if is_set( $blockName )}
                <li>{'block name'|i18n('admin/helptools')}: {$blockName}</li>
            {/if}
        </ul>
    {/if}
{/if}

{* $outputInformation is a array with last x published objects and last x modified objects *}
{if is_set( $outputInformation )}
    {foreach $outputInformation as $value => $outputInformation}
        <div class="last">
            <h1 class="context-title">{$outputInformation.headline|i18n('admin/helptools')}</h1>
            <div class="box-header"></div>
            {foreach $outputInformation|remove(0) as $count => $output}
                <div class="last-element">
                    <span>{$count|inc}:</span>
                    {if is_set($output.error)}
                        {$output.error}
                    {else}
                        <ul>
                            <li>
                                {if is_set($output.url)}
                                    {'name of the object'|i18n('admin/helptools')}: <a href="{$output.url|ezurl("no")}">{$output.name}</a> 
                                {else}
                                    {'name of the object'|i18n('admin/helptools')}: {$output.name}
                                {/if}
                            </li>
                            <li>{'object ID'|i18n('admin/helptools')}: {$output.ID}</li>
                            <li>{'node ID'|i18n('admin/helptools')}: {$output.nodeID}</li>
                            <li>
                                {if is_set($output.publisherUrl)}
                                    {'publisher'|i18n('admin/helptools')}: <a href="{$output.publisherUrl|ezurl("no")}">{$output.publisher}</a>
                                {else}
                                    {'publisher'|i18n('admin/helptools')}: {$output.publisher}
                                {/if}
                            </li>
                            <li>
                                {if is_set($output.modifierUrl)}
                                    {'modifier'|i18n('admin/helptools')}: <a href="{$output.modifierUrl|ezurl("no")}">{$output.modifier}</a>
                                {else}
                                    {'modifier'|i18n('admin/helptools')}: {$output.modifier}
                                {/if}
                            </li>
                        </ul>
                    {/if}
                </div>
            {/foreach}
        </div>
    {/foreach}
{/if}