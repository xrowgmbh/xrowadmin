<table width='100%'>
  	<tr>
  		<td style='border: 0pt' colspan='2'>
  		{if $result[2][persistent_data][result][value]}
  			{"No problems were found with the file upload."|i18n( 'extension/admin' )}
  		{else}
  			{"Problems were found with the file upload."|i18n( 'extension/admin' )}
  		{/if}
  		</td>
  	</tr>
  	{if $result[2][php_upload_dir]}
  	<tr>
  		<td style='border: 0pt;'>
  			{if $result[2][upload_dir_exists]}
  				{"The PHP upload directory:"|i18n( 'extension/admin' )} {$result[2][php_upload_dir]}
  			{else}
  				{"The PHP upload directory"|i18n( 'extension/admin' )} {$result[2][php_upload_dir]} {"does not exists or is not accessible, without this you will not be able to upload files or images to eZ Publish."|i18n( 'extension/admin' )}
  			{/if}
  		</td>
  		<td width='5%' style='border: 0pt; vertical-align: top'>
  			{if $result[2][upload_dir_exists]}
  				<img src={'icon_ok.gif'|ezimage} alt="{'Ok.'|i18n( 'extension/admin' )}" title="{'Ok.'|i18n( 'extension/admin' )}" />
  			{else}
  				<img src={'icon_cancel.gif'|ezimage} alt="{'Warning.'|i18n( 'extension/admin' )}" title="{'Warning.'|i18n( 'extension/admin' )}" />
  			{/if}
  		</td>
  	</tr>
  	{/if}
  	<tr>
  		<td style='border: 0pt;'>
  			{if $result[2][php_upload_is_enabled]}
  				{"The file upload is enabled."|i18n( 'extension/admin' )}
  			{else}
  				{"The file upload is disabled."|i18n( 'extension/admin' )}
  			{/if}
  		</td>
  		<td width='5%' style='border: 0pt; vertical-align: top'>
  			{if $result[2][php_upload_is_enabled]}
  				<img src={'icon_ok.gif'|ezimage} alt="{'Ok.'|i18n( 'extension/admin' )}" title="{'Ok.'|i18n( 'extension/admin' )}" />
  			{else}
  				<img src={'icon_cancel.gif'|ezimage} alt="{'Warning.'|i18n( 'extension/admin' )}" title="{'Warning.'|i18n( 'extension/admin' )}" />
  			{/if}
  		</td>
  	</tr>
  	<tr>
  		<td style='border: 0pt;'>
  			{if $result[2][upload_dir_writeable]}
  				{"The PHP upload directory is writable."|i18n( 'extension/admin' )}
  			{else}
  				{"The PHP upload directory is not writable."|i18n( 'extension/admin' )}
  			{/if}
  		</td>
  		<td width='5%' style='border: 0pt; vertical-align: top'>
  			{if $result[2][upload_dir_writeable]}
  				<img src={'icon_ok.gif'|ezimage} alt="{'Ok.'|i18n( 'extension/admin' )}" title="{'Ok.'|i18n( 'extension/admin' )}" />
  			{else}
  				<img src={'icon_cancel.gif'|ezimage} alt="{'Warning.'|i18n( 'extension/admin' )}" title="{'Warning.'|i18n( 'extension/admin' )}" />
  			{/if}
  		</td>
  	</tr>
</table>