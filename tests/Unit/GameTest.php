<?php

namespace Postcon\PhpKatas\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Postcon\PhpKatas\CliOutput;
use Postcon\PhpKatas\Game as NewGame;

class GameTest extends TestCase
{
    /** @var Game */
    private $goldenGame;
    /** @var NewGame */
    private $newGame;

    /** @var CliOutput|TestOutput */
    private $output;

    /**
     * @test
     */
    public function it_should_construct()
    {
        $this->assertStates();
    }

    /**
     * @test
     */
    public function it_should_add_players()
    {
        self::assertEquals($this->goldenGame->add('alice'), $this->newGame->add('alice'));
        $this->assertStates();
        self::assertEquals($this->goldenGame->add('bob'), $this->newGame->add('bob'));
        $this->assertStates();
        self::assertEquals($this->goldenGame->add('carol'), $this->newGame->add('carol'));
        $this->assertStates();
        self::assertEquals($this->goldenGame->add('dave'), $this->newGame->add('dave'));
        $this->assertStates();

        $this->expectOutputString($this->output->getLog());
    }

    /**
     * @test
     */
    public function it_should_return_playability()
    {
        self::assertFalse($this->newGame->isPlayable());

        $this->newGame->add('');
        self::assertFalse($this->newGame->isPlayable());

        $this->newGame->add('');
        self::assertTrue($this->newGame->isPlayable());
    }

    /**
     * @test
     */
    public function it_should_return_the_number_of_players()
    {
        for ($i = 1; $i < 1000; ++$i) {
            $this->goldenGame->add('player' . $i);
            $this->newGame->add('player' . $i);

            self::assertEquals($this->goldenGame->howManyPlayers(), $this->newGame->howManyPlayers());
        }
    }

    /**
     * @test
     */
    public function it_should_roll()
    {
        $this->addPlayers(2);

        for($i = 0; $i < 10000; ++$i) {
            $roll = random_int(1, 10);
            $this->goldenGame->roll($roll);
            $this->newGame->roll($roll);

            if (9 == $i % 10) {
                $this->goldenGame->wrongAnswer();
                $this->newGame->wrongAnswer();
            }

            $this->assertStates();
        }

        $this->expectOutputString($this->output->getLog());
    }

    /**
     *
     */
    protected function setUp()
    {
        $this->goldenGame = new Game();

        $this->output = new TestOutput();
        $this->newGame = new NewGame($this->output);
    }

    private function assertStates()
    {
        foreach ($this->goldenGame as $propName => $value) {
            self::assertEquals($value, $this->newGame->$propName);
        }
    }

    private function addPlayers($playerCount)
    {
        for ($i = 0; $i < $playerCount; ++$i) {
            $this->goldenGame->add('player' . $i);
            $this->newGame->add('player' . $i);
        }
    }
}