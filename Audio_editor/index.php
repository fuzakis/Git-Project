<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login_app/index.php'); // Redirect ke halaman login jika belum login
    exit;
}

// Database connection
$host = 'localhost'; // Host database
$username = 'root'; // Username database
$password = ''; // Password database
$dbname = 'login_system'; // Nama database

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($email);
$stmt->fetch();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:image" content="https://raw.githubusercontent.com/naomiaro/waveform-playlist/master/img/effects.png" />
    <meta property="og:image:height" content="401" />
    <meta property="og:image:width" content="1039" />

    <title>Web Audio Editor</title>
    <meta name="description" content="Waveform Playlist, the multitrack javascript web audio editor and player. Set audio cue in and cue out. Set linear, exponential, logarithmic, and s-curve fades. Shift audio in time. Zoom in and zoom out on the waveform. Play, stop, pause and seek inside the audio tracks." />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous" />
    <link rel="stylesheet" href="playlist.scss" />
    <link rel="stylesheet" href="style.css" />
    

    <link rel="canonical" href="https://naomiaro.github.io/waveform-playlist/web-audio-editor.html" />
    <link rel="alternate" type="application/rss+xml" title="Waveform Playlist" href="https://naomiaro.github.io/waveform-playlist/feed.xml">
    <script src="https://kit.fontawesome.com/a88ce4f8a0.js" crossorigin="anonymous"></script>
</head>

<body class="custom-background">
    <script>
        (function(i, s, o, g, r, a, m) {
            i["GoogleAnalyticsObject"] = r;
            (i[r] =
                i[r] ||
                function() {
                    (i[r].q = i[r].q || []).push(arguments);
                }),
            (i[r].l = 1 * new Date());
            (a = s.createElement(o)), (m = s.getElementsByTagName(o)[0]);
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m);
        })(window, document, "script", "//www.google-analytics.com/analytics.js", "ga");
        ga("create", "UA-62186746-1", "auto");
        ga("send", "pageview");
    </script>

    <header style="pointer-events: auto;">
        <ol class="breadcrumb">
            <li><img src="assets/logo.png" alt="Logo" width="100" height="40"></li>
            <div class="header-right">
                <span class="user-email"><?php echo htmlspecialchars($email); ?></span>
                <a href="../login_app/logout.php" class="btn btn-outline-danger btn-logout">Logout</a>
            </div>
        </ol>
    </header>



    <main class="container" title="">
        <div class="wrapper">
            <article class="post">
                <header class="post-header">
                    <h1 class="post-title">Edit your Audio Faster</h1>
                    <p class="lead">Trim it, manage the volume, and many more</p>
                </header>
                <div class="post-content">
                    <div id="top-bar" class="playlist-top-bar">
                        <div class="playlist-toolbar">
                            <div class="btn-group">
                                <button type="button" class="btn-pause btn btn-outline-warning" title="Pause">
                                    <i class="fas fa-pause"></i> Pause
                                </button>
                                <button type="button" class="btn-play btn btn-outline-success" title="Play">
                                    <i class="fas fa-play"></i> Play
                                </button>
                                <button type="button" class="btn-stop btn btn-outline-danger" title="Stop">
                                    <i class="fas fa-stop"></i> Stop
                                </button>
                                <button type="button" class="btn-rewind btn btn-outline-success" title="Rewind">
                                    <i class="fas fa-fast-backward"></i> Rewind
                                </button>
                                <button type="button" class="btn-fast-forward btn btn-outline-success" title="Fast forward">
                                    <i class="fas fa-fast-forward"></i> Fast Forward
                                </button>
                                <button type="button" class="btn-record btn btn-outline-primary disabled" title="Record">
                                    <i class="fas fa-microphone"></i> Record
                                </button>
                            </div>

                            <div class="btn-group">
                                <button type="button" title="Zoom in" class="btn-zoom-in btn btn-outline-dark">
                                    <i class="fas fa-search-plus"></i> Zoom In
                                </button>
                                <button type="button" title="Zoom out" class="btn-zoom-out btn btn-outline-dark">
                                    <i class="fas fa-search-minus"></i> Zoom Out
                                </button>
                            </div>

                            <div class="btn-group btn-playlist-state-group">
                                <button type="button" class="btn-cursor btn btn-outline-dark active" title="Select cursor">
                                    <i class="fa-solid fa-arrow-pointer"></i> Cursor
                                </button>
                                <button type="button" class="btn-select btn btn-outline-dark" title="Select audio region">
                                    <i class="fas fa-italic"></i> Select
                                </button>
                                <button type="button" class="btn-shift btn btn-outline-dark" title="Shift audio in time">
                                    <i class="fas fa-arrows-alt-h"></i> Shift
                                </button>
                                <button type="button" class="btn-fadein btn btn-outline-dark" title="Set audio fade in">
                                    <i class="fas fa-long-arrow-alt-left"></i> Fade In
                                </button>
                                <button type="button" class="btn-fadeout btn btn-outline-dark" title="Set audio fade out">
                                    <i class="fas fa-long-arrow-alt-right"></i> Fade Out
                                </button>
                            </div>

                            <div class="btn-group btn-select-state-group">
                                <button type="button" class="btn-loop btn btn-outline-success disabled" title="Loop a selected segment of audio">
                                    <i class="fas fa-redo-alt"></i> Loop
                                </button>
                                <button type="button" title="Keep only the selected audio region for a track" class="btn-trim-audio btn btn-outline-primary disabled">
                                    Trim
                                </button>
                            </div>
                            <div class="btn-group">
                                <button type="button" title="Clear the playlist's tracks" class="btn btn-clear btn-outline-danger">
                                    Clear
                                </button>
                            </div>
                            <div class="btn-group">
                                <button type="button" title="Download the current work as Wav file" class="btn btn-download btn-outline-primary">
                                    <i class="fas fa-download"></i> Download
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="playlist"></div>
                    <div class="playlist-bottom-bar">
                        <form class="form-inline">
                            <select class="time-format custom-select mr-sm-2" aria-label="Time format selection">
                                <option value="seconds">seconds</option>
                                <option value="thousandths">thousandths</option>
                                <option value="hh:mm:ss">hh:mm:ss</option>
                                <option value="hh:mm:ss.u">hh:mm:ss + tenths</option>
                                <option value="hh:mm:ss.uu">hh:mm:ss + hundredths</option>
                                <option value="hh:mm:ss.uuu" selected="selected">hh:mm:ss + milliseconds</option>
                            </select>
                            <label class="sr-only" for="audio_start">Start of audio selection</label>
                            <input type="text" class="audio-start form-control mr-sm-2" id="audio_start">
                            <label class="sr-only" for="audio_end">End of audio selection</label>
                            <input type="text" class="audio-end form-control mr-sm-2" id="audio_end">
                            <span class="audio-pos" aria-label="Audio position">00:00:00.0</span>

                            <label for="uploadAudio" class="mr-sm-2">Upload Audio</label>
                            <input type="file" class="form-control mr-sm-2" id="uploadAudio" name="audioFile" accept=".mp3, .wav, .ogg">
                        </form>
                    </div>
                </div>
            </article>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/waveform-playlist.js"></script>
    <script type="text/javascript" src="js/web-audio-editor.js"></script>
    <script type="text/javascript" src="js/emitter.js"></script>
</body>

</html>