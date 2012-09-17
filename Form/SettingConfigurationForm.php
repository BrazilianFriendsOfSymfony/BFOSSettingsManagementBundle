<?php
namespace BFOS\SettingsManagementBundle\Form;

use Symfony\Component\Validator\Constraints as Assert;
use BFOS\SettingsManagementBundle\Entity\Setting;

class SettingConfigurationForm
{
    /**
     * @var Setting $setting
     */
    private $setting;

    /**
     * @var integer $id
     *
     */
    private $id;

    /**
     * @var string $name
     *
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string $type
     *
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @var string $label
     */
    private $label;

    /**
     * @var string $help
     */
    private $help;

    /**
     * @var string $grantedEditingFor
     */
    private $grantedEditingFor;

    public function updateSetting(){
        $this->setting->setName($this->getName());
        $this->setting->setType($this->getType());
        $this->setting->setLabel($this->getLabel());
        $this->setting->setHelp($this->getHelp());
        $this->setting->setGrantedEditingFor(array_map('trim', explode(',', $this->getGrantedEditingFor())));
    }

    /**
     * @param Setting $setting
     */
    public function setSetting(Setting $setting)
    {
        $this->setting = $setting;

        $this->setId($setting->getId());
        $this->setName($setting->getName());
        $this->setType($setting->getType());
        $this->setLabel($setting->getLabel());
        $this->setHelp($setting->getHelp());
        $this->setGrantedEditingFor(implode(', ',$setting->getGrantedEditingFor()));

    }

    /**
     * @param string $help
     */
    public function setHelp($help)
    {
        $this->help = $help;
        return $this;
    }

    /**
     * @return Setting
     */
    public function getSetting()
    {
        return $this->setting;
    }

    /**
     * @return string
     */
    public function getHelp()
    {
        return $this->help;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $grantedEditingFor
     */
    public function setGrantedEditingFor($grantedEditingFor)
    {
        $this->grantedEditingFor = $grantedEditingFor;
        return $this;
    }

    /**
     * @return string
     */
    public function getGrantedEditingFor()
    {
        return $this->grantedEditingFor;
    }

}
