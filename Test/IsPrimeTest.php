<?php

class IsPrimeTest extends PHPUnit_Framework_TestCase {

	function testGenerateGoldenMaster() {
		$this->markTestSkipped();
		for ($i = 1; $i < 10000; $i++) {
			file_put_contents(__DIR__ . '/IsPrimeGoldenMaster.txt', $this->getPrimeResultAsString($i), FILE_APPEND);
		}
	}

	function testMatchesGoldenMaster() {
		$this->markTestSkipped();
		$goldenMaster = file(__DIR__ . '/IsPrimeGoldenMaster.txt');
		for ($i = 1; $i < 10000; $i++) {
			$actualResult = $this->getPrimeResultAsString($i);
			$this->assertTrue(in_array($actualResult, $goldenMaster), 'The value ' . $actualResult . ' is not in the golden master.');
		}
	}

	private function getPrimeResultAsString($i) {
		return $i . ' - ' . (isPrime($i) ? 'true' : 'false') . "\n";
	}
}

//Check if a number is prime
function isPrime($num, $divisors = null) {
	if (!is_array($divisors)) {
		return checkDivisorsBetween(2, highestPossibleFactor($num), $num);
	} else {
		return checkDivisorsBetween(0, count($divisors), $num, $divisors);
	}
}

function highestPossibleFactor($num) {
	return intval(sqrt($num));
}

function checkDivisorsBetween($start, $end, $num, $divisors = null) {
	for ($i = $start; $i < $end; $i++) {
		if (isDivisible($num, $divisors ? $divisors[$i] : $i)) {
			return false;
		}
	}
	return true;
}

function isDivisible($num, $i) {
	return $num % $i == 0;
}
 