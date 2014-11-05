{if or( and( is_set( $error ), $error|ne( '' ) ), and( is_set( $result ), $result|count|gt( 0 ) ) )}
<pre style="{if and( is_set( $error ), $error|ne( '' ) )}color:red;{else}color:green;{/if} text-align: left">
{if and( is_set( $error ), $error|ne( '' ) )}
{'Error while executing.'|i18n( 'extension/admin' )}
{$error|wash()}
{elseif and( is_set( $result ), $result|count|gt( 0 ) )}
{'Successfully executed at'|i18n( 'extension/admin' )} <strong>{$solr_uri}</strong>
{/if}
</pre>
{/if}

<form action={"/admin/solrcheck"|ezurl} method="post" name="solrcheck" id="solrcheck">
<div class="context-block">
    <div class="box-header">
        <div class="box-tc">
            <div class="box-ml">
                <div class="box-mr">
                    <div class="box-tl">
                        <div class="box-tr">
                            <h1 class="context-title">{'SOLR Test'|i18n( 'extension/admin' )}</h1>
                            <div class="header-mainline"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-ml">
        <div class="box-mr">
            <div class="box-content">
                <div class="context-toolbar">
                    <div class="block">
                        <div class="break"></div>
                    </div>
                </div>
                {if and( is_set( $result ), $result|count|gt( 0 ) )}
                <p>
                    <table class="list" width="100%">
                       <tr>
                           <th colspan="2"><strong>Response</strong></th>
                       </tr>
                    {if $wt|eq( 'standard' )}
                        {foreach $result as $value sequence array( 'bglight', 'bgdark' ) as $sequence}
                        <tr class="{$sequence}">
                            <td valign="top" colspan="2">
                                {$value}
                            </td>
                        </tr>
                        {/foreach}
                    {else}
                        {if and( is_set( $result.response ), $result.response|count|gt( 0 ) )}
                        {foreach $result.response as $headline => $value sequence array( 'bglight', 'bgdark' ) as $sequence}
                        <tr class="{$sequence}">
                            <td valign="top">{$headline}</td>
                            <td valign="top">
                                {if is_array( $value )}
                                <table cellpadding="0" cellspacing="0" width="100%">
                                    {foreach $value as $value_headline => $value_item}
                                    <tr>
                                        <td valign="top">{$value_headline}</td>
                                        <td valign="top">
                                        {if is_array( $value_item )}
                                            <table cellspacing="2" cellpadding="0" width="100%">
                                                {foreach $value_item as $value_item_headline => $value_item_item}
                                                <tr>
                                                    <td valign="top" width="30%">{$value_item_headline}</td>
                                                    <td valign="top" width="70%">
                                                    {if is_array( $value_item_item )}
                                                        <table cellpadding="0" cellspacing="0" width="100%">
                                                            {foreach $value_item_item as $value_item_item_item}
                                                            <tr>
                                                                <td valign="top">
                                                                    {$value_item_item_item}
                                                                </td>
                                                            </tr>
                                                            {/foreach}
                                                        </table>
                                                    {else}
                                                        {$value_item_item}
                                                    {/if}
                                                    </td>
                                                </tr>
                                                {/foreach}
                                            </table>
                                        {else}
                                            {$value_item}
                                        {/if}
                                        </td>
                                    </tr>
                                    {/foreach}
                                </table>
                                {else}
                                    {$value}
                                {/if}
                            </td>
                        </tr>
                        {/foreach}
                        {/if}
                        {if and( is_set( $result.responseHeader ), $result.responseHeader|count|gt( 0 ) )}
                        <tr>
                           <th colspan="2"><strong>ResponseHeader</strong></th>
                        </tr>
                        {foreach $result.responseHeader as $headline => $value sequence array( 'bglight', 'bgdark' ) as $sequence}
                        <tr class="{$sequence}">
                            <td valign="top">{$headline}</td>
                            <td valign="top">
                                {if is_array( $value )}
                                <table cellpadding="0" cellspacing="0" width="100%">
                                    {foreach $value as $value_headline => $value_item}
                                    <tr>
                                        <td valign="top">{$value_headline}</td>
                                        <td valign="top">
                                        {if is_array( $value_item )}
                                            <table cellspacing="2" cellpadding="0" width="100%">
                                                {foreach $value_item as $value_item_headline => $value_item_item}
                                                <tr>
                                                    <td valign="top">{$value_item_headline}</td>
                                                    <td valign="top">
                                                    {if is_array( $value_item_item )}
                                                        <table cellpadding="0" cellspacing="0" width="100%">
                                                            {foreach $value_item_item as $value_item_item_item}
                                                            <tr>
                                                                <td valign="top">
                                                                    {$value_item_item_item}
                                                                </td>
                                                            </tr>
                                                            {/foreach}
                                                        </table>
                                                    {else}
                                                        {$value_item_item}
                                                    {/if}
                                                    </td>
                                                </tr>
                                                {/foreach}
                                            </table>
                                        {else}
                                            {$value_item}
                                        {/if}
                                        </td>
                                    </tr>
                                    {/foreach}
                                </table>
                                {else}
                                    {$value}
                                {/if}
                            </td>
                        </tr>
                        {/foreach}
                        {/if}
                        {if and( is_set( $result.highlighting ), $result.highlighting|count|gt( 0 ) )}
                        <tr>
                           <th colspan="2"><strong>Highlighting</strong></th>
                        </tr>
                        {foreach $result.highlighting as $headline => $value sequence array( 'bglight', 'bgdark' ) as $sequence}
                        <tr class="{$sequence}">
                            <td valign="top" colspan="2">{$headline}</td>
                        </tr>
                        <tr>
                            <td valign="top" width="5%">&nbsp;</td>
                            <td>
                                {if is_array( $value )}
                                    {foreach $value as $value_headline => $value_item}
                                       <table cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td valign="top" width="15%">{$value_headline}</td>
                                                <td>
                                                {if is_array( $value_item )}
                                                    {foreach $value_item as $value_item_headline => $value_item_item}
                                                      <table cellspacing="2" cellpadding="0" width="100%">
                                                            <tr>
                                                                <td valign="top" width="15%">{$value_item_headline}</td>
                                                                <td valign="top">
                                                                {if is_array( $value_item_item )}
                                                                    <table cellpadding="0" cellspacing="0" width="100%">
                                                                        {foreach $value_item_item as $value_item_item_item}
                                                                        <tr>
                                                                            <td valign="top">
                                                                                {$value_item_item_item}
                                                                            </td>
                                                                        </tr>
                                                                        {/foreach}
                                                                    </table>
                                                                {else}
                                                                    {$value_item_item}
                                                                {/if}
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    {/foreach}
                                                {else}
                                                    {$value_item}
                                                {/if}
                                                </td>
                                            </tr>
                                        </table>
                                    {/foreach}
                                {else}
                                    {$value}
                                {/if}
                            </td>
                        </tr>
                        {/foreach}
                        {/if}
                        {/if}
                    </table>
                </p>
                {/if}
                {ezscript_require( 'ezjsc::jquery' )}
                <table class="list">
                    <tr class="bglight">
                        <td><strong>{'Here you can enter your SOLR query out from "Debug: Final query parameters sent to Solr backend"'|i18n( 'extension/admin' )}</strong></td>
                    </tr>
                    <tr>
                        <td>
                            <label>SOLR Data (<span id="solr_example">example</span>):</label>
                            <textarea style="width: 99%;" name="data" cols="5" rows="10">{if is_set( $data )}{$data|wash()}{/if}</textarea>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="controlbar">
        <div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
            <div class="block">
                <input class="button" name="Run" type="submit" value="Run" />
                <input class="button" type="submit" name="Cancel" value="Cancel" />
            </div>
        </div></div></div></div></div></div>
    </div>
</div>
</form>

<div id="tooltip" style="display: none; background-color: #FFFFFF; color: #3A3939; font-size: 12px; line-height: 18px; position: absolute; text-align: center; z-index: 200;"></div>
<div id="example_text" style="width: 450px; display: none; text-align: left; border: 1px solid #A5ACB2; padding: 5px">
{literal}
array(21)&nbsp;{<br>
&nbsp;&nbsp;["q"]=><br>
&nbsp;&nbsp;string(11) "Basischarts"<br>
&nbsp;&nbsp;["qf"]=><br>
&nbsp;&nbsp;string(687) "attr_alternatetitle_t attr_body_t attr_keywords_lk attr_maintext_t attr_name_t attr_tags_lk attr_teasertext_t attr_text_t attr_title_t attr_url_t meta_name_t"<br>
&nbsp;&nbsp;["qt"]=><br>
&nbsp;&nbsp;string(9) "ezpublish"<br>
&nbsp;&nbsp;["start"]=><br>
&nbsp;&nbsp;int(0)<br>
...<br>
}
{/literal}
</div>
<script>
{literal}
jQuery(document).ready(function($) {
    $('#solr_example').bind('mouseover', function(e){
        var page_left = e.pageX+5,
            page_top = e.pageY-100;

        $("#tooltip").css( { "left": page_left + "px", "top": page_top + "px" } );
        $('#tooltip').html( $('#example_text').show() );
        $('#tooltip').show();
    });
    $('#solr_example').bind('mouseleave', function(e){
        $('#tooltip').hide();
    });
});
{/literal}
</script>