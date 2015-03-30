<?php

namespace Michcald\FsmBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Michcald\Fsm\Model\Fsm;
use Michcald\Fsm\Model\State;
use Michcald\Fsm\Model\Transition;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MichcaldFsmExtension extends Extension
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

        foreach ($config['machines'] as $fsmName => $fsmConfig) {

            $fsmDefinition = $container
                ->register(sprintf('michcald_fsm.%s', $fsmName), 'Michcald\Fsm\Model\Fsm')
                ->setArguments(array($fsmName))
            ;

            foreach ($fsmConfig['states'] as $stateName => $stateConfig) {

                switch ($stateConfig['type']) {
                    case 'initial':
                        $stateType = FsmState::TYPE_START;
                        break;
                    case 'final':
                        $stateType = FsmState::TYPE_END;
                        break;
                    default:
                        $stateType = FsmState::TYPE_NORMAL;
                }

                $fsmDefinition->addMethodCall(
                    'addState',
                    array(
                        new Definition('Michcald\Fsm\Model\FsmState', array($stateName, $stateType))
                    )
                );
            }

            foreach ($fsmConfig['transitions'] as $transactionName => $transactionConfig) {
                $fsmDefinition->addMethodCall(
                    'addTransaction',
                    array(
                        new Definition(
                            'Michcald\Fsm\Model\FsmTransaction',
                            array($transactionName, $transactionConfig['from'], $transactionConfig['to'])
                        )
                    )
                );
            }



        }
    }
}
