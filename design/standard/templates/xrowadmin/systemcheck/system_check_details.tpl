{def has_warnings=false()}

  <form method="post" action="{$script}">

{*section loop=$test.results}
{section-exclude match=true()}
{section-include match=is_set($:item.2.warnings)}
 {set has_warnings=true()}
{/section}

{section show=$has_warnings}
<div class="warning">
{section name=Result loop=$test.results}
{section-exclude match=true()}
{section-include match=is_set($Result:item.2.warnings)}
<h2>Warning</h2>
<ul>
 {section name=Warning loop=$Result:item.2.warnings}
  {section show=is_array($:item.text)}
 <li>{$:item.name}


{section-else*}
<table>
	<tr>
		<td>{'Title'|i18n( 'extension/admin' )}</td>
		<td>{'Status'|i18n( 'extension/admin' )}</td>
	</tr>
</table>
{foreach $test.results as $result}
	<li></li>
  <h1>{"System check"|i18n("design/standard/setup/init")}</h1>
  <p>
{"There are some important issues that have to be resolved. A list of issues / problems is presented below. Each section contains a description and a suggested / recommended solution."|i18n("design/standard/setup/init")}
</p><p>
{"Once the problems / issues are fixed, you may click the <i>Next</i> button to continue. The system check will be run again. If everything is okay, the setup will go to the next stage. If there are problems, the system check page will reappear."|i18n("design/standard/setup/init")}
</p><p>
{"Some issues may be ignored by checking the <i>Ignore this test</i> checkbox(es); however, this is not recommended."|i18n("design/standard/setup/init")}
</p>
{section show=eq( $optional_test.result, 2 )}
<p>
{"It is also possible to do some finetuning of your system, click <i>Finetune</i> instead <i>Next</i> if you want to see the finetuning hints."|i18n("design/standard/setup/init")}
</p>
{/section}

  <h1>{"Issues"|i18n("design/standard/setup/init")}</h1>
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
  {section name=Result loop=$test.results}
  {section-exclude match=$:item[0]|ne(2)}
  <tr>
    <td>{include uri=concat('design:xrowadmin/setup/tests/',$:item[1],'_error.tpl') test_result=$:item result_number=$:number}</td>
  </tr>
  <tr>
    <td><input type="checkbox" name="{$:item[1]}_Ignore" id="ignore_test_{$:item[1]}" value="1" /><label class="checkbox" for="ignore_test_{$:item[1]}">{"Ignore this test"|i18n("design/standard/setup/init")}</label>
    </td>
  </tr>

  {delimiter}
  <tr><td>&nbsp;</td></tr>
  {/delimiter}

  {/section}
  </table>
  </form>

{/section}
{undef}
