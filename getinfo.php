<?php
include "config.php";

$regno = $_POST['regno'] ?? '';

if ($regno == '') {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "regno is required"
    ]);
    exit;
}

$sql = "
SELECT 
    gm.group_id,
    g.group_name,
    g.village_name,
    g.orientation_date,
    g.one_day_visit,
    g.faculty_id,
    c.coordinator_name,
    c.contact_number,
    c.email
FROM group_members gm
JOIN shepherd_groups g ON gm.group_id = g.group_id
JOIN coordinators c ON g.faculty_id = c.faculty_id
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
        "message" => "Student group information not found"
    ]);
    exit;
}

$data = $result->fetch_assoc();

http_response_code(200);

echo json_encode([
    "status" => true,
    "message" => "Information fetched successfully",
    "data" => [
        "regno" => $regno,
        "group_id" => $data['group_id'],
        "group_name" => $data['group_name'],
        "village_name" => $data['village_name'],
        "orientation_date" => $data['orientation_date'],
        "one_day_visit" => $data['one_day_visit'],
        "faculty_id" => $data['faculty_id'],
        "coordinator_name" => $data['coordinator_name'],
        "coordinator_contact" => $data['contact_number'],
        "coordinator_email" => $data['email']
    ]
]);
?>