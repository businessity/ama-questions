<?php
/**
 * This script handles the form processing
 *
 * PHP version 7.2
 *
 * @category Registration
 * @package  Registration
 * @author   Benson Imoh,ST <benson@stbensonimoh.com>
 * @license  GPL https://opensource.org/licenses/gpl-license
 * @link     https://stbensonimoh.com
 */
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// echo json_encode($_POST);

// Pull in the required files
require '../config.php';
require './DB.php';
require './Notify.php';

// Capture the post data coming from the form
$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$businessName = $_POST['businessName'];
$businessDescription = $_POST['businessDescription'];
$question = $_POST['question'];

$details = array(
    "name" => $name,
    "phone" => $phone,
    "email" => $email,
    "businessName" => $businessName,
    "businessDescription" => $businessDescription,
    "question" => $question
);

$db = new DB($host, $db, $username, $password);

$notify = new Notify($smstoken);

// First check to see if the user is in the Database
if ($db->userExists($email, "businessity_ama")) {
    echo json_encode("user_exists");
} else {
    // Insert the user into the database
    $db->getConnection()->beginTransaction();
    $db->insertUser("businessity_ama", $details);
        // Send SMS
        $notify->viaSMS("Businessity", "Dear {$name}, thank you for sending in your question. You will be reached via SMS with further details about the Live Session. Thank you.", $phone);

        $db->getConnection()->commit();

        echo json_encode("success");
}