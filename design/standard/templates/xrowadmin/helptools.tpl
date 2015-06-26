<h1 class="context-title">{'file search'|i18n('admin/helptools')}</h1>

<div class="box-header"></div>

<p>{'Enter the file name to receive information. (example : b73edf37e8e85df9ad8a5c4fb4e1d144.mp4)'|i18n('admin/helptools')}</p>

<form name="filename" method="post" action={"admin/helptools"|ezurl}>
    <input type="text" name="filename"/>
    <input type="submit" name="findfilesearchbutton" value="{'search'|i18n('admin/helptools')}"/>
</form>

{if eq($formtype , 'findfile')}
    {if is_set( $objectname )}
    <p>{'This filename'|i18n('admin/helptools')}: {$filename} {'was found'|i18n('admin/helptools')}</p> 
        <ul>
            {if is_set($objectname) && is_set($contentobject_id) && is_set($node_id)}
                <li>{'name of the object'|i18n('admin/helptools')}: <a href={$urlAlias|ezurl()}>{$objectname}</a> </li>
                <li>{'object ID'|i18n('admin/helptools')}: {$contentobject_id}</li>
                <li>{'node ID'|i18n('admin/helptools')}: {$node_id}</li>
            {else}
                <p>{$errortrash}</p>
            {/if}
            <li>{'filename'|i18n('admin/helptools')}: {$filename}</li>
        </ul>
    {elseif is_set ($errormessage)}
        <p>{'This filename'|i18n('admin/helptools')}: {$errormessage} {'was not found'|i18n('admin/helptools')}</p>
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
    {if is_set( $objectname )}
    <p>{'This block ID'|i18n('admin/helptools')}: {$block_id} {'was found'|i18n('admin/helptools')}</p>
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
    {elseif is_set ($errormessage)}
         <p>{'This block ID'|i18n('admin/helptools')}: {$errormessage} {'was not found'|i18n('admin/helptools')}</p>
    {/if}
{/if}

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