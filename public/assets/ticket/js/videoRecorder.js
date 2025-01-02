var stream = null,
	audio = null,
	mixedStream = null,
	chunks = [], 
	recorder = null
	startButton = null,
	stopButton = null,
	downloadButton = null,
	recordedVideo = null;

async function setupStream () {
	try {
		stream = await navigator.mediaDevices.getDisplayMedia({
			video: true,
			preferCurrentTab: true
		});

		audio = await navigator.mediaDevices.getUserMedia({
			audio: {
				echoCancellation: true,
				noiseSuppression: true,
				sampleRate: 44100,
			},
		});

	} catch (err) {
		alert(err);
	}
}

async function startRecording () {
	await setupStream();

	if (stream && audio) {
		mixedStream = new MediaStream([...stream.getTracks(), ...audio.getTracks()]);
		recorder = new MediaRecorder(mixedStream);
		recorder.ondataavailable = handleDataAvailable;
		recorder.onstop = handleStop;
		recorder.start(1000);
		var ele= document.getElementById('recordedMessage');
		ele.style.display = 'block';

		stream.getVideoTracks()[0].addEventListener('ended', () => {
			stopRecording ();
		});
	} 
	
	else if (stream) {
		mixedStream = new MediaStream([...stream.getTracks()]);
		recorder = new MediaRecorder(mixedStream);
		recorder.ondataavailable = handleDataAvailable;
		recorder.onstop = handleStop;
		recorder.start(1000);
		var ele= document.getElementById('recordedMessage');
		ele.style.display = 'block';
		stream.getVideoTracks()[0].addEventListener('ended', () => {
			stopRecording ();
		});

	} else {
		//console.warn('No stream available.');
	}
}

function stopRecording () {
	try {
		recorder.stop();
	} catch (error) {
		console.log(error);
	}
	
}

function handleDataAvailable (e) {
	chunks.push(e.data);
}

function handleStop (e) {
	const blob = new Blob(chunks, { 'type' : 'video/mp4' });
	chunks = [];

	var form_data = new FormData();
    form_data.append('video', blob, 'video.mp4');
    form_data.append('idReunion', idReunion);
    form_data.append('idImmeuble', idImmeuble);
    form_data.append('userId', userId);
    form_data.append('captureVideo', 1);
    form_data.append('plateforme', 1);
	$.ajax({
		url: varUrl+"/public/json/ajax/tenueReunion.php?action=recordScreen",
		dataType: 'script',
		cache: false,
		contentType: false,
		processData: false,
		data: form_data,                       
		method: 'POST',
		beforeSend: function() {
            $('#SignatairesSelected').modal('hide');
            $('#loaderModalRecording').modal('show');
        },
		success: function(response){
			$('#loaderModalRecording').modal('hide');
		}
	});

	stream.getTracks().forEach((track) => track.stop());
	audio.getTracks().forEach((track) => track.stop());

}


/*if(modeEcriture == true){
	window.addEventListener('load', () => {
		startRecording();

		window.addEventListener("beforeunload", function(event) {
			console.log(varUrl);
			stopRecording();
			event.preventDefault();
			event.returnValue = true;
			
			return undefined;
		});
	})
	
}*/