var userMediaStream;
var playlist;
var constraints = { audio: true };

navigator.getUserMedia = (navigator.getUserMedia ||
  navigator.webkitGetUserMedia ||
  navigator.mozGetUserMedia ||
  navigator.msGetUserMedia);

function gotStream(stream) {
  userMediaStream = stream;
  playlist.initRecorder(userMediaStream);
  $(".btn-record").removeClass("disabled");
}

function logError(err) {
  console.error(err);
}

playlist = WaveformPlaylist.init({
  samplesPerPixel: 3000,
  waveHeight: 100,
  container: document.getElementById("playlist"),
  state: 'cursor',
  colors: {
    waveOutlineColor: '#005BBB',
    timeColor: 'grey',
    fadeColor: 'black'
  },
  timescale: true,
  controls: {
    show: true,
    width: 200
  },
  seekStyle: 'line',
  zoomLevels: [500, 1000, 3000, 5000]
});

playlist.load([
  {
    src: 'path/to/default-audio-file.mp3' // Ganti dengan file audio default jika perlu
  }
]).then(function () {
  playlist.initExporter();

  if (navigator.mediaDevices) {
    navigator.mediaDevices.getUserMedia(constraints)
      .then(gotStream)
      .catch(logError);
  } else if (navigator.getUserMedia && 'MediaRecorder' in window) {
    navigator.getUserMedia(
      constraints,
      gotStream,
      logError
    );
  }

  $(".btn-trim-audio").prop("disabled", false);

  var track = playlist.tracks[0];
  track.setCues([{ start: 0, end: track.buffer.duration }]);

  playlist.draw();

  playlist.config.colors.selectionColor = 'rgba(0, 255, 0, 0.3)';

  playlist.getEventEmitter().on("select", function () {
    var selectedCues = track.cueIn;
    if (selectedCues.length > 0) {
      $(".btn-trim-audio").prop("disabled", false);
    } else {
      $(".btn-trim-audio").prop("disabled", true);
    }
  });
});

function handleFileSelect(event) {
  var file = event.target.files[0];
  console.log('File selected:', file);

  if (file) {
      var reader = new FileReader();
      reader.onload = function (e) {
          var audioSrc = URL.createObjectURL(file);
          console.log('Audio Source URL:', audioSrc);

          playlist.load([
              {
                  src: audioSrc
              }
          ]).then(function () {
              console.log('File loaded successfully:', file.name);

              var formData = new FormData();
              formData.append('audioFile', file);
              console.log('Form data prepared:', formData);

              $.ajax({
                  url: 'upload.php',
                  type: 'POST',
                  data: formData,
                  processData: false,
                  contentType: false,
                  success: function (response) {
                      console.log('File uploaded successfully');
                  },
                  error: function (xhr, status, error) {
                      console.error('Error uploading file:', error);
                  }
              });
          }).catch(function(error) {
              console.error('Error loading audio file:', error);
          });
      };
      reader.onerror = function(error) {
          console.error('File reading error:', error);
      };
      reader.readAsArrayBuffer(file);
  }
}

document.getElementById('uploadAudio').addEventListener('change', handleFileSelect, false);


function handleTrimButtonClick() {
  const startTime = parseFloat($(".audio-start").val());
  const endTime = parseFloat($(".audio-end").val());
  playlist.trim(startTime, endTime);
}

$(".btn-trim-audio").on("click", handleTrimButtonClick);

if (window.File && window.FileReader && window.FileList && window.Blob) {
  document.getElementById('uploadAudio').addEventListener('change', handleFileSelect, false);
} else {
  console.error('The File APIs are not fully supported in this browser.');
}