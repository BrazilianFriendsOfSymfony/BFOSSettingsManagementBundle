<?php

namespace BFOS\SettingsManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BFOS\SettingsManagementBundle\Entity\Setting
 * BFOSSettingsManagementBundle:Setting
 *
 * @ORM\Table(name="bfos_settings",uniqueConstraints={@ORM\UniqueConstraint(name="name_unique_idx", columns={"name"})})
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class Setting
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var array $value
     *
     * @ORM\Column(name="value", type="array", nullable=true)
     *
     */
    private $value;

    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=150, nullable=true)
     */
    private $type;

    /**
     * @var string $label
     *
     * @ORM\Column(name="label", type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @var string $help
     *
     * @ORM\Column(name="help", type="text", nullable=true)
     */
    private $help;

    /**
     * @var array $granted_editing_for
     *
     * @ORM\Column(name="granted_editing_for", type="array")
     */
    private $granted_editing_for;

    /**
     * @var \DateTime $created_at
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

    /**
     * @var \DateTime $updated_at
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updated_at;

    function __construct()
    {
        $this->created_at = new \DateTime('now');
        $this->created_at->setTimezone(new \DateTimeZone('UTC'));
        $this->updated_at = new \DateTime('now');
        $this->updated_at->setTimezone(new \DateTimeZone('UTC'));

        $this->type = 'text';
    }

    /**
     * @ORM\PreUpdate
     */
    function preUpdate(){
        $this->updated_at = new \DateTime('now');
        $this->updated_at->setTimezone(new \DateTimeZone('UTC'));
    }

    /**
     * @param \DateTime $created_at
     */
    public function setCreatedAt($created_at)
    {
        $created_at->setTimezone(new \DateTimeZone('UTC'));
        $this->created_at = $created_at;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt($time_zone = null)
    {
        $dateTime = new \DateTime($this->created_at->format('Y-m-d H:i:s'), new \DateTimeZone('UTC'));
        if(is_null($time_zone)){
            $time_zone = date_default_timezone_get();
        }
        $dateTime->setTimezone(new \DateTimeZone($time_zone));
        return $dateTime;
    }

    /**
     * @param \DateTime $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $updated_at->setTimezone(new \DateTimeZone('UTC'));
        $this->updated_at = $updated_at;
    }

    /**
     * @param string $time_zone
     *
     * @return \DateTime
     */
    public function getUpdatedAt($time_zone = null)
    {
        $dateTime = new \DateTime($this->updated_at->format('Y-m-d H:i:s'), new \DateTimeZone('UTC'));
        if(is_null($time_zone)){
            $time_zone = date_default_timezone_get();
        }
        $dateTime->setTimezone(new \DateTimeZone($time_zone));
        return $dateTime;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set value
     *
     * @param array $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return array 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set granted_editing_for
     *
     * @param array $grantedEditingFor
     */
    public function setGrantedEditingFor($grantedEditingFor)
    {
        $this->granted_editing_for = $grantedEditingFor;
    }

    /**
     * Get granted_editing_for
     *
     * @return array 
     */
    public function getGrantedEditingFor()
    {
        return $this->granted_editing_for;
    }


    /**
     * Set label
     *
     * @param string $label
     * @return Setting
     */
    public function setLabel($label)
    {
        $this->label = $label;
    
        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set help
     *
     * @param string $help
     * @return Setting
     */
    public function setHelp($help)
    {
        $this->help = $help;
    
        return $this;
    }

    /**
     * Get help
     *
     * @return string 
     */
    public function getHelp()
    {
        return $this->help;
    }
}