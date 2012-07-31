<?php

namespace BFOS\SettingsManagementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use BFOS\SettingsManagementBundle\Manager\ManagerSettings;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SettingFormType extends AbstractType
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        /**
         * @var ManagerSettings $msetting
         */
        $msetting = $this->container->get('bfos_setting_management.setting_manager');

        $builder
            ->add('name')
            ->add('value', 'textarea', array('required'=>false))
            ->add('granted_editing_for', 'choice', array(
                'choices' => $msetting->getRolesOptions(),
                'multiple' => true,
                'label' => 'Regras',
                'required'=> false
            ))
        ;

        $subscriber = new SettingFormSubscriber($builder->getFormFactory(), $this->container);
        $builder->addEventSubscriber($subscriber);

    }

    public function getName()
    {
        return 'bfos_settingsmanagementbundle_setting_type';
    }
}
