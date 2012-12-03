{if fetch( 'user', 'has_access_to',
                      hash( 'module',   'admin',
                            'function', 'setowner') )} 
<script language="JavaScript1.2" type="text/javascript">
menuArray['ContextMenu']['elements']['menu-admin-owner']= new Array();
menuArray['ContextMenu']['elements']['menu-admin-owner']['url'] = {"/admin/setowner/%objectID%"|ezurl};
</script>
<hr/>
<a id="menu-admin-owner" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )">{"Change Owner"|i18n("design/admin/popupmenu")}</a>
{/if}