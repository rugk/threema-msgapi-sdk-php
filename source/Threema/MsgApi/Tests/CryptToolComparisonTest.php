<?php
/**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015-2016 Threema GmbH
 */



namespace Threema\MsgApi\Tests;

use Threema\Console\Common;
use Threema\MsgApi\Tools\CryptTool;

/**
 * Tests only valid for Sodium as they cannot be implemented correctly in the PHP-only version.
 */
class CryptToolSodiumTests extends \PHPUnit_Framework_TestCase {
	/** @var Threema\MsgApi\Tools\CryptTool */
	private $cryptTool;

	/**
	 * Initialize crypt tool.
	 */
	function __construct() {
		$this->cryptTool = CryptTool::createInstance(CryptTool::TYPE_SODIUM);
	}

	/**
	 * test hex2bin and bin2hex functions to make sure they are resistant to timing attacks
	 */
	public function testHexBin() {
		// make strings large enough
		$testStrSmall = Constants::myPrivateKeyExtract;
		$testStrLong = Constants::myPublicKeyExtract;
		echo PHP_EOL;

		// test different strings when comparing and get time needed
		$result = [];
		foreach(array(
			'short' => $testStrSmall,
			'long' => $testStrLong
		) as $testName => $testString) {
			$timeStart = microtime(true);
			$conResultBin = $this->cryptTool->hex2bin($testString);
			$conResultHex = $this->cryptTool->bin2hex($conResultBin);
			$timeEnd = microtime(true);
			$timeElapsed = $timeEnd - $timeStart;

			echo $testName.': '.$timeElapsed.PHP_EOL;
			$result[$testName] = [$timeElapsed, $conResultBin, $conResultHex];

			// check result
			$this->assertEquals(hex2bin($testString), $conResultBin, $testName.': hex2bin returns different result than PHP-only implementation');
			$this->assertEquals($testString, $conResultHex, $testName.': hex string differs from original string after conversion');
		}

		// check timings
		$timingRatio = 2 - ($result['short'][0] / $result['long'][0]);
		$absoluteDifference = abs($result['short'][0] - $result['long'][0]);
		echo 'timing ratio: '.$timingRatio.PHP_EOL;
		echo 'absolute difference: '.$absoluteDifference.PHP_EOL;

		// only allow 10% relative difference of two values
		$allowedDifference = 0.10;
		// $this->assertLessThan(1+$allowedDifference, $timingRatio, 'difference of conversion ration of "short" compared to "long" is too high. Ration: '.$timingRatio);
		// $this->assertGreaterThan(1-$allowedDifference, $timingRatio, 'difference of conversion ration of "short" compared to "long" is too high. Ration: '.$timingRatio);

		// make sure the absolute difference is smaller than 0.05 microseconds
		$this->assertLessThan(0.05, $absoluteDifference, 'difference of conversion ration of "short" compared to "long" is too high. Value is: '.$absoluteDifference.' micro seconds');
	}
}
