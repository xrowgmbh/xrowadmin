{if fetch( 'user', 'has_access_to',
                      hash( 'module',   'admin',
                            'function', 'setowner') )} 
<script language="JavaScript1.2" type="text/javascript">
menuArray['SubitemsContextMenu']['elements']['submenu-admin-owner']= new Array();
menuArray['SubitemsContextMenu']['elements']['submenu-admin-owner']['url'] = {"/admin/setowner/%objectID%"|ezurl};
</script>
<hr/>
<a id="submenu-admin-owner" href="#" onmouseover="ezpopmenu_mouseOver( 'SubitemsContextMenu' )">{"Change Owner"|i18n("design/admin/popupmenu")}</a>
{/if}