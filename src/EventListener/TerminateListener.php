<?php

namespace AndrewCarterUK\NoMoreLeaksBundle\EventListener;

use AndrewCarterUK\NoMoreLeaksBundle\Resetter\ResetterInterface;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;

class TerminateListener
{
    /**
     * @var ResetterInterface[]
     */
    private $resetters;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->resetters = array();
    }

    /**
     * This method is called by the compiler pass adding tagged resetter
     * services.
     * 
     * @param ResetterInterface $resetter
     */
    public function addResetter(ResetterInterface $resetter)
    {
        $this->resetters[] = $resetter;
    }

    /**
     * Symfony kernel terminate event listener that calls all the resetters.
     * 
     * @param PostResponseEvent $event
     */
    public function onKernelTerminate(PostResponseEvent $event)
    {
        foreach ($this->resetters as $resetter) {
            $resetter->reset();
        }
    }
}
