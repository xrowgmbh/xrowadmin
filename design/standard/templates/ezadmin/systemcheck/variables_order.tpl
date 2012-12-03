<table width='100%'>
  	<tr>
  		<td style='border: 0pt'>
  		{if $result[2][persistent_data][result][value]}
  			{"PHP is currently configured to register enviroment variables in the global variable $_ENV."|i18n( 'extension/admin' )}
  		{else}
  			{"PHP is currently not configured to register enviroment variables in the global variable $_ENV."|i18n( 'extension/admin' )}
  		{/if}
  		</td>
  		<td rowspan='2' width='5%' style='border: 0pt; vertical-align: top'>
  			{if $result[2][persistent_data][result][value]}
  				<img src={'icon_ok.gif'|ezimage} alt="{'Ok.'|i18n( 'extension/admin' )}" title="{'Ok.'|i18n( 'extension/admin' )}" />
  			{else}
  				<img src={'icon_cancel.gif'|ezimage} alt="{'Warning.'|i18n( 'extension/admin' )}" title="{'Warning.'|i18n( 'extension/admin' )}" />
  			{/if}
  		</td>
  	</tr>
</table>