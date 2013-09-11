{set-block scope=root variable=subject}{'Notification: Images were missing and have been repaired.'|i18n('extension/xrowadmin')}{/set-block}
{'The following images were corrupt and have been repaired:'|i18n('extension/xrowadmin')}

<ul>
{foreach $repaired_objects as $key => $object}
	<li>{$key|inc()}: {fetch("content", "object", hash( "object_id", $object.obj_id )).name|wash()}({$object.lang})</li>
{/foreach}
</ul>