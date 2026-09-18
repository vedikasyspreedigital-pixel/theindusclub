<?php

// ==========================================================
// THE INDUS CLUB - CALLBACK HANDLER
// CLEAN TEST VERSION
// ==========================================================

date_default_timezone_set('Asia/Kolkata');


// ==========================================================
// ZOHO FUNCTION CONFIG
// ==========================================================
//
// IMPORTANT:
// Put ONLY your NEW Zoho API key here.
//
// Example:
// $zohoApiKey = "1003.xxxxxxxxxxxxxxxxx";
//
// DO NOT put the complete Zoho URL here.
//

$zohoApiKey = "1003.8561ff2bd4ce1bc6fc4ac35be9c9cd75.328d1ffe47abb25bf63a7c2909390a74";

// ==========================================================
// ZOHO FUNCTION URL
// ==========================================================

$zohoFunctionUrl =
    "https://www.zohoapis.com/crm/v7/functions/test_createprecisemmtask/actions/execute" .
    "?auth_type=apikey" .
    "&zapikey=" . urlencode($zohoApiKey);


// ==========================================================
// GET PARAMETERS
// ==========================================================

$leadId = isset($_GET['lead'])
    ? trim($_GET['lead'])
    : '';

$option = isset($_GET['option'])
    ? trim($_GET['option'])
    : '';


// ==========================================================
// CALENDAR DOWNLOAD
// ==========================================================
//
// IMPORTANT:
// This creates the ICS file IN MEMORY ONLY.
// Nothing is saved on cPanel.
//

if ($option === 'calendar') {

    $calendarDateTime = isset($_GET['datetime'])
        ? trim($_GET['datetime'])
        : '';

    if (
        $leadId === '' ||
        !preg_match('/^[0-9]+$/', $leadId)
    ) {
        showError("Invalid Lead ID.");
    }

    $callTime = DateTime::createFromFormat(
        'Y-m-d H:i:s',
        $calendarDateTime,
        new DateTimeZone('Asia/Kolkata')
    );

    if (!$callTime) {
        showError("Invalid calendar date or time.");
    }

    outputCalendar(
        $leadId,
        $callTime
    );
}


// ==========================================================
// VALIDATE LEAD ID
// ==========================================================

if ($leadId === '') {
    showError("Lead ID is missing.");
}

if (!preg_match('/^[0-9]+$/', $leadId)) {
    showError("Invalid Lead ID.");
}


// ==========================================================
// CUSTOM TIME FORM SUBMISSION
// ==========================================================

if ($option === 'custom_submit') {

    $customDate = isset($_GET['custom_date'])
        ? trim($_GET['custom_date'])
        : '';

    $customTime = isset($_GET['custom_time'])
        ? trim($_GET['custom_time'])
        : '';

    if (
        $customDate === '' ||
        $customTime === ''
    ) {
        showError(
            "Please select both date and time."
        );
    }


    $callTime = DateTime::createFromFormat(
        'Y-m-d H:i',
        $customDate . ' ' . $customTime,
        new DateTimeZone('Asia/Kolkata')
    );


    if (!$callTime) {
        showError(
            "Invalid date or time."
        );
    }


    $now = new DateTime(
        'now',
        new DateTimeZone('Asia/Kolkata')
    );


    if ($callTime <= $now) {
        showError(
            "Please select a future date and time."
        );
    }


    $displaySlot = $callTime->format(
        'd-M-Y h:i A'
    );


    processCallback(
        $leadId,
        $callTime,
        $displaySlot,
        $zohoFunctionUrl
    );
}


// ==========================================================
// CURRENT TIME
// ==========================================================

$now = new DateTime(
    'now',
    new DateTimeZone('Asia/Kolkata')
);

$callTime = null;
$displaySlot = '';


// ==========================================================
// TOMORROW 11 AM
// ==========================================================

if ($option === 'tomorrow_11am') {

    $callTime = clone $now;

    $callTime->modify('+1 day');

    $callTime->setTime(
        11,
        0,
        0
    );


    $displaySlot = $callTime->format(
        'd-M-Y h:i A'
    );
}


// ==========================================================
// TOMORROW 5 PM
// ==========================================================

elseif ($option === 'tomorrow_5pm') {

    $callTime = clone $now;

    $callTime->modify('+1 day');

    $callTime->setTime(
        17,
        0,
        0
    );


    $displaySlot = $callTime->format(
        'd-M-Y h:i A'
    );
}


// ==========================================================
// DAY AFTER TOMORROW 3 PM
// ==========================================================

elseif ($option === 'day_after_3pm') {

    $callTime = clone $now;

    $callTime->modify('+2 days');

    $callTime->setTime(
        15,
        0,
        0
    );


    $displaySlot = $callTime->format(
        'd-M-Y h:i A'
    );
}


// ==========================================================
// CUSTOM
// ==========================================================

elseif ($option === 'custom') {

    showCustomTimePage(
        $leadId
    );
}


// ==========================================================
// INVALID OPTION
// ==========================================================

else {

    showError(
        "Invalid callback option."
    );
}


// ==========================================================
// PROCESS CALLBACK
// ==========================================================

processCallback(
    $leadId,
    $callTime,
    $displaySlot,
    $zohoFunctionUrl
);


// ==========================================================
// PROCESS CALLBACK
// ==========================================================

function processCallback(
    $leadId,
    $callTime,
    $displaySlot,
    $zohoFunctionUrl
) {

    // ------------------------------------------------------
    // FORMAT DATETIME FOR ZOHO
    // ------------------------------------------------------

    $callDateTimeText =
        $callTime->format(
            'Y-m-d H:i:s'
        );


    // ------------------------------------------------------
    // CALL ZOHO FUNCTION
    // ------------------------------------------------------

    $zohoResponse =
        callZohoFunction(
            $zohoFunctionUrl,
            $leadId,
            $callDateTimeText
        );


    // ------------------------------------------------------
    // CHECK ZOHO RESPONSE
    // ------------------------------------------------------

    if ($zohoResponse === false) {

        showError(
            "We couldn't schedule your callback right now. Please try again."
        );
    }


    // ------------------------------------------------------
    // SUCCESS PAGE
    // ------------------------------------------------------
    //
    // No ICS file is created here.
    //
    // The calendar file will only be generated when
    // the user clicks "Add to Calendar".
    //

    showSuccessPage(
        $leadId,
        $callTime,
        $displaySlot
    );
}


// ==========================================================
// CALL ZOHO FUNCTION
// ==========================================================

function callZohoFunction(
    $url,
    $leadId,
    $callDateTimeText
) {

    // ------------------------------------------------------
    // PASS ARGUMENTS DIRECTLY IN URL
    // ------------------------------------------------------

    $requestUrl =
        $url .
        '&leadId=' .
        urlencode($leadId) .
        '&callDateTimeText=' .
        urlencode($callDateTimeText);


    // ------------------------------------------------------
    // CURL
    // ------------------------------------------------------

    $ch = curl_init(
        $requestUrl
    );


    curl_setopt(
        $ch,
        CURLOPT_POST,
        true
    );


    // Empty POST body.
    // Arguments are already in the URL.

    curl_setopt(
        $ch,
        CURLOPT_POSTFIELDS,
        ''
    );


    curl_setopt(
        $ch,
        CURLOPT_RETURNTRANSFER,
        true
    );


    curl_setopt(
        $ch,
        CURLOPT_TIMEOUT,
        20
    );


    // ------------------------------------------------------
    // EXECUTE
    // ------------------------------------------------------

    $response =
        curl_exec($ch);


    $httpCode =
        curl_getinfo(
            $ch,
            CURLINFO_HTTP_CODE
        );


    curl_close($ch);


    // ------------------------------------------------------
    // CURL ERROR
    // ------------------------------------------------------

    if ($response === false) {

        return false;
    }


    // ------------------------------------------------------
    // HTTP ERROR
    // ------------------------------------------------------

    if (
        $httpCode < 200 ||
        $httpCode >= 300
    ) {

        return false;
    }


    // ------------------------------------------------------
    // CHECK ZOHO RESPONSE
    // ------------------------------------------------------

    $decoded =
        json_decode(
            $response,
            true
        );


    if (
        isset($decoded['code']) &&
        $decoded['code'] !== 'success'
    ) {

        return false;
    }


    // ------------------------------------------------------
    // SUCCESS
    // ------------------------------------------------------

    return $response;
}


// ==========================================================
// OUTPUT CALENDAR
// ==========================================================
//
// Generates ICS directly to browser.
// NO FILE IS CREATED ON SERVER.
//

function outputCalendar(
    $leadId,
    $callTime
) {

    $eventTitle =
        "Membership Manager Call";


    $description =
        "Callback scheduled with The Indus Club Membership Manager.";


    // ------------------------------------------------------
    // 30 MINUTE EVENT
    // ------------------------------------------------------

    $eventEnd =
        clone $callTime;

    $eventEnd->modify(
        '+30 minutes'
    );


    // ------------------------------------------------------
    // UNIQUE EVENT ID
    // ------------------------------------------------------

    $uid =
        'indusclub-callback-' .
        $leadId .
        '-' .
        $callTime->format(
            'YmdHis'
        ) .
        '@theindusclub.com';


    // ------------------------------------------------------
    // BUILD ICS IN MEMORY
    // ------------------------------------------------------

    $ics =
        "BEGIN:VCALENDAR\r\n" .
        "VERSION:2.0\r\n" .
        "PRODID:-//The Indus Club//Callback//EN\r\n" .
        "CALSCALE:GREGORIAN\r\n" .
        "METHOD:PUBLISH\r\n" .
        "BEGIN:VEVENT\r\n" .
        "UID:" .
        $uid .
        "\r\n" .
        "DTSTAMP:" .
        gmdate(
            'Ymd\THis\Z'
        ) .
        "\r\n" .
        "DTSTART;TZID=Asia/Kolkata:" .
        $callTime->format(
            'Ymd\THis'
        ) .
        "\r\n" .
        "DTEND;TZID=Asia/Kolkata:" .
        $eventEnd->format(
            'Ymd\THis'
        ) .
        "\r\n" .
        "SUMMARY:" .
        escapeICS(
            $eventTitle
        ) .
        "\r\n" .
        "DESCRIPTION:" .
        escapeICS(
            $description
        ) .
        "\r\n" .
        "END:VEVENT\r\n" .
        "END:VCALENDAR\r\n";


    // ------------------------------------------------------
    // SEND ICS DIRECTLY TO USER
    // ------------------------------------------------------

    $fileName =
        'membership_callback_' .
        $callTime->format(
            'Ymd_His'
        ) .
        '.ics';


    header(
        'Content-Type: text/calendar; charset=utf-8'
    );


    header(
        'Content-Disposition: attachment; filename="' .
        $fileName .
        '"'
    );


    header(
        'Content-Length: ' .
        strlen($ics)
    );


    echo $ics;

    exit;
}


// ==========================================================
// CUSTOM TIME PAGE
// ==========================================================

function showCustomTimePage(
    $leadId
) {
?>

<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Choose Your Preferred Time
    </title>


    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            margin: 0;
            padding: 30px 20px;
        }

        .box {
            max-width: 500px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 14px;
            box-shadow:
                0 4px 20px rgba(0,0,0,.08);
        }

        h2 {
            margin-top: 0;
        }

        label {
            display: block;
            margin-top: 20px;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            padding: 13px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            width: 100%;
            margin-top: 25px;
            padding: 14px;
            border: 0;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

    </style>

</head>


<body>

<div class="box">

    <h2>
        Choose your preferred time
    </h2>


    <p>
        Select the date and time you'd like
        our Membership Manager to call you.
    </p>


    <form method="GET">

        <input
            type="hidden"
            name="lead"
            value="<?php
                echo htmlspecialchars(
                    $leadId
                );
            ?>"
        >


        <input
            type="hidden"
            name="option"
            value="custom_submit"
        >


        <label>
            Date
        </label>


        <input
            type="date"
            name="custom_date"
            required
        >


        <label>
            Time
        </label>


        <input
            type="time"
            name="custom_time"
            required
        >


        <button type="submit">
            Confirm Callback
        </button>

    </form>

</div>

</body>

</html>

<?php

    exit;
}


// ==========================================================
// SUCCESS PAGE
// ==========================================================

function showSuccessPage(
    $leadId,
    $callTime,
    $displaySlot
) {
?>

<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Callback Scheduled
    </title>


    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            margin: 0;
            padding: 30px 20px;
            text-align: center;
        }

        .box {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 35px 25px;
            border-radius: 16px;
            box-shadow:
                0 4px 20px rgba(0,0,0,.08);
        }

        .success {
            font-size: 45px;
        }

        h1 {
            margin-bottom: 10px;
        }

        .time {
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
        }

        .calendar {
            display: inline-block;
            padding: 14px 22px;
            background: #111;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }

    </style>

</head>


<body>

<div class="box">

    <div class="success">
        ✓
    </div>


    <h1>
        Callback Scheduled
    </h1>


    <p>
        Our Membership Manager will contact you at:
    </p>


    <div class="time">

        <?php
        echo htmlspecialchars(
            $displaySlot
        );
        ?>

    </div>


    <a
        class="calendar"
        href="<?php

            echo htmlspecialchars(
                'callback.php?lead=' .
                urlencode($leadId) .
                '&option=calendar&datetime=' .
                urlencode(
                    $callTime->format(
                        'Y-m-d H:i:s'
                    )
                )
            );

        ?>"
    >
        Add to Calendar
    </a>

</div>

</body>

</html>

<?php

    exit;
}


// ==========================================================
// ERROR PAGE
// ==========================================================

function showError(
    $message
) {

    http_response_code(
        400
    );

?>

<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Error
    </title>

</head>


<body
    style="
        font-family:Arial;
        text-align:center;
        padding:60px 20px;
    "
>

    <h2>
        Something went wrong
    </h2>


    <p>
        <?php
        echo htmlspecialchars(
            $message
        );
        ?>
    </p>

</body>

</html>

<?php

    exit;
}


// ==========================================================
// ICS ESCAPE
// ==========================================================

function escapeICS(
    $text
) {

    return str_replace(
        array(
            "\\",
            ";",
            ",",
            "\n"
        ),
        array(
            "\\\\",
            "\;",
            "\,",
            "\\n"
        ),
        $text
    );
}

?>