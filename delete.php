<?php
include 'dbcon.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("DELETE FROM users WHERE ID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: index.php?msg=deleted");
exit();
?>
