<?php

include "config.php";

$message = "";

if (isset($_POST["login"])) {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT id, name, password
         FROM voters
         WHERE email = ?"
    );

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (
            password_verify(
                $password,
                $user["password"]
            )
        ) {

            session_regenerate_id(true);

            $_SESSION["voter_id"] = $user["id"];
            $_SESSION["voter_name"] = $user["name"];

            header("Location: dashboard.php");

            exit;

        } else {

            $message = "Invalid password.";

        }

    } else {

        $message = "User not found.";

    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Voter Login</title>

<link rel="stylesheet" href="assets/style.css">

</head>

<body>

<div class="form-container">

<h2>Voter Login</h2>

<p class="message">
<?php echo htmlspecialchars($message); ?>
</p>

<form method="POST">

<input
    type="email"
    name="email"
    placeholder="Enter Email"
    required
>

<input
    type="password"
    name="password"
    placeholder="Enter Password"
    required
>

<button type="submit" name="login">
    Login
</button>

</form>

<p>
Don't have an account?
<a href="register.php">Register</a>
</p>

</div>

</body>

</html>