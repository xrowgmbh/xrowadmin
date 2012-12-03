<?php /* #?ini charset="utf-8"?
[BackupSettings]
Server=example.com
Username=exampleftpuser
Password=passwordofftpuser
Port=21
# How many days do you want to keep your Backups; 
BackupPurge=7

[Backup-default]
Databases=installation
Include[]=settings/
Include[]=var/


[Backup-system]
Databases=all
Include[]=/root
Include[]=/etc
Include[]=/var/mail
Include[]=/home

[Backup-test]
#use all, avialable, or installation
Databases=all
Include[]=var/log
Include[]=C:/workspace/xrow.de/var/storage
*/ ?>