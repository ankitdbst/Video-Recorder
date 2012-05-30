<?php

require_once dirname(__FILE__) . '/config.php';

class videorecorder {
    private $VideoFolder;
    private $errfile = "retrive.log";
    
    /**
     * Constructor
     */        
    public function __construct() {
        $VideoLocal = "/videofiles/";
        $this->VideoFolder = getcwd().$VideoLocal;
        $this->errfile = $this->VideoFolder.$this->errfile;
    }
    
    /**
     * Uploades the recorded video stream to the server
     */
    public function save($ACTION, $USERDIR, $filename, $filedata) {
        if ($ACTION == "CREATE" || $ACTION == "APPEND") {
            if (!empty($USERDIR) ) {
                $UserDir = $this->VideoFolder . $USERDIR . "/";
                // checking folder existance
                if (!is_dir($UserDir)) {
                    if ($ACTION == "CREATE") {   
                        // if creating file, creating folder first if not exists
                        umask(000);
                        //creating folder
                        if(!mkdir($UserDir, 0777)) {
                            $this->AddLogs($this->errfile,"Can't create folder: ".$UserDir);
                                ErrorMessage("Server can't create folder for your Video files...\n Tell about it to System Administrator...");
                        } else {
                            $this->AddLogs($this->errfile, "ACTION: ".$GLOBALS['ACTION']."; directory \"".$UserDir."\" doesn't exist");
                        }
                    }
                }
                //getting filename of Videofiles received
                $MVfile_name = $UserDir . $filename;
                //checking file existance for APPEND operation
                $this->Addlogs($this->errfile, "file name is ".$MVfile_name, "info");
                if ($ACTION == "APPEND" && !file_exists($MVfile_name)) { 
                    //file  should exist  for APPEND operation
                    $this->AddLogs($this->errfile, "ACTION: ".$GLOBALS['ACTION']."; file \"".$MVfile_name."\" doesn't exist");
                    exit(NotUpload);
                }
                //getting temporary filename of uploaded file
                $tmp_name = $filedata;
                //checking for file to be uploaded
                if(is_uploaded_file($tmp_name))    {
                    //reading uploaded file
                    $fupload = fopen($tmp_name, "rb");
                    $contents = fread($fupload, filesize($tmp_name));
                    fclose($fupload);
                    //Writing uploaded part on disk using APPEND mode or CREATE mode
                    //defining mode
                    $mode = ($ACTION == "CREATE") ? "wb" : "ab";
                    //opening file
                    $flocal = fopen($MVfile_name, $mode);
                    //writing to file
                    if (!(@fwrite($flocal, $contents))) 
                    {
                        //writing error if writing operation failed
                        $this->AddLogs($this->errfile, "ACTION: ".$GLOBALS['ACTION']."; error writing to file \"".$MVfile_name."\"");
                        exit(NotUpload);
                    }
                    //closing file 
                    fclose($flocal);
                    //writing log message
                    $this->Addlogs($this->errfile, "ACTION: ".$GLOBALS['ACTION']."; exit ACCEPTED", "info");
                    exit("ACCEPTED");
                } else {
                    //writing error log message
                    $this->AddLogs($this->errfile,"ACTION: ".$GLOBALS['ACTION']."; Can't move uploaded file: ".$filename." -> ".$MVfile_name);
                    exit(NotUpload);
                }
            } else {
                exit(NotUpload);
            }
        }
    }
    
    /**
     * Function to add logs while recording from the applet
     */
    private function AddLogs($FileName, $LogInf, $LogType = "error") {
        //making log message
        $str = "[".date("D M d H:i:s Y")."] [".$LogType."] [client ".getenv ("REMOTE_ADDR")."] ";
        //opening log file
        if(file_exists($FileName))//if exists
            $fp = @fopen($FileName,"a");//for appending
        else 
            $fp = @fopen($FileName,"w");//for writing
        //writing message to log file
        @fputs($fp, $str.$LogInf."\r\n");
        //closing log file
        fclose($fp);
    }
}

?>