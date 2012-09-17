<?php
namespace BFOS\SettingsManagementBundle\Form;

use Symfony\Component\Validator\Constraints as Assert;
use BFOS\SettingsManagementBundle\Entity\Setting;

class SettingForm
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
     * @var array $value
     *
     */
    private $value;

    /**
     * @var string $type
     */
    private $type;

    /**
     * @var string $html_template
     */
    private $html_template;

    /**
     * @var string $text_template
     */
    private $text_template;

    /**
     * @var string $emailName
     *
     *  @Assert\MinLength(limit=2, groups={"email_address"})
     */
    private $emailName;

    /**
     * @var string $emailAddress
     *
     * @Assert\NotBlank(groups={"email_address"})
     */
    private $emailAddress;


    /**
     * @param string $html_template
     */
    public function setHtmlTemplate($html_template)
    {
        $this->html_template = $html_template;
    }

    /**
     * @return string
     */
    public function getHtmlTemplate()
    {
        return $this->html_template;
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
     * @param string $text_template
     */
    public function setTextTemplate($text_template)
    {
        $this->text_template = $text_template;
    }

    /**
     * @return string
     */
    public function getTextTemplate()
    {
        return $this->text_template;
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
     * @param array $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return array
     */
    public function getValue()
    {
        return $this->value;
    }

    public function updateSetting(){
        $this->setting->setName($this->getName());
        $this->setting->setType($this->getType());
        if($this->getType()=='email_template'){
            $this->setting->setValue(array('text_template'=>$this->getTextTemplate(), 'html_template'=>$this->getHtmlTemplate()));
        } elseif($this->getType()=='email_address'){
            $this->setting->setValue(array('email_name'=>$this->getEmailName(), 'email_address'=>$this->getEmailAddress()));
        } else {
            $this->setting->setValue(array('value'=>$this->getValue()));
        }
    }

    /**
     * @param Setting $setting
     */
    public function setSetting(Setting $setting)
    {
        $this->setting = $setting;

        $this->setId($setting->getId());
        $value = $setting->getValue();

        // EMAIL TEMPLATE
        if($setting->getType()=='email_template' && !is_null($value) ){

            $this->setTextTemplate($value['text_template']);
            $this->setHtmlTemplate($value['html_template']);

        // EMAIL ADDRESS
        } elseif($setting->getType()=='email_address' && !is_null($value) ){
            if(isset($value['email_name'])){
                $this->setEmailName($value['email_name']);
            }
            if(isset($value['email_address'])){
                $this->setEmailAddress($value['email_address']);
            }

        // OTHER
        } else {
            $this->setValue($value['value']);
        }
        $this->setName($setting->getName());
        $this->setType($setting->getType());

    }

    /**
     * @return Setting
     */
    public function getSetting()
    {
        return $this->setting;
    }

    /**
     * @param string $emailAddress
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param string $emailName
     */
    public function setEmailName($emailName)
    {
        $this->emailName = $emailName;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmailName()
    {
        return $this->emailName;
    }


}
