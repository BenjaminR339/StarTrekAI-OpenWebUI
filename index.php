<?php

$status = "";
$feedRaw = "";
$feedSummary = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture raw form inputs safely
    $feedRaw = filter_input(INPUT_POST, 'feedRaw', FILTER_SANITIZE_STRING);

    if (empty($feedRaw)) {
        $status .= "Please enter some text.<br>";
    } else {
        try {
            // ---------------------------------------------------
            // Ollama Summarization Request using Open WebUI API
            // ---------------------------------------------------
            $api_url = "removed for privacy";  // Updated API endpoint
            $apiKey = "removed for privacy";

            // Prepare the request data according to the new format
            $requestData = [
                "model" => "phi4:latest",  // Specify the model for Ollama
                "messages" => [             // Use messages array as per the new API specification
                    [
                        "role" => "You are Commander Data the tv show Star Trek the Next Generation, you will respond as if you are speaking directly with the Captain. Make your responses easy to understand",
                        "content" => $feedRaw
                    ]
                ]
            ];

            // Initialize cURL for Open WebUI API
            $ch = curl_init($api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json",
                "Authorization: Bearer $apiKey"  // Include the API key in the Authorization header
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));

            // Execute request
            $response = curl_exec($ch);

            // Handle cURL errors
            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
                curl_close($ch);
                throw new Exception("Error communicating with Open WebUI API: " . $error_msg);
            }
            curl_close($ch);

            // Decode API response
            $responseData = json_decode($response, true);

            // Check for valid response
            if (isset($responseData['choices'][0]['message']['content'])) {
                $feedSummary = trim(htmlspecialchars($responseData['choices'][0]['message']['content']));
            } else {
                throw new Exception("Open WebUI API did not return a valid summary.");
            }

        } catch (Exception $e) {
            // Display error message
            $status .= "Error: " . htmlspecialchars($e->getMessage()) . "<br>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./style.css">
        <title>Star Trek Control</title>
        
        
    </head>

    <body>
        <!--header decoration-->
        <h1 class="header">COMMANDER DATA</h1> 
        <h1 class="stardate">STARDATE:297806 TIME:13:53.27</h1>
        <div class="small-box"><p class="box-text">LCARS-7102</p></div>
        <img class="under-header" src="./uppershape.png" >
        <div class="small-box2"></div>
        <div class="small-box3"></div>
        <div class="small-box4"></div>
        <div class="large-box"></div>
        <div class="large-box2"></div>

        <!--main body decorations-->
        <img class="lowershape" src="./lowershape.png">
        <div class="small-box5"></div>
        <div class="small-box6"></div>
        <div class="small-box7"></div>
        <div class="large-box3"></div>
        <div class="large-box4"></div>

        <!--Sidebar decorations-->
        <div class="medium-box"><p class="zone1">ZONE 1</p></div>
        <div class="medium-box2"><p class="zone2">ZONE 2</p></div>
        <div class="medium-box3"><p class="zone3">ZONE 3</p></div>
        <div class="medium-box4"><p class="zone4">ZONE 4</p></div>
        <div class="medium-box5"><p class="zone5">ZONE 5</p></div>
        <div class="medium-box6"><p class="zone6">ZONE 6</p></div>
        <div class="medium-box7"><p class="zone7">ZONE 7</p></div>

         <!--AI integration-->
        

        <!--footer-->
        <img class="footershape" src="./footershape.png">
        <div class="small-box8"></div>
        <div class="small-box9"></div>
        <div class="small-box10"></div>
        <div class="large-box5"></div>
        <div class="large-box6"></div>

        <div class="form">
            <form name="form" method="post" action="">
                <input type="hidden" name="new" value="1" />
                <textarea class="form-control" cols="28" rows="1" name="feedRaw" required></textarea>
                <br>
                <button type="submit" class="submit-button">Submit</button>
            </form>
        </div>

        <?php if ($status): ?>
            <div class="status-message">
                <?= nl2br($status) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($feedSummary)): ?>
            <div class="summary-result">
                <p><?= $feedSummary ?><p>
            </div>
        <?php endif; ?>
        
    </body>
</html>