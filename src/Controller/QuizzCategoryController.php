<?php


namespace Quizz\Controller;


use Silex\Application;

class QuizzCategoryController
{
    public function indexAction($id, Application $app)
    {
          $app['session']->clear();
        $category = $app['dao.quizz']->getCategory($id);
        return $app['twig']->render('category.html', array('category' => $category));
    }
}