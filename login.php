<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">

</head>
<!-- dropdown -->
<nav>
    <div class="dropdown">

        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2"
            data-bs-toggle="dropdown" aria-expanded="false">
            Admin
        </button>
        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton2">
            <li><a class="dropdown-item active" href="login.php">Admin</a></li>
            <li><a class="dropdown-item" href="loginViewer.php">Viewer</a></li>
            <li><a class="dropdown-item" href="studentLogin.php">Student</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
        </ul>
    </div>
   

</nav>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        overflow: hidden;
        margin: 0;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background-image: url("bsu2.jpg");
        background-size: cover;
        background-position: center;

    }

    main {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 64px);
        background-size: cover;
        background-position: center;
    }


    .container {

        height: 100%;
        width: 420px;
        background: transparent;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        width: 100%;
        height: 100%;
        margin: 30px 0;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background: rgba(255, 255, 255, 0.7);
        font-weight: bold;
    }

    .form-btn {
        text-align: center;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        background: rgba(0, 123, 255, 0.7);
        background: red;
        color: white;
        font-weight: bold;
    }

    .btn:hover {
        background: rgba(0, 123, 255, 1);
    }

    nav {
        align-items: center;
        display: flex;
        flex-direction: row-reverse;
        height: 64px;
        padding: 20px;
        width: 100%;

    }

    .dropdown .btn {
        background-color: #333;
        color: white;
        border: none;
    }

    .dropdown-menu {
        background-color: #333;
        color: white;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .dropdown-menu .dropdown-item {
        color: white;
    }

    .dropdown-menu .dropdown-item:hover {
        background-color: #555;
    }

    a{
    text-decoration: none;
    margin-right: 10px;
    }
</style>

<body>
    <!-- Login -->
    <main>
        <div class="container">
            <?php
            if (isset($_POST["login"])) {
                $email = $_POST["email"];
                $password = $_POST["password"];

                require_once "database.php";
                
                $sql = "SELECT * FROM tb_admin WHERE email = ?";
                $stmt = mysqli_stmt_init($conn);

                if (mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_stmt_bind_param($stmt, "s", $email);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    if ($user) {
                        if (password_verify($password, $user["password"])) {
                            header("Location: index.php");
                            die();
                        } else {
                            echo "<div class='alert alert-danger'>Password does not match</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Email not found</div>";
                    }
                } else {
                    die("Prepare statement failed: " . mysqli_error($conn));
                }
            }
            ?>
            <form action="login.php" method="post">
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Enter Email">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Enter Password">
                </div>
                <div class="form-btn">
                    <input type="submit" name="login" class="btn btn-primary" value="LOGIN">
                </div>
            </form>
        </div>
    </main>
</body>

</html>