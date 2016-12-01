<?php

namespace AndrewCarterUK\NoMoreLeaksBundle\Tests\EventListener;

use AndrewCarterUK\NoMoreLeaksBundle\Resetter\ResetterInterface;

class MockResetter implements ResetterInterface
{
    public $state = false;

    public function reset()
    {
        $this->state = true;
    }

    public function hasReset()
    {
        return $this->state;
    }
}
