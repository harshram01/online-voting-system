<?php

include "config.php";

if (!isset($_SESSION["voter_id"])) {

    header("Location: login.php");
    exit;

}

$voter_id = $_SESSION["voter_id"];

$stmt = $conn->prepare(
    "SELECT has_voted
     FROM voters
     WHERE id = ?"
);

$stmt->bind_param("i", $voter_id);

$stmt->execute();

$voter = $stmt->get_result()->fetch_assoc();

if ($voter["has_voted"] == 1) {

    die("You have already voted.");

}

if (isset($_POST["vote"])) {

    $candidate_id = intval(
        $_POST["candidate_id"]
    );

    $conn->begin_transaction();

    try {

        // Check again inside transaction
        $stmt = $conn->prepare(
            "SELECT has_voted
             FROM voters
             WHERE id = ?
             FOR UPDATE"
        );

        $stmt->bind_param(
            "i",
            $voter_id
        );

        $stmt->execute();

        $current = $stmt
            ->get_result()
            ->fetch_assoc();

        if ($current["has_voted"] == 1) {

            throw new Exception(
                "You have already voted."
            );

        }

        // Update candidate vote
        $stmt = $conn->prepare(
            "UPDATE candidates
             SET votes = votes + 1
             WHERE id = ?"
        );

        $stmt->bind_param(
            "i",
            $candidate_id
        );

        $stmt->execute();

        if ($stmt->affected_rows != 1) {

            throw new Exception(
                "Invalid candidate."
            );

        }

        // Mark voter as voted
        $stmt = $conn->prepare(
            "UPDATE voters
             SET has_voted = 1
             WHERE id = ?"
        );

        $stmt->bind_param(
            "i",
            $voter_id
        );

        $stmt->execute();

        $conn->commit();

        header("Location: results.php");

        exit;

    } catch (Exception $e) {

        $conn->rollback();

        $error = $e->getMessage();

    }

}

$candidates = $conn->query(
    "SELECT *
     FROM candidates
     ORDER BY id"
);

?>

<!DOCTYPE html>
<html>

<head>

<title>Cast Vote</title>

<link rel="stylesheet" href="assets/style.css">

</head>

<body>

<div class="form-container">

<h2>🗳️ Cast Your Vote</h2>

<?php

if (isset($error)) {

echo "<p class='message'>";
echo htmlspecialchars($error);
echo "</p>";

}

?>

<form method="POST">

<?php while (
    $candidate = $candidates->fetch_assoc()
): ?>

<div class="candidate">

<label>

<input
    type="radio"
    name="candidate_id"
    value="<?php echo $candidate["id"]; ?>"
    required
>

<strong>
<?php
echo htmlspecialchars(
    $candidate["name"]
);
?>
</strong>

<br>

<span>
Party:
<?php
echo htmlspecialchars(
    $candidate["party"]
);
?>
</span>

</label>

</div>

<?php endwhile; ?>

<button
    type="submit"
    name="vote"
    onclick="return confirm('Are you sure you want to vote?')"
>

Confirm Vote

</button>

</form>

</div>

</body>

</html>