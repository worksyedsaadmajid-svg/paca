<?php

// =====================================================
// CONTACT FORM CONFIGURATION
// =====================================================

// CHANGE THIS TO YOUR EMAIL ADDRESS
$to = "sa955659@gmail.com";

$subject = "New Contact Form Submission";


// =====================================================
// ONLY ALLOW POST REQUEST
// =====================================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Invalid request.");
}


// =====================================================
// GET FORM DATA
// =====================================================

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$mobile = trim($_POST["mobile"] ?? "");
$message = trim($_POST["message"] ?? "");
$gdpr = $_POST["gdpr_agreement"] ?? "";


// =====================================================
// REQUIRED FIELD VALIDATION
// =====================================================

if ($name === "") {
    exit("Please enter your name.");
}

if ($email === "") {
    exit("Please enter your email address.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("Please enter a valid email address.");
}

if ($mobile === "") {
    exit("Please enter your phone number.");
}

if ($message === "") {
    exit("Please enter your message.");
}

if ($gdpr !== "yes") {
    exit("Please accept the consent checkbox.");
}


// =====================================================
// SANITIZE DATA
// =====================================================

$name = htmlspecialchars($name, ENT_QUOTES, "UTF-8");
$email = htmlspecialchars($email, ENT_QUOTES, "UTF-8");
$mobile = htmlspecialchars($mobile, ENT_QUOTES, "UTF-8");
$message = htmlspecialchars($message, ENT_QUOTES, "UTF-8");


// =====================================================
// EMAIL MESSAGE
// =====================================================

$email_body = "

<!DOCTYPE html>

<html>

<head>

<meta charset='UTF-8'>

<title>Contact Form Submission</title>

</head>

<body>

<h2>New Contact Form Submission</h2>

<table
    width='100%'
    cellpadding='10'
    cellspacing='0'
    border='1'
    style='border-collapse:collapse;'
>

<tr>
    <td width='150'>
        <strong>Name</strong>
    </td>

    <td>
        {$name}
    </td>
</tr>


<tr>
    <td>
        <strong>Email</strong>
    </td>

    <td>
        {$email}
    </td>
</tr>


<tr>
    <td>
        <strong>Phone</strong>
    </td>

    <td>
        {$mobile}
    </td>
</tr>


<tr>
    <td>
        <strong>Message</strong>
    </td>

    <td>
        " . nl2br($message) . "
    </td>
</tr>


<tr>
    <td>
        <strong>GDPR Consent</strong>
    </td>

    <td>
        Yes
    </td>
</tr>

</table>

</body>

</html>

";


// =====================================================
// EMAIL HEADERS
// =====================================================

$host = $_SERVER["HTTP_HOST"];

$headers = "MIME-Version: 1.0\r\n";

$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$headers .= "From: Website Contact Form <no-reply@{$host}>\r\n";

$headers .= "Reply-To: {$email}\r\n";


// =====================================================
// SEND EMAIL
// =====================================================

if (mail($to, $subject, $email_body, $headers)) {

    // SUCCESS
    header("Location: thank-you.html");
    exit;

} else {

    // ERROR
    http_response_code(500);

    echo "Sorry, your message could not be sent. Please try again later.";

    exit;
}

?>