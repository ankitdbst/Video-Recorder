<?php 

require_once dirname(__FILE__) . '/videorecorder.php';

$client = new videorecorder();
$rec = $client->flash_recorder();
//$rec = $client->java_recorder();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
    <head>
        <title>Video Recording Plugin</title>
        <script src="videorecorder.js" type="text/javascript"></script>
        <script src="applet/record.vbs" type="text/vbscript"></script>
        <script src="flash/AC_OETags.js" type="text/javascript"></script>
        <script src="flash/history/history.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="flash/history/history.css" />
    </head>

    <body onLoad="vision();">
      <span id="loading" style="visibility:visible">
        <div align="center" style="color:#000000;font-family: Verdana, Arial, Helvetica, sans-serif;font-size:14px">
          Loading Video Recorder...
        </div>
      </span>
      <span id="loaded" style="visibility:hidden">
        <?php echo $rec; ?>
      </span>
    </body>
</html>
