<?php

include __DIR__ . '/../vendor/autoload.php';

use Postcon\PhpKatas\Game;

$notAWinner;

$aGame = new Game(new \Postcon\PhpKatas\CliOutput());

$aGame->add("Chet");
$aGame->add("Pat");
$aGame->add("Sue");

srand(1);
do {
    $aGame->roll(rand(0, 5) + 1);

    if (rand(0, 9) == 7) {
        $notAWinner = $aGame->wrongAnswer();
    } else {
        $notAWinner = $aGame->wasCorrectlyAnswered();
    }
} while ($notAWinner);
  
