<?php
// Koneksi ke database

use Dom\CharacterData;

$conn = new mysqli('localhost', 'root', '', 'trip_booking');

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data dari form dengan validasi dan sanitasi
$name = htmlspecialchars(strip_tags($_POST['name']));
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
$destination = htmlspecialchars(strip_tags($_POST['destination']));
$people = filter_var($_POST['people'], FILTER_VALIDATE_INT);
$car = filter_var($_POST['car'], FILTER_VALIDATE_INT);
$arrival_date = $_POST['tripStartDate'];
$departure_date = $_POST['tripEndDate'];
$message = htmlspecialchars(strip_tags($_POST['message']));

// Validasi input
if (!$email) {
    die("Invalid email format!");
}
if (!$people || $people <= 0) {
    die("Number of people must be a positive integer!");
}
if (!$car || $car <= 0) {
    die("Car seat must be a positive integer!");
}
if (strtotime($arrival_date) === false || strtotime($departure_date) === false) {
    die("Invalid date format!");
}
if (strtotime($arrival_date) > strtotime($departure_date)) {
    die("Arrival date cannot be later than departure date!");
}

// Gunakan prepared statement untuk mencegah SQL injection
$stmt = $conn->prepare("INSERT INTO trip_bookings (name, email, destination, people, arrival_date, departure_date, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssisss", $name, $email, $destination, $people, $car, $arrival_date, $departure_date, $message);

// Eksekusi statement
if ($stmt->execute()) {
    // Redirect ke WhatsApp dengan pesan sukses
    echo "<script>
            alert('Booking submitted successfully!');
            window.location.href = 'https://wa.me/6281111170403?text=" . urlencode("Hello, my name is $name. I would like to book a trip to $destination for $people people and $car seats car. Arrival: $arrival_date, Departure: $departure_date. Message: $message") . "';
          </script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
