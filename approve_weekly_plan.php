<?php

include "config.php";

$faculty_id = $_POST['faculty_id'] ?? '';
$visit_id = $_POST['visit_id'] ?? '';
$status = $_POST['status'] ?? '';
$remarks = $_POST['remarks'] ?? null;

if ($faculty_id == '' || $visit_id == '' || $status == '') {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "faculty_id, visit_id and status are required"
    ]);
    exit;
}

if ($status != 'approved' && $status != 'rejected') {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "Invalid status"
    ]);
    exit;
}

$sql = "
SELECT visit_id, faculty_id, status
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
        "message" => "Visit plan not found"
    ]);
    exit;
}

$data = $result->fetch_assoc();

if ($data['faculty_id'] != $faculty_id) {
    http_response_code(403);
    echo json_encode([
        "status" => false,
        "message" => "You are not the coordinator for this plan"
    ]);
    exit;
}

if ($data['status'] != 'pending') {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "This plan has already been processed"
    ]);
    exit;
}

$sql = "
UPDATE visit_plans
SET status = ?, remarks = ?, approved_by = ?, approved_at = NOW()
WHERE visit_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssi",
    $status,
    $remarks,
    $faculty_id,
    $visit_id
);

if ($stmt->execute()) {

    echo json_encode([
        "status" => true,
        "message" => "Plan $status successfully"
    ]);

} else {

    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => "Failed to update plan"
    ]);
}
?>