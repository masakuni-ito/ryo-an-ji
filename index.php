<?php
require_once dirname(__FILE__).'/vendor/autoload.php';

session_start();

$loader = new Twig_Loader_Filesystem(dirname(__FILE__));
$twig = new Twig_Environment($loader);

$choosed = null;
$indicator = 'choose the next number';

$numbers = array(6,60,7,50,49,19,5,14,34,28,47,56,33,18,29,4,43,54,2,27,23,16,35,45,8,52,32,1,11,25,41,44,30,39,48,20,31,58,59,36,15,3,46,24,12,40,10,13,42,17,53,38,37,51,55,26,9,57,22,21);
$correct_numbers = array(57,28,18,54,53,19,45,1,17,30,5,13,59,6,29,47,38,51,34,10,40,48,41,31,35,32,27,16,33,7,52,4,49,26);

$table = array_chunk($numbers, 15);

if (!isset($_SESSION['choosed']) || (isset($_GET['reset']) && $_GET['reset'])) {
	$_SESSION['choosed'] = array();
}

if (count($_SESSION['choosed']) < count($correct_numbers)) {
	$correct_number = $correct_numbers[count($_SESSION['choosed'])];

	if (isset($_GET['choosed']) && in_array($_GET['choosed'], $numbers)) {
		$choosed = $_GET['choosed'];
	}

	if (!is_null($choosed) && $correct_number == $choosed) {
		$_SESSION['choosed'][] = $choosed;
		$indicator = 'next';

	} else {
		if (count($_SESSION['choosed']) > 0) {
			$indicator = 'wrong';
		}

		foreach ($table as $row) {
			if (in_array($choosed, $row) && in_array($correct_number, $row)) {
				if (array_search($choosed, $row) < array_search($correct_number, $row)) {
					$indicator = 'right';
				} else {
					$indicator = 'left';
				}
			}
		}
	}
}

if (count($correct_numbers) == count($_SESSION['choosed'])) {
	$indicator = 'what is the *next* number?';
}

$template = $twig->loadTemplate('index.twig');
$context = array(
	'indicator' => $indicator,
	'table' => $table,
	'choosed' => $_SESSION['choosed']
);

echo $template->render($context);

