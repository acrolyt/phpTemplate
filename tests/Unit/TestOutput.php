<?php

namespace Postcon\PhpKatas\Tests\Unit;

use Postcon\PhpKatas\CliOutput;

class TestOutput extends CliOutput
{
    private $log = '';

    public function echoln($string)
    {
        $this->log .= $string . "\n";
    }

    /**
     * @return string
     */
    public function getLog(): string
    {
        return $this->log;
    }

}