<table width='100%'>
  	<tr>
  		<td style='border: 0pt'>{"Needed PHP version: "|i18n( 'extension/admin' )}{$result[2].needed_version}</td>
  		<td rowspan='2' width='5%' style='border: 0pt; vertical-align: top'>
  			{if $result[2][persistent_data][result][value]}
  				<img src={'icon_ok.gif'|ezimage} alt="{'Ok.'|i18n( 'extension/admin' )}" title="{'Ok.'|i18n( 'extension/admin' )}" />
  			{else}
  				<img src={'icon_cancel.gif'|ezimage} alt="{'Warning.'|i18n( 'extension/admin' )}" title="{'Warning.'|i18n( 'extension/admin' )}" />
  			{/if}
  		</td>
  	</tr>
  	<tr>
  		<td style='border: 0pt'>{"Your PHP version: "|i18n( 'extension/admin' )}{$result[2].current_version}</td>
  	</tr>
</table>