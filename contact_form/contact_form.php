<?php
/* ──────────────────────────────
   Simple contact‑form handler
   Save as:  contact_form/contact_form.php
   ────────────────────────────── */

/* ---------- CONFIG ---------- */
$sendTo = 'webdev.by.dan@gmail.com';  // where you want to receive the mail
$from   = 'webdev.by.dan@gmail.com';     // a real mailbox on your domain
$subj   = 'New message from website contact form';

/* ---------- COLLECT & SANITISE POST ---------- */
$fields = [
    'name'    => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    'email'   => FILTER_VALIDATE_EMAIL,
    'subject' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    'message' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
];
$data = filter_input_array(INPUT_POST, $fields);

if (!$data || in_array(false, $data, true)) {
    exit(json_encode(['type' => 'danger', 'message' => 'Please fill in all fields correctly.']));
}

/* ---------- BUILD HTML BODY ---------- */
$body = "<h2>Contact‑form message</h2>\n"
      . "<p><strong>Name:</strong> {$data['name']}</p>\n"
      . "<p><strong>Email:</strong> {$data['email']}</p>\n"
      . "<p><strong>Subject:</strong> {$data['subject']}</p>\n"
      . "<p><strong>Message:</strong><br>" . nl2br($data['message']) . '</p>';

/* ---------- HEADERS ---------- */
$headers = [
    'MIME-Version: 1.0',
    'Content-Type: text/html; charset=UTF-8',
    "From: $from",
    "Reply-To: {$data['email']}",   // lets you hit “Reply” to respond
];

/* ---------- SEND ---------- */
$sent = mail($sendTo, $subj, $body, implode("\r\n", $headers));

$response = $sent
    ? ['type' => 'success', 'message' => 'Thank you! Your message has been sent.']
    : ['type' => 'danger',  'message' => 'Sorry, something went wrong. Please try again.'];

/* ---------- RETURN JSON FOR AJAX (or plain text fallback) ---------- */
header('Content-Type: application/json');
echo json_encode($response);
?>
