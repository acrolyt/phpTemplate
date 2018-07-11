<?php

namespace Postcon\PhpKatas;

class Game
{
    var $players;
    var $places;
    var $purses;
    var $inPenaltyBox;
    var $popQuestions;
    var $scienceQuestions;
    var $sportsQuestions;
    var $rockQuestions;
    var $currentPlayer = 0;
    var $isGettingOutOfPenaltyBox;
    /**
     * @var CliOutput
     */
    private $output;
    const QUESTIONS_PER_CATEGORY = 50;

    function __construct(CliOutput $output)
    {
        $this->players = [];
        $this->places = [0];
        $this->purses = [0];
        $this->inPenaltyBox = [0];

        $this->generateGameQuestions();
        $this->output = $output;
    }

    function isPlayable()
    {
        return $this->howManyPlayers() >= 2;
    }

    function add($playerName)
    {
        $this->players[] = $playerName;
        $this->places[] = 0;
        $this->purses[] = 0;
        $this->inPenaltyBox[] = false;

        $this->output->echoln($playerName . " was added");
        $this->output->echoln("They are player number " . $this->howManyPlayers());

        return true;
    }

    public function howManyPlayers()
    {
        return count($this->players);
    }

    function roll($roll)
    {
        $this->output->echoln($this->players[$this->currentPlayer] . " is the current player");
        $this->output->echoln("They have rolled a " . $roll);

        if ($this->inPenaltyBox[$this->currentPlayer]) {
            if ($this->shouldStayInBox($roll)) {
                $this->playerStayesInPenaltyBox();
                return;
            }
            $this->getPlayerOutOfPenaltyBox();
        }
        $this->takePlayerTurn($roll);
    }

    function askQuestion()
    {
        if ($this->currentCategory() == "Pop") {
            $this->output->echoln(array_shift($this->popQuestions));
        }
        if ($this->currentCategory() == "Science") {
            $this->output->echoln(array_shift($this->scienceQuestions));
        }
        if ($this->currentCategory() == "Sports") {
            $this->output->echoln(array_shift($this->sportsQuestions));
        }
        if ($this->currentCategory() == "Rock") {
            $this->output->echoln(array_shift($this->rockQuestions));
        }
    }

    function currentCategory()
    {
        if ($this->places[$this->currentPlayer] == 0) {
            return "Pop";
        }
        if ($this->places[$this->currentPlayer] == 4) {
            return "Pop";
        }
        if ($this->places[$this->currentPlayer] == 8) {
            return "Pop";
        }
        if ($this->places[$this->currentPlayer] == 1) {
            return "Science";
        }
        if ($this->places[$this->currentPlayer] == 5) {
            return "Science";
        }
        if ($this->places[$this->currentPlayer] == 9) {
            return "Science";
        }
        if ($this->places[$this->currentPlayer] == 2) {
            return "Sports";
        }
        if ($this->places[$this->currentPlayer] == 6) {
            return "Sports";
        }
        if ($this->places[$this->currentPlayer] == 10) {
            return "Sports";
        }

        return "Rock";
    }

    function wasCorrectlyAnswered()
    {
        if ($this->inPenaltyBox[$this->currentPlayer]) {
            if ($this->isGettingOutOfPenaltyBox) {
                $this->output->echoln("Answer was correct!!!!");
                $this->purses[$this->currentPlayer]++;
                $this->output->echoln($this->players[$this->currentPlayer]
                    . " now has "
                    . $this->purses[$this->currentPlayer]
                    . " Gold Coins.");

                $winner = $this->didPlayerWin();


                $this->nextPlayer();

                return $winner;
            } else {
                $this->nextPlayer();

                return true;
            }
        } else {
            $this->output->echoln("Answer was corrent!!!!");
            $this->purses[$this->currentPlayer]++;
            $this->output->echoln($this->players[$this->currentPlayer]
                . " now has "
                . $this->purses[$this->currentPlayer]
                . " Gold Coins.");

            $winner = $this->didPlayerWin();
            $this->nextPlayer();

            return $winner;
        }
    }

    function wrongAnswer()
    {
        $this->output->echoln("Question was incorrectly answered");
        $this->output->echoln($this->players[$this->currentPlayer] . " was sent to the penalty box");
        $this->inPenaltyBox[$this->currentPlayer] = true;

        $this->nextPlayer();

        return true; // i.e. go on, not a winner
    }

    function didPlayerWin()
    {
        return !($this->purses[$this->currentPlayer] == 6);
    }

    private function generateGameQuestions()
    {
        $this->popQuestions = [];
        $this->scienceQuestions = [];
        $this->sportsQuestions = [];
        $this->rockQuestions = [];

        for ($i = 0; $i < self::QUESTIONS_PER_CATEGORY; ++$i) {
            $this->popQuestions[] = "Pop Question " . $i;
            $this->scienceQuestions[] = "Science Question " . $i;
            $this->sportsQuestions[] = "Sports Question " . $i;
            $this->rockQuestions[] = "Rock Question " . $i;
        }
    }

    private function nextPlayer()
    {
        $this->currentPlayer = ($this->currentPlayer + 1) % $this->howManyPlayers();
    }

    /**
     * @param $roll
     */
    private function takePlayerTurn($roll)
    {
        $this->places[$this->currentPlayer] = ($this->places[$this->currentPlayer] + $roll) % 12;

        $this->output->echoln($this->players[$this->currentPlayer]
            . "'s new location is "
            . $this->places[$this->currentPlayer]);
        $this->output->echoln("The category is " . $this->currentCategory());

        $this->askQuestion();
    }

    /**
     * @param $roll
     */
    private function getPlayerOutOfPenaltyBox()
    {
        $this->isGettingOutOfPenaltyBox = true;

        $this->output->echoln($this->players[$this->currentPlayer] . " is getting out of the penalty box");
    }

    /**
     * @param $roll
     * @return bool
     */
    private function shouldStayInBox($roll): bool
    {
        return $roll % 2 == 0;
    }

    private function playerStayesInPenaltyBox()
    {
        $this->output->echoln($this->players[$this->currentPlayer] . " is not getting out of the penalty box");
        $this->isGettingOutOfPenaltyBox = false;
    }
}
