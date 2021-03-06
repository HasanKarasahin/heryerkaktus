<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dirname = $_SERVER["SCRIPT_NAME"];
$requestUri = str_replace(['e-commerce-app/', '.php'], null, $dirname);

$requestUri = strtok($requestUri, '?');

if (isset($_GET['_page_'])) $requestUri = "/" . $_GET['_page_'];

$routeMap = array(
    "/" => "Home",
    "/index" => "Home",
    "/home" => "Home",
    "/anasayfa" => "Home",
    "/sepetim" => "Basket",
    "/mybasket" => "Basket",
    "/detay"=>"Details"
);

/*
spl_autoload_register(function ($class_name) {

    try {

        $parts = explode('\\', $class_name);

        $namespace = $parts[0];
        $classsName = $parts[1] ?? null;
        $filePath = "";

        if ($namespace == 'Templates') {
            $namespace = 'Views';
        }

        if ($namespace == 'Modeller') {
            $namespace = 'Model';
        }

        if ($classsName != null) {
            if ($classsName == 'View' || $classsName == 'Controller' || $classsName == 'Model') {
                $filePath = $namespace . DIRECTORY_SEPARATOR . $classsName . '.php';
            } else {
                $filePath = $namespace . DIRECTORY_SEPARATOR . $classsName . DIRECTORY_SEPARATOR . $classsName . '.php';
            }
        } else {
            $classsName = $namespace;
            $filePath = 'Controller' . DIRECTORY_SEPARATOR . $classsName . DIRECTORY_SEPARATOR . $classsName . '.php';
        }

        if (is_file($filePath)) {
            include_once $filePath;
        } else {
            echo "Hata var.";
        }

    } catch (Exception $e) {
        echo "Hata var.";
        exit();
    }

});*/

include_once __DIR__.'/Views/AbstractView.php';
include __DIR__."/Model/Model.php";
//include_once __DIR__.'/Controller/Home/HomeController.php';

echo AbstractView::getTemplateFromFile('_header');

$result = array_key_exists($requestUri, $routeMap);

if ($result) {
    $routeMapKey = $routeMap[$requestUri];
    if (is_file('Controller' . DIRECTORY_SEPARATOR . $routeMapKey . DIRECTORY_SEPARATOR . $routeMapKey . 'Controller.php')) {

        $className = $routeMap[$requestUri].'Controller';

        include_once __DIR__.'/Controller/'.$routeMap[$requestUri].'/'.$className.'.php';

        $obj = new $className;

        if (isset($_GET['a']) == 'action') {
            $obj->action($_GET['actionType'], $_GET['id']);
            print_r($obj->display());
        } else {
            print_r($obj->display());
        }

    } else {
        echo AbstractView::getTemplateFromFile('_notfoundpage');
    }
} else {
    echo AbstractView::getTemplateFromFile('_notfoundpage');
}
echo AbstractView::getTemplateFromFile('_footer');