<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verify Visit Proof</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 520px;
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .title {
            font-size: 26px;
            font-weight: bold;
            color: #222;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 7px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d6d9df;
            border-radius: 10px;
            font-size: 15px;
            outline: none;
            transition: 0.2s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        .remarks-group {
            display: none;
        }

        .submit-btn {
            width: 100%;
            border: none;
            background: #4f46e5;
            color: white;
            padding: 14px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .submit-btn:hover {
            background: #4338ca;
        }

        .submit-btn:disabled {
            background: #999;
            cursor: not-allowed;
        }

        .message {
            display: none;
            margin-top: 18px;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 14px;
        }

        .success {
            background: #ecfdf3;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="title">
        Verify Visit Proof
    </div>

    <div class="subtitle">
        Verify the submitted visit proof and bill details.
    </div>

    <form id="verifyForm">

        <div class="form-group">
            <label for="faculty_id">
                Faculty ID
            </label>

            <input
                type="text"
                id="faculty_id"
                name="faculty_id"
                placeholder="Enter faculty ID"
                required
            >
        </div>

        <div class="form-group">
            <label for="visit_id">
                Visit ID
            </label>

            <input
                type="number"
                id="visit_id"
                name="visit_id"
                placeholder="Enter visit ID"
                required
            >
        </div>

        <div class="form-group">
            <label for="bill_status">
                Bill Status
            </label>

            <select
                id="bill_status"
                name="bill_status"
                required
            >
                <option value="">
                    Select status
                </option>

                <option value="approved">
                    Approved
                </option>

                <option value="rejected">
                    Rejected
                </option>
            </select>
        </div>

        <div
            class="form-group remarks-group"
            id="remarksGroup"
        >
            <label for="remarks">
                Remarks
            </label>

            <textarea
                id="remarks"
                name="remarks"
                placeholder="Enter rejection remarks"
            ></textarea>
        </div>

        <div class="form-group">
            <label for="allocated_hours">
                Allocated Hours
            </label>

            <input
                type="number"
                id="allocated_hours"
                name="allocated_hours"
                placeholder="Enter allocated hours"
                min="0"
                step="0.01"
                required
            >
        </div>

        <button
            type="submit"
            class="submit-btn"
            id="submitButton"
        >
            Verify Proof
        </button>

    </form>

    <div
        id="message"
        class="message"
    ></div>

</div>

<script>

const billStatus = document.getElementById('bill_status');
const remarksGroup = document.getElementById('remarksGroup');
const remarks = document.getElementById('remarks');
const form = document.getElementById('verifyForm');
const submitButton = document.getElementById('submitButton');
const message = document.getElementById('message');

billStatus.addEventListener('change', function () {

    if (this.value === 'rejected') {

        remarksGroup.style.display = 'block';

    } else {

        remarksGroup.style.display = 'none';
        remarks.value = '';

    }

});

form.addEventListener('submit', async function (event) {

    event.preventDefault();

    message.style.display = 'none';

    submitButton.disabled = true;
    submitButton.textContent = 'Verifying...';

    const formData = new FormData();

    formData.append(
        'faculty_id',
        document.getElementById('faculty_id').value.trim()
    );

    formData.append(
        'visit_id',
        document.getElementById('visit_id').value.trim()
    );

    formData.append(
        'bill_status',
        billStatus.value
    );

    formData.append(
        'remarks',
        remarks.value.trim()
    );

    formData.append(
        'verified_by',
        document.getElementById('faculty_id').value.trim()
    );

    formData.append(
        'allocated_hours',
        document.getElementById('allocated_hours').value.trim()
    );

    try {

        const response = await fetch(
            'http://192.168.1.33:8080/shepherd/verify_proof.php',
            {
                method: 'POST',
                body: formData
            }
        );

        const data = await response.json();

        message.style.display = 'block';

        if (data.status === true) {

            message.className = 'message success';

            message.textContent =
                data.message +
                ' Completed Hours: ' +
                data.completed_hours;

            form.reset();

            remarksGroup.style.display = 'none';

        } else {

            message.className = 'message error';

            message.textContent =
                data.message || 'Verification failed';

        }

    } catch (error) {

        message.style.display = 'block';

        message.className = 'message error';

        message.textContent =
            'Unable to connect to the verification API.';

    }

    submitButton.disabled = false;
    submitButton.textContent = 'Verify Proof';

});

</script>

</body>
</html>