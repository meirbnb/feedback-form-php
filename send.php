<?php

$review = $_FILES['file'];

if (isset($review)) {

    $filename = $review['name'];
    $tmp_path = $review['tmp_name'];

    if ($review['size'] / 1024 <= 1024 && move_uploaded_file($tmp_path, "uploads/" . $filename)) {

        $conn  = new mysqli("localhost", "root", "", "feedback");

        // Check connection
        if ($conn->connect_errno) {
            echo "Failed to connect to MySQL: " . $conn->connect_error;
            exit();
        }

        $name = validate($_POST['name']);
        $email = validate($_POST['email']);
        $text = validate($_POST['text']);

        $sql = "INSERT INTO feedbacks (name, email, text, url, approved) VALUES ('$name', '$email', '$text', 'uploads/$filename', false)";

        echo json_encode(array("status" => $conn->query($sql)));
    } else {
        echo json_encode(array(
            "status" => 0,
            "message" => "Failed to upload image"
        ));
    }
}

function validate($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
