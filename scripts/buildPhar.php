#!/usr/bin/php
<?php
// set variables
$dir = dirname(__DIR__);
$source = dirname(__DIR__) . '/source';
$file = $dir . '/threema_msgapi.phar';

// preparation
if (!is_dir($dir)) {
	mkdir($dir);
}
if (file_exists($file)) {
	unlink($file);
}

$phar = new Phar(
	$file,
	FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME,
	'threema_msgapi.phar'
);
$phar->setStub("<?php
Phar::mapPhar();

include 'phar://threema_msgapi.phar/bootstrap.php';
__HALT_COMPILER(); ?>");
$phar->setSignatureAlgorithm(Phar::SHA256);

$phar->buildFromDirectory($source);
