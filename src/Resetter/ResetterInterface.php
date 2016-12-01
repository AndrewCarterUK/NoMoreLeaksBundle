<?php

namespace AndrewCarterUK\NoMoreLeaksBundle\Resetter;

interface ResetterInterface
{
    /**
     * Reset the component that the resetter is responsible for.
     */
    public function reset();
}
