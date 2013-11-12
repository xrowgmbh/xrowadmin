{set-block scope=root variable=subject}{'Notification: Images were missing and have been repaired.'|i18n('extension/xrowadmin')}{/set-block}
{'The following images were corrupt and have been repaired:'|i18n('extension/xrowadmin')}

<ul>
{foreach $repaired_objects as $key => $object}
	<li>{$key|inc()}: {fetch("content", "object", hash( "object_id", $object.obj_id )).name|wash()}({$object.lang} / {$object.attr_id}) - ObjectID: {$object.obj_id} on image: {$object.path|wash()}</li>
{/foreach}
</ul>