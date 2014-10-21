<?php

interface Display {
	function statusAfterRoll($rolledNumber, $currentPlayer);
	function playerSentToPenaltyBox($currentPlayer);
	function playerStaysInPenaltyBox($currentPlayer);
	function statusAfterNonPenalizedPlayerMove($currentPlayer, $currentPlace, $currentCategory);
	function statusAfterPlayerGettingOutOfPenaltyBox($currentPlayer, $currentPlace, $currentCategory);
	function playerAdded($playerName, $numberOfPlayers);
	function  askQuestion($currentCategory);
	function correctAnswer();
	function correctAnswerWithTypo();
	function incorrectAnswer();
	function playerCoins($currentPlayer, $playerCoins);
} 