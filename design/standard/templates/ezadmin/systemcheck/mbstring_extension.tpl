<table width='100%'>
  	<tr>
  		<td style='border: 0pt'>
  		{if $result[2][persistent_data][result][value]}
  			{"eZ Publish works with this option."|i18n( 'extension/admin' )}
  		{else}
  			{"eZ Publish comes with a good list of supported charsets by default, however they can be a bit slow due to being made in pure PHP code. Luckily eZ Publish supports the mbstring extension for handling some of the charsets."|i18n( 'extension/admin' )}
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