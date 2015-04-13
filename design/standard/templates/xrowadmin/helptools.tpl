<h1 class="context-title">{'file search'|i18n('admin/helptools')}</h1>

<div class="box-header"></div>

<p>{'With this file search you can return the object ID and node ID. You will receive a link to the file location.'|i18n('admin/helptools')}</p>

<form name="filename" method="post" action={"admin/helptools"|ezurl}>
    <input type="text" name="filename"/>
    <input type="submit" name="findfilesearchbutton" value="{'search'|i18n('admin/helptools')}"/>
</form>

{if is_set( $filename )} 
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