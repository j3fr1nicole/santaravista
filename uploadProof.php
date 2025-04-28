<?php
// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'trip_booking');

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validasi dan sanitasi file
$allowedExtensions = ['jpg', 'jpeg', 'png'];
$maxFileSize = 6 * 1024 * 1024; // 2MB
$uploadDir = "uploads/";

// Pastikan folder upload ada
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// File Booking Proof
if (!isset($_FILES['bookingProof']) || $_FILES['bookingProof']['error'] != 0) {
    die("Error: Booking proof upload failed.");
}

$bookingProof = $_FILES['bookingProof'];
$bookingExtension = strtolower(pathinfo($bookingProof['name'], PATHINFO_EXTENSION));

if (!file_exists($bookingProof['tmp_name'])) {
    die("Error: Booking proof file is missing.");
}

$bookingMimeType = mime_content_type($bookingProof['tmp_name']);
$uniqueBookingName = uniqid() . "_booking." . $bookingExtension;
$bookingPath = $uploadDir . $uniqueBookingName;

// Validasi file booking proof
if (!in_array($bookingExtension, $allowedExtensions) || !in_array($bookingMimeType, ['image/jpeg', 'image/png'])) {
    die("Invalid booking proof format. Only JPG, JPEG, and PNG are allowed.");
}
if ($bookingProof['size'] > $maxFileSize) {
    die("Booking proof exceeds 2MB size limit.");
}

// File Transfer Proof
if (!isset($_FILES['transferProof']) || $_FILES['transferProof']['error'] != 0) {
    die("Error: Transfer proof upload failed.");
}

$transferProof = $_FILES['transferProof'];
$transferExtension = strtolower(pathinfo($transferProof['name'], PATHINFO_EXTENSION));

if (!file_exists($transferProof['tmp_name'])) {
    die("Error: Transfer proof file is missing.");
}

$transferMimeType = mime_content_type($transferProof['tmp_name']);
$uniqueTransferName = uniqid() . "_transfer." . $transferExtension;
$transferPath = $uploadDir . $uniqueTransferName;

// Validasi file transfer proof
if (!in_array($transferExtension, $allowedExtensions) || !in_array($transferMimeType, ['image/jpeg', 'image/png'])) {
    die("Invalid transfer proof format. Only JPG, JPEG, and PNG are allowed.");
}
if ($transferProof['size'] > $maxFileSize) {
    die("Transfer proof exceeds 2MB size limit.");
}

// Pindahkan file ke folder upload
if (!move_uploaded_file($bookingProof['tmp_name'], $bookingPath)) {
    die("Failed to upload booking proof.");
}

if (!move_uploaded_file($transferProof['tmp_name'], $transferPath)) {
    die("Failed to upload transfer proof.");
}

// Simpan ke database
$stmt = $conn->prepare("INSERT INTO proofs (booking_proof, transfer_proof) VALUES (?, ?)");
$stmt->bind_param("ss", $uniqueBookingName, $uniqueTransferName);

if ($stmt->execute()) {
    echo "Proof uploaded successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
