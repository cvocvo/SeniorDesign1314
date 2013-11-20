<?php

include_once(SERVER_ROOT . '/util/logger.php');

//Automatically includes files containing classes that are called
/*function __autoload($className)
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
}*/

//determine if was get or post
//all forms have a hidden field containing their page source
//check if it is set, if not, it is a get
if(isset($_POST['page'])){

	Logger::log("router", "POST to " . $_POST['page']);

	Logger::log_post("router", $_POST);

	//pass control over to the specific page's controller
	$target = SERVER_ROOT . '/controller/' . $_POST['page'] . '.php';
	
	include_once($target);
	$class = ucfirst($_POST['page']) . '_Controller';
	$controller = new $class;
	$controller->do_post();
}


//this is a GET, parse the get parameters and pass control over to the correct controller
else{
	//load the request from index.php in order to map to correct page
	$request = $_SERVER['QUERY_STRING'];

	//parse into individual query arguments, with the first being the page
	$parsed = explode('&', $request);

	//page is the first arg, remove it from the array and store it
	$page = array_shift($parsed);
	
	if($page == ""){
		$page = 'login';
	}
	
	Logger::log("router", "GET to " . $page);

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
		//die('page does not exist');
		header("Location: " . SITE_ROOT . "/404.php");
		exit();
	}

	$controller->main($getVars);

}//end GET else

?>
