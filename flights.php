<?php
session_start();


if (isset($_POST['token'])) {
    session_regenerate_id(true);
    $_SESSION['token'] = $_POST['token'];
    echo "Token is set to: " . htmlspecialchars($_SESSION['token']) . "<br>";
}


function getTodayFlights()
{
    date_default_timezone_set('Asia/Colombo'); 
    $api = "https://cinnamon.go.digitable.io/avidi/api/avidi/v1/flights?";
    $date = date('Y-m-d');
    $url = $api . "date=" . $date . "&type=todayFlight"; 

    if (!isset($_SESSION['token'])) {
        return ['error' => 'Authentication token is missing. Please log in.'];
    }

    $token = $_SESSION['token'];

    $headers = [
        "Accept: application/json",
        "Content-Type: application/x-www-form-urlencoded",
        "Authorization: Bearer " . $token,
        "Build: 11",
        "Header-from: web"
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $errorMsg = 'Curl error: ' . curl_error($ch);
        file_put_contents('error_log.log', $errorMsg . PHP_EOL, FILE_APPEND);
        return ['error' => $errorMsg];
    }

    curl_close($ch);

    $jsonData = json_decode($response, true);

    if (isset($jsonData['data']) && is_array($jsonData['data']) && count($jsonData['data']) > 0) {
        return ['flights' => $jsonData['data']];
    }

    return ['flights' => [], 'message' => 'No flights available for today.'];
}

$result = getTodayFlights();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today's Flights</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <style>
        .blinking {
            animation: blinker 500ms linear infinite;
        }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Today's Flights</h1>

        <?php if (!isset($_SESSION['token'])): ?>
            <div class="text-red-500 mb-4">
                <p>Token is missing. Please enter the token below:</p>
            </div>
            <form action="" method="POST">
                <input type="text" name="token" class="p-2 border rounded" placeholder="Enter token">
                <button type="submit" class="mt-2 bg-blue-500 text-white p-2 rounded">Set Token</button>
            </form>
        <?php elseif (isset($result['error'])): ?>
            <div class="text-red-500 mb-4">
                <p><?php echo htmlspecialchars($result['error']); ?></p>
            </div>
        <?php elseif (!empty($result['flights'])): ?>
            <ul class="space-y-4">
                <?php
                $todayDate = date('dS D, M Y');

                foreach ($result['flights'] as $flight):

                    if (isset($flight['flightDate']) && $flight['flightDate'] === $todayDate):

                        $isBlinking = false;
                        $desTime = strtotime($flight['desTime']);
                        $timeDifference = null;
                        $depTime = strtotime($flight['depTime']);
                        $current_time = time();

                        if (!empty($flight['desTime'])) {
                            $timeDifference = floor(($desTime - $current_time) / 60);

                            if ($timeDifference > 0) {
                                $isBlinking = ($timeDifference < 30 && $timeDifference > 27 || $timeDifference < 5);
                            } else {
                                $timeDifference = "Flight time passed " . abs($timeDifference);
                            }
                        }
                ?>

                        <li class="p-4 border rounded-lg shadow-sm bg-green-500 <?php echo ($isBlinking ? 'blinking' : ''); ?>" id="flight-<?php echo $flight['flightNo']; ?>">
                            <h2 class="text-lg font-semibold">Flight Date: <?php echo htmlspecialchars($flight['flightDate']); ?></h2>

                            <?php
                            if (!empty($flight['depTime'])) {
                                $formattedDepTime = date('H:i', $depTime);
                                echo "<p><strong>Departure Time:</strong> <span>" . htmlspecialchars($formattedDepTime) . "</span></p>";
                                echo "<p><strong>Next flight:</strong> <span class='next-flight-time'>" . htmlspecialchars($timeDifference) . " minutes</span></p>";
                            }

                            echo "<p><strong>Current Time:</strong> <span id='current-time'>" . htmlspecialchars(date('H:i', $current_time)) . "</span></p>";

                            if (!empty($flight['desTime'])) {
                                $formattedDesTime = date('H:i', $desTime);
                                echo "<p><strong>Destination Time:</strong> " . htmlspecialchars($formattedDesTime) . "</p>";
                            } else {
                                echo "<p><strong>Destination Time:</strong> N/A</p>";
                            }

                            echo "<p><strong>CAP:</strong> " . htmlspecialchars($flight['cap'] ?? 'N/A') . "</p>";
                            echo "<p><strong>FO:</strong> " . htmlspecialchars($flight['fo'] ?? 'N/A') . "</p>";
                            echo "<p><strong>Departure Route:</strong> " . htmlspecialchars($flight['route'] ?? 'N/A') . "</p>";
                            echo "<p><strong>Flight No:</strong> " . htmlspecialchars($flight['flightNo'] ?? 'N/A') . "</p>";
                            ?>
                        </li>
                <?php
                    endif;
                endforeach;
                ?>
            </ul>
        <?php else: ?>
            <div class="text-gray-500">
                <p><?php echo htmlspecialchars($result['message']); ?></p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function updateCurrentTime() {
            var currentTimeElement = document.getElementById('current-time');
            if (currentTimeElement) {
                var currentTime = new Date();
                currentTimeElement.textContent = currentTime.toLocaleTimeString();
            }
        }

        function updateFlightTimes() {
            var flights = <?php echo json_encode($result['flights']); ?>;
            var currentTime = new Date().getTime() / 1000;

            flights.forEach(function(flight) {
                var desTimeElement = document.getElementById('flight-' + flight.flightNo);
                if (desTimeElement && flight.desTime) {
                    var desTime = new Date("<?php echo date('Y-m-d'); ?> " + flight.desTime).getTime() / 1000;
                    var timeDifference = Math.floor((desTime - currentTime) / 60);

                    if (timeDifference > 0) {
                        desTimeElement.querySelector('.next-flight-time').textContent = timeDifference + " minutes";
                    } else {
                        desTimeElement.querySelector('.next-flight-time').textContent = "Flight time passed " + Math.abs(timeDifference);
                    }
                }
            });
        }

        setInterval(function() {
            updateCurrentTime();
            updateFlightTimes();
        }, 60000);
    </script>

</body>

</html>