<?php 

namespace bin\command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use src\TestHelloWord;
use src\libraries\Utils;

class testCommand extends Command {
    protected function configure() {
        $this
            ->setName('test')
            ->setDescription('Test commands')
            ->addArgument('name', InputArgument::REQUIRED, 'The main word')
            ->setHelp("Test command");
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        $key = 'my secret key';
        $file_path = __DIR__.'/test.txt';
        $decryption = __DIR__.'/desc.txt';
        print_r($file_path);
        
        Utils::encryptFile($file_path , $key , $file_path);
        Utils::decryptFile($file_path , $key , $decryption);
        print_r('done');
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

