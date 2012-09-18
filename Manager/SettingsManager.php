<?php

namespace BFOS\SettingsManagementBundle\Manager;

use BFOS\SettingsManagementBundle\Entity\Setting;
use Doctrine\ORM\EntityRepository;

class SettingsManager
{
    static public $allowedTypes = array(
        'text',
        'email_template',
        'email_address',
        'boolean',
        'integer',
        'number',
        'html'
    );
    static public $allowedTypesForChoices = array(
        'text' => 'Text',
        'email_template' => 'E-mail template',
        'email_address' => 'E-mail address',
        'boolean' => 'Yes/No',
        'integer' => 'Integer',
        'number' => 'Number',
        'html' => 'HTML Excerpt'
    );

    private $container;

    function __construct(\Symfony\Component\DependencyInjection\ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Creates a Setting entity.
     *
     * @param string  $name
     * @param string  $type
     * @param array  $value
     * @param array  $granted_editing_for
     *
     * @return Setting
     */
    public function createSetting($name, $type = 'text', $value = null, $granted_editing_for = null, $label = null, $help = null)
    {
        if(!in_array($type, self::$allowedTypes)){
            throw new \Exception('Setting type is invalid');
        }

        $em = $this->container->get('doctrine')->getEntityManager();
        $setting = new Setting();
        $setting->setName($name);
        $setting->setType($type);
        $setting->setLabel($label);
        $setting->setHelp($help);
        if($value) {
            $setting->setValue($value);
        }
        if($granted_editing_for) {
            $setting->setGrantedEditingFor($granted_editing_for);
        } else {
            $setting->setGrantedEditingFor(array('ROLE_ADMIN'));
        }

        $em->persist($setting);
        $em->flush();

        return $setting;
    }

    /**
     * Updates a Setting entity.
     *
     * @param string  $name
     * @param string  $type
     * @param array  $value
     * @param array  $granted_editing_for
     *
     * @return boolean
     */
    public function updateSetting($name, $type = null, $value = null, $granted_editing_for = null, $label = null, $help = null)
    {
        if(!in_array($type, self::$allowedTypes)){
            throw new \Exception('Setting type is invalid');
        }

        /**
         * @var \Doctrine\ORM\EntityManager $em
         */
        $em = $this->container->get('doctrine')->getEntityManager();
        /**
         * @var EntityRepository $rsetting
         */
        $rsetting = $em->getRepository('BFOSSettingsManagementBundle:Setting');

        if($name) {
            /**
             * @var Setting $esetting
             */
            $esetting = $rsetting->findOneBy(array('name'=>$name));
            if(!$esetting){
                return false;
            }
        } else {
            return false;
        }

        if($type) {
            $esetting->setType($type);
        }
        if($value) {
            $esetting->setValue($value);
        }
        if($granted_editing_for) {
            $esetting->setGrantedEditingFor($granted_editing_for);
        } else {
            $esetting->setGrantedEditingFor(array('ROLE_ADMIN'));
        }
        $esetting->setLabel($label);
        $esetting->setHelp($help);

        $em->persist($esetting);
        $em->flush();

        return true;
    }

    /**
     * Deletes a Setting entity.
     *
     * @param string  $name
     *
     * @return boolean
     */
    public function deleteSetting($name)
    {
        /**
         * @var \Doctrine\ORM\EntityManager $em
         */
        $em = $this->container->get('doctrine')->getEntityManager();
        /**
         * @var EntityRepository $rsetting
         */
        $rsetting = $em->getRepository('BFOSSettingsManagementBundle:Setting');

        if($name) {
            /**
             * @var Setting $esetting
             */
            $esetting = $rsetting->findOneBy(array('name'=>$name));
            if(!$esetting){
                return false;
            }
        } else {
            return false;
        }

        $em->remove($esetting);
        $em->flush();

        return true;
    }


    /**
     * Get Setting entities by name.
     *
     * @param string $name
     *
     * @return array
     */
    public function getSettingByName($name)
    {
        /**
         * @var \Doctrine\ORM\EntityManager $em
         */
        $em = $this->container->get('doctrine')->getEntityManager();
        /**
         * @var EntityRepository $rsetting
         */
        $rsetting = $em->getRepository('BFOSSettingsManagementBundle:Setting');

        return $rsetting->findOneBy(array('name'=>$name));
    }

    /**
     * Get Setting value by name.
     *
     * @param string $name
     * @param null|mixed $default
     *
     * @return array
     */
    public function getValue($name, $default = null)
    {
        /**
         * @var \Doctrine\ORM\EntityManager $em
         */
        $em = $this->container->get('doctrine')->getEntityManager();
        /**
         * @var EntityRepository $rsetting
         */
        $rsetting = $em->getRepository('BFOSSettingsManagementBundle:Setting');
        /**
         * @var Setting $entity
         */
        $entity = $rsetting->findOneBy(array('name'=>$name));

        if($entity->getValue()!==null) {
            $v = $entity->getValue();
            if(in_array($entity->getType(), array('email_template','email_address'))){
                return $v;
            } elseif($entity->getType()=='integer'){
                return (int) $v['value'];
            } elseif($entity->getType()=='number'){
                return (float) $v['value'];
            } else if($entity->getType()=='boolean'){
                if($v['value']){
                    return true;
                } else {
                    return false;
                }
            }
            return $v['value'];
        } else {
            return $default;
        }
    }

    /**
     * Get the type options
     *
     * @return array
     */
    public function getTypeOptions()
    {
        return array(
            'text'           => 'Text',
            'email_template' => 'E-mail Template'
        );
    }
}
