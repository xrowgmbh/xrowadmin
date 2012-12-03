<table width='100%'>
  	<tr>
  		<td style='border: 0pt'>
  		{if $customresult[result]}
  			{$customresult[true_text]|i18n( 'extension/admin' )}
  		{else}
  			{$customresult[false_text]|i18n( 'extension/admin' )}
  		{/if}
  		</td>
  		<td width='5%' style='border: 0pt; vertical-align: top'>
  			{if $customresult[result]}
  				<img src={'icon_ok.gif'|ezimage} alt="{'Ok.'|i18n( 'extension/admin' )}" title="{'Ok.'|i18n( 'extension/admin' )}" />
  			{else}
  				<img src={'icon_cancel.gif'|ezimage} alt="{'Warning.'|i18n( 'extension/admin' )}" title="{'Warning.'|i18n( 'extension/admin' )}" />
  			{/if}
  		</td>
  	</tr>
</table>