<?php

namespace Topxia\AdminBundle\Controller;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;

class ThemeController extends BaseController
{
    public function indexAction(Request $request)
    {
        $currentTheme = $this->setting('theme', array('uri' => 'default'));

        $themes = $this->getThemes();

        return $this->render('TopxiaAdminBundle:Theme:index.html.twig', array(
            'themes'       => $themes,
            'currentTheme' => $currentTheme
        ));
    }

    public function changeAction(Request $request)
    {
        $themeUri = $request->query->get('uri');

        $theme = $this->getTheme($themeUri);

        if (empty($theme)) {
            return $this->createJsonResponse(false);
        }

        $this->getSettingService()->set('theme', $theme);

        return $this->createJsonResponse(true);
    }

    public function saveConfigAction(Request $request, $uri)
    {
        $config      = $request->request->get('config');
        $currentData = $request->request->get('currentData');
        $config      = $this->getThemeService()->saveCurrentThemeConfig($config);

        if ($currentData) {
            return $this->render('TopxiaAdminBundle:Theme:theme-edit-config-li.html.twig', array(
                'pendant' => $currentData,
                'uri'     => $uri
            ));
        }

        return $this->createJsonResponse(true);
    }

    public function confirmConfigAction(Request $request, $uri)
    {
        $this->getThemeService()->saveConfirmConfig();
        return $this->redirect($this->generateUrl('admin_theme_manage', array('uri' => $uri), true));
    }

    public function manageIndexAction(Request $request, $uri)
    {
        // if (!$this->getThemeService()->isAllowedConfig()) {
        //     return $this->redirect($this->generateUrl('admin_setting_theme'));
        // }

        $themeConfig = $this->getThemeService()->getCurrentThemeConfig();

        return $this->render('TopxiaAdminBundle:Theme:edit.html.twig', array(
            'themeConfig' => $themeConfig['config'],
            'allConfig'   => $themeConfig['allConfig'],
            'themeUri'    => $uri
        ));
    }

    public function resetConfigAction(Request $request, $uri)
    {
        //var_dump($uri);
        //exit();
        $this->getThemeService()->resetConfig();
        return $this->redirect($this->generateUrl('admin_theme_manage', array('uri' => $uri), true));
    }

    public function showAction(Request $request, $uri)
    {
        $themeConfig = $this->getThemeService()->getCurrentThemeConfig();

        return $this->render('TopxiaWebBundle:Default:show.html.twig', array(
            'themeConfig' => $themeConfig['config'],
            'allConfig'   => $themeConfig['allConfig'],
            'isEditColor' => true
        ));
    }

    public function themeConfigEditAction(Request $request, $uri)
    {
        $config = $request->query->get('config');

        $code = "edit".$this->fiterCode($config['code']);

        return $this->$code($config);
    }

    protected function fiterCode($code)
    {
        $codes = explode('-', $code);
        $code  = '';

        foreach ($codes as $value) {
            $code .= ucfirst($value);
        }

        return $code;
    }

    protected function getTheme($uri)
    {
        if (empty($uri)) {
            return;
        }

        $dir = $this->container->getParameter('kernel.root_dir').'/../web/themes';

        $metaPath = $dir.'/'.$uri.'/theme.json';

        if (!file_exists($metaPath)) {
            return;
        }

        $theme = json_decode(file_get_contents($metaPath), true);

        if (empty($theme)) {
            return;
        }

        $theme['uri'] = $uri;

        return $theme;
    }

    protected function getThemes()
    {
        $themes = array();

        $dir    = $this->container->getParameter('kernel.root_dir').'/../web/themes';
        $finder = new Finder();

        foreach ($finder->directories()->in($dir)->depth('== 0') as $directory) {
            $theme = $this->getTheme($directory->getBasename());

            if ($theme) {
                $themes[] = $theme;
            }
        }

        return $themes;
    }

    private function editCourseGridWithConditionIndex($config)
    {
        return $this->render('TopxiaWebBundle:Theme:edit-course-grid-with-condition-index-modal.html.twig', array(
            'config' => $config
        ));
    }

    private function editGroups($config)
    {
        return $this->render('TopxiaWebBundle:Theme:edit-groups-modal.html.twig', array(
            'config' => $config
        ));
    }

    private function editLiveCourse($config)
    {
        return $this->render('TopxiaWebBundle:Theme:edit-live-course-modal.html.twig', array(
            'config' => $config
        ));
    }

    private function editRecommendTeacher($config)
    {
        return $this->render('TopxiaWebBundle:Theme:edit-recommend-teacher-modal.html.twig', array(
            'config' => $config
        ));
    }

    protected function getSettingService()
    {
        return $this->getServiceKernel()->createService('System.SettingService');
    }

    protected function getThemeService()
    {
        return $this->getServiceKernel()->createService('Theme.ThemeService');
    }
}
