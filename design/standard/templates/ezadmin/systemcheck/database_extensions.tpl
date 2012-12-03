<table width='100%'>
  	<tr>
  		<td style='border: 0pt' colspan='2'>
  		{if $result[2][persistent_data][result][value]}
  			{"Supported database handlers were found."|i18n( 'extension/admin' )}
  		{else}
  			{"No supported database handlers were found."|i18n( 'extension/admin' )}
  		{/if}
  		</td>
  	</tr>
  	{foreach $result[2][found_extensions] as $found_extensions}
  	<tr>
  		<td style='border: 0pt'>{$found_extensions}</td>
  		<td width='5%' style='border: 0pt; vertical-align: top'>
  			<img src={'icon_ok.gif'|ezimage} alt="{'Ok.'|i18n( 'extension/admin' )}" title="{'Ok.'|i18n( 'extension/admin' )}" />
  		</td>
  	</tr>
  	{/foreach}
  	{foreach $result[2][failed_extensions] as $failed_extensions}
  	<tr>
  		<td style='border: 0pt'>{$failed_extensions}</td>
  		<td width='5%' style='border: 0pt; vertical-align: top'>
  			<img src={'icon_cancel.gif'|ezimage} alt="{'Warning.'|i18n( 'extension/admin' )}" title="{'Warning.'|i18n( 'extension/admin' )}" />
  		</td>
  	</tr>
  	{/foreach}
</table>