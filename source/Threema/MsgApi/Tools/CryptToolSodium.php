<?php
/**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015-2016 Threema GmbH
 */


namespace Threema\MsgApi\Tools;

use Threema\Core\Exception;
use Threema\Core\KeyPair;

/**
 * Contains static methods to do various Threema cryptography related tasks.
 * Support libsodium >= 0.2.0 (Namespaces)
 *
 * @package Threema\Core
 */
class CryptToolSodium extends CryptTool {
	/**
	 * @param string $data
	 * @param string $nonce
	 * @param string $senderPrivateKey
	 * @param string $recipientPublicKey
	 * @return string encrypted box
	 */
	protected function makeBox($data, $nonce, $senderPrivateKey, $recipientPublicKey) {
		/** @noinspection PhpUndefinedNamespaceInspection @noinspection PhpUndefinedFunctionInspection */
		$kp = \Sodium\crypto_box_keypair_from_secretkey_and_publickey($senderPrivateKey, $recipientPublicKey);

		/** @noinspection PhpUndefinedNamespaceInspection @noinspection PhpUndefinedFunctionInspection */
		return \Sodium\crypto_box($data, $nonce, $kp);
	}

	/**
	 * make a secret box
	 *
	 * @param $data
	 * @param $nonce
	 * @param $key
	 * @return mixed
	 */
	protected function makeSecretBox($data, $nonce, $key) {
		/** @noinspection PhpUndefinedNamespaceInspection @noinspection PhpUndefinedFunctionInspection */
		return \Sodium\crypto_secretbox($data, $nonce, $key);
	}


	/**
	 * @param string $box
	 * @param string $recipientPrivateKey
	 * @param string $senderPublicKey
	 * @param string $nonce
	 * @return null|string
	 */
	protected function openBox($box, $recipientPrivateKey, $senderPublicKey, $nonce) {
		/** @noinspection PhpUndefinedNamespaceInspection @noinspection PhpUndefinedFunctionInspection */
		$kp = \Sodium\crypto_box_keypair_from_secretkey_and_publickey($recipientPrivateKey, $senderPublicKey);
		/** @noinspection PhpUndefinedNamespaceInspection @noinspection PhpUndefinedFunctionInspection */
		return \Sodium\crypto_box_open($box, $nonce, $kp);
	}

	/**
	 * decrypt a secret box
	 *
	 * @param string $box as binary
	 * @param string $nonce as binary
	 * @param string $key as binary
	 * @return string as binary
	 */
	protected function openSecretBox($box, $nonce, $key) {
		/** @noinspection PhpUndefinedNamespaceInspection @noinspection PhpUndefinedFunctionInspection */
		return \Sodium\crypto_secretbox_open($box, $nonce, $key);
	}

	/**
	 * Generate a new key pair.
	 *
	 * @return KeyPair the new key pair
	 */
	final public function generateKeyPair() {
		/** @noinspection PhpUndefinedNamespaceInspection @noinspection PhpUndefinedFunctionInspection */
		$kp = \Sodium\crypto_box_keypair();
		/** @noinspection PhpUndefinedNamespaceInspection @noinspection PhpUndefinedFunctionInspection */
		return new KeyPair(\Sodium\crypto_box_secretkey($kp), \Sodium\crypto_box_publickey($kp));
	}

	/**
	 * @param int $size
	 * @return string
	 */
	protected function createRandom($size) {
		/** @noinspection PhpUndefinedNamespaceInspection @noinspection PhpUndefinedFunctionInspection */
		return \Sodium\randombytes_buf($size);
	}

	/**
	 * Derive the public key
	 *
	 * @param string $privateKey in binary
	 * @return string public key as binary
	 */
	final public function derivePublicKey($privateKey) {
		/** @noinspection PhpUndefinedNamespaceInspection @noinspection PhpUndefinedFunctionInspection */
		return \Sodium\crypto_box_publickey_from_secretkey($privateKey);
	}

	/**
	 * Converts a binary string to an hexdecimal string.
	 *
	 * This is the same as PHP's bin2hex() implementation, but it is resistant to
	 * timing attacks.
	 *
	 * @link https://paragonie.com/book/pecl-libsodium/read/03-utilities-helpers.md#bin2hex
	 * @param  string $binaryString The binary string to convert
	 * @return string
	 */
	public function bin2hex($binaryString)
	{
		return \Sodium\bin2hex($binaryString);
	}

	/**
	 * Converts an hexdecimal string to a binary string.
	 *
	 * This is the same as PHP's hex2bin() implementation, but it is resistant to
	 * timing attacks.
	 *
	 * @link https://paragonie.com/book/pecl-libsodium/read/03-utilities-helpers.md#hex2bin
	 * @param  string $hexString The hex string to convert
	 * @param  string|null $ignore	(optional) Characters to ignore
	 * @throws \Threema\Core\Exception
	 * @return string
	 */
	public function hex2bin($hexString, $ignore = null)
	{
		return \Sodium\hex2bin($hexString, $ignore);
	}


	/**
	 * Compares two strings in a secure way.
	 *
	 * This is the same as PHP's strcmp() implementation, but it is resistant to
	 * timing attacks.
	 *
	 * @link https://paragonie.com/book/pecl-libsodium/read/03-utilities-helpers.md#compare
	 * @param  string $str1 The first string
	 * @param  string $str2 The second string
	 * @return bool
	 */
	public function stringCompare($str1, $str2)
	{
		// check variable type manually
		if (!is_string($str1) || !is_string($str2)) {
			return false;
		}

		return \Sodium\memcmp($str1, $str2) === 0;
	}

	/**
	 * Check if implementation supported
	 * @return bool
	 */
	public function isSupported() {
		return true === extension_loaded('libsodium')
			&& false === method_exists('Sodium', 'sodium_version_string');
	}

	/**
	 * Validate crypt tool
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function validate() {
		if(false === $this->isSupported()) {
			throw new Exception('Sodium implementation not supported');
		}
		return true;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'sodium';
	}

	/**
	 * Description of the CryptTool
	 * @return string
	 */
	public function getDescription() {
		/** @noinspection PhpUndefinedNamespaceInspection @noinspection PhpUndefinedFunctionInspection */
		return 'Sodium implementation '.\Sodium\version_string();
	}
}
