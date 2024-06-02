<?php
session_start();
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
<script>

    var amount = <?php echo $_SESSION['packageprice']*100 ?>;



    console.log(amount); // Log the amount for debugging purposes
</script>
<script>


    var options = {
        "key": "rzp_test_7INLkTMERE7fqi",
        "amount": amount,
        "currency": "INR",
        "name": "AutoCare Hub",
        "image": "./assets/website_logo.png",
        "description": "",
        "handler": function (response) {
            var transaction_id = response.razorpay_payment_id;
            console.log(transaction_id);
            console.log(response.razorpay_payment_method);
            $.ajax({
                type: 'post',
                url: 'booking-confirmation.php',
                data: { 'transaction_id': response.razorpay_payment_id },

                success: function (result) {
                    window.location.href = "index.php";
                }
            });
        }
    };

    $(document).ready(function () {
        var rzp1 = new Razorpay(options);
        rzp1.open();
    });
</script>

<?php

error_reporting(0);
include('includes/config.php');

if (isset($_POST['transaction_id'])) {

    $pid = $_SESSION['pkgid'];
    $useremail = $_SESSION['login'];
    $fromdate = $_SESSION['fromdate'];
    $todate = $_SESSION['todate'];
    $comment = $_SESSION['comment'];
    $status = 0;
    $transaction_id = $_POST['transaction_id'];
    // $_SESSION['method'] = $_POST['method'];
    $date = date("Y-m-d");

    try {
        // Inserting Booking details
        $sql = "INSERT INTO tblbooking(PackageId, UserEmail, FromDate, ToDate, Comment, status) VALUES (:pid, :useremail, :fromdate, :todate, :comment, :status)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':pid', $pid, PDO::PARAM_STR);
        $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
        $query->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
        $query->bindParam(':todate', $todate, PDO::PARAM_STR);
        $query->bindParam(':comment', $comment, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);

        if ($query->execute()) {
            // Retrieve the last inserted ID
            $lastInsertId = $dbh->lastInsertId();
            // Store the last inserted ID in the session
            $_SESSION['bookingid'] = $lastInsertId;
        } else {
            $error = "Something went wrong. Please try again";
        }

        // Fetch payment details from Razorpay API using the transaction ID
        $razorpay_key_id = 'rzp_test_7INLkTMERE7fqi';
        $razorpay_key_secret = 'Ssa3HWzqg5BqtkpHKC9iJDAl';
        $ch = curl_init("https://api.razorpay.com/v1/payments/$transaction_id");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$razorpay_key_id:$razorpay_key_secret");
        $result = curl_exec($ch);
        curl_close($ch);

        // Parse the JSON response
        $payment_details = json_decode($result, true);

        // Extract method and amount details
        $method = $payment_details['method'];
        $amount = $payment_details['amount'] / 100; // assuming amount is in paise, convert to rupees

        // Inserting Payment details
        $sql = "INSERT INTO payment (BookingID, userEmail, amount, transactionID, packageID, method, date) VALUES (:bookingid, :useremail, :amount, :transaction_id, :packageid, :method, :date)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':bookingid', $lastInsertId, PDO::PARAM_STR);
        $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
        $query->bindParam(':amount', $amount, PDO::PARAM_STR);
        $query->bindParam(':transaction_id', $transaction_id, PDO::PARAM_STR);
        $query->bindParam(':packageid', $pid, PDO::PARAM_STR);
        $query->bindParam(':method', $method, PDO::PARAM_STR);
        $query->bindParam(':date', $date, PDO::PARAM_STR);

        if ($query->execute()) {
            echo '<script>"Booking and payment details have been successfully saved."</script>' ;
        } else {
            echo '<script>"Something went wrong. Please try again"</script>';
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
        echo $error;
    }
}
?>
