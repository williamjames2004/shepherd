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

/* Get student and group */

$sql = "
SELECT
    s.regno,
    s.stud_name,
    s.contact_number,
    s.dept,
    s.year,
    s.section,
    gm.group_id,
    gm.is_leader,
    g.group_name,
    g.village_name,
    g.orientation_date,
    g.one_day_visit,
    g.camp_date,
    g.total_hours,
    g.completed_hours
FROM students s
JOIN group_members gm ON s.regno = gm.regno
JOIN shepherd_groups g ON gm.group_id = g.group_id
WHERE s.regno = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $regno);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    http_response_code(404);
    echo json_encode([
        "status" => false,
        "message" => "Student information not found"
    ]);
    exit;
}

$student = $result->fetch_assoc();

$group_id = $student['group_id'];

/* Get plans and paid bill details */

$sql = "
SELECT
    vp.visit_id,
    vp.date_of_visit,
    vp.objective,
    vp.plan,
    vp.status,
    vp.remarks,
    vp.allocated_hours,

    vb.bill_amount,
    vb.bill_status,
    vb.transaction_id,
    vb.transaction_date

FROM visit_plans vp

LEFT JOIN visit_bills vb
    ON vp.visit_id = vb.visit_id
    AND vb.bill_status = 'paid'

WHERE vp.group_id = ?

ORDER BY vp.date_of_visit
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $group_id);
$stmt->execute();

$result = $stmt->get_result();

$plans = [];

while ($row = $result->fetch_assoc()) {

    $plans[] = [
        "visit_id" => $row['visit_id'],
        "date_of_visit" => $row['date_of_visit'],
        "objective" => $row['objective'],
        "plan" => $row['plan'],
        "status" => $row['status'],
        "remarks" => $row['remarks'],
        "allocated_hours" => $row['allocated_hours'],

        "bill_amount" => $row['bill_amount'],
        "bill_status" => $row['bill_status'],
        "transaction_id" => $row['transaction_id'],
        "transaction_date" => $row['transaction_date']
    ];
}

echo json_encode([
    "status" => true,
    "message" => "Dashboard fetched successfully",

    "student" => [
        "regno" => $student['regno'],
        "stud_name" => $student['stud_name'],
        "contact_number" => $student['contact_number'],
        "dept" => $student['dept'],
        "year" => $student['year'],
        "section" => $student['section'],
        "is_leader" => $student['is_leader']
    ],

    "group" => [
        "group_id" => $student['group_id'],
        "group_name" => $student['group_name'],
        "village_name" => $student['village_name'],
        "orientation_date" => $student['orientation_date'],
        "one_day_visit" => $student['one_day_visit'],
        "camp_date" => $student['camp_date'],
        "total_hours" => $student['total_hours'],
        "completed_hours" => $student['completed_hours']
    ],

    "plans" => $plans
]);

?>