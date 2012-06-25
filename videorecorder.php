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
     * Prints java applet video recorder
     */
    public function java_recorder() {
        $html = '
            <div align="center" style="color:#000000;font-family: Verdana, Arial, Helvetica, sans-serif;font-size:14px">
                <applet
                  ID       = "applet"
                  ARCHIVE  = "VideoApplet.jar"
                  codebase = "applet/"
                  code     = "com.vimas.videoapplet.VimasVideoApplet.class"
                  name     = "VideoApplet"
                  width    = "260"
                  height   = "245"
                  hspace   = "0"
                  vspace   = "0"
                  align    = "middle"
                 MAYSCRIPT>
                    <param name = "left"                value = "100">
                    <param name = "top"                 value = "200">
                    <param name = "Registration"        value = "demo">
                    <param name = "LocalizationFile"    value = "applet/localization.xml">
                    <param name = "ServerScript"        value = "http://localhost/videorecorder/record.php">
                    <param name = "TimeLimit"           value = "30">
                    <param name = "BlockSize"           value = "10240">
                    <param name = "UserServerFolder"    value = "mp4">
                    <param name = "LowQuality"          value = "96,24">
                    <param name = "NormalQuality"       value = "160,32">
                    <param name = "HighQuality"         value = "256,48">
                    <param name = "FrameSize"           value = "large">
                    <param name = "UserPostVariables"   value = "mode">
                    <param name = "mode"                value = "applet">
                </applet>
            </div>';
        return $html;
    }

    /**
     * Prints flash video recorder
     */
    public function flash_recorder() {
        $js = '
            <script language="JavaScript" type="text/javascript">
            <!--
            // -----------------------------------------------------------------------------
            // Globals
            // Major version of Flash required
            var requiredMajorVersion = 9;
            // Minor version of Flash required
            var requiredMinorVersion = 0;
            // Minor version of Flash required
            var requiredRevision = 28;
            // -----------------------------------------------------------------------------
            // -->
            </script>
            </head>

            <body scroll="no">
            <script language="JavaScript" type="text/javascript">
            <!--
            // Version check for the Flash Player that has the ability to start Player Product Install (6.0r65)
            var hasProductInstall = DetectFlashVer(6, 0, 65);

            // Version check based upon the values defined in globals
            var hasRequestedVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);

            if ( hasProductInstall && !hasRequestedVersion ) {
                // DO NOT MODIFY THE FOLLOWING FOUR LINES
                // Location visited after installation is complete if installation is required
                var MMPlayerType = (isIE == true) ? "ActiveX" : "PlugIn";
                var MMredirectURL = window.location;
                document.title = document.title.slice(0, 47) + " - Flash Player Installation";
                var MMdoctitle = document.title;

                AC_FL_RunContent(
                    "src", "playerProductInstall",
                    "FlashVars", "MMredirectURL="+MMredirectURL+"&MMplayerType="+MMPlayerType+"&MMdoctitle="+MMdoctitle+"",
                    "width", "210px",
                    "height", "160px",
                    "align", "middle",
                    "id", "red5recorder",
                    "quality", "high",
                    "bgcolor", "#869ca7",
                    "name", "red5recorder",
                    "allowScriptAccess","sameDomain",
                    "type", "application/x-shockwave-flash",
                    "pluginspage", "http://www.adobe.com/go/getflashplayer"
                );
            } else if (hasRequestedVersion) {
                // if we have detected an acceptable version
                // embed the Flash Content SWF when all tests are passed
                AC_FL_RunContent(
                        "src", "flash/red5recorder",
                        "width", "210px",
                        "height", "160px",
                        "align", "middle",
                        "id", "red5recorder",
                        "quality", "high",
                        "bgcolor", "#869ca7",
                        "name", "red5recorder",
                        "allowScriptAccess","sameDomain",
                        "type", "application/x-shockwave-flash",
                        "pluginspage", "http://www.adobe.com/go/getflashplayer"
                );
              } else {  // flash is too old or we can not detect the plugin
                var alternateContent = "Alternate HTML content should be placed here. "
                + "This content requires the Adobe Flash Player. "
                + "<a href=http://www.adobe.com/go/getflash/>Get Flash</a>";
                document.write(alternateContent);  // insert non-flash content
              }
            // -->
            </script>';
        $html = $js.'
            <noscript>
                <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
                        id="red5recorder" width="100%" height="100%"
                        codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
                        <param name="movie" value="flash/red5recorder.swf" />
                        <param name="quality" value="high" />
                        <param name="bgcolor" value="#869ca7" />
                        <param name="allowScriptAccess" value="sameDomain" />
                        <embed src="flash/red5recorder.swf" quality="high" bgcolor="#869ca7"
                            width="210px" height="160px" name="red5recorder" align="middle"
                            play="true"
                            loop="false"
                            quality="high"
                            allowScriptAccess="sameDomain"
                            type="application/x-shockwave-flash"
                            pluginspage="http://www.adobe.com/go/getflashplayer">
                        </embed>
                </object>
            </noscript>
            <form action="record.php" method="post">
                <label for="filename">Filename:
                <input type="text" id="filename" name="filename" style="width:100px;" />
                <input type="hidden" id="mode" name="mode" value="flash" />
                <input type="submit" value="Save" />
            </form>
            ';
        return $html;
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
                            $this->addlogs($this->errfile,"Can't create folder: ".$UserDir);
                                ErrorMessage("Server can't create folder for your Video files...\n Tell about it to System Administrator...");
                        } else {
                            $this->addlogs($this->errfile, "ACTION: ".$GLOBALS['ACTION']."; directory \"".$UserDir."\" doesn't exist");
                        }
                    }
                }
                //getting filename of Videofiles received
                $MVfile_name = $UserDir . $filename;
                //checking file existance for APPEND operation
                $this->addlogs($this->errfile, "file name is ".$MVfile_name, "info");
                if ($ACTION == "APPEND" && !file_exists($MVfile_name)) { 
                    //file  should exist  for APPEND operation
                    $this->addlogs($this->errfile, "ACTION: ".$GLOBALS['ACTION']."; file \"".$MVfile_name."\" doesn't exist");
                    exit(NotUpload);
                }
                //getting temporary filename of uploaded file
                $tmp_name = $filedata;
                //checking for file to be uploaded
                if(!empty($tmp_name)) {
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
                        $this->addlogs($this->errfile, "ACTION: ".$GLOBALS['ACTION']."; error writing to file \"".$MVfile_name."\"");
                        exit(NotUpload);
                    }
                    //closing file 
                    fclose($flocal);
                    //writing log message
                    $this->addlogs($this->errfile, "ACTION: ".$GLOBALS['ACTION']."; exit Uploaded", "info");
                    exit("Uploded");
                } else {
                    //writing error log message
                    $this->addlogs($this->errfile,"ACTION: ".$GLOBALS['ACTION']."; Can't move uploaded file: ".$filename." -> ".$MVfile_name);
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
    private function addlogs($FileName, $LogInf, $LogType = "error") {
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