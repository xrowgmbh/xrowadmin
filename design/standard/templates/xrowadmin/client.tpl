{section show=$Output}

<pre style="color:green;">
{$Output|wash()}
</pre>

{/section}
<form name="contentserver" method="post" action={'admin/client'|ezurl}>
<div class="context-block">
    <div class="box-header">
        <div class="box-tc">
            <div class="box-ml">
                <div class="box-mr">
                    <div class="box-tl">
                        <div class="box-tr">
                            <h1 class="context-title">{'SOAP test webclient'|i18n( 'extension/admin' )}</h1>
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
                        <p>{'SOAP test webclient'|i18n( 'extension/admin' )}</p>
                    </div>
                </div>
                <table class="list">
                  <tr class="bglight">
                    <th scope="row">{'Server'|i18n( 'extension/admin' )}</th>
                    <td>
                        <input name="Server" type="text" value="{$Server|wash()}" size="40" />
                    </td>
                  </tr>
                  <tr class="bgdark">
                    <th scope="row">{'Port'|i18n( 'extension/admin' )}</th>
                    <td>
                        <input class="half" name="Port" type="text" value="{$Port|wash()}" size="40" />
                    </td>
                  </tr>
                  <tr class="bglight">
                    <th scope="row">{'Username'|i18n( 'extension/admin' )}</th>
                    <td>
                        <input name="Username" type="text" value="{$Username}" size="40" />
                    </td>
                  </tr>
                  <tr class="bgdark">
                    <th scope="row">{'Password'|i18n( 'extension/admin' )}</th>
                    <td>
                        <input name="Password" type="password" value="{$Password}" size="40" />
                    </td>
                  </tr>
                  <tr class="bglight">
                    <th scope="row">{'Function'|i18n( 'extension/admin' )}</th>
                    <td>
                        <input name="Function" type="text" value="{$Function|wash()}" size="40" />
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