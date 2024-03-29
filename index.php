<?php
    session_start();
    include_once 'dbh.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>PHP File Manager</title>
</head>
<body>

    <?php
        // list all the users from the DB
        $sql_users = "SELECT * FROM users;";
        $result_users = mysqli_query($conn, $sql_users);

        if (mysqli_num_rows($result_users) > 0) 
        {
            while ($row_users = mysqli_fetch_assoc($result_users))
            {
                $user_id = $row_users['user_id'];
                
                $sql_img = "SELECT * FROM profile_imgs WHERE user_id = '$user_id';";
                $result_img = mysqli_query($conn, $sql_img);

                while ($row_img = mysqli_fetch_assoc($result_img))
                {
                    echo "
                        <div class='user-container'>";
                            if ($row_img['profile_img_status'] == 0) 
                            {
                                // get the uploaded image file extension
                                $file_name = "uploads/profile_".$user_id."*";
                                $file_info = glob($file_name);
                                $file_ext = explode(".", $file_info[0]);
                                $file_actual_ext = $file_ext[1];

                                echo "
                                    <img src='uploads/profile_".$user_id.".".$file_actual_ext."?".mt_rand()."'>"; // mt_rand is used to help with the issue when browser remembers the actual image by it's file name
                            }
                            else
                            {
                                echo "
                                    <img src='uploads/profile_default.jpg'>";
                            }

                            echo "
                                <p class='p-title'>".$row_users['user_un']."<br>
                                ".$row_users['user_fn']." ".$row_users['user_ln']."</p>";
                    echo "
                        </div>";
                }
            }
        }
        else
        {
            echo "There are no users yet!";
        }

        // check if the user is logged in
        if (isset($_SESSION['user_id'])) 
        {
            if ($_SESSION['user_id'] == 1) 
            {
                echo "You are logged in as user #1";
            }

            // show file upload form only if the user is logged in
            echo "
                <form action='upload.php' method='POST' enctype='multipart/form-data'>
                    <input type='file' name='file'>
                    <button type='submit' name='submit_upload'>Upload</button>
                </form>";

            // delete profile image form
            echo "
                <form action='delete.php' method='POST'>
                    <button type='submit' name='submit_delete'>Delete Profile Img</button>
                </form>";
        }
        else
        {
            echo "You are not logged in!";
            echo "
                <form action='signup.php' method='POST'>
                    <input type='text' name='first_name' placeholder='First name'>
                    <input type='text' name='last_name' placeholder='Last name'>
                    <input type='text' name='username' placeholder='Username'>
                    <input type='password' name='pwd' placeholder='Password'>
                    <button type='submit' name='submit_signup'>Signup</button>
                </form>";
        }
    ?> 
    
    <p>Login as user!</p>
    <form action="login.php" method="POST">
        <button type="submit" name="submit_login">Login</button>
    </form>

    <p>Logout as user!</p>
    <form action="logout.php" method="POST">
        <button type="submit" name="submit_logout">Logout</button>
    </form>
</body>
</html>