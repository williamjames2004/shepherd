<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Payment</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6fb;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 500px;
            background: #ffffff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        h2 {
            color: #4f46e5;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #6b7280;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
            color: #374151;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 15px;
            outline: none;
        }

        input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
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
            transition: 0.2s;
        }

        button:hover {
            background: #4338ca;
        }

        button:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        .message {
            margin-top: 18px;
            padding: 12px;
            border-radius: 10px;
            display: none;
            font-size: 14px;
        }

        .success {
            display: block;
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .error {
            display: block;
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Update Payment</h2>

    <p class="subtitle">
        Enter the payment details to update the visit bill.
    </p>

    <form id="paymentForm">

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
            <label for="transaction_id">Transaction ID</label>
            <input
                type="text"
                id="transaction_id"
                name="transaction_id"
                placeholder="Enter transaction ID"
                required
            >
        </div>

        <div class="form-group">
            <label for="transaction_date">Transaction Date</label>
            <input
                type="date"
                id="transaction_date"
                name="transaction_date"
                required
            >
        </div>

        <button type="submit" id="submitBtn">
            Update Payment
        </button>

    </form>

    <div id="message" class="message"></div>

</div>

<script>

const form = document.getElementById("paymentForm");
const message = document.getElementById("message");
const submitBtn = document.getElementById("submitBtn");

form.addEventListener("submit", async function (e) {

    e.preventDefault();

    message.className = "message";
    message.style.display = "none";

    submitBtn.disabled = true;
    submitBtn.textContent = "Updating...";

    const formData = new FormData(form);

    try {

        const response = await fetch(
            "http://192.168.1.33:8080/shepherd/payment_update.php",
            {
                method: "POST",
                body: formData
            }
        );

        const data = await response.json();

        if (data.status) {

            message.className = "message success";
            message.textContent = data.message;
            message.style.display = "block";

            form.reset();

        } else {

            message.className = "message error";
            message.textContent = data.message || "Payment update failed";
            message.style.display = "block";
        }

    } catch (error) {

        message.className = "message error";
        message.textContent = "Unable to connect to the API.";
        message.style.display = "block";

        console.error(error);

    } finally {

        submitBtn.disabled = false;
        submitBtn.textContent = "Update Payment";
    }

});

</script>

</body>
</html>