<?php

require_once dirname(__FILE__) . '/videorecorder.php';

if (isset($_POST['mode'])) {
    $mode = $_POST['mode'];
}

$client = new videorecorder();

switch($mode) {
    case "applet":
        if(!empty($_POST['ACTION'])) {                            
            $ACTION = $_POST['ACTION'];
            $USERDIR = $_POST['USERDIR'];
            $filename = $_FILES['USERFILE']['name'];
            $filedata = $_FILES['USERFILE']['tmp_name'];
        } else {
            exit(NotReg);
        }    
        break;
    case "flash":
        // save file from red5
        $ACTION = 'CREATE';
        $USERDIR = 'mp4_flash';
        $filename = $_POST['filename'].'.flv';
        $filedata = RED5_HOME . 'red5recorder/streams/video.flv';
        break;
}

// save the recorder video
$client->save($ACTION, $USERDIR, $filename, $filedata);
