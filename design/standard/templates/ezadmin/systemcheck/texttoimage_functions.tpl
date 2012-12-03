<table width='100%'>
  	<tr>
  		{if $result[2][persistent_data][result]}
  		<td style='border: 0pt' colspan='2'>
  			{"The PHP functions were found: "|i18n( 'extension/admin' )}
  		</td>
  		{else}
  		<td style='border: 0pt'>
  			{"The PHP functions ImageTTFText and ImageTTFBBox are missing. Without these functions it is not possible to use the texttoimage template operator."|i18n( 'extension/admin' )}
  		</td>
  		<td width='5%' style='border: 0pt; vertical-align: top'>
  			<img src={'icon_cancel.gif'|ezimage} alt="{'Warning.'|i18n( 'extension/admin' )}" title="{'Warning.'|i18n( 'extension/admin' )}" />
  		</td>
  		{/if}
  	</tr>
  	{if $result[2][persistent_data][result]}
  		{if $result[2][found_extensions]|is_array}
  			{def $count_found_extensions=$result[2][found_extensions]|count()}
  			{if $count_found_extensions|gt( 0 )}
  				{foreach $result[2][found_extensions] as $found_extensions}
			  	<tr>
			  		<td>
			  		{if $found_extensions|eq('imagettftext')}
			  			ImageTTFText
			  		{/if}
			  		{if $found_extensions|eq('imagettfbbox')}
			  			ImageTTFBBox
			  		{/if}
			  		</td>
			  		<td width='5%' style='border: 0pt; vertical-align: top'>
			  			<img src={'icon_ok.gif'|ezimage} alt="{'Ok.'|i18n( 'extension/admin' )}" title="{'Ok.'|i18n( 'extension/admin' )}" />
			  		</td>
			  	</tr>
  				{/foreach}
  			{/if}
  		{/if}
		{if $result[2][failed_extensions]|is_array}
  			{def $count_failed_extensions=$result[2][failed_extensions]|count()}
  			{if $count_failed_extensions|gt( 0 )}
  				{foreach $result[2][failed_extensions] as $failed_extensions}
			  	<tr>
			  		<td>
			  		{if $failed_extensions|eq('imagettftext')}
			  			ImageTTFText
			  		{/if}
			  		{if $failed_extensions|eq('imagettfbbox')}
			  			ImageTTFBBox
			  		{/if}
			  		</td>
			  		<td width='5%' style='border: 0pt; vertical-align: top'>
			  			<img src={'icon_cancel.gif'|ezimage} alt="{'Warning.'|i18n( 'extension/admin' )}" title="{'Warning.'|i18n( 'extension/admin' )}" />
			  		</td>
			  	</tr>
  				{/foreach}
  			{/if}
  		{/if}  	
  	{/if}
</table>