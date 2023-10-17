<?php
$email = $_SESSION['email'];
$password = $_SESSION['password'];
if ($email != false && $password != false) {

    $query = $this->db->query("SELECT * FROM user WHERE email = '$email'");

    if ($query->num_rows() > 0) {
        $fetch_info = $query->row_array();
        $status = $fetch_info['status'];
        $code = $fetch_info['code'];
    }
} else {
    redirect('auth');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo $fetch_info['nama'] ?> | Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

        nav {
            padding-left: 100px !important;
            padding-right: 100px !important;
            background: #6665ee;
            font-family: 'Poppins', sans-serif;
        }

        nav a.navbar-brand {
            color: #fff;
            font-size: 30px !important;
            font-weight: 500;
        }

        button a {
            color: #6665ee;
            font-weight: 500;
        }

        button a:hover {
            text-decoration: none;
        }

        h1 {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            text-align: center;
            transform: translate(-50%, -50%);
            font-size: 50px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <a class="navbar-brand" href="#">Brand name</a>
        <form action="<?= base_url('auth/logout') ?>"> <button type="submit" class="btn btn-light">Logout</button></form>
    </nav>
    <h1>Welcome <?php echo $fetch_info['nama'] ?></h1>

</body>

</html>