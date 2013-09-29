<?php

//Automatically includes files containing classes that are called
function __autoload($className)
{
    //parse out filename where class should be located
    list($filename , $suffix) = split('_' , $className);

    //compose file name
    $file = SERVER_ROOT . '/model/' . strtolower($filename) . '.php';

    //fetch file
    if (file_exists($file))
    {
        //get file
        include_once($file);        
    }
    else
    {
        //file does not exist!
        die("File '$filename' containing class '$className' not found.");    
    }
}

//load the request from index.php in order to map to correct page
$request = $_SERVER['QUERY_STRING'];

//parse into individual query arguments, with the first being the page
$parsed = explode('&', $request);

//page is the first arg, remove it from the array and store it
$page = array_shift($parsed);

//rest of the array is key val pairs of GET args
$getVars = array();
foreach($parsed as $argument){
	list($variable, $value) = split('=', $argument);
	$getVars[$variable] = $value;
}

$target = SERVER_ROOT . '/controller/' . $page . '.php';

if(file_exists($target)){
	include_once($target);

	$class = ucfirst($page) . '_Controller';

	if(class_exists($class)){
		$controller = new $class;
	}
	else{
		die('class does not exist');
	}
}
else{
	die('page does not exist');
}

$controller->main($getVars);

?>
