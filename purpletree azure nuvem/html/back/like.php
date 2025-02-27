<?php
include '../db.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_SESSION['username'];
    $postId = $_POST['post_id'];

    $sql = "SELECT count(*) as count FROM curte WHERE username = '$username' AND id = '$postId'";

    $curtiu = mysqli_query($c, $sql);

    $curtiu = mysqli_fetch_assoc($curtiu);

    if (!$curtiu['count']) {
        $sql_create = "INSERT INTO curte (username, id) VALUES ('$username', '$postId')";
        mysqli_query($c, $sql_create);
        $status = "liked";
    } else {
        $sql_delete = "DELETE FROM curte WHERE username = '$username' AND id = '$postId'";
        mysqli_query($c, $sql_delete);
        $status = "unliked";
    }

    $curtidas = mysqli_query($c, "SELECT count(*) as curtidas FROM curte WHERE id = '$postId'");
    $curtidas = mysqli_fetch_assoc($curtidas);

    echo json_encode([
        "status" => $status,
        "likes" => $curtidas['curtidas']
    ]);
}
?>
