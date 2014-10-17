<table width='100%'>
  	<tr>
  		{if $result[2][result]}
  			<td style='border: 0pt' colspan='2'>
  				{"One or more image conversion capabilities was detected."|i18n( 'extension/admin' )}
  			</td>
  		{else}
  			<td style='border: 0pt'>
  				{"No image conversion capabilities was detected, this means that eZ Publish cannot scale any images or detect their type. This is vital functionality in eZ Publish and must be supported."|i18n( 'extension/admin' )}
  			</td>
  			<td width='5%' style='border: 0pt; vertical-align: top'>
  				<img src={'icon_cancel.gif'|ezimage} alt="{'Warning.'|i18n( 'extension/admin' )}" title="{'Warning.'|i18n( 'extension/admin' )}" />
  			</td>
  		{/if}
  	</tr>
  	{if $result[2][result]}
  		{foreach $result[2][test_results] as $test_results_item}
  			{if and( $test_results_item[1]|eq('imagemagick_program'), $test_results_item[2][result] )}
  				{def $filesystem_type=$test_results_item[2][filesystem_type]
  					$correct_path=$test_results_item[2][correct_path]}
  			{/if}
  		{/foreach}
  	
  		{foreach $result[2][persistence_list] as $persistence_item}
	  	<tr>
	  		<td style='border: 0pt'>
	  			{if $persistence_item[0]|eq('imagegd_extension')}
	  				{if $persistence_item[1][result][value]}
	  					{"The imagegd2 extension is available to eZ Publish."|i18n( 'extension/admin' )}
	  				{else}
	  					{"The imagegd2 extension is not available to eZ Publish."|i18n( 'extension/admin' )}
	  				{/if}
	  			{elseif $persistence_item[0]|eq('imagemagick_program')}
	  				{if $persistence_item[1][result][value]}
	  					{"The ImageMagick program is available to eZ Publish."|i18n( 'extension/admin' )}
	  					<br />
	  					{"File system type: "|i18n( 'extension/admin' )} {$filesystem_type}
	  					<br />
	  					{"Program path: "|i18n( 'extension/admin' )} {$correct_path}
	  				{else}
	  					{"The ImageMagick program is not available to eZ Publish."|i18n( 'extension/admin' )}
		  			{/if}
	  			{/if}
	  		</td>
	  		<td width='5%' style='border: 0pt; vertical-align: top'>
	  			{if $persistence_item[1][result][value]}
	  				<img src={'icon_ok.gif'|ezimage} alt="{'Ok.'|i18n( 'extension/admin' )}" title="{'Ok.'|i18n( 'extension/admin' )}" />
	  			{else}
	  				<img src={'icon_cancel.gif'|ezimage} alt="{'Warning.'|i18n( 'extension/admin' )}" title="{'Warning.'|i18n( 'extension/admin' )}" />
	  			{/if}
	  		</td>
	  	</tr>
	  	{/foreach}
	{/if}
</table>

{undef}