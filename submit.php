<?php
$entries = json_decode(file_get_contents("leaderboard.json"), true) ?? [];

$name = $_POST['name'];
$amount = floatval($_POST['amount']);
$message = $_POST['message'] ?? '';
$picture = '';

if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
    $fileName = uniqid() . '.' . $ext;
    move_uploaded_file($_FILES['picture']['tmp_name'], 'uploads/' . $fileName);
    $picture = 'uploads/' . $fileName;
}

$newEntry = [
    'name' => htmlspecialchars($name),
    'amount' => $amount,
    'message' => htmlspecialchars($message),
    'picture' => $picture
];
$entries[] = $newEntry;

usort($entries, fn($a, $b) => $b['amount'] <=> $a['amount']);

file_put_contents("leaderboard.json", json_encode($entries, JSON_PRETTY_PRINT));

header("Location: index.html");
exit;
?>
