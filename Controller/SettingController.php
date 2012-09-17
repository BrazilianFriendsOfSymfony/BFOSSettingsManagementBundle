<?php

namespace BFOS\SettingsManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BFOS\SettingsManagementBundle\Form\SettingForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BFOS\SettingsManagementBundle\Entity\Setting;
use BFOS\SettingsManagementBundle\Form\SettingFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Setting controller.
 *
 * @Route("/bfos/setting")
 */
class SettingController extends Controller
{
    /**
     * Lists all Setting entities.
     *
     * @Route("/", name="bfos_settings", requirements={"_method": "get"})
     * @Template()
     */
    public function indexAction()
    {
        /**
         * @var \Doctrine\ORM\EntityManager $em
         */
        $em = $this->getDoctrine()->getEntityManager();

        $entity_name = 'BFOSSettingsManagementBundle:Setting';

        $entities = $em->getRepository($entity_name)->findAll();

        $view_vars = array(
            'entities' => $entities,
            'dev_role' => $this->container->getParameter('bfos_settings.developer_role')
        );
        if($this->getRequest()->query->has('nojs') && $this->getRequest()->query->get('nojs')){
            return $this->render("BFOSSettingsManagementBundle:Setting:index_table.html.twig", $view_vars);
        }
        return $view_vars;

    }

    private function grantedEditing(Setting $setting){

        $granted = false;
        foreach($setting->getGrantedEditingFor() as $role){
            if($this->get('security.context')->isGranted($role)){
                $granted = true;
                break;
            }
        }
        return $granted;
    }
    /**
     * Finds and displays a Setting entity.
     *
     * @Route("/{id}/show", name="bfos_settings_show", requirements={"_method": "get"})
     * @Template()
     */
    public function showAction($id)
    {
        $request = $this->getRequest();

        if(!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException($this->get('translator')->trans('Invalid Request'));
        }

        $em = $this->getDoctrine()->getEntityManager();

        /**
         * @var Setting $entity
         */
        $entity = $em->getRepository('BFOSSettingsManagementBundle:Setting')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Unable to find Setting entity'));
        }


        if(!$this->grantedEditing($entity)){
            return new Response('', 403);
        }


        return array(
            'entity'      => $entity
        );
    }

    /**
     * Displays a form to edit an existing Setting entity.
     *
     * @Route("/{id}/edit", name="bfos_settings_edit", requirements={"_method": "get"})
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BFOSSettingsManagementBundle:Setting')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Unable to find Setting entity'));
        }


        if(!$this->grantedEditing($entity)){
            return new Response('', 403);
        }

        $setting_form = new SettingForm();
        $setting_form->setSetting($entity);
        $editForm = $this->createForm(new SettingFormType($this->container), $setting_form);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
     * Edits an existing Setting entity.
     *
     * @Route("/{id}/update", name="bfos_settings_update")
     * @Method("post")
     * @Template("BFOSSettingsManagementBundle:Setting:edit.html.twig")
     */
    public function updateAction($id)
    {
        $request = $this->getRequest();
        if(!$request->isXmlHttpRequest()){
            throw $this->createNotFoundException('Invalid request');
        }
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BFOSSettingsManagementBundle:Setting')->find($id);
        $entities = $em->getRepository('BFOSSettingsManagementBundle:Setting')->findAll();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Setting entity.');
        }

        if(!$this->grantedEditing($entity)){
            return new Response('', 403);
        }

        $validationGroup = $entity->getType();
        $setting_form = new SettingForm();
        $setting_form->setSetting($entity);
        $editForm   = $this->createForm(new SettingFormType($this->container), $setting_form, array('validation_groups' => array($validationGroup)));


        $editForm->bind($request);

        $view_vars =  array(
            'entity'      => $entity,
            'entities'    => $entities,
            'edit_form'   => $editForm->createView(),
            'dev_role' => $this->container->getParameter('bfos_settings.developer_role')
        );

        if ($editForm->isValid()) {
            $setting_form->updateSetting();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->setFlash('notice', $this->get('translator')->trans('Record was updated successfully'));

            return $this->render('BFOSSettingsManagementBundle:Setting:index_table.html.twig', $view_vars);
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

}
