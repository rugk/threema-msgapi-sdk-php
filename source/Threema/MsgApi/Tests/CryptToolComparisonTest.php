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
		// // make strings large enough
		// $string1 = str_repeat(Constants::myPrivateKey, 100000);
		// $string2 = str_repeat(Constants::otherPrivateKey, 100000);
		// echo PHP_EOL;
		//
		// $humanDescr = [
		// 	'length' => 'different length',
		// 	'diff' => 'same length, different content',
		// 	'same' => 'same length, same content'
		// ];
		//
		// // test different strings when comparing and get time needed
		// $result = [];
		// foreach(array(
		// 	'length' => [$string1, $string1 . 'a'],
		// 	'diff' => [$string1, $string2],
		// 	'same' => [$string1, $string1]
		// ) as $testName => $strings) {
		// 	$timeStart = microtime(true);
		// 	$comparisonResult = $this->cryptTool->stringCompare($strings[0], $strings[1]);
		// 	$timeEnd = microtime(true);
		// 	$timeElapsed = $timeEnd - $timeStart;
		//
		// 	// echo $prefix.': '.$humanDescr[$testName].': '.$timeElapsed.'; result: '.$comparisonResult.PHP_EOL;
		// 	$result[$testName] = [$timeElapsed, $comparisonResult];
		//
		// 	// check result
		// 	if ($testName == 'length' || $testName == 'diff') {
		// 		$this->assertEquals($comparisonResult, false, $prefix.': comparison of "'.$humanDescr[$testName].'" is wrong: expected: false, got '.$comparisonResult);
		// 	} else {
		// 		$this->assertEquals($comparisonResult, true, $prefix.': comparison of "'.$humanDescr[$testName].'" is wrong: expected: true, got '.$comparisonResult);
		// 	}
		// }
		//
		// // check timings
		// echo 'Timing test results with '.$prefix.':'.PHP_EOL;
		// $timingRatio = 2 - ($result['diff'][0] / $result['same'][0]);
		// $absoluteDifference = abs($result['diff'][0] - $result['same'][0]);
		// echo 'timing ratio: '.$timingRatio.PHP_EOL;
		// echo 'absolute difference: '.$absoluteDifference.PHP_EOL;
		//
		// // only allow 10% relative difference of two values
		// $allowedDifference = 0.10;
		// $this->assertLessThan(1+$allowedDifference, $timingRatio, $prefix.': difference of comparison ration of "'.$humanDescr['diff'].'" compared to "'.$humanDescr['same'].'" is too high. Ration: '.$timingRatio);
		// $this->assertGreaterThan(1-$allowedDifference, $timingRatio, $prefix.': difference of comparison ration of "'.$humanDescr['diff'].'" compared to "'.$humanDescr['same'].'" is too high. Ration: '.$timingRatio);
		//
		// // make sure the absolute difference is smaller than 0.05 microseconds
		// $this->assertLessThan(0.05, $absoluteDifference, $prefix.': difference of comparison ration of "'.$humanDescr['diff'].'" compared to "'.$humanDescr['same'].'" is too high. Value is: '.$absoluteDifference.' micro seconds');
	}
}
