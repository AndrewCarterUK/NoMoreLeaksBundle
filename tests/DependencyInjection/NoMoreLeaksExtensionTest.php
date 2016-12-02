<?php

namespace AndrewCarterUK\NoMoreLeaksBundle\Tests\DependencyInjection;

use AndrewCarterUK\NoMoreLeaksBundle\DependencyInjection\NoMoreLeaksExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class NoMoreLeaksExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        $builder = new ContainerBuilder();
        $extension = new NoMoreLeaksExtension();

        $extension->load(array(), $builder);

        $definitions = array(
            'no_more_leaks.listener.terminate' => array(
                'class' => 'AndrewCarterUK\NoMoreLeaksBundle\EventListener\TerminateListener',
                'abstract' => false,
            ),
            'no_more_leaks.resetter.doctrine_entity_manager_resetter_prototype' => array(
                'class' => 'AndrewCarterUK\NoMoreLeaksBundle\Resetter\DoctrineEntityManagerResetter',
                'abstract' => true,
            ),
            'no_more_leaks.resetter.monolog_prototype' => array(
                'class' => 'AndrewCarterUK\NoMoreLeaksBundle\Resetter\MonologResetter',
                'abstract' => true,
            ),
            'no_more_leaks.resetter.doctrine.entity_manager.default' => array(
                'prototype' => 'no_more_leaks.resetter.doctrine_entity_manager_resetter_prototype',
                'abstract' => false,
            ),
            'no_more_leaks.resetter.monolog' => array(
                'prototype' => 'no_more_leaks.resetter.monolog_prototype',
                'abstract' => false,
            ),
        );

        $this->assertDefinitions($definitions, $builder->getDefinitions());
    }

    /**
     * Assert that definitions match an expectation.
     * 
     * @param array $expectedDefinitions
     * @param Definition[] $definitions
     */
    private function assertDefinitions(array $expectedDefinitions, array $definitions)
    {
        $this->assertEquals(count($expectedDefinitions), count($definitions), 'Same number of definitions');

        foreach ($expectedDefinitions as $id => $expectedDefinition) {
            $this->assertArrayHasKey($id, $definitions);

            $definition = $definitions[$id];

            if (isset($expectedDefinition['class'])) {
                $this->assertEquals($expectedDefinition['class'], $definition->getClass());
            }

            if (isset($expectedDefinition['abstract'])) {
                $this->assertEquals($expectedDefinition['abstract'], $definition->isAbstract());
            }

            if (isset($expectedDefinition['prototype'])) {
                if (!$definition instanceof DefinitionDecorator) {
                    $this->fail('Definition should be decorated');
                }

                $this->assertEquals($expectedDefinition['prototype'], $definition->getParent());
            }
        }
    }
}