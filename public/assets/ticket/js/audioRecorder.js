//webkitURL is deprecated but nevertheless
URL = window.URL || window.webkitURL;

var gumStream; 						//stream from getUserMedia()
var rec; 							//Recorder.js object
var inputElement; 							//MediaStreamAudioSourceNode we'll be recording

// shim for AudioContext when it's not avb. 
var AudioContext = window.AudioContext || window.webkitAudioContext;
var audioContext; //audio context to help us record

var recordButton = document.getElementById("recordButton");
var stopButton = document.getElementById("stopButton");
var pauseButton = document.getElementById("pauseButton");

//add events to those 2 buttons
recordButton.addEventListener("click", startRecording);
stopButton.addEventListener("click", stopRecording);
pauseButton.addEventListener("click", pauseRecording);

function startRecording() {
    var constraints = { audio: true, video:false }
	recordButton.disabled = true;
	stopButton.disabled = false;
	pauseButton.disabled = false;
    document.getElementById('play-pause').style.display = 'block';
    document.getElementById('img-playing-pause').src = urlRoot+'/public/images/audioRecording-play.gif';
	navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
		//console.log("getUserMedia() success, stream created, initializing Recorder.js ...");
		audioContext = new AudioContext();
		gumStream = stream;
		inputElement = audioContext.createMediaStreamSource(stream);
		rec = new Recorder(inputElement,{numChannels:1})
		rec.record()
		//console.log("Recording started");

	}).catch(function(err) {
	  	//enable the record button if getUserMedia() fails
    	recordButton.disabled = false;
    	stopButton.disabled = true;
    	pauseButton.disabled = true
	});
}

function pauseRecording(){
	if (rec.recording){
		rec.stop();
		pauseButton.innerHTML="<i class='bx bx-play-circle' ></i> Reprendre";
        document.getElementById('img-playing-pause').src = urlRoot+'/public/images/audioRecording-pause.png';
	}else{
		//resume
		rec.record();
		pauseButton.innerHTML="<i class='bx bx-pause-circle' ></i> Pause";
        document.getElementById('img-playing-pause').src = urlRoot+'/public/images/audioRecording-play.gif';
	}
}

function stopRecording() {
    document.getElementById('play-pause').style.display = 'none';
    document.getElementById('img-playing-pause').src = '';
	//console.log("stopButton clicked");
	stopButton.disabled = true;
	recordButton.disabled = false;
	pauseButton.disabled = true;
	pauseButton.innerHTML="<i class='bx bx-pause-circle' ></i> Pause";
	rec.stop();
	gumStream.getAudioTracks()[0].stop();
	rec.exportWAV(createDownloadLink);
}

function createDownloadLink(blob) {
	var url = URL.createObjectURL(blob);
	var au = document.createElement('audio');
	var li = document.createElement('li');
	var filename = new Date().toISOString();
	au.controls = true;
	au.src = url;
    //console.log(url);
	li.appendChild(au);
	//li.appendChild(document.createTextNode(filename+".wav "))
	recordingsList.appendChild(li);
    listNote.push(blob);
    //console.log(listNote);
}