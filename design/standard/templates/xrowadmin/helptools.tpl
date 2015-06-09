<h1 class="context-title">{'file search'|i18n('admin/helptools')}</h1>

<div class="box-header"></div>

<p>{'Enter the file name to receive information. (example : b73edf37e8e85df9ad8a5c4fb4e1d144.mp4)'|i18n('admin/helptools')}</p>

<form name="filename" method="post" action={"admin/helptools"|ezurl}>
    <input type="text" name="filename"/>
    <input type="submit" name="findfilesearchbutton" value="{'search'|i18n('admin/helptools')}"/>
</form>

{if eq($formtype , 'findfile')}
    {if is_set( $objectname )} 
        <ul>
            <li>{'name of the object'|i18n('admin/helptools')} : <a href={$urlAlias|ezurl()}>{$objectname}</a> </li>
            <li>{'object ID'|i18n('admin/helptools')} : {$contentobject_id}</li>
            <li>{'node ID'|i18n('admin/helptools')} : {$node_id}</li>
            <li>{'filename'|i18n('admin/helptools')} : {$filename}</li>
        </ul>
    {/if}
    {if is_set ($errormessage)}
        <p>{$errormessage}</p>
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
    {if is_set ($errormessage)}
        <p>{$errormessage}</p>
    {/if}
{/if}



{if is_set( $x )}

    <div class="last10mod">
    
    <h1 class="context-title">{'Last 10 modified objects'|i18n('admin/helptools')}</h1>
    
    <div class="box-header"></div>
    
        {foreach $x as $count => $lastmodifiedobject}
        	<div class="lastmod-element">
            <span>{$count|inc()}:</span>
            <ul>
                <li>{'name of the object'|i18n('admin/helptools')} : <a href={$lastmodifiedobject.url|ezurl()}>{$lastmodifiedobject.name}</a> </li>
                <li>{'object ID'|i18n('admin/helptools')} : {$lastmodifiedobject.id}</li>
                <li>{'node ID'|i18n('admin/helptools')} : {$lastmodifiedobject.nodeId}</li>                
                <li>{'publisher'|i18n('admin/helptools')} : <a href={$lastmodifiedobject.publisherUrl|ezurl()}>{$lastmodifiedobject.publisher}</a></li>
                <li>{'modifier'|i18n('admin/helptools')} : <a href={$lastmodifiedobject.modifierUrl|ezurl()}>{$lastmodifiedobject.modifier}</a></li>
            </ul>
            {if is_set ($lastmodifiedobject.error)}
            	{$lastmodifiedobject.error}
            {/if}
            </div>
        {/foreach} 
    </div>
{/if}

{if is_set( $x )}

    <div class="last10pub">
    
    <h1 class="context-title">{'Last 10 published objects'|i18n('admin/helptools')}</h1>
    
    <div class="box-header"></div>
    
        {foreach $x as $count => $lastpublishedobject}
        	<div class="lastpub-element">
            <span>{$count|inc}:</span>
            {if is_set($lastpublishedobject.error)}
            	{$lastpublishedobject.error}
            {else}
	            <ul>
	                <li>{'name of the object'|i18n('admin/helptools')} : <a href={$lastpublishedobject.url|ezurl()}>{$lastpublishedobject.name}</a> </li>
	                <li>{'object ID'|i18n('admin/helptools')} : {$lastpublishedobject.id}</li>
	                <li>{'node ID'|i18n('admin/helptools')} : {$lastpublishedobject.nodeId}</li>
	                <li>{'publisher'|i18n('admin/helptools')} : <a href={$lastpublishedobject.publisherUrl|ezurl()}>{$lastpublishedobject.publisher}</a></li>
	                <li>{'modifier'|i18n('admin/helptools')} : <a href={$lastpublishedobject.modifierUrl|ezurl()}>{$lastpublishedobject.modifier}</a></li>
	            </ul>
            {/if}
            </div>
        {/foreach}
    </div>
{/if}
