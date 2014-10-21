<?php

require __DIR__ . '/../trivia/php/Runner.php';

class GoldenMasterTest extends PHPUnit_Framework_TestCase {

	private $gmPath;

	function setUp() {
		$this->gmPath = __DIR__ . '/gm.txt';
	}

	function testGenerateOutput() {
		$this->markTestSkipped();
//		$times = 20000;
//		$this->generateMany($times, $this->gmPath);
	}

	function testOutputMatchesGoldenMaster() {
		$this->markTestSkipped();

		$times = 20000;
		$actualPath = __DIR__ . '/actual.txt';
		$this->generateMany($times, $actualPath);
		$file_content_gm = file_get_contents($this->gmPath);
		$file_content_actual = file_get_contents($actualPath);
		$this->assertTrue($file_content_gm == $file_content_actual);
	}

	private function generateMany($times, $fileName) {
		$first = true;
		while ($times) {
			if ($first) {
				file_put_contents($fileName, $this->generateOutput($times));
				$first = false;
			} else {
				file_put_contents($fileName, $this->generateOutput($times), FILE_APPEND);
			}
			$times--;
		}
	}

	private function generateOutput($seed) {
		ob_start();
		srand($seed);
		(new Runner())->run();
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
}
 