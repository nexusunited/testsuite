 ## Rules :fire:
 This testsuite contains <span style="color:red;">failing tests</span> that can be fixed by writing and adjusting the related parts inside the code.
 
 Depending on the complexity level of <span style="color:green;">passing tests</span> you will archive a different amount of ![Gems](https://www.webfx.com/tools/emoji-cheat-sheet/graphics/emojis/gem.png=22x22), try to get much ![Gems](https://www.webfx.com/tools/emoji-cheat-sheet/graphics/emojis/gem.png=22x22) as possible. 
 All tests are readonly and should not be changed. If you are finished, please push your changes to your branch and submit reuslts by executing `vendor/bin/codecept run --submit yourbranch`.
 
 (Optional) In the end please checkout master branch and add your individual testcase via PullRequest to master branch, this testcase will be used to increase the variety of the testcase pool.
 


 ## Getting started
    - create your own branch (from master) and name it as your github username
    i.e. Branch: wesolowski
    - cd docker
    - ./setup.sh
    - edit your /etc/hosts file and add 127.0.0.1	de.www.testsuite.local, de.zed.testsuite.local
    - 
  
 ## Technology Stack (Setup tested on ubuntu 18.04)
    - Docker
    - PHP7
    - Codeception