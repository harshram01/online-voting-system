<?php

include "config.php";

if (!isset($_SESSION["voter_id"])) {

    header("Location: login.php");
    exit;

}

$voter_id = $_SESSION["voter_id"];

$stmt = $conn->prepare(
    "SELECT name, has_voted
     FROM voters
     WHERE id = ?"
);

$stmt->bind_param("i", $voter_id);

$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

?>

<!DOCTYPE html>
<html>

<head>

<title>Voter Dashboard</title>

<link rel="stylesheet" href="assets/style.css">

</head>

<body>

<div class="navbar">

<h2>Voter Dashboard</h2>

<div>

<a href="dashboard.php">Dashboard</a>
<a href="results.php">Results</a>
<a href="logout.php">Logout</a>

</div>

</div>

<div class="dashboard">

<h1>
Welcome,
<?php echo htmlspecialchars($user["name"]); ?>
</h1>

<?php if ($user["has_voted"] == 1): ?>

<div class="alert">

<h3>You have already voted.</h3>

<p>You cannot vote again.</p>

</div>

<?php else: ?>

<div class="card">

<h3>You have not voted yet.</h3>

<a class="btn" href="vote.php">

Cast Your Vote

</a>

</div>

<?php endif; ?>

</div>

</body>

</html>