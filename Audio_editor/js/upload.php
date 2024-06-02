<?php
// Lokasi penyimpanan file audio
$uploadDirectory = "C:/xampp/htdocs/Git_Project/Uploads/";
error_log("Upload Directory: " . $uploadDirectory);

// Pastikan file telah diunggah
if (isset($_FILES['audioFile'])) {
    $file = $_FILES['audioFile'];
    error_log("File: " . var_export($file, true));

    // Ambil informasi file
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];

    // Ambil ekstensi file
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    error_log("File Extension: " . $fileExt);

    // Ekstensi file yang diperbolehkan
    $allowedExtensions = array("mp3", "wav", "ogg");

    // Cek apakah ekstensi file diperbolehkan
    if (in_array($fileExt, $allowedExtensions)) {
        // Cek apakah tidak ada error saat mengunggah file
        if ($fileError === 0) {
            // Generate nama unik untuk file
            $newFileName = uniqid('', true) . '.' . $fileExt;

            // Tentukan lokasi penyimpanan file baru
            $uploadPath = $uploadDirectory . $newFileName;
            error_log("Upload Path: " . $uploadPath);

            // Pindahkan file ke direktori penyimpanan yang ditentukan
            if (move_uploaded_file($fileTmpName, $uploadPath)) {
                echo "File berhasil diunggah.";
            } else {
                error_log("Error moving uploaded file.");
                echo "Terjadi kesalahan saat mengunggah file.";
            }
        } else {
            error_log("File upload error: " . $fileError);
            echo "Terjadi kesalahan saat mengunggah file.";
        }
    } else {
        error_log("Invalid file extension: " . $fileExt);
        echo "Ekstensi file tidak valid. Hanya file dengan ekstensi .mp3, .wav, dan .ogg yang diperbolehkan.";
    }
} else {
    error_log("No file uploaded.");
    echo "Tidak ada file yang diunggah.";
}
?>
