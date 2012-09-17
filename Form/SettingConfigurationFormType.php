<?php

namespace BFOS\SettingsManagementBundle\Form;

use Symfony\Component\Form\AbstractType;
use BFOS\SettingsManagementBundle\Entity\Setting;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormBuilder;
use BFOS\SettingsManagementBundle\Manager\SettingsManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SettingConfigurationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $choices = SettingsManager::$allowedTypesForChoices;
        $builder
            ->add('name', 'text')
            ->add('type', 'choice', array('choices'=>$choices))
            ->add('label', 'text', array('required'=>false))
            ->add('grantedEditingFor', 'text', array('required'=>false))
            ->add('help', 'textarea', array('required'=>false))
        ;

    }

    public function getName()
    {
        return 'bfos_settings_setting_configuration_form_type';
    }
}
