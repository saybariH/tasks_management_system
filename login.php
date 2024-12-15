
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Link for custom CSS -->
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
	<style>
		body {
    font-family: 'Arial', sans-serif;
    background-color: #f8f9fa;
}

img {
    max-width: 500px;
}

h2 {
    font-weight: 700;
    color: #333;
}

.form-label {
    font-weight: 600;
    color: #555;
}

.btn-primary {
    background-color: #6c5ce7; /* Light purple color */
    border: none;
}

.btn-primary:hover {
    background-color: #5a48d8; /* Slightly darker purple */
}

.text-primary {
    color: #6c5ce7 !important;
}

.text-muted {
    font-size: 0.9rem;
}

a.text-primary:hover {
    text-decoration: underline;
}

.fab {
    transition: transform 0.3s ease-in-out;
}

.fab:hover {
    transform: scale(1.2);
}

	</style>

<div class="container-fluid min-vh-100 d-flex align-items-center bg-light">
    <div class="row w-100 justify-content-center">
        <!-- Left Section with Illustration -->
        <div class="col-md-6 d-none d-md-block">
            <div class="d-flex justify-content-center align-items-center h-100">
			<img src="img/login.png" alt="Illustration" class="img-fluid">
            </div>
        </div>

        <!-- Right Section with Form -->
        <div class="col-md-5 col-lg-4 bg-white p-5 rounded shadow-sm">
            <h2 class="mb-4 text-center">Sign In</h2>
            <p class="text-muted text-center">Lorem ipsum dolor sit amet elit.</p>
            <!-- Form Start -->
            <form  method="POST" action="app/login.php">
			<?php if (isset($_GET['error'])) {?>
      	  	<div class="alert alert-danger" role="alert">
			  <?php echo stripcslashes($_GET['error']); ?>
			</div>
      	  <?php } ?>

      	  <?php if (isset($_GET['success'])) {?>
      	  	<div class="alert alert-success" role="alert">
			  <?php echo stripcslashes($_GET['success']); ?>
			</div>
      	  <?php } ?>
                <div class="mb-3">
                    <label for="username" class="form-label" >Username</label>
                    <input type="text" class="form-control" name="user_name" id="username" placeholder="Enter your username">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password">
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <input type="checkbox" id="rememberMe">
                        <label for="rememberMe" class="text-muted">Remember me</label>
                    </div>
                    <a href="#" class="text-primary text-decoration-none">Forgot Password</a>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Log In</button>
                </div>
            </form>
            <div class="text-center mt-4">
                <p class="mb-2 text-muted">— or login with —</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="#" class="text-decoration-none text-primary fs-4">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" class="text-decoration-none text-info fs-4">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-decoration-none text-danger fs-4">
                        <i class="fab fa-google"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
