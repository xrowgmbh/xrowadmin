<?php /* #?ini charset="utf-8"?
[SystemCheckSettings]
PHPTest=enabled

# enable or disable individual checks
[PHPTestSettings]
PHPTests[]=phpversion
PHPTests[]=variables_order
PHPTests[]=php_session
PHPTests[]=directory_permissions
PHPTests[]=settings_permission
PHPTests[]=database_extensions
PHPTests[]=php_magicquotes
PHPTests[]=magic_quotes_runtime
PHPTests[]=php_register_globals
PHPTests[]=mbstring_extension
PHPTests[]=curl_extension
PHPTests[]=zlib_extension
PHPTests[]=dom_extension
PHPTests[]=iconv_extension
PHPTests[]=file_upload
PHPTests[]=open_basedir
PHPTests[]=safe_mode
PHPTests[]=image_conversion
PHPTests[]=texttoimage_functions
PHPTests[]=memory_limit
PHPTests[]=execution_time
PHPTests[]=allow_url_fopen
PHPTests[]=accept_path_info
PHPTests[]=timezone
PHPTests[]=ezcversion

# if you would like to create a custom system check
# define a name for the check class and create a section
# with this class name e.g. class/checkurltest.php
#[SystemCheckSettings]
#CustomTests[]=checkUrlTest
# 
# here is the section for the new custom class with all
# variables you need for the example class class/checkurltest.php
#[checkUrlTest]
#CustomHandlerName=checkUrlTest
#CustomTestTitle=Check url
#CustomTests[]=check_url
*/ ?>