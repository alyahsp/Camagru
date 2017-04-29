(function() {
	// xhr.onreadystatechange = ensureReadiness;
	//
	// function ensureReadiness() {
	// 	if(xhr.readyState < 4) {
	// 		return;
	// 	}
	// 	if(xhr.status !== 200) {
	// 		return;
	// 	}
	// 	else if (xhr.readyState == 4)
	// 		console.log(xhr.responseText);
	// }

	var streaming = false,
	video	  = document.querySelector('#video'),
	canvas	 = document.querySelector('#canvas'),
	startbutton  = document.querySelector('#startbutton'),
	width = 420,
	height = 0;

	navigator.getMedia = ( navigator.getUserMedia ||
	navigator.webkitGetUserMedia ||
	navigator.mozGetUserMedia ||
	navigator.msGetUserMedia);

	navigator.getMedia(
		{
		video: true,
		audio: false
	},
	function(stream) {
		if (navigator.mozGetUserMedia) {
			video.mozSrcObject = stream;
	} else {
		var vendorURL = window.URL || window.webkitURL;
		video.src = vendorURL.createObjectURL(stream);
	}
	video.play();
	},
	function(err) {
		console.log("An error occured! " + err);
	}
	);

	video.addEventListener('canplay', function(ev){
		if (!streaming) {
		height = video.videoHeight / (video.videoWidth/width);
		video.setAttribute('width', width);
		video.setAttribute('height', height);
		canvas.setAttribute('width', width);
		canvas.setAttribute('height', height);
		streaming = true;
	}
	}, false);

	function takepicture() {
		var xhr = new XMLHttpRequest;
		var check;
		if (check = document.getElementById("photo"))
			check.parentNode.removeChild(check);
		canvas.width = width;
		canvas.height = height;
		canvas.getContext('2d').drawImage(video, 0, 0, width, height);
		var data = canvas.toDataURL('image/png');
		var chosen = chosenfilter();
		var img = document.createElement("img");
		img.id = "photo";
		document.getElementById("preview").appendChild(img);
		var sending = "src=" + data + "&filter=" + chosen;
		xhr.open("POST", "add_filter.php", true);
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send(sending);
		xhr.onreadystatechange = function(){
		if (this.readyState == 4 && this.status == 200)
			photo.setAttribute('src', "data:image/png;base64,"+this.responseText);
		}
		var save = document.getElementById("save");
		save.disabled = false;
	}

	startbutton.addEventListener('click', function(ev){
		takepicture();
		ev.preventDefault();
	}, false);

})();
