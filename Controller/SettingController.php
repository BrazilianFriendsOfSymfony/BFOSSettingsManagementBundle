<?php

namespace BFOS\SettingsManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @Route("/", name="bfos_settings")
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

        return array(
            'entities' => $entities,
        );

    }

    /**
     * Finds and displays a Setting entity.
     *
     * @Route("/{id}/show", name="bfos_settings_show")
     * @Template()
     */
    public function showAction($id)
    {
        $request = $this->getRequest();

        if(!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException($this->get('translator')->trans('Invalid Request'));
        }

        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BFOSSettingsManagementBundle:Setting')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Unable to find Setting entity'));
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Setting entity.
     *
     * @Route("/new", name="bfos_settings_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Setting();
        $form   = $this->createForm(new SettingFormType($this->container), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Setting entity.
     *
     * @Route("/create", name="bfos_settings_create")
     * @Method("post")
     * @Template("BFOSSettingsManagementBundle:Setting:new.html.twig")
     */
    public function createAction()
    {
        $request = $this->getRequest();

        if(!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException($this->get('translator')->trans('Invalid Request'));
        }
        $entity  = new Setting();
        $form    = $this->createForm(new SettingFormType($this->container), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->setFlash('notice', $this->get('translator')->trans('Record was created successfully'));

            $m = $request->get('_save_and_add');
            if(!is_null($m)) {
                return $this->redirect($this->generateUrl('bfos_settings_new'));
            } else {
                return $this->redirect($this->generateUrl('bfos_settings'));
            }
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Setting entity.
     *
     * @Route("/{id}/edit", name="bfos_settings_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BFOSSettingsManagementBundle:Setting')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Unable to find Setting entity'));
        }

        $setting_form = new \BFOS\SettingsManagementBundle\Form\SettingForm();
        $setting_form->setSetting($entity);
        $editForm = $this->createForm(new SettingFormType($this->container), $setting_form);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BFOSSettingsManagementBundle:Setting')->find($id);
        $entities = $em->getRepository('BFOSSettingsManagementBundle:Setting')->findAll();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Setting entity.');
        }

        $setting_form = new \BFOS\SettingsManagementBundle\Form\SettingForm();
        $setting_form->setSetting($entity);
        $editForm   = $this->createForm(new SettingFormType($this->container), $setting_form);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        $deleteForm = $this->createDeleteForm($id);

        $view_vars =  array(
            'entity'      => $entity,
            'entities'    => $entities,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );

        if ($editForm->isValid()) {
            $setting_form->updateSetting();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->setFlash('notice', $this->get('translator')->trans('Record was updated successfully'));

            return new Response($this->renderView('BFOSSettingsManagementBundle:Setting:index.html.twig', $view_vars));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Setting entity.
     *
     * @Route("/{id}/delete", name="bfos_settings_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $request = $this->getRequest();

        if(!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException($this->get('translator')->trans('Invalid Request'));
        }
        $form = $this->createDeleteForm($id);

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('BFOSSettingsManagementBundle:Setting')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException($this->get('translator')->trans('Unable to find Setting entity'));
            }

            $em->remove($entity);
            $em->flush();

            $this->get('session')->setFlash('notice', $this->get('translator')->trans('Record was removed successfully.'));

            $entities = $em->getRepository('BFOSSettingsManagementBundle:Setting')->findAll();

            return new Response($this->renderView('BFOSSettingsManagementBundle:Setting:index.html.twig', array('entities'=>$entities)), 205);
        }

        return $this->redirect($this->generateUrl('bfos_settings'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
            ;
    }
}
