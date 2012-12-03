<?php
/**
 * File info.php
 *
 * @package ezadmin
 * @version //autogentag//
 * @copyright Copyright (C) 2007 xrow. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl.txt GPL License
 */

/*
Add to your vhost

RewriteCond %{REQUEST_URI} ^/info\.php$
RewriteRule ^/(.*)$ /extension/ezadmin/scripts/$1  [L]

Call URL http//www.example.com/info.php to view script
*/
phpinfo();
gd_info();
?>