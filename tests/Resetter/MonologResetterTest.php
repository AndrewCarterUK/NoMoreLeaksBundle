<?php

namespace AndrewCarterUK\NoMoreLeaksBundle\Tests\Resetter;

use AndrewCarterUK\NoMoreLeaksBundle\Util;
use AndrewCarterUK\NoMoreLeaksBundle\Resetter\MonologResetter;
use Monolog\Logger;
use Monolog\Handler\BufferHandler;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\GroupHandler;
use Monolog\Handler\NullHandler;
use Monolog\Handler\TestHandler;

class MonologResetterTest extends \PHPUnit_Framework_TestCase
{
    public function testBufferHandlerClear()
    {
        $testHandler = new TestHandler();
        $bufferHandler = new BufferHandler($testHandler);

        $logger = new Logger('Test', array($bufferHandler));

        $logger->notice('hello');

        $buffer = Util::readProperty($bufferHandler, 'buffer');
        $this->assertArrayHasKey(0, $buffer);
        $this->assertArrayHasKey('message', $buffer[0]);
        $this->assertEquals('hello', $buffer[0]['message']);

        $this->assertCount(0, $testHandler->getRecords());

        $resetter = new MonologResetter($logger);
        $resetter->reset();

        $buffer = Util::readProperty($bufferHandler, 'buffer');
        $this->assertCount(0, $buffer);

        $records = $testHandler->getRecords();
        $this->assertArrayHasKey(0, $records);
        $this->assertArrayHasKey('message', $records[0]);
        $this->assertEquals('hello', $records[0]['message']);
    }

    public function testFingersCrossedHandlerClear()
    {
        $fingersCrossedHandler = new FingersCrossedHandler(new NullHandler());

        $groupHandler = new GroupHandler(array(
            new NullHandler(),
            $fingersCrossedHandler,
        ));

        $logger = new Logger('Test', array($groupHandler));

        $logger->notice('hello');

        $buffer = Util::readProperty($fingersCrossedHandler, 'buffer');
        $this->assertArrayHasKey(0, $buffer);
        $this->assertArrayHasKey('message', $buffer[0]);
        $this->assertEquals('hello', $buffer[0]['message']);

        $resetter = new MonologResetter($logger);
        $resetter->reset();

        $buffer = Util::readProperty($fingersCrossedHandler, 'buffer');
        $this->assertCount(0, $buffer);
    }
}
