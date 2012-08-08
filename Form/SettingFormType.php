<?php

namespace BFOS\SettingsManagementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormBuilder;
use BFOS\SettingsManagementBundle\Manager\SettingsManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SettingFormType extends AbstractType
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $subscriber = new SettingFormSubscriber($builder->getFormFactory(), $this->container);
        $builder->addEventSubscriber($subscriber);

    }

    public function getName()
    {
        return 'bfos_settingsmanagementbundle_setting_type';
    }
}
