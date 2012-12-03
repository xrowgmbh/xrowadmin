{if and(is_set($error),$error|eq(1))}
	{'Could not fetch Node, please doublecheck the ID.'|i18n("admin/migration")}
{elseif is_set($error)}
	{'An error has occured, please check your permissions.'|i18n("admin/migration")}
{else}
	<a href={$auto_url|ezurl()} title="{$auto_name|wash()}">{$auto_name|wash()}</a>
{/if}