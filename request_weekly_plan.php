<?php

include "config.php";

$regno = $_POST['regno'] ?? '';
$dateofvisit = $_POST['dateofvisit'] ?? '';
$objective = $_POST['objective'] ?? '';
$plan = $_POST['plan'] ?? '';

if ($regno == '' || $dateofvisit == '' || $objective == '' || $plan == '') {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "All fields are required"
    ]);
    exit;
}

$sql = "
SELECT gm.group_id, gm.is_leader, g.faculty_id
FROM group_members gm
JOIN shepherd_groups g ON gm.group_id = g.group_id
WHERE gm.regno = ?
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

$data = $result->fetch_assoc();

if ($data['is_leader'] == 0) {
    http_response_code(403);
    echo json_encode([
        "status" => false,
        "message" => "Only group leader can submit weekly plan"
    ]);
    exit;
}

$group_id = $data['group_id'];
$faculty_id = $data['faculty_id'];

$sql = "
INSERT INTO visit_plans
(group_id, faculty_id, date_of_visit, objective, plan, status, created_by)
VALUES (?, ?, ?, ?, ?, 'pending', ?)
";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "isssss",
    $group_id,
    $faculty_id,
    $dateofvisit,
    $objective,
    $plan,
    $regno
);

if ($stmt->execute()) {

    http_response_code(201);

    echo json_encode([
        "status" => true,
        "message" => "Weekly plan submitted successfully",
        "visit_id" => $conn->insert_id
    ]);

} else {

    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => "Failed to submit weekly plan"
    ]);
}
?>