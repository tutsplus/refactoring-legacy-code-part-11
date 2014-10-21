<?php
require_once __DIR__ . '/CLIDisplay.php';
include_once __DIR__ . '/Game.php';

class Runner {

	function run() {
		$display = new CLIDisplay();
		$aGame = new Game($display);
		$aGame->add("Chet");
		$aGame->add("Pat");
		$aGame->add("Sue");

		do {
			$dice = rand(0, 5) + 1;
			$aGame->roll($dice);
		} while (!$aGame->didSomebodyWin($aGame->isCurrentAnswerCorrect()));
	}


}
