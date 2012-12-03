<table width='100%'>
  	<tr>
  		<td style='border: 0pt'>
  		{if $result[2][result]}
  			{"PHP safe mode is disabled."|i18n( 'extension/admin' )}
  		{else}
			{"PHP safe mode is enabled. eZ Publish may work with safe mode on, however there might be several features that will be unavailable."|i18n( 'extension/admin' )}
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