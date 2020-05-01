<?php
session_start();


$dsn = "mysql:host=localhost; dbname=core_php_functions";
$db_user = "root";
$db_password = "root";



// For connecting to database currently i am using pdo approach...
try {
    $conn = new PDO($dsn, $db_user, $db_password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    if (isset($_REQUEST['Submit'])) {

        // checking for empty field

        if (($_REQUEST['name'] == "") || ($_REQUEST['email'] == "") || ($_REQUEST['phone'] == "") || ($_REQUEST['feedback'] == "")) {

            echo '<script>
              alert("Fill All The Fields")
              window.location.href="./index.php";
            </script>';
        } else {

            // get values from inputs

            $name = $_REQUEST['name'];
            $email = $_REQUEST['email'];
            $phone = $_REQUEST['phone'];
            $feedback = $_REQUEST['feedback'];


            // query for insert data in organization_request table if not exist

            $sql = "INSERT INTO submission (full_name, email, phone_number, feedback) VALUES ('$name', '$email', '$phone', '$feedback')";
            $conn->exec($sql);
            echo '<script>
                  alert("Thank you for filling out your information and your info is under review");
                  window.location.href="./index.php";
                </script>';
        }
    }
} catch (PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    <title>Pak Rashaan</title>
</head>

<body>
    <section>
        <form action="" method="POST">
            <input name="name" id="name" type="text" placeholder="Name">

            <input name="email" id="email" type="email" placeholder="Email">

            <input name="phone" id="phone" type="number" placeholder="Phone Number">

            <input name="feedback" id="feedback" type="text" placeholder="feedback">

            <button name="Submit" value="Submit">Submit</button>
        </form>

    </section>
    </section>

</body>

</html>

<?php //$conn = null; 
?>