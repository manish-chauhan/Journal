<?php 

namespace bin\command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use src\User;

class CreateUserCommand extends Command {
    protected function configure() {
        $this
            ->setName('create-user')
            ->setDescription('Create User For Journal')
            ->addArgument('user_name', InputArgument::REQUIRED, 'User Name is required')
            ->setHelp("User Creation");
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        $user_name = $input->getArgument('user_name');
        if(! $this->userNameValidaton($user_name)){
            print_r('User name should not contain any space or special characte'.PHP_EOL);
            die;
        }
        print_r('Enter Password :');
        $this->hide_term();
	$password = rtrim(fgets(STDIN), PHP_EOL);
	$this->restore_term();
        $user = new User();
        $user_creation_inputs = [];
        $user_creation_inputs['user_name'] = $user_name  ;
        $user_creation_inputs['password'] = $password ;
        print_r(PHP_EOL);
        $user_creation_output = $user->createUser($user_creation_inputs);
        if(!empty($user_creation_output[CODE]) && $user_creation_output[CODE] != SUCCESS && !empty($user_creation_output[ERROR]) ){
            print_r($user_creation_output[ERROR]);
        }
        if(!empty($user_creation_output[CODE]) && $user_creation_output[CODE] == SUCCESS && !empty($user_creation_output[DATA]) ){
            print_r($user_creation_output[DATA]);
        }
        print_r(PHP_EOL);
    }
    
    
    protected function  hide_term() {
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')
            system('stty -echo');
    }
    
    protected function restore_term() {
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')
            system('stty echo');
    }
    protected function userNameValidaton($userName){
        $pattern = "/^[a-zA-Z0-9]+$/";
        if(preg_match($pattern, $userName)){
           return true;
        }
        return false;
    }
    
}


