<?php 

namespace src;
use src\libraries\Utils as Utils;

class JournalQueue{
    protected $queue_data = [];
    protected $queue_file_path = '';
            
    function __construct($queue_file_path)
    {
        if(!file_exists($queue_file_path)){
            file_put_contents($queue_file_path, '');
        }
        $file_content = file_get_contents($queue_file_path);
        $file_content = !empty($file_content) ? unserialize($file_content) : [] ; 
        $this->queue_data = $file_content;
        $this->queue_file_path = $queue_file_path;
    }
    
    public function push($queue_inputs){
        if( !empty($this->queue_data) && count($this->queue_data) >= MAX_JOURNALS ){
            $this->pop();
        }
        if(empty($queue_inputs['file_name']) || empty($queue_inputs['file_description']) || empty($queue_inputs['file_path']) ){
            return false;
        }
        $queue_data = [];
        $queue_data[$queue_inputs['file_name']] = [];
        $queue_data[$queue_inputs['file_name']]['file_path'] = $queue_inputs['file_path'];
        $queue_data[$queue_inputs['file_name']]['file_description'] = $queue_inputs['file_description'];
        $queue_data[$queue_inputs['file_name']]['time_stamp'] = date("M,d,Y h:i:s A");
        $this->queue_data = array_merge($this->queue_data , $queue_data);
        file_put_contents($this->queue_file_path, serialize($queue_data));
        return true;
    }
    
    public function pop(){
        $queue_data = $this->queue_data;
        $deleted_items = !empty($queue_data) ? reset($queue_data) : [] ; 
        if(empty($deleted_items['file_path'])){
            return false;
        }
        unlink($deleted_items['file_path']);
        array_splice($queue_data, 0, 1);
        $this->queue_data = $queue_data;
        file_put_contents($this->queue_file_path, serialize($queue_data));
        return true;
    }
    
    public function getJurnolQueueData(){
        return $this->queue_data;
    }
    
}