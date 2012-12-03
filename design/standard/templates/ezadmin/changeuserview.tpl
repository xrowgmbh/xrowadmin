<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'User change'|i18n( 'design/admin/shop/productsoverview' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-toolbar">
<div class="block">
<div class="left">
    <p>
    {switch match=$limit}

        {case match=25}
        <a href={'/user/preferences/set/changeuser_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/shop/productsoverview' )}">10</a>
        <span class="current">25</span>
        <a href={'/user/preferences/set/changeuser_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/shop/productsoverview' )}">50</a>
        {/case}

        {case match=50}
        <a href={'/user/preferences/set/changeuser_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/shop/productsoverview' )}">10</a>
        <a href={'/user/preferences/set/changeuser_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/shop/productsoverview' )}">25</a>
        <span class="current">50</span>
        {/case}

        {case}
        <span class="current">10</span>
        <a href={'/user/preferences/set/changeuser_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/shop/productsoverview' )}">25</a>
        <a href={'/user/preferences/set/changeuser_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/shop/productsoverview' )}">50</a>
        {/case}

        {/switch}
    </p>
</div>
<div class="break"></div>
</div>
</div>
{def $siteaccesses=ezini( 'SiteAccessSettings','AvailableSiteAccessList','site.ini')}

{if $search_text}
    {def $search=fetch(content,search,
                      hash(text,$search_text,
                           subtree_array, array(1),
                           class_id,$ids,
                           offset,$view_parameters.offset,
                           limit,$limit))}
    {def $list=$search['SearchResult']}
    {def $list_count=$search['SearchCount']}
    {def $stop_word_array=$search['StopWordArray']}
    {def $search_data=$search}
{else} 
{def $list=fetch( 'content','tree', hash( 'parent_node_id', 1,
             'sort_by', array( array( name, false() ) ),
             'class_filter_type',  'include',
             'class_filter_array', $identifiers,
             'main_node_only', true() ,
             'offset', $view_parameters.offset,
             'limit', $limit,
             'ignore_visibility', true() ) )
}
{def $list_count=fetch( 'content','tree_count', hash( 'parent_node_id', 1,
             'sort_by', array( array( name, false() ) ),
             'class_filter_type',  'include',
             'class_filter_array', $identifiers,
             'main_node_only', true() ,
             'offset', $view_parameters.offset,
             'limit', $limit,
             'ignore_visibility', true() ) )
}
{/if}
{section show=$recall}
<a href={concat( "admin/recalluser/")|ezurl} >Recall original user.</a>
{/section}

<table class="list">
<tr>
<th>Object Name</th>
<th></th>
</tr>
{foreach $list as $node sequence array( 'bglight', 'bgdark' ) as $sequence}
<tr class="{$sequence}">
<td>
{node_view_gui view=line content_node=$node.object.main_node}
</td>
<td>
<form name="user_{$node.object.id}"action={'admin/changeuserview'|ezurl} method="post">

<select name="SiteAccess">
{foreach $siteaccesses as $siteaccess}
<option value="{$siteaccess}" {if $siteaccess|eq($current_siteaccess)}selected='selected'{/if} >{$siteaccess}</option>
{/foreach}
</select>
<input class="button" type="button" onclick="user_{$node.object.id}.submit();return false;" name="LoginButton" value="{'Login'|i18n( 'design/admin/shop/productsoverview' )}" title="{'Login with this user.'|i18n( 'design/admin/shop/productsoverview' )}" />
<input type="hidden" value="{$node.object.id}" name="ObjectID" />

</form>
</td>
</tr>
{/foreach}
</table>

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat( '/admin/changeuserview' )
         item_count=$list_count
         view_parameters=$view_parameters
         item_limit=$limit}
</div>

{* DESIGN: Content END *}</div></div></div>

{* Button bar for filter and sorting. *}
<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<form action={'admin/changeuserview'|ezurl} method="post">
<div class="block">


        <div class="right">
            <label>{'Search'|i18n( 'design/admin/shop/productsoverview' )}:</label>

            <input type="text" name="search_text" id="search_text" value="{$search_text}" title="{'Your search string'|i18n( 'design/admin/shop/productsoverview' )}" />

            {* Sort button *}
            <input class="button" type="submit" name="SearchButton" value="{'Search users'|i18n( 'design/admin/shop/productsoverview' )}" title="{'Sort products.'|i18n( 'design/admin/shop/productsoverview' )}" />
        </div>


    <div class="break"></div>
</div>

<input type="hidden" name="Offset" value="{$view_parameters.offset}" />

</form>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>
</div>
