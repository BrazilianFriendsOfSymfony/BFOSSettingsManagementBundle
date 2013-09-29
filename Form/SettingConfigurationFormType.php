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
            ->add('name', 'text', array('label'=>'bfos.settings.form.name.label'))
            ->add('type', 'choice', array('choices'=>$choices, 'label'=>'bfos.settings.form.type.label'))
            ->add('label', 'text', array('required'=>false, 'label'=>'bfos.settings.form.label.label'))
            ->add('grantedEditingFor', 'text', array('required'=>false, 'label'=>'bfos.settings.form.granted_editing_for.label'))
            ->add('help', 'textarea', array('required'=>false, 'label'=>'bfos.settings.form.help.label'))
        ;

    }

    public function getName()
    {
        return 'bfos_settings_setting_configuration_form_type';
    }
}
