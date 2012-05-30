function vision()	
{
	document.getElementById("loading").style.visibility="hidden";
	document.getElementById("loaded").style.visibility="visible";
}

function setStatus(num, str)	{
	// Handle status changes
	//**********************
	// Status codes:
	// StartUpload = 0;
	// UploadDone = 1;
	// StartRecord = 2;
	// StartPlay = 3;
	// PauseSet = 4;
	// Stopped = 5;
}

function setTimer(str)	{
}

function setFileName()
{
	// document.getElementById("applet").setUploadFileName("test.mp4");
	//  if you want to pass video file name to applet, please, uncomment it. In this case pop-up window does not appear when you click on the Upload button.
}