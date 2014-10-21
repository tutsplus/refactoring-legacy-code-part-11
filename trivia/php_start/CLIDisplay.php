<?php

class CLIDisplay implements Display {
	private $popQuestions = [];
	private $scienceQuestions = [];
	private $sportsQuestions = [];
	private $rockQuestions = [];

	function __construct() {
		$this->initializeQuestions();
	}

	function statusAfterRoll($rolledNumber, $currentPlayer) {
		$this->currentPlayer($currentPlayer);
		$this->rolledNumber($rolledNumber);
	}

	function playerSentToPenaltyBox($currentPlayer) {
		$this->echoln($currentPlayer . " was sent to the penalty box");
	}

	function playerStaysInPenaltyBox($currentPlayer) {
		$this->echoln($currentPlayer . " is not getting out of the penalty box");
	}

	function statusAfterNonPenalizedPlayerMove($currentPlayer, $currentPlace, $currentCategory) {
		$this->playersNewLocation($currentPlayer, $currentPlace);
		$this->currentCategory($currentCategory);
	}

	function statusAfterPlayerGettingOutOfPenaltyBox($currentPlayer, $currentPlace, $currentCategory) {
		$this->playerGettingOutOfPenaltyBox($currentPlayer);
		$this->playersNewLocation($currentPlayer, $currentPlace);
		$this->currentCategory($currentCategory);
	}

	function playerAdded($playerName, $numberOfPlayers) {
		$this->echoln($playerName . " was added");
		$this->echoln("They are player number " . $numberOfPlayers);
	}

	function  askQuestion($currentCategory) {
		if ($currentCategory == "Pop") {
			$this->echoln(array_shift($this->popQuestions));
		}
		if ($currentCategory == "Science") {
			$this->echoln(array_shift($this->scienceQuestions));
		}
		if ($currentCategory == "Sports") {
			$this->echoln(array_shift($this->sportsQuestions));
		}
		if ($currentCategory == "Rock") {
			$this->echoln(array_shift($this->rockQuestions));
		}
	}

	function correctAnswer() {
		$this->echoln("Answer was correct!!!!");
	}

	function correctAnswerWithTypo() {
		$this->echoln("Answer was corrent!!!!");
	}

	function incorrectAnswer() {
		$this->echoln("Question was incorrectly answered");
	}

	function playerCoins($currentPlayer, $playerCoins) {
		$this->echoln($currentPlayer . " now has " . $playerCoins . " Gold Coins.");
	}

	private function echoln($string) {
		echo $string . "\n";
	}

	private function currentPlayer($currentPlayer) {
		$this->echoln($currentPlayer . " is the current player");
	}

	private function rolledNumber($rolledNumber) {
		$this->echoln("They have rolled a " . $rolledNumber);
	}

	private function playersNewLocation($currentPlayer, $currentPlace) {
		$this->echoln($currentPlayer . "'s new location is " . $currentPlace);
	}

	private function currentCategory($currentCategory) {
		$this->echoln("The category is " . $currentCategory);
	}

	private function playerGettingOutOfPenaltyBox($currentPlayer) {
		$this->echoln($currentPlayer . " is getting out of the penalty box");
	}

	private function initializeQuestions() {
		$categorySize = 50;
		for ($i = 0; $i < $categorySize; $i++) {
			array_push($this->popQuestions, "Pop Question " . $i);
			array_push($this->scienceQuestions, ("Science Question " . $i));
			array_push($this->sportsQuestions, ("Sports Question " . $i));
			array_push($this->rockQuestions, "Rock Question " . $i);
		}
	}

} 