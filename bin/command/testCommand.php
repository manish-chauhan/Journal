<?php 

namespace bin\command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use src\TestHelloWord;
use src\libraries\Utils;
use \Exception;

class testCommand extends Command {
    protected function configure() {
        $this
            ->setName('test')
            ->setDescription('Test commands')
            ->addArgument('name', InputArgument::REQUIRED, 'The main word')
            ->setHelp("Test command");
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        try{
            $a = $b;
            $fileName = __DIR__.'/testfile.txt';
            $key = 'my secret key';
            file_put_contents($fileName, 'Hello World, here I am.');
    //        Utils::encryptFile($fileName, $key, $fileName . '.enc');
    //        Utils::decryptFile($fileName . '.enc', $key, $fileName . '.dec');

            print_r('done');
            
        } catch (\ErrorException $ex) {
            print_r('-------++++++++++++++++-exception---');
//            Utils::exceptionError($ex);
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

