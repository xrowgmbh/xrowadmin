<form name="contentserver" method="post" action={'admin/systemcheck'|ezurl}>
<div class="context-block">
    <div class="box-header">
        <div class="box-tc">
            <div class="box-ml">
                <div class="box-mr">
                    <div class="box-tl">
                        <div class="box-tr">
                            <h1 class="context-title">{'Check system'|i18n( 'extension/admin' )}</h1>
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

{if or( $phptest[results]|is_array(), is_set( $customtest ) )}
    {if $phptest[results]|is_array()}
        {def $phptestcount=$phptest[results]|count()}
        {if $phptestcount gt 0}
            <table class="list">
                <tr>
                    <th colspan="2">{$phptest[testtitle]}</th>
                </tr>
            {foreach $phptest[results] as $result}
                <tr>
                    <td style='vertical-align: top' width="150"><b><span style='position: relative; top: 5px'>{$phptest[headlines][$result[1]]}</span></b></td>
                    <td style='vertical-align: top'>{include uri=concat('design:ezadmin/systemcheck/',$result[1],'.tpl')}</td>
                </tr>
            {/foreach}
            </table>
        {/if}
    {/if}

    {if is_set( $customtests )}
        {def $customtestcount=$customtests|count()}
        {if $customtestcount gt 0}
            {foreach $customtests as $customtest}
                <table class="list">
                    <tr>
                        <th colspan="2">{$customtest[testtitle]}</th>
                    </tr>
                {foreach $customtest as $customresultkey => $customresult}
                    <tr>
                        <td style='vertical-align: top' width="150"><b><span style='position: relative; top: 5px'>{$customtest[headlines][$customresultkey]}</span></b></td>
                        <td style='vertical-align: top'>
                        {if and( $customresultkey|ne('headlines'), $customresultkey|ne('testtitle') )}
                            {include uri=concat('design:ezadmin/systemcheck/custom_',$customresultkey,'.tpl')}
                        {/if}
                        </td>
                    </tr>
                {/foreach}
                </table>
            {/foreach}
        {/if}
    {/if}
            </div>
        </div>
    </div>

    <div class="controlbar">
        <div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
            <div class="block"></div>
        </div></div></div></div></div></div>
    </div>
{else}
                <table class="list">
                    <tr class="bglight">
                        <td><strong>{'To show you the system setup push the "Run" button'|i18n( 'extension/admin' )}</strong></td>
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
{/if}
</div>

</form>
{undef}
<div class="break"></div>