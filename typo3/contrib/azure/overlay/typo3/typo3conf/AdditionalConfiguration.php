<?php
//parameters will be set from parameters.xml
$GLOBALS['TYPO3_CONF_VARS']['DB']['host'] = 'PlaceHolderForServer';
$GLOBALS['TYPO3_CONF_VARS']['DB']['database'] = 'PlaceHolderForDb';
$GLOBALS['TYPO3_CONF_VARS']['DB']['username'] = 'PlaceHolderForUser';
$GLOBALS['TYPO3_CONF_VARS']['DB']['password'] = 'PlaceHolderForPassword';
//we need to disable the strict mode
$GLOBALS['TYPO3_CONF_VARS']['SYS']['setDBinit'] = "SET SESSION sql_mode='';";
//fix openssl config path
putenv("OPENSSL_CONF=" . dirname(__FILE__) . "\openssl.cnf")
?>