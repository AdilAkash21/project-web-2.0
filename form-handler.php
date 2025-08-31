<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check honeypot field - if it's filled out, it's likely a bot
    if (!empty($_POST['website'])) {
        // Silently reject what is likely spam
        header("Location: contact.html");
        exit;
    }
    
    // Validate and sanitize input data
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $visitor_email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $subject = filter_var(trim($_POST['subject']), FILTER_SANITIZE_STRING);
    $message = filter_var(trim($_POST['message']), FILTER_SANITIZE_STRING);
    
    // Check if fields are empty
    if (empty($name) || empty($visitor_email) || empty($subject) || empty($message)) {
        http_response_code(400);
        echo "Please fill out all fields.";
        exit;
    }
    
    // Validate email
    if (!filter_var($visitor_email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Invalid email format.";
        exit;
    }
    
    // Set email parameters
    $email_from = 'adilakash23@gmail.com';
    $email_subject = 'New Form Submission: ' . $subject;
    $email_body = "You have received a new message from your website contact form.\n\n".
                  "Name: $name\n".
                  "Email: $visitor_email\n".
                  "Subject: $subject\n".
                  "Message:\n$message\n";
    
    $to = 'nirob7040@gmail.com';
    
    // Build headers
    $headers = "From: $email_from\r\n";
    $headers .= "Reply-To: $visitor_email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Send email
    if (mail($to, $email_subject, $email_body, $headers)) {
        // Success - redirect to thank you page
        header("Location: contact.html?status=success");
        exit;
    } else {
        // Failed to send
        http_response_code(500);
        echo "Oops! Something went wrong and we couldn't send your message.";
        exit;
    }
} else {
    // Not a POST request
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
    exit;
}
?>