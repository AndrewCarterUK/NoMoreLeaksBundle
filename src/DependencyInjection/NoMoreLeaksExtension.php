<?php

namespace AndrewCarterUK\NoMoreLeaksBundle\DependencyInjection;
    
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

class NoMoreLeaksExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (!empty($config)) {
            $this->process($container, $config);
        }
    }

    private function process(ContainerBuilder $container, array $config)
    {
        $listenerDefinition = $container->getDefinition('no_more_leaks.listener.terminate');

        if (!empty($config['doctrine'])) {
            $this->processDoctrine($container, $listenerDefinition, $config['doctrine']);
        }

        if (!empty($config['monolog'])) {
            $this->processMonolog($container, $listenerDefinition, $config['monolog']);
        }
    }
 
    private function processDoctrine(ContainerBuilder $container, Definition $listenerDefinition, array $config)
    {
        if (!$config['enabled']) {
            return;
        }

        if (empty($config['managers'])) {
            $config['managers'] = array('default');
        }

        foreach ($config['managers'] as $manager) {
            $managerPrototype = new DefinitionDecorator('no_more_leaks.resetter.doctrine_entity_manager_resetter_prototype');
            $managerPrototype->replaceArgument(0, new Reference('doctrine.orm.' . $manager . '_entity_manager'));
            $serviceId = 'no_more_leaks.resetter.doctrine.entity_manager.' . $manager;
            $container->setDefinition($serviceId, $managerPrototype);
            $this->addResetter($listenerDefinition, $serviceId);
        }
    }

    private function processMonolog(ContainerBuilder $container, Definition $listenerDefinition, array $config)
    {
        if (!$config['enabled']) {
            return;
        }

        if (empty($config['channels'])) {
            $config['channels'] = array('app');
        }

        foreach ($config['channels'] as $channel) {
            $monologPrototype = new DefinitionDecorator('no_more_leaks.resetter.monolog_prototype');

            if ('app' === $channel) {
                $serviceId = 'no_more_leaks.resetter.monolog';
            } else {
                $monologPrototype->replaceArgument(0, new Reference('monolog.logger.' . $channel));
                $serviceId = 'no_more_leaks.resetter.monolog.' . $channel;
            }

            $container->setDefinition($serviceId, $monologPrototype);
            $this->addResetter($listenerDefinition, $serviceId);
        }
    }

    private function addResetter(Definition $listenerDefinition, $resetterId)
    {
        $listenerDefinition->addMethodCall('addResetter', array(new Reference($resetterId)));
    }
}
