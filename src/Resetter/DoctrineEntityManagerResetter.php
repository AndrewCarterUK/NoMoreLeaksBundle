<?php

namespace AndrewCarterUK\NoMoreLeaksBundle\Resetter;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Logging\DebugStack;
use Doctrine\DBAL\Logging\LoggerChain;

class DoctrineEntityManagerResetter implements ResetterInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Constructor.
     * 
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function reset()
    {
        $this->entityManager->clear();

        $sqlLogger = $this->entityManager->getConnection()->getConfiguration()->getSQLLogger();

        if (null !== $sqlLogger) {
            $this->resetLogger($sqlLogger);
        }
    }

    private function resetLogger($sqlLogger)
    {
        if ($sqlLogger instanceof DebugStack) {
            $sqlLogger->queries = array();
            $sqlLogger->currentQuery = 0;
        } elseif ($sqlLogger instanceof LoggerChain) {
            $sqlLoggers = $this->readProperty($sqlLogger, 'loggers');

            foreach ($sqlLoggers as $sqlLogger) {
                $this->resetLogger($sqlLogger);
            }
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
