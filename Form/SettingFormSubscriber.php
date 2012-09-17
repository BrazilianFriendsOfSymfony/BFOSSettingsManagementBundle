<?php
namespace BFOS\SettingsManagementBundle\Form;


use Symfony\Component\Form\Event\DataEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SettingFormSubscriber implements EventSubscriberInterface
{
    private $factory;

    private $container;

    public function __construct(FormFactoryInterface $factory, ContainerInterface $container)
    {
        $this->factory = $factory;
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that we want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(DataEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        // During form creation setData() is called with null as an argument
        // by the FormBuilder constructor. We're only concerned with when
        // setData is called with an actual Entity object in it (whether new,
        // or fetched with Doctrine). This if statement let's us skip right
        // over the null condition.
        if (null === $data) {
            return;
        }

            // check if the product object is not "new"
        if($data->getType() == 'email_template'){
            $form->add($this->factory->createNamed('html_template', 'textarea'));
            $form->add($this->factory->createNamed('text_template', 'textarea'));
        } else if($data->getType() == 'boolean'){
            $form->add($this->factory->createNamed('value', 'checkbox'));
        } else if($data->getType() == 'email_address'){
            $form->add($this->factory->createNamed('emailName', 'text'));
            $form->add($this->factory->createNamed('emailAddress', 'text'));
        } else if($data->getType() == 'html'){
            $form->add($this->factory->createNamed('value', 'textarea'));
        } else if($data->getType() == 'integer'){
            $form->add($this->factory->createNamed('value', 'integer'));
        } else if($data->getType() == 'number'){
            $form->add($this->factory->createNamed('value', 'number'));
        } else {
            $form->add($this->factory->createNamed('value', 'text', null,  array('required'=>false)));

        }
    }
}
