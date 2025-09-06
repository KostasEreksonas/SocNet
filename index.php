<?php
include("includes/header.php");
include("includes/classes/User.php");
include("includes/classes/Post.php");

if (isset($_POST['post'])) {
    $post = new Post($con, $user_logged_in);
    $post->submitPost($_POST['post_text'], 'none');
    header("Location: index.php");
}
?>
<div class="user_details column">
    <a href="<?php echo $user_logged_in; ?>"> <img src="<?php echo $user['profile_pic']; ?>" alt="Profile Pic"> </a>

    <div class="user_details_left_right">
        <a href="<?php echo $user_logged_in; ?>"><?php echo $user['first_name'] . " " . $user['last_name']; ?></a>
        <?php
        echo "Posts: " . $user['num_posts'] . "<br>";
        echo "Likes: " . $user['num_likes'];
        ?>
    </div>
</div>

<div class="main_column column">
    <form class="post_form" action="index.php" method="post">
        <textarea name="post_text" id="post_text" placeholder="Got something to say?" cols="30" rows="10"></textarea>
        <input type="submit" name="post" id="post_button" value="Post">
        <hr>
    </form>
    <?php
        $user_obj = new User($con, $user_logged_in);
        echo $user_obj->getFirstAndLastName();
    ?>
</div>

</div>
</body>
</html>