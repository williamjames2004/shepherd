<?php
include "config.php";

header("Content-Type: application/json");

$regno = $_POST['regno'] ?? '';
$visit_id = $_POST['visit_id'] ?? '';
$proof_type = $_POST['proof_type'] ?? '';
$bill_amount = $_POST['bill_amount'] ?? '';
$notes = $_POST['notes'] ?? null;

/* Check required fields */

if (
    $regno == '' ||
    $visit_id == '' ||
    $proof_type == '' ||
    $bill_amount == ''
) {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "Required fields are missing"
    ]);
    exit;
}

/* Check uploaded files */

if (
    !isset($_FILES['image_file']) ||
    !isset($_FILES['bill_file'])
) {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "Visit proof and bill proof files are required"
    ]);
    exit;
}

$imageFile = $_FILES['image_file'];
$billFile = $_FILES['bill_file'];

/* Check upload errors */

if (
    $imageFile['error'] != UPLOAD_ERR_OK ||
    $billFile['error'] != UPLOAD_ERR_OK
) {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "File upload failed"
    ]);
    exit;
}

/* Allow only PDF */

$imageExtension = strtolower(
    pathinfo($imageFile['name'], PATHINFO_EXTENSION)
);

$billExtension = strtolower(
    pathinfo($billFile['name'], PATHINFO_EXTENSION)
);

if (
    $imageExtension != 'pdf' ||
    $billExtension != 'pdf'
) {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "Only PDF files are allowed"
    ]);
    exit;
}

/* Get student group */

$sql = "
SELECT group_id, is_leader
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

if ($member['is_leader'] == 0) {
    http_response_code(403);
    echo json_encode([
        "status" => false,
        "message" => "Only group leader can submit proof"
    ]);
    exit;
}

$group_id = $member['group_id'];

/* Check visit */

$sql = "
SELECT visit_id, status
FROM visit_plans
WHERE visit_id = ?
AND group_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $visit_id, $group_id);
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

$visit = $result->fetch_assoc();

if ($visit['status'] != 'approved') {
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "message" => "Visit plan is not approved"
    ]);
    exit;
}

/* Create upload folder */

$uploadDir = "uploads/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

/* Generate unique file names */

$imageFileName = "visit_" . $visit_id . "_" . time() . ".pdf";
$billFileName = "bill_" . $visit_id . "_" . time() . ".pdf";

$imagePath = $uploadDir . $imageFileName;
$billPath = $uploadDir . $billFileName;

/* Move visit proof */

if (!move_uploaded_file(
    $imageFile['tmp_name'],
    $imagePath
)) {
    http_response_code(500);
    echo json_encode([
        "status" => false,
        "message" => "Failed to save visit proof"
    ]);
    exit;
}

/* Move bill proof */

if (!move_uploaded_file(
    $billFile['tmp_name'],
    $billPath
)) {
    unlink($imagePath);

    http_response_code(500);
    echo json_encode([
        "status" => false,
        "message" => "Failed to save bill proof"
    ]);
    exit;
}

/* Insert proof */

$sql = "
INSERT INTO visit_proofs
(visit_id, proof_type, image_path, uploaded_by)
VALUES (?, ?, ?, ?)
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "isss",
    $visit_id,
    $proof_type,
    $imagePath,
    $regno
);

if (!$stmt->execute()) {
    unlink($imagePath);
    unlink($billPath);

    http_response_code(500);
    echo json_encode([
        "status" => false,
        "message" => "Failed to insert proof"
    ]);
    exit;
}

/* Insert bill */

$sql = "
INSERT INTO visit_bills
(visit_id, bill_proof_path, bill_amount, bill_status)
VALUES (?, ?, ?, 'pending')
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "isd",
    $visit_id,
    $billPath,
    $bill_amount
);

if (!$stmt->execute()) {
    unlink($imagePath);
    unlink($billPath);

    http_response_code(500);
    echo json_encode([
        "status" => false,
        "message" => "Failed to insert bill"
    ]);
    exit;
}

/* Insert submission */

$sql = "
INSERT INTO visit_submissions
(visit_id, submitted_by, notes)
VALUES (?, ?, ?)
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "iss",
    $visit_id,
    $regno,
    $notes
);

if (!$stmt->execute()) {
    unlink($imagePath);
    unlink($billPath);

    http_response_code(500);
    echo json_encode([
        "status" => false,
        "message" => "Failed to insert submission"
    ]);
    exit;
}

/* Success */

echo json_encode([
    "status" => true,
    "message" => "Proof submitted successfully"
]);

?>