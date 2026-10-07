<?php

include "config.php";

$message = "";

if (isset($_POST["register"])) {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if ($name == "" || $email == "" || $password == "") {

        $message = "Please fill all fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email.";

    } elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters.";

    } else {

        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $stmt = $conn->prepare(
            "INSERT INTO voters
            (name, email, password)
            VALUES (?, ?, ?)"
        );

        $stmt->bind_param(
            "sss",
            $name,
            $email,
            $hashedPassword
        );

        if ($stmt->execute()) {

            $message = "Registration successful!";

        } else {

            $message = "Email already exists.";

        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Voter Registration</title>

<link rel="stylesheet" href="assets/style.css">

</head>

<body>

<div class="form-container">

<h2>Voter Registration</h2>

<p class="message">
    <?php echo htmlspecialchars($message); ?>
</p>

<form method="POST">

<input
    type="text"
    name="name"
    placeholder="Enter Full Name"
    required
>

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

<button type="submit" name="register">
    Register
</button>

</form>

<p>
Already registered?
<a href="login.php">Login</a>
</p>

</div>

</body>
</html>