<?php

require_once __DIR__ . '/Display.php';

class Game {
	const WRONG_ANSWER_ID = 7;
	const MIN_ANSWER_ID = 0;
	const MAX_ANSWER_ID = 9;

	static $minimumNumberOfPlayers = 2;
	static $numberOfCoinsToWin = 6;

	private $display;

	var $players;
	var $places;
	var $purses;
	var $inPenaltyBox;

	var $currentPlayer = 0;
	var $isGettingOutOfPenaltyBox;

	function  __construct(Display $display) {

		$this->players = array();
		$this->places = array(0);
		$this->purses = array(0);
		$this->inPenaltyBox = array(0);

		$this->display = $display;
	}

	function isCurrentAnswerCorrect($minAnswerId = self::MIN_ANSWER_ID, $maxAnswerId = self::MAX_ANSWER_ID) {
		return rand($minAnswerId, $maxAnswerId) != self::WRONG_ANSWER_ID;
	}

	function didSomebodyWin($isCurrentAnswerCorrect) {
		if ($isCurrentAnswerCorrect) {
			return !$this->wasCorrectlyAnswered();
		} else {
			return !$this->wrongAnswer();
		}
	}

	function isPlayable() {
		return ($this->howManyPlayers() >= self::$minimumNumberOfPlayers);
	}

	function add($playerName) {
		array_push($this->players, $playerName);
		$this->setDefaultPlayerParametersFor($this->howManyPlayers());

		$this->display->playerAdded($playerName, count($this->players));
		return true;
	}

	function howManyPlayers() {
		return count($this->players);
	}

	function  roll($rolledNumber) {
		$this->display->statusAfterRoll($rolledNumber, $this->players[$this->currentPlayer]);
		if ($this->inPenaltyBox[$this->currentPlayer]) {
			$this->playNextMoveForPlayerInPenaltyBox($rolledNumber);
		} else {
			$this->playNextMove($rolledNumber);
		}
	}

	function currentCategory() {
		$popCategory = "Pop";
		$scienceCategory = "Science";
		$sportCategory = "Sports";
		$rockCategory = "Rock";

		if ($this->places[$this->currentPlayer] == 0) {
			return $popCategory;
		}
		if ($this->places[$this->currentPlayer] == 4) {
			return $popCategory;
		}
		if ($this->places[$this->currentPlayer] == 8) {
			return $popCategory;
		}
		if ($this->places[$this->currentPlayer] == 1) {
			return $scienceCategory;
		}
		if ($this->places[$this->currentPlayer] == 5) {
			return $scienceCategory;
		}
		if ($this->places[$this->currentPlayer] == 9) {
			return $scienceCategory;
		}
		if ($this->places[$this->currentPlayer] == 2) {
			return $sportCategory;
		}
		if ($this->places[$this->currentPlayer] == 6) {
			return $sportCategory;
		}
		if ($this->places[$this->currentPlayer] == 10) {
			return $sportCategory;
		}
		return $rockCategory;
	}

	function wasCorrectlyAnswered() {
		if ($this->inPenaltyBox[$this->currentPlayer]) {
			if ($this->isGettingOutOfPenaltyBox) {
				$this->display->correctAnswer();
				$this->purses[$this->currentPlayer]++;

				$this->display->playerCoins($this->players[$this->currentPlayer], $this->purses[$this->currentPlayer]);

				$winner = $this->didPlayerNotWin();
				$this->currentPlayer++;
				if ($this->shouldResetCurrentPlayer()) {
					$this->currentPlayer = 0;
				}

				return $winner;
			} else {
				$this->currentPlayer++;
				if ($this->shouldResetCurrentPlayer()) {
					$this->currentPlayer = 0;
				}
				return true;
			}

		} else {

			$this->display->correctAnswerWithTypo();
			$this->purses[$this->currentPlayer]++;
			$this->display->playerCoins($this->players[$this->currentPlayer], $this->purses[$this->currentPlayer]);

			$winner = $this->didPlayerNotWin();
			$this->currentPlayer++;
			if ($this->shouldResetCurrentPlayer()) {
				$this->currentPlayer = 0;
			}

			return $winner;
		}
	}

	function wrongAnswer() {
		$this->display->incorrectAnswer();
		$currentPlayer = $this->players[$this->currentPlayer];
		$this->display->playerSentToPenaltyBox($currentPlayer);
		$this->inPenaltyBox[$this->currentPlayer] = true;

		$this->currentPlayer++;
		if ($this->shouldResetCurrentPlayer()) {
			$this->currentPlayer = 0;
		}
		return true;
	}

	function didPlayerNotWin() {
		return !($this->purses[$this->currentPlayer] == self::$numberOfCoinsToWin);
	}

	private function isOdd($roll) {
		return $roll % 2 != 0;
	}

	private function playerShouldStartANewLap() {
		$lastPositionOnTheBoard = 11;
		return $this->places[$this->currentPlayer] > $lastPositionOnTheBoard;
	}

	private function shouldResetCurrentPlayer() {
		return $this->currentPlayer == count($this->players);
	}

	private function setDefaultPlayerParametersFor($playerId) {
		$this->places[$playerId] = 0;
		$this->purses[$playerId] = 0;
		$this->inPenaltyBox[$playerId] = false;
	}

	private function movePlayer($rolledNumber) {
		$boardSize = 12;
		$this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] + $rolledNumber;
		if ($this->playerShouldStartANewLap()) {
			$this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] - $boardSize;
		}
	}

	private function getPlayerOutOfPenaltyBoxAndPlayNextMove($rolledNumber) {
		$this->isGettingOutOfPenaltyBox = true;
		$this->movePlayer($rolledNumber);
		$this->display->statusAfterPlayerGettingOutOfPenaltyBox($this->players[$this->currentPlayer], $this->places[$this->currentPlayer], $this->currentCategory());
		$this->display->askQuestion($this->currentCategory());
	}

	private function keepPlayerInPenaltyBox() {
		$this->display->playerStaysInPenaltyBox($this->players[$this->currentPlayer]);
		$this->isGettingOutOfPenaltyBox = false;
	}

	private function playNextMove($rolledNumber) {
		$this->movePlayer($rolledNumber);
		$this->display->statusAfterNonPenalizedPlayerMove($this->players[$this->currentPlayer], $this->places[$this->currentPlayer], $this->currentCategory());
		$this->display->askQuestion($this->currentCategory());
	}

	private function playNextMoveForPlayerInPenaltyBox($rolledNumber) {
		if ($this->isOdd($rolledNumber)) {
			$this->getPlayerOutOfPenaltyBoxAndPlayNextMove($rolledNumber);
		} else {
			$this->keepPlayerInPenaltyBox();
		}
	}
}
