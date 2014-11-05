<table width='100%'>
  	<tr>
  		<td style='border: 0pt'>
  		{if $result[2][result]}
  			{"No problems were found with the time zone."|i18n( 'extension/admin' )}
  		{else}
  			{"You are using the default time zone, UTC. It is important that you set your time zone to make sure date and time is handled correctly."|i18n( 'extension/admin' )}
  		{/if}
  		</td>
  		<td width='5%' style='border: 0pt; vertical-align: top'>
  			{if $result[2][result]}
  				<img src={'icon_ok.gif'|ezimage} alt="{'Ok.'|i18n( 'extension/admin' )}" title="{'Ok.'|i18n( 'extension/admin' )}" />
  			{else}
  				<img src={'icon_cancel.gif'|ezimage} alt="{'Warning.'|i18n( 'extension/admin' )}" title="{'Warning.'|i18n( 'extension/admin' )}" />
  			{/if}
  		</td>
  	</tr>
</table>