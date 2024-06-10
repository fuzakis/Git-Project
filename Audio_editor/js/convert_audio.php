<?php
$inputFile = $_GET['input'];
$outputFile = 'output.wav'; // Nama file output yang dihasilkan

// Panggil ffmpeg untuk melakukan konversi
exec("ffmpeg -i " . $inputFile . " " . $outputFile);

// Set header untuk mengirim file yang akan di-download
header("Content-Type: audio/wav");
header("Content-Disposition: attachment; filename=output.wav");
readfile($outputFile);

// Hapus file output setelah selesai di-download
unlink($outputFile);
?>
