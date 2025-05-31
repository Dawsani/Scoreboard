<?php
include($_SERVER["DOCUMENT_ROOT"] . "/scripts/helpers.php");

$passwordChangeSuccessBlock = <<<HTML
    <div>
        <p>Password changed successfully.</p>
        <a href="/login.php"><button>Return to Login</button></a>
    </div>
HTML;

function generateEmailForm($errors) {
    $emailForm = <<<HTML
        <form action="" method="POST">
            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>

            <button type="submit">Send Password Reset Link</button>
        </form>
    HTML;

    foreach ($errors as $error) {
        $emailForm .= "<p style='color:red'>" . $error . "</p><br>";
    }

    return $emailForm;
}

function generateTokenForm($email, $error = FALSE) {
    $tokenForm = <<<HTML
        <p>A verification code has been sent to {$email}. Type the code in the space below within the next 30 minutes:</p>
        <form action="" method="POST">
            <div>
                <input type="hidden" name="email" id="email" value="{$email}">
                <label for="token">Password Reset Token:</label>
                <input type="text" name="token" id="token" required>
            </div>
            <button type="submit">Reset Password</button>
        </form>
    HTML;

    if ($error) {
        $tokenForm .= "<p style='color:red;'>Invalid code</p>";
    }

    return $tokenForm;
}

function generateNewPasswordForm($email, $token, $errors) {

    $newPasswordForm = <<<HTML
        <form action="" method="POST">
            <input type="hidden" name="email" id="email" value="{$email}" reqiored>
            <input type="hidden" name="token" id="token" value="{$token}" required>

            <div>
                <label for="password1">New Password:</label>
                <input type="password" name="password1" id="password1" minlength="3" maxlength="25" required>
            </div>

            <div>
                <label for="password2">Repeat New Password:</label>
                <input type="password" name="password2" id="password2" minlength="3" maxlength="25" required>
            </div>

            <button type="submit">Reset Password</button>
        </form>
    HTML;

    foreach ($errors as $error) {
        $newPasswordForm .= "<p style='color:red'>" . $error . "</p><br>";
    }

    return $newPasswordForm;
}

function generateTokenAndSendEmail($email) {
    $resetToken = getToken(6);
                
    $mysqli = db_connect();
    $stmt = $mysqli->prepare("INSERT INTO password_reset (user_id, code, expire) VALUES (?, ?, NOW() + INTERVAL 30 MINUTE)");
    
    $userId = userEmailToId($mysqli, $email);

    $stmt->bind_param("is", $userId, $resetToken);
    $stmt->execute();

    send_email($email, $resetToken);
}

function validate_password($password1, $password2) {
    $errors = array();

    if ($password1 != $password2) {
        $errors[] = "Passwords do not match.";

        return $errors;
    }

    $password = $password1;

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    return $errors;
}

function update_password($email, $newPassword) {
    $mysqli = db_connect();
    $hashed_password = password_hash($newPassword, PASSWORD_BCRYPT);
    $stmt = $mysqli->prepare("  UPDATE user 
                                SET password_hash = ?
                                WHERE id = ?;");

    $userId = userEmailToId($mysqli, $email);
    $stmt->bind_param("si", $hashed_password, $userId);
    $result = $stmt->execute();
}

function validate_email($email) {
    $errors = array();
                
    $mysqli = db_connect();    
    $userId = userEmailToId($mysqli, $email);

    if ($userId == -1) {
        $errors[] = "No account exists with the email " . $email . ".";
    }

    return $errors;
}
?>

<html>
<head>
    <title>The Archives</title>
    <link rel="stylesheet" href="/styles.css">
</head>

<body>
    <main>
    <h1>Reset Password</h1>

    <?php
        if (isset($_POST["email"])) {

             $email = $_POST["email"];

             // make sure the email is valid
            $emailErrors = validate_email($email);
            if (sizeof($emailErrors) == 0) {
                // email and token are set, verify them and show password input
                if (isset($_POST["token"])) {

                    $token = $_POST["token"];

                    // check if passwords were set
                    if (isset($_POST["password1"]) and isset($_POST["password2"])) {
                        $password1 = $_POST["password1"];
                        $password2 = $_POST["password2"];

                        // If input is good, make an account
                        if ($_SERVER["REQUEST_METHOD"] === "POST") {

                            $errors = validate_password($password1, $password2);

                            if (sizeof($errors) == 0) {
                                update_password($email, $password1);
                                echo $passwordChangeSuccessBlock;
                            }

                            else {
                                echo generateNewPasswordForm($email, $token, $errors);
                            }

                        }
                    }

                    else {
                        // either show the password form
                        $errors = verify_password_reset_token($email, $token);
                        if (sizeof($errors) == 0) {
                            echo generateNewPasswordForm($email, $token, array());
                        }

                        // or show an error with the token form
                        else {
                            echo generateTokenForm($email, $errors);
                        }
                    }

                }

                // if the email is set but not the token, get the token
                else {
                    // if it was a post, send the email
                    if ($_SERVER["REQUEST_METHOD"] === "POST") {
                        generateTokenAndSendEmail($email);
                    }

                    echo generateTokenForm($email);

                } 
            }
            
            else {
                echo generateEmailForm($emailErrors);
            }
        } 
        
        // if an email isn't set, get the email from the user
        else {
            echo generateEmailForm(array());
        }
    ?>
    </main>
</body>
</html>

