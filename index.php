<?php
//Controller for the cupcakes site

//Turn on error-reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Start a session
session_start();

//Require necessary files
require_once ('vendor/autoload.php');
require_once ('model/data-layer.php');
require_once ('model/validation.php');

//Instantiate Fat-Free
$f3 = Base::instance();

//Define default route
$f3->route('GET|POST /', function($f3){

    $_SESSION = array();

    $userName = "";
    $userFlavors = array();

    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $userName = $_POST['name'];

        //If name is valid
        if (validName($userName))
        {
            $_SESSION['userName'] = $userName;
        }
        else
        {
            $f3->set('errors["name"]', 'Please enter your name');
        }

        //If flavors are selected
        if (validFlavors($_POST['flavors']))
        {
            $userFlavors = $_POST['flavors'];
            //Get user input
            $_SESSION['userFlavors'] = $userFlavors;
            $_SESSION['total'] = number_format((double)count($userFlavors) * 3.50, 2);
        }
        else
        {
            $f3->set('errors["flavors"]', 'Please select a flavor');
        }
        //If there are no errors redirect to summary route
        if (empty($f3->get('errors')))
        {
            header('location: summary');
        }
    }


    //Get the flavors from the Model and send them to the View
    $f3->set('flavors', getFlavors());

    //Store the user input in the hive
    $f3->set('userName', $userName);
    $f3->set('userFlavors', $userFlavors);


    //Display the home page
    $view = new Template();
    echo $view->render('views/home.html');
});

$f3->route('GET /summary', function(){

    //Display the summary page
    $view = new Template();
    echo $view->render('views/summary.html');
});

//Run Fat-Free
$f3->run();