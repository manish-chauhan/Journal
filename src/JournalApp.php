<?php 

namespace src;
use src\libraries\Utils as Utils;
use src\JournalQueue;

class JournalApp{
    
    protected $journalQueueObj;
            
    function __construct()
    {
        $user_data = Utils::getUser();
        if(empty($user_data)){
            throw new Exception('User is not logged in');
        }
        $this->initilizeJurnalQueue();
        $this->startApp();
    }
    
    protected function startApp(){
        while( true ) {
		
            // Print the menu on console
            $this->printAppMenu();

            // Read user choice
            $choice = trim( fgets(STDIN) );

            // Exit application
            switch ($choice){
                case 1: 
                    $this->createUserJounal();
                    break;
                case 2:
                    $this->viewUserJounal();
                    break;
                case 3:
                        die;
                    break;
            }
	}
    }

    protected function printAppMenu(){
        echo "************ AmbitionBox Journal ******************\n";
        echo "1 - Create Journal".PHP_EOL;
        echo "2 - View All Journals".PHP_EOL;
        echo "3 - Quit".PHP_EOL;
        echo "************ AmbitionBox Journal ******************\n";
        echo "Enter your choice from 1 to 3 ::";
    }
    
    protected function createUserJounal(){
        $jounal_data = $this->getUserJournalData();
        $this->saveUserJournal($jounal_data);
    }
    
    protected function getUserJournalData(){
        $jounal_data = '';
        print_r("Enter 'Exit' in next line to save ".PHP_EOL);
        while(1){
            print_r('>');
            $input = trim( fgets(STDIN) );
            if($input == 'Exit'){
                break;
            }
            $jounal_data = $jounal_data.PHP_EOL.$input;
        }
        return $jounal_data;
    }
    
    protected function saveUserJournal($jounal_data){
        $user_data = Utils::getUser();
        $user_journal_file_path  = !empty($_ENV['users_journals_path']) ? $_ENV['users_journals_path'] : '';
        $user_journal_file_path  = ( !empty($user_journal_file_path) && !empty($user_data['user_name']) ) ? $user_journal_file_path.DIRECTORY_SEPARATOR.$user_data['user_name'] : '';
        $file_name = uniqid().'.txt';
        if(!empty($user_journal_file_path) && !file_exists($user_journal_file_path)){
            mkdir($user_journal_file_path , 0755 , true);
        }
        $user_journal_file_path = $user_journal_file_path.DIRECTORY_SEPARATOR.$file_name;
        file_put_contents($user_journal_file_path, $jounal_data);
        
        //Mark entry in queue
        $queue_inputs['file_name'] = $file_name;
        $queue_inputs['file_description'] = trim(preg_replace('/\s\s+/', ' ', substr($jounal_data, 0, 15) ) );
        $queue_inputs['file_path'] = $user_journal_file_path;
        $this->journalQueueObj->push($queue_inputs);
    }
    
    protected function viewUserJounal(){
        $current_qeue_state =  $this->journalQueueObj->getJurnolQueueData();
        if(empty($current_qeue_state)){
            print_r(PHP_EOL."******************No Journal Found*********************************".PHP_EOL);
            return true;
        }
        print_r('S.No            |       File Decription           |           Created At '.PHP_EOL);
        $i = 1;
        foreach($current_qeue_state as $queue_index => $queue_value){
            $sno = $i++;
            $file_description = !empty($queue_value['file_description']) ? $queue_value['file_description'] : '';
            $created_at = !empty($queue_value['time_stamp']) ? $queue_value['time_stamp'] : '';
            print_r($sno.'  | '.$file_description.' | '.$created_at.PHP_EOL);
            
        }
    }
    
    protected function initilizeJurnalQueue(){
        $user_data = Utils::getUser();
        $jurnolQueueFilePath  = !empty($_ENV['users_journals_path']) ? $_ENV['users_journals_path'] : '';
        $jurnolQueueFilePath  = ( !empty($jurnolQueueFilePath) && !empty($user_data['user_name']) ) ? $jurnolQueueFilePath.DIRECTORY_SEPARATOR.$user_data['user_name'] : '' ;
        if(!empty($jurnolQueueFilePath) &&  ! file_exists($jurnolQueueFilePath) ){
            mkdir($jurnolQueueFilePath);
        }
        $jurnolQueueFilePath = $jurnolQueueFilePath.DIRECTORY_SEPARATOR.'queue_data.qu';
        $this->journalQueueObj = new JournalQueue($jurnolQueueFilePath);
    }
    
}