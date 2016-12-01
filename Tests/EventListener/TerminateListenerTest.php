<?php

namespace AndrewCarterUK\NoMoreLeaksBundle\Tests\EventListener;

use AndrewCarterUK\NoMoreLeaksBundle\EventListener\TerminateListener;

class TerminateListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testTerminateListener()
    {
        $listener = new TerminateListener();
        $resetter = new MockResetter();

        $event = $this->createMock('Symfony\\Component\\HttpKernel\\Event\\PostResponseEvent');

        $listener->addResetter($resetter);

        $this->assertFalse($resetter->hasReset());
        $listener->onKernelTerminate($event);
        $this->assertTrue($resetter->hasReset());
    }
}
