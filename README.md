 On The Beach Coding Exercise.
 
 Considerations taken into account:
 
 WHAT STRUCTURE SHOULD THE INPUT (JOB STRUCTURE) STRING HAVE?
 ------------------------------------------------------------
 The posiblity of another input format being used was investigated but the interface providing the structure could only output strings at current (possiblity to look into upgrading and removing this limitation in the future was noted).
 
 While the initial determination found that each job was a single character - this left the system only able to accept up to 62 (upder and lowercase letters A to Z plus numbers 0 to 9 ) jobs.  While this was sufficent to cover the current needs of the business it was determined that it was possible that more than 62 jobs might need to be processed by this function at a time in the future.
 
 It was also noted that not all jobs have dependencies and as such the string needed to have a clear seperator between each job and also to seperate jobs from dependencies.  It was therefore determined that the inputing string would seperate jobs with a semi colon (;) and would put a comma ',' before a dependency.  The string would therefore have the following structure:
 
 PrimaryJob,Dependency;PrimaryJob,Dependency;
 
 Inputs as follows would then need to be accepted:
   a,b;b;c,b;d;
   a,b;b,;c,b;d,;
   abdc,ab;ab,jk;
 (Note - the possiblity of a single job having multiple dependencies was also discussed, however it was determined that at no point now - or in the future - could a job have more than one dependency.)
 
 WHAT STRUCTURE WOULD THE OUTPUT TAKE?
 -------------------------------------
 While the current receving process for this function was expecting a string it was determined that this would need to be edited.  This was due to the fact that at current the function treated each letter as a new job, however we had determined that this limitation of 62 letters and numbers would not be enough to cover the company in the future. It was therefore determined that the output would be an array.
 
 POTENTIAL PROBLEMS
 ------------------
 The follwing Potential problems were mentioned that we need to accomodate for:
 * Invalid input structure
 * Empty input
 * Empty primary jobs
 * Empty dependencies
 * Duplicate primary jobs
 * Self Dependecies
 * Circular dependies 
 * Dependencies that are not listed in the primary job list.
 
 The following was decided regarding these issues:
 * Empty strings would be allowed and these would result in an empty array being returned.
 
 * Empty primrary jobs would not be accepted (ie a,b;;c and a,b;,b;c would not be accepted) and would return an error.
 
 * Empty dependencies would be accepted (ie a,b;d,;c and a,b;e;c would be accepted) and processed accordingly.
 
 * Duplicate primrary jobs would not be accepted - allowing these could cause an issue with a single primary job having multiple dependencies which priliminary investigation had stated was impossible.  It was discussed that we could do a check on if the duplicated primaries had the same dependencies and if they did share the same dependencies to simply remove one of the Primary Jobs;  However it was decided that this funtion was also a check to ensure the integrity of the information being passed - receiving duplicated primary jobs (even if the dependencies were the same) was a potential warning sign that something was potentially wrong and as such an error should be thrown.
 
 * It was determined that Self Dependencies and Circular Dependencies were to result in errors.
 
 *There were discussions regarding adding dependencies that were not on the primary list of jobs to be added to the job list but this was dismissed and it was determined that if this did occur it would also result in an error for the following reasons:
     -  Adding the dependent job to a list of primary jobs to be executed might cause errors down the line if the dependency is not acutally a valid job - it was better to circumvent this potential problem here;
     -  Not having the dependency as a primary job we could not be certain if that job itself had any dependencies that would need to be taken into account.
 
 WHAT STRUCTURE WOULD THE CODE TAKE
 ----------------------------------
 There was a discussion taken on if a class or a normal function should be used for the processing.  As there would be processing on multiple levels it was decided that a php class was the better option to use.
