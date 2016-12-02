<?php

namespace AndrewCarterUK\NoMoreLeaksBundle\Tests\DependencyInjection;

use AndrewCarterUK\NoMoreLeaksBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the default configuration.
     */
    public function testDefault()
    {
        $config = $this->process(array());

        $expectedConfig = array(
            'doctrine' => array(
                'enabled' => true,
                'managers' => array(),
            ),
            'monolog' => array(
                'enabled' => true,
                'channels' => array(),
            ),
        );

        $this->assertSame($expectedConfig, $config);
    }

    /**
     * Processes an array of configurations and returns a compiled version.
     *
     * @param array $configs An array of raw configurations
     *
     * @return array A normalized array
     */
    private function process($configs)
    {
        $processor = new Processor();

        return $processor->processConfiguration(new Configuration(), $configs);
    }
}