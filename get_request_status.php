<?php

include "config.php";

header("Content-Type: application/json");

$regno = $_POST['regno'] ?? '';

if ($regno == '') {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "regno is required"
    ]);
    exit;
}

/* Get group_id from group_members */

$sql = "
SELECT group_id
FROM group_members
WHERE regno = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $regno);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    http_response_code(404);
    echo json_encode([
        "status" => false,
        "message" => "Student is not assigned to any group"
    ]);
    exit;
}

$member = $result->fetch_assoc();

$group_id = $member['group_id'];

/* Get all visit plans for the group */

$sql = "
SELECT
    visit_id,
    group_id,
    faculty_id,
    date_of_visit,
    objective,
    plan,
    status,
    remarks,
    allocated_hours
FROM visit_plans
WHERE group_id = ?
ORDER BY date_of_visit DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $group_id);
$stmt->execute();

$result = $stmt->get_result();

$visits = [];

while ($row = $result->fetch_assoc()) {
    $visits[] = $row;
}

echo json_encode([
    "status" => true,
    "message" => "Visit requests fetched successfully",
    "data" => $visits
]);

?>