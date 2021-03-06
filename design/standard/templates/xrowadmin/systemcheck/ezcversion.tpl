{def $required_version = $result[2].needed_version
     $class_exists = $result[2].class_exists}

<table width='100%'>
  	<tr>
  		<td style='border: 0pt'>
  		{if $result[2][persistent_data][result][value]}
  			{"No problems were found with the eZ Components version"|i18n( 'extension/admin' )} {$required_version}.
  		{else}
  			{if $class_exists}
			  {"Wrong eZ Components version detected"|i18n( 'extension/admin' )}
			{else}
			  {"Missing eZ Components dependancy"|i18n( 'extension/admin' )}
			{/if}
  		{/if}
  		</td>
  		<td width='5%' style='border: 0pt; vertical-align: top'>
  			{if $result[2][persistent_data][result][value]}
  				<img src={'icon_ok.gif'|ezimage} alt="{'Ok.'|i18n( 'extension/admin' )}" title="{'Ok.'|i18n( 'extension/admin' )}" />
  			{else}
  				<img src={'icon_cancel.gif'|ezimage} alt="{'Warning.'|i18n( 'extension/admin' )}" title="{'Warning.'|i18n( 'extension/admin' )}" />
  			{/if}
  		</td>
  	</tr>
</table>
{undef}