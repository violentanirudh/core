<?php

function send_mail($to, $from, $subject, $body, $type = 'default') {
    $headers = "From: $from\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    return mail($to, $subject, $body, $headers);
}

function send_verification_mail($to, $from, $verification_token) {
    $base_url = get_current_url();
    $subject = "Verify Your Email";
    $verify_url = $base_url . "/verify/" . $verification_token;
    
    $body = "Click the link below to verify your email:
             <a href='$verify_url'>Verify Email</a>";
    
    return send_mail($to, $from, $subject, $body, 'verification');
}

function send_reset_password_mail($to, $from, $reset_token) {
    $base_url = get_current_url();
    $subject = "Reset Your Password";
    $reset_url = $base_url . "/reset-password/" . $reset_token;
    
    $body = "Click the link below to reset your password:
             <a href='$reset_url'>Reset Password</a>";
    
    return send_mail($to, $from, $subject, $body, 'verification');
}

function send_contact_support_mail($to, $from, $message) {
    $subject = "Support Request";
    $body = "**From:** $from\n\n**Message:**\n\n$message";
    
    return send_mail($to, $from, $subject, $body, 'contact');
}
