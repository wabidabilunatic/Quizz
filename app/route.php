<?php 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Quizz\Domain\Category;
use Doctrine\DBAL\Schema\Table;
use Silex\Route\SecurityTrait;
use Quizz\DAO\QuizzDAO;

$app->get('/',function() use ($app){
     return $app->redirect($app["url_generator"]->generate("quizz_home"));
});
/***
 *  first page of the quizz
 */
    //index
    $app->get('/quizz/',"Quizz\Controller\QuizzIndexController::indexAction")->bind('quizz_home');
    //category page

    $app->get('/category/{id}',"Quizz\Controller\QuizzCategoryController::indexAction")->bind('quizz_category');

    //uploadPage
    $app->get('/quizz/admin/uploadPage',"Quizz\Controller\QuizzIndexController::LoadPageAction")->bind('uploadPage');


/***
 * quizz page
 */

$app->match('/quizz/test/{category}_{numTest}_{numQuestion}',"Quizz\Controller\QuizzTesterController::indexAction")
->value('numQuestion',1)
->bind('quizz_question')->method('GET|POST');


/***
 * Forms
 * Load files from form
 */
$app->post('/load',"Quizz\Controller\LoadImageController::loadAction")->bind('loadImage');


 /***
  * REST API
  */

/***
 * get categories
 */
$app->get('/api/categories',"Quizz\Controller\ApiRestController::getAllCategoriesName")->bind('api_categories');


/***
 * Script to read files from folders
 * then put into db 
 */
$app->get('/script/PutFilesIntoDb',"Quizz\Controller\ScriptController::putFilesIntoDb");
$app->get('/script/clearDb',"Quizz\Controller\ScriptController::clearDb");

$app->get('/script/createTable',
    function() use ($app) {

        $schema = $app['db']->getSchemaManager();
        if (!$schema->tablesExist(QuizzDAO::$dbName)) 
        {
            $table = new Table(QuizzDAO::$dbName);
            // create id
            $table->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
            $table->setPrimaryKey(array('id'));
            
            //create category 
            $table->addColumn(QuizzDAO::$nameCategory, 'string', array('length' => 3, 'autoincrement' => true));

            //create numTest
            $table->addColumn(QuizzDAO::$nameNumTest, 'integer',  array('unsigned' => false));
            
            // create nameNumQuestion
              $table->addColumn(QuizzDAO::$nameNumQuestion, 'integer',  array('unsigned' => false));
            
            
            //create nameQuantityAns
              $table->addColumn(QuizzDAO::$nameQuantityAns, 'integer',  array('unsigned' => false));
              
              
              
            //create nameNumCorrectAns
              $table->addColumn(QuizzDAO::$nameNumCorrectAns, 'integer',  array('unsigned' => false));
            
    
            $table->addOption('engine' , 'MyISAM');

            $schema->createTable($table);
            
            return new Response("okay created");
        }
        else
        {
             return new Response("impossible to create");
        }
            
           
           } );

$app->get('/script/setUpUsers',

    function() use ($app) {

        $schema = $app['db']->getSchemaManager();
        if (!$schema->tablesExist('users')) {
            $users = new Table('users');
            $users->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
            $users->setPrimaryKey(array('id'));
            $users->addColumn('username', 'string', array('length' => 32));
            $users->addUniqueIndex(array('username'));
            $users->addColumn('password', 'string', array('length' => 255));
            $users->addColumn('roles', 'string', array('length' => 255));

            $schema->createTable($users);


            $passwordUser = "student";
            $passwordTeacher = "teacher";



            $app['db']->insert('users', array(
                'username' => 'student',
                'password' => $app['security.default_encoder']->encodePassword($passwordUser, ''),
                'roles' => 'ROLE_USER'
            ));

            $app['db']->insert('users', array(
                'username' => 'admin',
                'password' => $app['security.default_encoder']->encodePassword($passwordTeacher,''),
                'roles' => 'ROLE_ADMIN'
            ));
            return new Response("success");
        }else
            return new Response("fail");
    });

/*
$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    switch ($code) {
        case 403:
            $message = 'Access denied.';
            break;
        case 404:
            $message = 'The requested resource could not be found.';
            break;
        default:
            $message = "Something went wrong.";
    }
    return $app['twig']->render('error.html.twig', array('message' => $message));
});*/

$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})->bind('login');