<?php

include "config.php";

header("Content-Type: application/json");

$faculty_id = $_POST['faculty_id'] ?? '';
$visit_id = $_POST['visit_id'] ?? '';
$bill_status = $_POST['bill_status'] ?? '';
$remarks = $_POST['remarks'] ?? null;
$verified_by = $_POST['verified_by'] ?? '';
$allocated_hours = $_POST['allocated_hours'] ?? '';

if (
    $faculty_id == '' ||
    $visit_id == '' ||
    $bill_status == '' ||
    $verified_by == '' ||
    $allocated_hours == ''
) {
    http_response_code(400);

    echo json_encode([
        "status" => false,
        "message" => "Required fields are missing"
    ]);

    exit;
}

/* Check bill status */

if (
    $bill_status != 'approved' &&
    $bill_status != 'rejected'
) {
    http_response_code(400);

    echo json_encode([
        "status" => false,
        "message" => "Invalid bill status"
    ]);

    exit;
}

/* Check verified_by */

if ($verified_by != $faculty_id) {
    http_response_code(400);

    echo json_encode([
        "status" => false,
        "message" => "Invalid verified_by"
    ]);

    exit;
}

/* Check allocated hours */

if (!is_numeric($allocated_hours)) {
    http_response_code(400);

    echo json_encode([
        "status" => false,
        "message" => "Invalid allocated hours"
    ]);

    exit;
}

$allocated_hours = (float)$allocated_hours;

/* Check visit */

$sql = "
SELECT
    vp.group_id,
    vp.faculty_id,
    g.total_hours
FROM visit_plans vp
JOIN shepherd_groups g
    ON vp.group_id = g.group_id
WHERE vp.visit_id = ?
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

$visit = $result->fetch_assoc();

/* Check coordinator */

if ($visit['faculty_id'] != $faculty_id) {
    http_response_code(403);

    echo json_encode([
        "status" => false,
        "message" => "You are not the coordinator for this visit"
    ]);

    exit;
}

/* Update bill */

$sql = "
UPDATE visit_bills
SET
    bill_status = ?,
    remarks = ?,
    verified_by = ?,
    verified_at = NOW()
WHERE visit_id = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sssi",
    $bill_status,
    $remarks,
    $verified_by,
    $visit_id
);

if (!$stmt->execute()) {
    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => "Failed to update bill"
    ]);

    exit;
}

/* Update visit */

$sql = "
UPDATE visit_plans
SET
    allocated_hours = ?,
    verified_by = ?,
    verified_at = NOW(),
    status = 'verified'
WHERE visit_id = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "dsi",
    $allocated_hours,
    $verified_by,
    $visit_id
);

if (!$stmt->execute()) {
    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => "Failed to update visit"
    ]);

    exit;
}

/* Calculate completed hours */

$sql = "
SELECT
    SUM(allocated_hours) AS completed_hours
FROM visit_plans
WHERE group_id = ?
AND status = 'verified'
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $visit['group_id']
);

$stmt->execute();

$result = $stmt->get_result();

$data = $result->fetch_assoc();

$completed_hours = $data['completed_hours'] ?? 0;

if ($completed_hours == null) {
    $completed_hours = 0;
}

/* Update group completed hours */

$sql = "
UPDATE shepherd_groups
SET
    completed_hours = ?
WHERE group_id = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "di",
    $completed_hours,
    $visit['group_id']
);

if (!$stmt->execute()) {
    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => "Failed to update group hours"
    ]);

    exit;
}

/* Success */

echo json_encode([
    "status" => true,
    "message" => "Proof verified successfully",
    "completed_hours" => $completed_hours
]);

?>