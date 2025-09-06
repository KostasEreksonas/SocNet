<?php
class Post {
    private $user_obj;
    private $con;

    public function __construct($con, $user) {
        $this->con = $con;
        $this->user_obj = new User($con, $user);
    }

    public function submitPost($body, $user_to) {
        $body = strip_tags($body); // Removes html tags
        $body = mysqli_real_escape_string($this->con, $body);

        $body = str_replace("\r\n", "\n", $body);
        $body = nl2br($body);

        $check_empty = preg_replace("/\s+/", "", $body); // Deletes all spaces

        if ($check_empty != "") {
            // Current date and time
            $date_added = date("Y-m-d H:i:s");

            // Get username
            $added_by = $this->user_obj->getUsername();

            // If user is on own profile, user_to is 'None'
            if ($user_to == $added_by) {
                $user_to = "none";
            }

            // Insert post
            try {
                $query = mysqli_query($this->con, "INSERT INTO posts VALUES(NULL, '$body', '$added_by', '$user_to', '$date_added', 'no', 'no', 0)");
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
            $returned_id = mysqli_insert_id($this->con);

            // Insert notification
            // blank for now

            // Update post count for user
            $num_posts = $this->user_obj->getNumPosts();
            $num_posts++;
            $update_query = mysqli_query($this->con, "UPDATE users SET num_posts = '$num_posts' WHERE username = '$added_by'");
        }

    }
}