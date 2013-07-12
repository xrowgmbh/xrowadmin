{section show=$Output}

<pre style="color:green;">
{$Output|wash()}
</pre>

{/section}
<form name="contentserver" method="post" action={'admin/mailtest'|ezurl}>
<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Test mail'|i18n( 'extension/admin' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-toolbar">

    <div class="block">
        <div class="break"></div>
        <p>{'Test webmail client'|i18n( 'extension/admin' )}</p>
    </div>
    
</div>
<div class="block">
<div>
    <select name="transporttype">
        <option value="ezcMail">ezcMail</option>
        <option value="eZMail">eZMail</option>
    </select>
</div>
</div>

<table class="list">
  <tr class="bglight">
    <th scope="row">{'Receiver address'|i18n( 'extension/admin' )}</th>
    <td>
        <input name="To" type="text" value="{$To}" />
    </td>
  </tr>
</table>


{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
    <input class="button" name="Run" type="submit" value="Run" />
    <input class="button" type="submit" name="Cancel" value="Cancel" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
