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
    show: true, //whether or not to include the track controls
    width: 200 //width of controls in pixels
  },
  seekStyle: 'line',
  zoomLevels: [500, 1000, 3000, 5000]
});

playlist.load([
  {
    src: 'path/to/default-audio-file.mp3' // Ganti dengan file audio default jika perlu
  }
]).then(function () {
  // can do stuff with the playlist.

  // initialize the WAV exporter.
  playlist.initExporter();

  // Check for File API support
  if (window.File && window.FileReader && window.FileList && window.Blob) {
    // Add event listener for file input change
    document.getElementById('uploadAudio').addEventListener('change', handleFileSelect, false);
  } else {
    console.error('The File APIs are not fully supported in this browser.');
  }

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
});

// Fungsi untuk menangani pemilihan file audio
function handleFileSelect(event) {
  var file = event.target.files[0];
  
  if (file) {
    var reader = new FileReader();
    reader.onload = function (e) {
      // Menggunakan URL.createObjectURL untuk mendapatkan URL file yang dapat dimainkan
      var audioSrc = URL.createObjectURL(file);
      // Menambahkan audio baru ke dalam playlist
      playlist.load([
        {
          src: audioSrc
        }
      ]).then(function () {
        console.log('File loaded successfully:', file.name);
        // Lakukan sesuatu setelah file berhasil dimuat, jika perlu
      });
    };
    reader.readAsArrayBuffer(file);
  }
}
