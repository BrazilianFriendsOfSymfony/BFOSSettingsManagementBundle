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
     * @var string $from_name
     */
    private $from_name;

    /**
     * @var string $from_email
     */
    private $from_email;

    /**
     * @var string to_name
     */
    private $to_name;

    /**
     * @var string $to_email
     */
    private $to_email;

    /**
     * @var string $subject
     *
     * @Assert\NotBlank(groups={"email_notification"})
     */
    private $subject;

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
        } elseif($this->getType()=='email_notification'){
            $this->setting->setValue(array(
                'text_template'=>$this->getTextTemplate(),
                'html_template'=>$this->getHtmlTemplate(),
                'from_name' => $this->getFromName(),
                'from_email' => $this->getFromEmail(),
                'to_name' => $this->getToName(),
                'to_email' => $this->getToEmail(),
                'subject' => $this->getSubject()
            ));
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

            if(isset($value['text_template'])){
                $this->setTextTemplate($value['text_template']);
            }
            if(isset($value['html_template'])){
                $this->setHtmlTemplate($value['html_template']);
            }

        } elseif($setting->getType()=='email_notification' && !is_null($value) ){

            if(isset($value['text_template'])){
                $this->setTextTemplate($value['text_template']);
            }
            if(isset($value['html_template'])){
                $this->setHtmlTemplate($value['html_template']);
            }
            if(isset($value['from_name'])){
                $this->setFromName($value['from_name']);
            }
            if(isset($value['from_email'])){
                $this->setFromEmail($value['from_email']);
            }
            if(isset($value['to_name'])){
                $this->setToName($value['to_name']);
            }
            if(isset($value['to_email'])){
                $this->setToEmail($value['to_email']);
            }
            if(isset($value['subject'])){
                $this->setSubject($value['subject']);
            }

        } elseif($setting->getType()=='email_address' && !is_null($value) ){  // EMAIL ADDRESS

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

    /**
     * @param string $from_email
     */
    public function setFromEmail($from_email)
    {
        $this->from_email = $from_email;
        return $this;
    }

    /**
     * @return string
     */
    public function getFromEmail()
    {
        return $this->from_email;
    }

    /**
     * @param string $from_name
     */
    public function setFromName($from_name)
    {
        $this->from_name = $from_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getFromName()
    {
        return $this->from_name;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $to_email
     */
    public function setToEmail($to_email)
    {
        $this->to_email = $to_email;
        return $this;
    }

    /**
     * @return string
     */
    public function getToEmail()
    {
        return $this->to_email;
    }

    /**
     * @param string $to_name
     */
    public function setToName($to_name)
    {
        $this->to_name = $to_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getToName()
    {
        return $this->to_name;
    }


}
