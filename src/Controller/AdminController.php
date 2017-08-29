<?php
/**
 * Created by PhpStorm.
 * User: mupac_000
 * Date: 17-08-17
 * Time: 15:59
 */

namespace Quizz\Controller;


use Silex\Application;

class AdminController
{
    public function indexAction(Application $app)
    {
         $app['session']->clear();
        $categories = $app['dao.quizz']->getAllCategories();
        return $app['twig']->render('admin.html', array('categories' => $categories));
    }

    public function LoadPageAction(Application $app)
    {
        return $app['twig']->render('loadPage.html');
    }
}