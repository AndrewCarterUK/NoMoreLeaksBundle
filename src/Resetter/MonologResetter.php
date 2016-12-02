<?php

namespace AndrewCarterUK\NoMoreLeaksBundle\Resetter;

use AndrewCarterUK\NoMoreLeaksBundle\Util;
use Monolog\Logger;
use Monolog\Handler\HandlerInterface;

class MonologResetter implements ResetterInterface
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * Constructor.
     * 
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function reset()
    {
        $handlers = $this->logger->getHandlers();
        $this->resetHandlers($handlers);
    }

    private function resetHandlers(array $handlers)
    {
        foreach($handlers as $handler) {
            $this->resetHandler($handler);
        }
    }

    private function resetHandler(HandlerInterface $handler)
    {
        $class = get_class($handler);

        $prefix = 'Monolog\\Handler\\';

        if (substr($class, 0, strlen($prefix)) == $prefix) {
            $handlerName = substr($class, strlen($prefix));

            if ('FingersCrossedHandler' == $handlerName) {
                $handler->clear();
            } elseif ('BufferHandler' == $handlerName || 'StreamHandler' == $handlerName) {
                $handler->close();
            }

            if (property_exists($handler, 'handler')) {
                $subHandler = Util::readProperty($handler, 'handler');
                $this->resetHandler($subHandler);
            }

            if (property_exists($handler, 'handlers')) {
                $subHandlers = Util::readProperty($handler, 'handlers');
                $this->resetHandlers($subHandlers);
            }
        }
    }
}
