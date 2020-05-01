<?php
session_start();


$dsn = "mysql:host=localhost; dbname=core_php_function";
$db_user = "root";
$db_password = "root";



// For connecting to database currently i am using pdo approach...
try {
  $conn = new PDO($dsn, $db_user, $db_password);

  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


  if (isset($_REQUEST['Submit'])) {

    // checking for empty field

    if (($_REQUEST['org_req_name'] == "") || ($_REQUEST['org_req_number'] == "") || ($_REQUEST['org_req_email'] == "") || ($_REQUEST['org_req_password'] == "") || ($_REQUEST['confirm_password'] == "")) {

      echo '<script>
              alert("Fill All The Fields")
              window.location.href="./index.php";
            </script>';
    } else {

      // checking for password and confirm password are same

      if ($_REQUEST['org_req_password'] == $_REQUEST['confirm_password']) {

        // get values from inputs

        $password = $_REQUEST['org_req_password'];
        $org_req_name = $_REQUEST['org_req_name'];
        $org_req_number = $_REQUEST['org_req_number'];
        $org_req_email = $_REQUEST['org_req_email'];
        $org_req_password = $password;

        // query for check email exist or not in organization_request table

        $email_exist_check = $conn->query("SELECT * from organization_request where org_req_email = '$org_req_email'")->rowCount();

         // query for check email exist or not in organizations table

        $email_exist_check2 = $conn->query("SELECT * from organizations where org_email = '$org_req_email'")->rowCount();

        // condition for check exist or not

        if (!($email_exist_check > 0) && !($email_exist_check2 > 0)) {

          // query for insert data in organization_request table if not exist

          $sql = "INSERT INTO organization_request (org_req_name, org_req_number, org_req_email, org_req_password) VALUES ('$org_req_name', '$org_req_number', '$org_req_email', '$org_req_password')";
          $conn->exec($sql);
          echo '<script>
                  alert("Thank you for filling out your information and your info is under review");
                  window.location.href="./index.php";
                </script>';
        } else {

          // this alert run when data already exist

          echo '<script>
                  alert("Email already exist")
                  window.location.href="./index.php";
                </script>';
        }
      } else {

        // this alert run when password and confirm does not match

        echo '<script>
                alert("Password does not match");
                window.location.href="./index.php";
              </script>';
      }
    }
  }
} catch (PDOException $e) {
  die("ERROR: Could not connect. " . $e->getMessage());
}

?>

<!-- run when login as a organization; -->
<?php

if (isset($_POST["login"])) {

    // checking for empty field

  if (empty($_POST["log_email"]) || empty($_POST["log_password"])) {

    echo '<script>
                alert("fill all the field");
                window.location.href="./index.php";
              </script>';
  } else {

    // checking organization account exist or not

    $log_org_email = $conn->query('SELECT * from organizations WHERE org_email = "' . $_POST['log_email'] . '"')->rowCount();

    // if exist run this condition

    if ($log_org_email > 0) {

      // login email or password match or not query
      
      $query = "SELECT * FROM organizations WHERE org_email = :log_email AND org_password = :log_password";
      $statement = $conn->prepare($query);
      $statement->execute(
        array(
          'log_email'     =>     $_POST["log_email"],
          'log_password'     =>     $_POST["log_password"]
        )
      );
      $count = $statement->rowCount();

      // if match run this query

      if ($count > 0) {

        // start a session

        $_SESSION["org_email"] = $_POST["log_email"];

        header("location:add-volunteer.php");
      } else {

        // show error when password not match

        echo '<script>
                alert("Password does not match");
                window.location.href="./index.php";
              </script>';
      }
    } else {

      // show error when email not exist

      echo '<script>
                alert("Email does not exist");
                window.location.href="./index.php";
              </script>';
    }
  }
}


?>

<!-- run when login as a volunteer; -->

<?php

if (isset($_POST["login_vol"])) {

  // checking empty field

  if (empty($_POST["log_email"]) || empty($_POST["log_password"])) {

    echo '<script>
                alert("fill all the field");
                window.location.href="./index.php";
              </script>';
  } else {

    // checking volunteer account exist or not

    $log_vol_email = $conn->query('SELECT * from volunteer WHERE vol_email = "' . $_POST['log_email'] . '"')->rowCount();

    // if yes

    if ($log_vol_email > 0) {

      // checking password or email match

      $query = "SELECT * FROM volunteer WHERE vol_email = :log_email AND vol_password = :log_password";
      $statement = $conn->prepare($query);
      $statement->execute(
        array(
          'log_email'     =>     $_POST["log_email"],
          'log_password'     =>     $_POST["log_password"]
        )
      );
      $count = $statement->rowCount();

      // if yes

      if ($count > 0) {

        // session start

        $_SESSION["vol_email"] = $_POST["log_email"];

        header("location:add-info.php");
      } else {

        // show error when password not match

        echo '<script>
                alert("Password does not match");
                window.location.href="./index.php";
              </script>';
      }
    } else {

      // show error when email not exist

      echo '<script>
                alert("Email does not exist");
                window.location.href="./index.php";
              </script>';
    }
  }
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
  <header>
    <!-- Header above section starts-->
    <p style="background-color:#081b1a; color:white; text-align:center; padding:5px;"><b>Contact Info: team@pakrashan.com</b></p>
    <div class="logo">
      <img src="images/logo.png" alt="logo" />
    </div>

    <!-- Header above section ends -->
    <!-- Navigation starts -->
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="#">About Us</a></li>

      </ul>
    </nav>
    <!-- Navigation Ends -->
  </header>
  <!-- Banner Section Starts -->
  <section>
    <span>LETS JOIN HANDS TOGETHER TO FIGHT AGAINST COVID-19 TO SAVE OUR BELOVED COUNTRY PAKISTAN</span>
    <img src="images/banner-1.jpg" alt="banner image" />
  </section>
  <!-- Banner Section Ends -->
  <!-- Forms Section Starts -->
  <h1 id="form_heading">Login | Sign Up</h1>

  <section id="forms">

    <section class="center_line">
      <form action="" method="POST">
        <label for="log_email">Email</label>
        <input id="log_email" name="log_email" type="email" placeholder="Enter Your Email">

        <label for="log_password">Password</label>
        <input id="log_password" name="log_password" type="password" placeholder="Enter Password">


        <button name="login" value="login">Login With Organization</button>
        <button name="login_vol" value="login_vol">Login With Volunteer</button>

      </form>
    </section>
    <section>
      <form action="" method="POST">
        <label for="org_req_name">Organization Name</label>
        <input name="org_req_name" id="org_req_name" type="text" placeholder="Enter Name">

        <label for="org_req_number">Registration No</label>
        <input name="org_req_number" id="org_req_number" type="number" placeholder="Enter Registeration No">

        <label for="org_req_email">Organization Email</label>
        <input name="org_req_email" id="org_req_email" type="email" placeholder="Enter Organization Email">

        <label for="org_req_password">Create Password</label>
        <input name="org_req_password" id="org_req_password" type="password" placeholder="Create Password">

        <label for="confirm_password">Confirm Password</label>
        <input name="confirm_password" id="confirm_password" type="password" placeholder="Confirm Password">

        <button name="Submit" value="Submit">Sign Up</a></button>




      </form>

    </section>
  </section>
  <!--- Verification Section Ends -->
  <!-- Message Section Starts -->
  <section class="message-section-spacing">
    <h1>Message By Parents of Nation</h1>
    <div class="message-section">
      <div>
        <img src="images/quaid.jpeg" alt="quaid" />
        <p class="message-text">
          "Come forward as servants of Islam,organize the people
          economically,socially,educationally and politically and I am sure that
          you will be a power that will be accepted by everybody."
          <pre><b>
              -  Mohammad Ali Jinnah</b></pre>
        </p>
      </div>
      <div>
        <img src="images/fatima.jpg" alt="fatima" />
        <p class="message-text">
          "There is a magic power in your own hands. Take your vital decisions-they may be grave
          and momentous and far-reaching in their consequences. Think a hundred times before you take any decision,
          but once a decision is taken, stand by it as one man."
          <pre><b>
            -  Fatima Jinnah</b></pre>
        </p>
      </div>
    </div>
  </section>
  <!-- Message Section Ends -->

</body>

</html>

<?php //$conn = null; 
?>