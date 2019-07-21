<?php 

namespace src\libraries;

class Utils {
    
    
    public static function customError($error , $error_code = ''){
        return [CODE => FAILED , ERROR => $error];
    }
    
    public static function sendResponse($message){
        return [CODE => SUCCESS , DATA => $message];
    }
    
    public static function exceptionError($exception,$code = null)
    {
        if($exception instanceof \Exception){
            $file = $exception->getFile();
            $path = pathinfo ( $file );
            $where = ' (' . $exception->getFile() . ':' . $exception->getLine() . ')';
            $errorMsg = $exception->getMessage();
        }
        
        $error = array ();
        $error ['exception'] = $errorMsg;
        $error ['domain'] = !empty($path ['filename']) ? $path ['filename'] : '';
        $error ['datetime'] = date ( 'Y-m-d H:i:s' );
        $error ['timestamp'] = date('YmdHis');
        $error ['type'] = 'ERROR';
        $error ['eid'] = round(microtime(true) * 1000);
        
        $file = fopen ( storage_path () . "/logs/ambitionbox_journal.log", "a" );
        fwrite ( $file, json_encode ( $error ) . "\n" );
        fclose ( $file );
        
        $error ['trace'] = [];
        if(empty($code)){
            $error ['trace'] = $exception->getTrace ();
        }
       
        $responseMessage = 'Some error occured on server '.$error ['timestamp'];
        if ($exception->getCode() == -1) {
                $responseMessage = $exception->getMessage();
        }
        if (str_contains($error['exception'],"exception 'Exception' with message ")) {
                $responseMessage =  $exception->getMessage();
        }

        $response = array (
                        CODE => FAILED,
                        ERROR => array (
                                        $responseMessage
                        )
        );

        return $response;

    }//end exceptionError()
    
    public static function getUser(){
        $user_data = [];
        $user_data['user_name'] = !empty($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
        $user_data['user_token'] = !empty($_SESSION['user_token']) ? $_SESSION['user_token'] : '';
        return $user_data;
    }
    

    function encryptFile($source, $key, $dest)
    {
        $key = substr(sha1($key, true), 0, 16);
        $iv = openssl_random_pseudo_bytes(16);

        $error = false;
        if ($fpOut = fopen($dest, 'w')) {
            // Put the initialzation vector to the beginning of the file
            fwrite($fpOut, $iv);
            if ($fpIn = fopen($source, 'rb')) {
                while (!feof($fpIn)) {
                    $plaintext = fread($fpIn, 16 * FILE_ENCRYPTION_BLOCKS);
                    $ciphertext = openssl_encrypt($plaintext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
                    // Use the first 16 bytes of the ciphertext as the next initialization vector
                    $iv = substr($ciphertext, 0, 16);
                    fwrite($fpOut, $ciphertext);
                }
                fclose($fpIn);
            } else {
                $error = true;
            }
            fclose($fpOut);
        } else {
            $error = true;
        }

        return $error ? false : $dest;
    }

    function decryptFile($source, $key, $dest)
    {
        $key = substr(sha1($key, true), 0, 16);

        $error = false;
        if ($fpIn = fopen($source, 'rb')) {
            if ($fpOut = fopen($dest, 'w')) {
                // Get the initialzation vector from the beginning of the file
                $iv = fread($fpIn, 16);
                while (!feof($fpIn)) {
                    $ciphertext = fread($fpIn, 16 * (FILE_ENCRYPTION_BLOCKS + 1)); // we have to read one block more for decrypting than for encrypting
                    $plaintext = openssl_decrypt($ciphertext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
                    // Use the first 16 bytes of the ciphertext as the next initialization vector
                    $iv = substr($ciphertext, 0, 16);
                    fwrite($fpOut, $plaintext);
                }
                fclose($fpIn);
            }else{
                $error = true;
            }
        } else {
            $error = true;
        }
        fclose($fpOut);
        

        return $error ? false : $dest;
    }


}