<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Staff Plan Approval</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
        }

        .container {
            width: 100%;
            max-width: 500px;
            margin: 60px auto;
            padding: 30px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        h2 {
            margin-top: 0;
            margin-bottom: 25px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d5d8df;
            border-radius: 10px;
            font-size: 15px;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #4f46e5;
        }

        button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 10px;
            background: #4f46e5;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        button:hover {
            background: #4338ca;
        }

        button:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        .message {
            margin-top: 20px;
            padding: 12px;
            border-radius: 10px;
            display: none;
            text-align: center;
        }

        .success {
            display: block;
            background: #dcfce7;
            color: #166534;
        }

        .error {
            display: block;
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Staff Plan Approval</h2>

    <form id="approvalForm">

        <div class="form-group">
            <label for="faculty_id">Faculty ID</label>

            <input
                type="text"
                id="faculty_id"
                name="faculty_id"
                placeholder="Enter faculty ID"
                required
            >
        </div>

        <div class="form-group">
            <label for="visit_id">Visit ID</label>

            <input
                type="number"
                id="visit_id"
                name="visit_id"
                placeholder="Enter visit ID"
                required
            >
        </div>

        <div class="form-group">
            <label for="status">Status</label>

            <select id="status" name="status" required>
                <option value="">Select status</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>

        <div class="form-group">
            <label for="remarks">Remarks</label>

            <textarea
                id="remarks"
                name="remarks"
                placeholder="Enter remarks (optional)"
            ></textarea>
        </div>

        <button type="submit" id="submitButton">
            Submit
        </button>

    </form>

    <div id="message" class="message"></div>

</div>

<script>

document.getElementById('approvalForm').addEventListener('submit', async function(e) {

    e.preventDefault();

    const submitButton = document.getElementById('submitButton');
    const message = document.getElementById('message');

    const facultyId = document.getElementById('faculty_id').value.trim();
    const visitId = document.getElementById('visit_id').value.trim();
    const status = document.getElementById('status').value;
    const remarks = document.getElementById('remarks').value.trim();

    if (!facultyId || !visitId || !status) {
        message.className = 'message error';
        message.textContent = 'Please fill all required fields.';
        return;
    }

    submitButton.disabled = true;
    submitButton.textContent = 'Submitting...';

    const formData = new FormData();

    formData.append('faculty_id', facultyId);
    formData.append('visit_id', visitId);
    formData.append('status', status);
    formData.append('remarks', remarks);

    try {

        const response = await fetch(
            'http://192.168.1.33:8080/shepherd/approve_weekly_plan.php',
            {
                method: 'POST',
                body: formData
            }
        );

        const data = await response.json();

        if (data.status === true) {

            message.className = 'message success';
            message.textContent = data.message;

            document.getElementById('approvalForm').reset();

        } else {

            message.className = 'message error';
            message.textContent = data.message || 'Something went wrong.';
        }

    } catch (error) {

        message.className = 'message error';
        message.textContent = 'Unable to connect to server.';

    }

    submitButton.disabled = false;
    submitButton.textContent = 'Submit';

});

</script>

</body>
</html>