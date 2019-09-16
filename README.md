 ## Rules :fire:
 This testsuite contains :x: failing tests that need to be fixed by writing and adjusting the related parts inside the code.
 
 Depending on the complexity level of :heavy_check_mark: passing tests you will archive a different amount of :gem:, try to get much :gem: as possible. 
 All tests are readonly and should not be changed. 
 
 If you are finished, please push your changes to your branch and submit results by executing `vendor/bin/codecept run --submit yourbranch`.
 
 (Optional) In the end please checkout master branch and add your personal testcase via PullRequest to master branch, this testcase will be used to increase the variety of the testcase pool.

 ## Getting started
    - create your own branch (from master) and name it as your github username
        i.e. Branch: wesolowski
    - cd docker
    - ./setup.sh
    - edit your /etc/hosts file and add 127.0.0.1	de.www.testsuite.local, de.zed.testsuite.local
    - docker exec -it testsuite_app_1 bash
    - vendor/bin/codecept run (write/adjust code and repeat)
  
 ## Technology Stack (Setup tested on ubuntu 18.04)
    - Docker
    - PHP7
    - Codeception