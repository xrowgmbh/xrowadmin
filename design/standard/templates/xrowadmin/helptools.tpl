<h1 class="context-title">{'file search'|i18n('admin/helptools')}</h1>

<div class="box-header"></div>

<p>{'Enter the file name to receive information. (example : b73edf37e8e85df9ad8a5c4fb4e1d144.mp4)'|i18n('admin/helptools')}</p>

<form name="filename" method="post" action={"admin/helptools"|ezurl}>
    <input type="text" name="filename"/>
    <input type="submit" name="findfilesearchbutton" value="{'search'|i18n('admin/helptools')}"/>
</form>

{if eq($formtype , 'findfile')}      
    {if is_set($errormessage)}
        <p>{$errormessage}</p>           
    {else}
        <p>{'This filename %filename was found'|i18n('admin/helptools' , '' , hash('%filename', $filename))}</p> 
        <ul>
            <li>{'name of the object'|i18n('admin/helptools')}: <a href={$urlAlias|ezurl()}>{$objectname}</a> </li>
            <li>{'object ID'|i18n('admin/helptools')}: {$contentobject_id}</li>
            <li>{'node ID'|i18n('admin/helptools')}: {$node_id}</li>
            <li>{'filename'|i18n('admin/helptools')}: {$filename}</li>
        </ul>
    {/if} 
{/if}
            
<h1 class="context-title">{'contentobject attribute id search'|i18n('admin/helptools')}</h1>

<div class="box-header"></div>

<p>{'Enter the contentobject attribute ID to receive information. (example : 10905831)'|i18n('admin/helptools')}</p>

<form name="attribute_id" method="post" action={"admin/helptools"|ezurl}>
    <input type="text" name="attribute_id"/>
    <input type="submit" name="findattribute_id" value="{'search'|i18n('admin/helptools')}"/>
</form>

{if eq($formtype , 'findattribute')}
    {if is_set($errormessage)}
        <p>{$errormessage}</p>
    {else}
        <p>{'This contentobject attribute ID %attribute_id was found'|i18n('admin/helptools' , '' , hash('%attribute_id', $attribute_id))}</p>
        <ul>
            <li>{'name of the object'|i18n('admin/helptools')} :
                 {if and(is_set($objectname) , is_set($urlAlias))}
                    <a href={$urlAlias|ezurl()}>{$objectname}</a>
                 {elseif is_set($errormessage)}
                    <p>{$errormessage}</p>
                 {/if}
            </li>
            <li>{'object ID'|i18n('admin/helptools')} : {$contentobject_id}</li>
            <li>{'node ID'|i18n('admin/helptools')} : {$node_id}</li>
            <li>{'contentobject attribute ID'|i18n('admin/helptools')} : {$attribute_id}</li>
        </ul>
    {/if}
{/if}

<h1 class="context-title">{'block search'|i18n('admin/helptools')}</h1>

<div class="box-header"></div>

<p>{'Enter the block ID to receive information. (example : b4fcd2bc56fa7d5a7b54772de029dadd)'|i18n('admin/helptools')}</p>

<form name="blockid" method="post" action={"admin/helptools"|ezurl}>
    <input type="text" name="blockid"/>
    <input type="submit" name="findblockid" value="{'search'|i18n('admin/helptools')}"/>
</form>

{if eq($formtype , 'findblock')}
    {if is_set($errormessage)}
        <p>{$errormessage}</p>
    {else}
        <p>{'This block ID %block_id was found'|i18n('admin/helptools' , '' , hash('%block_id', $block_id))}</p>
        <ul>
            <li>{'name of the object'|i18n('admin/helptools')} : <a href={$urlAlias|ezurl()}>{$objectname}</a> </li>
            <li>{'object ID'|i18n('admin/helptools')} : {$contentobject_id}</li>
            <li>{'node ID'|i18n('admin/helptools')} : {$node_id}</li>
            <li>{'zone ID'|i18n('admin/helptools')} : {$zone_id}</li>
            <li>{'zone name'|i18n('admin/helptools')} : {$zone_identifier}</li>
            <li>{'zone layout'|i18n('admin/helptools')} : {$zone_layout}</li>
            <li>{'block ID'|i18n('admin/helptools')} : {$block_id}</li>
            <li>{'block type'|i18n('admin/helptools')} : {$block_type}</li>
            {if is_set( $block_name )}
                <li>{'block name'|i18n('admin/helptools')} : {$block_name}</li>
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
                            <li>{'name of the object'|i18n('admin/helptools')} : <a href={$output.url|ezurl()}>{$output.name}</a> </li>
                            <li>{'object ID'|i18n('admin/helptools')} : {$output.id}</li>
                            <li>{'node ID'|i18n('admin/helptools')} : {$output.nodeId}</li>
                            <li>{'publisher'|i18n('admin/helptools')} : <a href={$output.publisherUrl|ezurl()}>{$output.publisher}</a></li>
                            <li>{'modifier'|i18n('admin/helptools')} : <a href={$output.modifierUrl|ezurl()}>{$output.modifier}</a></li>
                        </ul>
                    {/if}
                </div>
            {/foreach}
        </div>
    {/foreach}
{/if}