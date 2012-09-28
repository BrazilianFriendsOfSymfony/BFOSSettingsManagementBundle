<?php

namespace BFOS\SettingsManagementBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class TwigExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    private $container;

    /**
     * @var \Twig_Environment
     */
    protected $env;

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->env = $environment;
    }

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            'bfos_settings_management'    => new \Twig_Function_Method($this, 'settingManagement', array('is_safe' => array('html'))),
//            'duo_base_admin_delete_form' => new \Twig_Function_Method($this, 'deleteForm', array('is_safe' => array('html'))),
//            'duo_base_admin_delete_link' => new \Twig_Function_Method($this, 'deleteLink', array('is_safe' => array('html'))),
        );
    }


    public function settingManagement()
    {
        return $this->container->get('templating.helper.actions')->render('BFOSSettingsManagementBundle:Setting:index');

        /*switch ($view) {
            case 'edit':
                return $this->env->render('BFOSSettingsManagementBundle:Setting:edit.html.twig', array('entities' => $entities));
            case 'new':
                return $this->env->render('BFOSSettingsManagementBundle:Setting:new.html.twig', array('entities' => $entities));
            case 'show':
                return $this->env->render('BFOSSettingsManagementBundle:Setting:show.html.twig', array('entities' => $entities));
            default:
                return $this->env->render('BFOSSettingsManagementBundle:Setting:index.html.twig', array('entities' => $entities));
        }*/

    }

    public function deleteForm($id, $url_action = null, $form_id_prefix = 'form_delete_'){
        $form_id = $form_id_prefix . $id;
        $form = $this->container->get('form.factory')->createNamedBuilder('form', $form_id, array('id' => $id));
        $form_view = $form
            ->add('id', 'hidden')
            ->getForm()->createView();
        return $this->env->render('DuoAdminBundle:TwigExtension:form_delete.html.twig', array('form' => $form_view, 'url_action' => $url_action, 'form_id' => $form_id));
    }

    public function deleteLink($id, $url_action = null, $form_id_prefix = 'form_delete_'){
        return $this->env->render('DuoAdminBundle:TwigExtension:link_delete.html.twig', array('id' => $id, 'url_action' => $url_action, 'form_id_prefix' => $form_id_prefix));
    }

    private function getQueryStringArray($url){
        $params = array();
        $qs = parse_url($url, PHP_URL_QUERY);
        $qsa = explode('&', $qs);
        foreach($qsa as $qi){
            $tmp = explode('=', $qi);
            if(count($tmp)>1){
                $params[$tmp[0]] = $tmp[1];
            } else {
                $params[$tmp[0]] = '';
            }
        }
        return $params;
    }

    private function buildUrlFromQueryStringArray($url, array $params){
        $qs = '';
        foreach($params as $key => $value){
            if($qs){
                $qs .= "&";
            }
            $qs .= "$key=$value";
        }
        $tmp = explode('?', $url);
        $url = $tmp[0] . '?' . $qs;
        return $url;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bfos_setting_management';
    }
}

