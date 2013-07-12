<form action={"/admin/migration"|ezurl} method="post" name="migration" id="migration">
    <p>{'Please provide input data.'|i18n("admin/migration")}</p>
    <label>{'Source ID'|i18n("admin/migration")}: 
        <input onchange="jQuery('#migration').xrowadmin( 'showNodeName', {literal}{'type':'source'}{/literal} );" name="source_id" id="source_id" type="text" value="" />
        <span id="source_name"></span>
    </label>
    <label>{'Operation'|i18n("admin/migration")}: 
        <select name="operation">
            <option value="1" selected="selected">{'Move Subtree'|i18n("admin/migration")}</option>
            <option value="2">{'Move Children'|i18n("admin/migration")}</option>
            <option value="3">{'Swap Nodes'|i18n("admin/migration")}</option>
        </select>
    </label>
    <label>{'Target ID'|i18n("admin/migration")}: 
        <input onchange="jQuery('#migration').xrowadmin( 'showNodeName', {literal}{'type':'target'}{/literal} );" name="target_id" id="target_id" type="text" value="" />
        <span id="target_name"></span>
    </label>
    <p>
        <input class="button" type="submit" name="Execute" value={'Execute'|i18n("admin/migration")} />
    </p>
</form>
{if is_set($error)}
<div class="message-error">
    <ul>
        <li>{'ERROR'|i18n("admin/migration")}:: {$operation|i18n("admin/migration")}</li>
    </ul>
</div>
{elseif is_set($success)}
<div class="message-feedback">
    <ul>
       <li>{'SUCCESS'|i18n("admin/migration")}:: {$operation|i18n("admin/migration")}</li>
    </ul>
</div>
{/if}