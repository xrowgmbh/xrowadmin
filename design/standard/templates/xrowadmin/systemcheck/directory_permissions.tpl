<table width='100%'>
  	<tr>
  		<td style='border: 0pt' colspan='3'>
  		{if $result[2][persistent_data][result][value]}
  			{'No problems were found with the directories. Your current path is '|i18n( 'extension/admin' )} {$result[2][current_path]}
  		{else}
  			{'Problems were found with the directories. Your current path is '|i18n( 'extension/admin' )} {$result[2][current_path]}
  		{/if}
  		</td>
  	</tr>
  	<tr>
  		<td style='border: 0pt;'>Directory</td>
  		<td style='border: 0pt; vertical-align: top'>
  			Permission
  		</td>
  		<td width='5%' style='border: 0pt; vertical-align: top'>
  			&nbsp;
  		</td>
  	</tr>
  	{foreach $result[2][result_elements] as $result_elements}
  	<tr>
  		<td style='border: 0pt;'>{$result_elements[file]}</td>
  		<td style='border: 0pt; vertical-align: top'>
  			{$result_elements[permission]}
  			{if $result_elements[result]|eq(2)}
  				&nbsp;(unable to create unexistent dir)
  			{elseif $result_elements[result]|eq(3)}
  				&nbsp;(directory has wrong permissions)
  			{elseif $result_elements[result]|eq(4)}
  				&nbsp;(directory exists but it is a file)
  			{/if}
  		</td>
  		<td width='5%' style='border: 0pt; vertical-align: top'>
  			{if $result_elements[result]|eq(2, 3, 4)}
  				<img src={'icon_cancel.gif'|ezimage} alt="{'Warning.'|i18n( 'extension/admin' )}" title="{'Warning.'|i18n( 'extension/admin' )}" />
  			{else}
  				<img src={'icon_ok.gif'|ezimage} alt="{'Ok.'|i18n( 'extension/admin' )}" title="{'Ok.'|i18n( 'extension/admin' )}" />
  			{/if}
  		</td>
  	</tr>
  	{/foreach}
</table>