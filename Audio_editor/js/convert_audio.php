<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $format = $_POST['format'];
    $data = file_get_contents('php://input');
    
    // Menyimpan data sementara sebagai file input.wav
    $inputFile = 'input.wav';
    file_put_contents($inputFile, $data);

    $outputFile = 'output.' . $format;
    $escapedInputFile = escapeshellarg($inputFile);
    $escapedOutputFile = escapeshellarg($outputFile);

    // Panggil ffmpeg untuk melakukan konversi
    exec("ffmpeg -i $escapedInputFile $escapedOutputFile 2>&1", $output, $return_var);

    if ($return_var !== 0) {
        echo "Error executing ffmpeg: " . implode("\n", $output);
        exit;
    }

    // Tentukan tipe konten berdasarkan format output
    $contentType = '';
    switch ($format) {
        case 'wav':
            $contentType = 'audio/wav';
            break;
        case 'mp3':
            $contentType = 'audio/mpeg';
            break;
        case 'ogg':
            $contentType = 'audio/ogg';
            break;
        default:
            die("Unsupported format");
    }

    // Set header untuk mengirim file yang akan di-download
    header("Content-Type: " . $contentType);
    header("Content-Disposition: attachment; filename=" . $outputFile);
    readfile($outputFile);

    // Hapus file input dan output setelah selesai di-download
    unlink($inputFile);
    unlink($outputFile);
}
?>
