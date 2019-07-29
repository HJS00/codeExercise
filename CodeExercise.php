<?php
/**
 * +-----------------------------------------------------------------+
 * |                  On The Beach Coding Exercise                   |
 * +-----------------------------------------------------------------+
 * |               @copyright Copyright (c) On The Beach             |
 * |               Code by Heather Scrooby July 2019                 |
 * +-----------------------------------------------------------------+
 * | This function takes a String input of PrimaryJobs and           |
 * | and dependencies and outputs an Array with the ordered results  |
 * | or a string with the error message to be returned.              |
 * | Input to be PrimaryJob,Dependency;PrimaryJob,Dependency;  etc   |
 * | for more information read the ReadMe File supplied.             |
 * +-----------------------------------------------------------------+
 * |                          Usage Example                          |
 * | $jobs = new jobs('a;b,c;c,f;d,a;e,b;f');                        |
 * | $results = $jobs->processJobs();                                |
 * +-----------------------------------------------------------------+ 
 */

// NOTE: For the moment I just return a string with an error message, 
// in a live environment the error message would be sent to the
// error/message stack and dealt with accordingly on the front end.  
// Language packs would also need to be taken into account in a live 
// environment.
// This is a concept test and is not intended as production ready.

class jobs {
    protected $ordered = array();
	protected $primary = array();
	protected $dependency = array();
	protected $processed = array();
	protected $jobList = '';
	
	function jobs($jobs)
	{
		$this->jobList = $jobs;
	}

    public function processJobs() 
	{
        // If $jobList is not empty process otherwise skip and do no processing,
		// this will result in the return of an empty array.
		if(!empty($this->jobList))
		{			
			// Split the list by semi colon to get the relevant data
			$splitArray = explode(';',$this->jobList);
			
			// We now have an array containing indiviual strings of primaryJob,Dependency.
			// We need to go through the list to get the Primary Jobs and Dependecies
			foreach($splitArray as $jobArray)
			{
				// Check if the $jobArray is empty, if so there is a blank primaryJob, as per 
				//specifications, provide error message.
				if(empty($jobArray))
					return "Primary Job cannot be empty.";
								
				// Split $jobArray to get an array with the Primary Job [0] and any Dependency [1]
				$primaryDependency = explode(',',$jobArray);
				
				// Check we only have letters and numbers otherwise fail
				if (!ctype_alnum($primaryDependency[0]))
					return $primaryDependency[0] . " is not a valid string.";

				// Check if we have any duplicate primary jobs which is not allowed.
				// If so provide error message.
				if(isset($this->primary[$primaryDependency[0]]))
					return "You have a duplicate Primary Job " . $primaryDependency[0] . " , this is not allowed.";
				
				//I need to do this otherwise I could end up with a failure if the array index is out of bounds.
				switch(sizeof($primaryDependency))
				{
					case 1:
						// There are no dependencies, create a blank dependency and break.
						$this->primary[$primaryDependency[0]] = '';
						break;
					case 2:						
						// There is only one dependency, we can add the dependency to the list knowing
						// that there is definately a second index in the array.
						$theDep = $primaryDependency[1];

						// Check if primary and dependency are the same, this is not allowed.
						// If so provide error message.
						if($primaryDependency[0] == $theDep)
							return "Jobs cannot depend on themselves for job " . $primaryDependency[0] . ".";
						
						// Add the details to the primary array.
						$this->primary[$primaryDependency[0]] = $theDep;
						
						// If the dependency is not blank, add it to the Dependencies Array.
						if(!empty($theDep))
							$this->dependency[] = $theDep;
						break;
					default:
						// If we get here, there was more than 1 dependency which is not valid as per specifications.
						// Provide error message.
						return "Too many dependencies listed for job " . $primaryDependency[0] . ".";
				}
			}

			// Now we have two arrays one containing the list of primary jobs and one with only the dependencies.

			// The Dependencies now need to be processed to find in which order we need to provide the output.
			$combinedArray = array();
			foreach($this->dependency as $sDependency)
			{
				// Check if we have already processed this, if so we don't need to do it again.
				if(!isset($this->processed[$sDependency]))
				{
					// Process the dependencies
					$processedDependencies = $this->getDependencies($sDependency);
					
					// If we do not get an array from the getDependencies function there was an error.
					// If this is the case provide the error message.
					if(!is_array($processedDependencies))
						return $processedDependencies;
					
					//Add the processed data to the combined array for future processing.
					$combinedArray = array_merge($processedDependencies,$combinedArray);	
				}
			}
			// We only want the unique values for the combinedArray (we do not want duplicates which
			// could have been created by processing)
			$combinedArray = array_unique($combinedArray);
			
			// We need to extract all the primary jobs from the Primary array that are not in the $combinedArray
			// and merge these results with the $combinedArray into the final ordered array.
			$this->ordered = array_merge($combinedArray,array_diff(array_keys($this->primary),$combinedArray));			 
		}
		// We now have a blank array or an Array that has been ordered and has the relevant dependencies first
		return $this->ordered;
    }
	
	// This is a recursive function that ultimately provides an array of all nested dependencies for a specific
	// Primary Job, to save processing it checks if the Dependency has already been processed and if so provides
	// the details already calculated.  It also checks to ensure that all dependencies are actually valid Primary
	// Jobs and that no circular references are given.
	private function getDependencies($sDependency, $processing = array())
	{
		//Check if this dependency is a valid Primary Job, if not return the error.
		if(!isset($this->primary[$sDependency]))
			return $sDependency . " is not a valid Job";
		
		//Check if we have processed this dependency already, if so provide data already processed
		if(isset($this->processed[$sDependency]))
			return $this->processed[$sDependency];
		
		//This is to check for circular references.
		$processing[] = $sDependency;
		
		$dependencies = array();
		$dependencies[] = $sDependency;
		if(!empty($this->primary[$sDependency]))
		{
			//Check for a circular reference, if so stop processing and return the error.
			if(in_array($this->primary[$sDependency], $processing))
				return "Jobs can't have circular references.  A circular Reference was found for " . $this->primary[$sDependency];
			
			$thedependencies = $this->getDependencies($this->primary[$sDependency],$processing);
			
			//check if the process found any errors, if so stop processing and return the error.
			if(!is_array($thedependencies))
				return $thedependencies;
			
			$dependencies = $thedependencies;
			$dependencies[] = $sDependency;
		}
		//Add this dependency as processed so we do not process it again unnecessarily in future
		$this->processed[$sDependency] = $dependencies;
		return $dependencies;
	}
}
 ?>
