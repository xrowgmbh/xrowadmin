<?php /* #?ini charset="utf-8"?
[CronjobSettings]
ScriptDirectories[]=extension/xrowadmin/bin
ExtensionDirectories[]=session_cleanup
Scripts[]=session_cleanup.php

[CronjobPart-fixscript]
Scripts[]=fixscript.php

[CronjobPart-backup]
Scripts[]=ezbackup.php

[CronjobPart-session_cleanup]
Scripts[]
Scripts[]=session_cleanup.php

[CronjobPart-directorycleanup]
Scripts[]=directory_cleanup.php

[CronjobPart-checkimages]
Scripts[]=image_existance.php

*/ ?>