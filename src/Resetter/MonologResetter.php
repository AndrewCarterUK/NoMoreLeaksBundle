<?php

namespace AndrewCarterUK\NoMoreLeaksBundle\Resetter;

use Monolog\Logger;

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

    private function resetHandler($handler)
    {
        $class = get_class($handler);

        $prefix = 'Monolog\\Handler\\';

        if (substr($class, 0, strlen($prefix)) != $prefix) {
           return;
        }

        $handlerName = substr($class, strlen($prefix));

        if ('FingersCrossedHandler' == $handlerName) {
            $handler->clear();
        } elseif ('BufferHandler' == $handlerName || 'StreamHandler' == $handlerName) {
            $handler->close();
        }

        if (property_exists($handler, 'handler')) {
            $subHandler = $this->readProperty($handler, 'handler');
            $this->resetHandler($subHandler);
        }

        if (property_exists($handler, 'handlers')) {
            $subHandlers = $this->readProperty($handler, 'handlers');
            $this->resetHandlers($subHandlers);
        }
    }

    private function readProperty($object, $property)
    {
        $closure = \Closure::bind(function () use ($object, $property) {
            return $object->{$property};
        }, null, $object);

        return $closure();
    }
}
