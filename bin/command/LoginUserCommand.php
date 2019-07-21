<?php 

namespace bin\command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use src\User;
use src\JournalApp;

class LoginUserCommand extends Command {
    protected function configure() {
        $this
            ->setName('login')
            ->setDescription('Login to the Journal')
            ->addArgument('user_name', InputArgument::REQUIRED, 'User Name is required')
            ->setHelp("Login to the journal");
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        $user_name = $input->getArgument('user_name');
        print_r('Enter Password :');
        $this->hide_term();
	$password = rtrim(fgets(STDIN), PHP_EOL);
	$this->restore_term();
        print_r(PHP_EOL);
        $user = new User();
        $user_login_inputs = [];
        $user_login_inputs['user_name'] = $user_name  ;
        $user_login_inputs['password'] = $password ;
        $user_creation_output = $user->loginUser($user_login_inputs);
        if(!empty($user_creation_output[CODE]) && $user_creation_output[CODE] != SUCCESS && !empty($user_creation_output[ERROR]) ){
            print_r($user_creation_output[ERROR]);
            print_r(PHP_EOL);
        }
        if(!empty($user_creation_output[CODE]) && $user_creation_output[CODE] == SUCCESS && !empty($user_creation_output[DATA]) ){
            print_r($user_creation_output[DATA]);
            print_r(PHP_EOL);
            $startApp = new JournalApp();
        }
    }
    
    
    protected function  hide_term() {
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')
            system('stty -echo');
    }
    
    protected function restore_term() {
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')
            system('stty echo');
    }
   
}


