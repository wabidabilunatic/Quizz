<?php
/**
 * Created by PhpStorm.
 * User: mupac_000
 * Date: 17-08-17
 * Time: 15:55
 */

namespace Quizz\Controller;


use Quizz\DAO\QuizzDAO;
use Silex\Application;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    
    
class ScriptController
{
    public function saveInDb($nameOfFile,$app)
    {
        $category = substr($nameOfFile,0,2);
        $numOfTest= substr($nameOfFile,2,3);
        $numOfQuestion = substr($nameOfFile,5,2);
        $correctAnswer = substr($nameOfFile,7,1);
        $answers = substr($nameOfFile,8,1);

        $app['db']->insert(QuizzDAO::$dbName, array(QuizzDAO::$nameCategory=>strval($category),QuizzDAO::$nameNumTest=>$numOfTest,QuizzDAO::$nameNumQuestion=>$numOfQuestion,QuizzDAO::$nameQuantityAns=> $answers,QuizzDAO::$nameNumCorrectAns=>$correctAnswer));
    }
    
    
    public function stringToDBformat($nameOfFile)
    {
        $category = substr($nameOfFile, 0, 2);
        $numOfTest = substr($nameOfFile, 2, 3);
        $numOfQuestion = substr($nameOfFile, 5, 2);
        $answers = substr($nameOfFile, 7, 1);
        $correctAsnwer = substr($nameOfFile, 8, 1);

        return "category:" . $category . " / numOfTest : " . $numOfTest . " / numOfQuestion : " . $numOfQuestion . " / answers: " . $answers . " / correctAsnwer : " . $correctAsnwer;

    }
    
    
  public function clearDb(Application $app)
  {
      $app['dao.quizz']->clearAll();
       return new Response("Table cleared");
  }

    public function putFilesIntoDb(Application $app)
    {
        $path= "./web/images/";
        $dir = dir($path);

        print_r($dir);
        $response = "<br/>";

      //array of files
        $filesNames = [];
        while($name = $dir->read())
        {
            $filesNames[] = $name;
        }

        foreach($filesNames as $name)
        {
            //we take all file with a name bigger than 3 characters
            if(strlen($name)>3)
            {
                //rest
                 $response = $response.''.$name.' :'.$this->stringToDBformat($name).' #ok<br/>';
           
 

                $this->saveInDb($name,$app);
            }
        }
        
        $dir->close();

        return new Response($response);
    }
}