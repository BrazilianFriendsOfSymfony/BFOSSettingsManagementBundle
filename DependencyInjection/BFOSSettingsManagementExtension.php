<?php

namespace BFOS\SettingsManagementBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BFOSSettingsManagementExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $admin_role = 'ROLE_ADMIN';
        if(isset($config['security']['admin_role'])){
            $admin_role = $config['security']['admin_role'];
        }
        $container->setParameter('bfos_settings.admin_role', $admin_role);

        $super_dev_role = 'ROLE_SUPER_ADMIN';
        if(isset($config['security']['developer_role'])){
            $super_dev_role = $config['security']['developer_role'];
        }
        $container->setParameter('bfos_settings.developer_role', $super_dev_role);


        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}
