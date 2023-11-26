document.addEventListener('DOMContentLoaded', () => {
    const appElement = document.getElementById('app');
    const downloadButton = document.getElementById('downloadButton');
    const resultElement = document.getElementById('result');

    window.performAction = async () => {
        const action = document.getElementById('action').value;
        const startTime = parseFloat(document.getElementById('startTime').value);
        const endTime = parseFloat(document.getElementById('endTime').value);
        const audioFileInput = document.getElementById('audioFile');
        const audioFile = audioFileInput.files[0];

        try {
            const editedAudioBlob = await performAudioEditing(audioFile, action, startTime, endTime);

            // Tampilkan tombol unduh
            downloadButton.style.display = 'block';

            resultElement.innerHTML = `
                <p>Action completed successfully!</p>
                <button onclick="downloadEditedAudio()">Download Edited Audio</button>
                <button onclick="playEditedAudio()">Play Edited Audio</button>
                <audio controls id="audioPlayer">
                    <source src="${URL.createObjectURL(editedAudioBlob)}" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
            `;

            // Simpan blob audio hasil editing untuk diunduh
            window.editedAudioBlob = editedAudioBlob;
        } catch (error) {
            console.error('Error:', error);
            resultElement.innerHTML = `Error processing audio: ${error.message}`;
        }
    };

    window.downloadEditedAudio = () => {
        const editedAudioBlob = window.editedAudioBlob;
        const downloadLink = document.createElement('a');
        downloadLink.href = URL.createObjectURL(editedAudioBlob);
        downloadLink.download = 'edited_audio.mp3';
        downloadLink.click();
    };

    window.playEditedAudio = () => {
        const audioPlayer = document.getElementById('audioPlayer');
        audioPlayer.play();
    };

    window.clearResult = () => {
        resultElement.innerHTML = '';
        downloadButton.style.display = 'none'; // Sembunyikan tombol unduh ketika hasil dihapus
    };

    // Fungsi performAudioEditing tetap sama seperti sebelumnya
    const performAudioEditing = async (audioFile, action, startTime, endTime) => {
        const { createWorker } = FFmpeg;
        const worker = createWorker({ workerPath: 'ffmpeg-all-codecs.js' });

        await worker.load();
        await worker.write('input.' + fileExtension, audioFile);

        // ...

        await worker.seek(startTime).output('output.mp3');

        // Contoh: Mengembalikan blob audio hasil editing
        return editedAudioBlob;
    };
});
