<?php
session_start(); // Starting Session
$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
    if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
        $error = "Username, Password or Email is empty";
    }
    else
    {
// Define $username and $password
        $username=$_POST['username'];
        $password=$_POST['password'];
        $email = $_POST['email'];

// Establishing Connection with Server by passing server_name, user_id and password as a parameter
// Selecting Database
        $connection = new mysqli("eu-cdbr-azure-west-c.cloudapp.net", "b0b05a48637b3e", "2d0628d7", "wb1306507");
// To protect MySQL injection for Security purpose
        $username = stripslashes($username);
        $email = stripslashes($email);
        $password = stripslashes($password);
        $password = crypt($password,'bananafacemcghee');
        //$username = mysql_real_escape_string($username);
        // $password = mysql_real_escape_string($password);
// SQL query to insert new user details into database and log them in
        $query = mysqli_query($connection,"SELECT * FROM users WHERE username='$username'");
        $result = mysqli_num_rows($query);
        if ($result == 0) {
            mysqli_query($connection, "INSERT INTO users(username,password,permissionlevel,verified,email,displayname,bio) VALUES('$username', '$password', 1,0,'$email','$username','') ");
            $userid = getval($connection,"SELECT userid FROM users WHERE username='$username'");
            $permLevel= getval($connection,"SELECT permissionLevel FROM users WHERE userid='$userid'");
            $_SESSION['login_user']=$username; // Initializing Session
            $_SESSION['userid'] = $userid;
            $_SESSION['email'] = $email;
            $_SESSION['displayName'] = $username;
            $_SESSION['permLevel'] = $permLevel;
            header("location: profile.php"); // Redirecting To Other Page
        } else {
            $error = "Username is already taken";
        }
        $connection->close(); // Closing Connection
    }
}
function getval($mysqli, $sql) {
    $result = $mysqli->query($sql);
    $value = $result->fetch_array(MYSQLI_NUM);
    return is_array($value) ? $value[0] : "";
}

?>