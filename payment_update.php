<?php

include "config.php";

$faculty_id = $_POST['faculty_id'] ?? '';
$visit_id = $_POST['visit_id'] ?? '';
$transaction_id = $_POST['transaction_id'] ?? '';
$transaction_date = $_POST['transaction_date'] ?? '';

if (
    $faculty_id == '' ||
    $visit_id == '' ||
    $transaction_id == '' ||
    $transaction_date == ''
) {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "All fields are required"
    ]);
    exit;
}

$sql = "
SELECT faculty_id
FROM visit_plans
WHERE visit_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $visit_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    http_response_code(404);
    echo json_encode([
        "status" => false,
        "message" => "Visit not found"
    ]);
    exit;
}

$data = $result->fetch_assoc();

if ($data['faculty_id'] != $faculty_id) {
    http_response_code(403);
    echo json_encode([
        "status" => false,
        "message" => "You are not the coordinator for this visit"
    ]);
    exit;
}

$sql = "
UPDATE visit_bills
SET
    transaction_id = ?,
    transaction_date = ?,
    bill_status = 'paid'
WHERE visit_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssi",
    $transaction_id,
    $transaction_date,
    $visit_id
);

if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "visit_id" => $visit_id,
        "transaction_id" => $transaction_id,
        "transaction_date" => $transaction_date,
        "message" => "Payment details updated successfully"
    ]);

} else {

    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => "Failed to update payment"
    ]);
}
?>