<?php

namespace BFOS\SettingsManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BFOS\SettingsManagementBundle\Form\SettingConfigurationForm;
use BFOS\SettingsManagementBundle\Form\SettingConfigurationFormType;
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
 * @Route("/bfos/setting/dev")
 */
class SettingDeveloperController extends Controller
{

    /**
     * Displays a form to edit an existing Setting entity.
     *
     * @Route("/{id}/edit", name="bfos_settings_dev_edit", requirements={"_method": "get"})
     * @Template()
     */
    public function editAction($id)
    {
        if(!$this->get('security.context')->isGranted($this->container->getParameter('bfos_settings.developer_role'))){
            return new Response('', 403);
        }

        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BFOSSettingsManagementBundle:Setting')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Unable to find Setting entity'));
        }

        $data = new SettingConfigurationForm();
        $data->setSetting($entity);
        $editForm = $this->createForm(new SettingConfigurationFormType(), $data);
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
     * @Route("/{id}/update", name="bfos_settings_dev_update")
     * @Method("post")
     * @Template("BFOSSettingsManagementBundle:Setting:edit.html.twig")
     */
    public function updateAction($id)
    {
        if(!$this->get('security.context')->isGranted($this->container->getParameter('bfos_settings.developer_role'))){
            return new Response('', 403);
        }

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

        $data = new SettingConfigurationForm();
        $data->setSetting($entity);
        $editForm   = $this->createForm(new SettingConfigurationFormType(), $data);


        $editForm->bind($request);

        $deleteForm = $this->createDeleteForm($id);

        $view_vars =  array(
            'entity'      => $entity,
            'entities'    => $entities,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );

        if ($editForm->isValid()) {
            $data->updateSetting();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->setFlash('notice', $this->get('translator')->trans('Record was updated successfully'));

            $view_vars['dev_role'] = $this->container->getParameter('bfos_settings.developer_role');
            return $this->render('BFOSSettingsManagementBundle:Setting:index_table.html.twig', $view_vars);
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
     * @Route("/{id}/delete", name="bfos_settings_dev_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        if(!$this->get('security.context')->isGranted($this->container->getParameter('bfos_settings.developer_role'))){
            return new Response('', 403);
        }

        $request = $this->getRequest();

        if(!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException($this->get('translator')->trans('Invalid Request'));
        }
        $form = $this->createDeleteForm($id);

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('BFOSSettingsManagementBundle:Setting')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException($this->get('translator')->trans('Unable to find Setting entity'));
            }

            if(!$this->grantedEditing($entity)){
                return new Response('', 403);
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
