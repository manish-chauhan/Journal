# Journal

## version
Php5

## Installation
composer update<br/>
edit .env.php to change paths<br/>
  'user_file_path' => __DIR__.'/user_data.text',<br/>
  'users_journals_path' => __DIR__.'/../UsersJournal',<br/>

## Usage
1.create user
  a. php bin/start.php create-user user-name       #user will be created by this command with user-name being primary <br/>
  b. enter password                                #password of user <br/>

2.login <br/>
  a.php bin/start.php login user-name <br/>
  b.enter password  <br/>
  c.User Logged In Succesfully<br/>
  d.************ AmbitionBox Journal ******************<br/>
    1 - Create Journal<br/>
    2 - View All Journals<br/>
    3 - Quit<br/>
    ************ AmbitionBox Journal ******************<br/>
    Enter your choice from 1 to 3 ::<br/>

