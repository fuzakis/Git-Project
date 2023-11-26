import { FFmpeg } from '@ffmpeg/ffmpeg';
import { fetchFile } from '@ffmpeg/util';

document.addEventListener('DOMContentLoaded', () => {
    const appElement = document.getElementById('app');
    const downloadButton = document.getElementById('downloadButton');
    const resultElement = document.getElementById('result');
    const audioRef = document.getElementById('audioPlayer');
    const { createWorker } = FFmpeg;
    const worker = createWorker({ workerPath: 'ffmpeg-core.js' }); // Sesuaikan dengan nama file yang benar

    window.performAction = async () => {
        const action = document.getElementById('action').value;
        const startTime = parseFloat(document.getElementById('startTime').value);
        const endTime = parseFloat(document.getElementById('endTime').value);
        const audioFileInput = document.getElementById('audioFile');
        const audioFile = audioFileInput.files[0];

        try {
            const allowedExtensions = ['mp3', 'wav'];
            const fileExtension = audioFile.name.split('.').pop().toLowerCase();

            if (!allowedExtensions.includes(fileExtension)) {
                throw new Error('Invalid audio format. Please upload an MP3 or WAV file.');
            }

            const editedAudioBlob = await performAudioEditing(audioFile, action, startTime, endTime, fileExtension);

            downloadButton.style.display = 'block';

            resultElement.innerHTML = `
                <p>Action completed successfully!</p>
                <button onclick="downloadEditedAudio()">Download Edited Audio</button>
                <button onclick="playEditedAudio()">Play Edited Audio</button>
            `;

            window.editedAudioBlob = editedAudioBlob;
        } catch (error) {
            console.error('Error:', error);
            resultElement.innerHTML = `Error processing audio: ${error.message}`;
        } finally {
            if (worker) {
                await worker.terminate();
            }
        }
    };

    const performAudioEditing = async (audioFile, action, startTime, endTime, fileExtension) => {
        await worker.load();
        await worker.write('input.' + fileExtension, audioFile);

        // ...

        const { data } = await worker.seek(startTime).output('output.mp3').run();
        const editedAudioBlob = new Blob([data], { type: 'audio/mpeg' });

        return editedAudioBlob;
    };

    window.downloadEditedAudio = () => {
        const editedAudioBlob = window.editedAudioBlob;
        const downloadLink = document.createElement('a');
        downloadLink.href = URL.createObjectURL(editedAudioBlob);
        downloadLink.download = 'edited_audio.mp3';
        downloadLink.click();
    };

    window.playEditedAudio = () => {
        audioRef.play();
    };

    window.clearResult = () => {
        resultElement.innerHTML = '';
        downloadButton.style.display = 'none';
    };
});
