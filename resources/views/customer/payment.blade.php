
<!DOCTYPE html>
<html>
<head>
    <title>AzamPay Payment</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h2>Make Payment</h2>
    <form id="paymentForm">
        <input type="text" name="phone_number" placeholder="Phone number (e.g. 2557xxxxxxx)" required><br><br>
        <input type="number" name="amount" placeholder="Amount" required><br><br>
        <button type="submit">Pay Now</button>
    </form>

    <p id="msg"></p>

    <script>
        document.getElementById('paymentForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(e.target);
            const csrf = document.querySelector('meta[name="csrf-token"]').content;

            const response = await fetch('/azampay/pay', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf },
                body: formData
            });

            const data = await response.json();
            document.getElementById('msg').innerText = JSON.stringify(data);
        });
    </script>
</body>
</html>
