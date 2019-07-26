<?php
/**
 * +-----------------------------------------------------------------+
 * |                  On The Beach Coding Exercise                   |
 * +-----------------------------------------------------------------+
 * |               @copyright Copyright (c) On The Beach             |
 * |               Code by Heather Scrooby July 2019                 |
 * +-----------------------------------------------------------------+
 * | This code was created to test the code created for the Coding   |
 * | Exercise.  To add extra tests you can either insert at the end  |
 * | of the current array or add another line under $thejobs as shown|
 * | below:                                                          |
 * | $thejobs[] = 'your new test case';                              |
 * +-----------------------------------------------------------------+ 
 */
include 'CodeExercise.php';

$thejobs = array('a,b;b,c;c','','a','a,;b;c','a,;b,c;c','a;b,c;c,f;d,a;e,b;f','a;b,;c,c','a;b,c;c,f;d,a;e;f,b','a;b,k;c,f;d,a;e;f,b','a;b,k;c,f;d,a;;f,b','a;b,a,a;a,f;d,a;f,b','a;a','invalid string');

foreach($thejobs as $job)
{
	$jobs = new jobs($job);
	$results = $jobs->processJobs();
	if(is_array($results))
	{
		$results = implode(",", $results);
	}
	echo "The following order was obtained for " .$job . " : <b>" . $results . "</b><HR>";	
}
?>