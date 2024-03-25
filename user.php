<?php
	date_default_timezone_set("Etc/GMT+8");
	session_start();
    $conn = new mysqli("localhost", "root", "", "sacco");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $username = $_SESSION['username'];

    // Prepare SQL query to retrieve user information based on username
    $sql = "SELECT * FROM user_info WHERE username = '$username'"; // Modify the table and column names as per your database schema
    
    // Execute the query
    $result3 = $conn->query($sql);
    
    // Check if the query executed successfully
    if ($result3 === false) {
        die("Error executing query: " . $conn->error);
    }
    
    // Check if a row is returned
    if ($result3->num_rows > 0) {
        // Fetch the first row (assuming there is only one row for each username)
        $row = $result3->fetch_assoc();
    } else {
        echo "User account not found.";
    }
?>
<html lang="en">

<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<title>Jitahidi Sacco</title>

<link href="fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

<link rel="icon" type="image/png" href="asset/images/favicon2.jpg">

<link href="css/sb-admin-2.css" rel="stylesheet">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">


</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
            <div class="sidebar-brand-text mx-3">Admin Panel</div>
        </a>


         <!-- Nav Item - Dashboard -->
         <li class="nav-item active">
                <a class="nav-link" href="adminhome.php">
                    <i class="fas fa-fw fa-home"></i>
                    <span>Home</span></a>
            </li>
			
			<li class="nav-item">
                <a class="nav-link" href="adminpayment.php">
                    <i class="fas fa-fw fas fa-coins"></i>
                    <span>Payments</span></a>
            </li>
			<li class="nav-item">
                <a class="nav-link" href="borrower.php">
                    <i class="fas fa-fw fas fa-book"></i>
                    <span>Borrowers</span></a>
            </li>
			<li class="nav-item">
                <a class="nav-link" href="loan_plan.php">
                    <i class="fas fa-fw fa-piggy-bank"></i>
                    <span>Loan Plans</span></a>
            </li>
			<li class="nav-item">
                <a class="nav-link" href="loan_type.php">
                    <i class="fas fa-fw fa-money-check"></i>
                    <span>Loan Types</span></a>
            </li>
			<li class="nav-item">
                <a class="nav-link" href="user.php">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Users</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Logout</span></a>
            </li>
        </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>


                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-800 small"><?= $row['username'] ?></span>
                            <img class="img-profile rounded-circle"
                                src="asset/images/image/admin_profile.svg">
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>

                </ul>

            </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Users</h1>
                    </div>
					<button class="mb-2 btn btn-lg btn-success" href="#" data-toggle="modal" data-target="#addModal"><span class="fa fa-plus"></span> Add User</button>
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php
											
                                            // Define your custom select function
                                            function select($conn, $sql)
                                            {
                                                $result = $conn->query($sql);
                                                if ($result === false) {
                                                    die("Error executing query: " . $conn->error);
                                                }
                                                return $result;
                                            }
                                            
                                            // Assuming $conn is your database connection object
                                            
                                            // Prepare SQL query to retrieve user information
                                            $sql = "SELECT * FROM user_info"; // Modify the table name as per your database schema
                                            
                                            // Execute the query using the custom select function
                                            $result = select($conn, $sql);
                                            
                                            // Loop through the result set
                                            while ($fetch = $result->fetch_array()) {
                                                ?>
                                                <!-- Output HTML table row -->
                                                <tr>
                                                    <td><?php echo $fetch['username']?></td>
                                                    <td><?php echo $fetch['full_name'] . " " ?></td>
                                                    <td>
                                                        <!-- Dropdown menu -->
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Action
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <!-- Edit action -->
                                                                <a class="dropdown-item bg-warning text-white" href="#" data-toggle="modal" data-target="#updateModal<?php echo $fetch['user_id']?>"><i class="fa fa-edit fa-1x"></i> Edit</a>
                                                                <!-- Delete action -->
                                                                <?php if ($fetch['username'] == $_SESSION['username']) { ?>
                                                                    <a class="dropdown-item bg-danger text-white" href="#" disabled="disabled"><i class="fa fa-exclamation fa-1x"></i> Cannot Delete</a>
                                                                <?php } else { ?>
                                                                    <a class="dropdown-item bg-danger text-white" href="#" data-toggle="modal" data-target="#deleteModal<?php echo $fetch['user_id']?>"><i class="fa fa-trash fa-1x"></i> Delete</a>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                            
										
										
                                        <tr>
                                            
                                            <td>
												<div class="dropdown">
													<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														Action
													</button>
													<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
														<a class="dropdown-item bg-warning text-white" href="#" data-toggle="modal" data-target="#updateModal<?php echo $fetch['username']?>"><i class="fa fa-edit fa-1x"></i> Edit</a>
														<?php
															if($fetch['username'] == $_SESSION['username']){
														?>
															<a class="dropdown-item bg-danger text-white" href="#" disabled="disabled"><i class="fa fa-exclamation fa-1x"></i> Cannot Delete</a>
														<?php
															}else{
														?>
															<a class="dropdown-item bg-danger text-white" href="#" data-toggle="modal" data-target="#deleteModal<?php echo $fetch['username']?>"><i class="fa fa-trash fa-1x"></i> Delete</a>
														<?php
															}
														?>
													</div>
												</div>
												 
												
											</td>
                                        </tr>
										
										
										<!-- Update User Modal -->
										<div class="modal fade" id="updateModal<?php echo $fetch['username']?>" tabindex="-1" aria-hidden="true">
											<div class="modal-dialog">
												<form method="POST" action="updateUser.php">
													<div class="modal-content">
														<div class="modal-header bg-warning">
															<h5 class="modal-title text-white">Edit User</h5>
															<button class="close" type="button" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">×</span>
															</button>
														</div>
														<div class="modal-body">
															<div class="form-group">
																<label>Username</label>
																<input type="text" name="username" value="<?php echo $fetch['username']?>" class="form-control" required="required" />
															</div>
															
															<div class="form-group">
																<label>Full Name</label>
																<input type="text" name="firstname" value="<?php echo $fetch['full_name']?>" class="form-control" required="required" />
															</div>
															
														</div>
														<div class="modal-footer">
															<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
															<button type="submit" name="update" class="btn btn-warning">Update</a>
														</div>
													</div>
												</form>
											</div>
										</div>
										
										
										
										<!-- Delete User Modal -->
										
										<div class="modal fade" id="deleteModal<?php echo $fetch['username']?>" tabindex="-1" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header bg-danger">
														<h5 class="modal-title text-white">System Information</h5>
														<button class="close" type="button" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">×</span>
														</button>
													</div>
													<div class="modal-body">Are you sure you want to delete this record?</div>
													<div class="modal-footer">
														<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
														<a class="btn btn-danger" href="deleteUser.php?user_id=<?php echo $fetch['username']?>&user=<?php echo $_SESSION['username']?>">Delete</a>
													</div>
												</div>
											</div>
										</div>
										
										
										
										
										
                                    </tbody>
                                </table>
                            </div>
						</div>
                       
                    </div>
				</div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="stocky-footer">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Jitahidi Sacco <?php echo date("Y")?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
	
	
	<!-- Add User Modal-->
	<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<form method="POST" action="addUser.php">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title text-white">Add User</h5>
						<button class="close" type="button" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label>Username</label>
							<input type="text" name="username" class="form-control" required="required" />
						</div>
						<div class="form-group">
							<label>Password</label>
							<input type="password" name="password" class="form-control" required="required" />
						</div>
						<div class="form-group">
							<label>Firstname</label>
							<input type="text" name="firstname" class="form-control" required="required" />
						</div>
						<div class="form-group">
							<label>Lastname</label>
							<input type="text" name="lastname" class="form-control" required="required" />
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
						<button type="submit" name="confirm" class="btn btn-primary">Confirm</a>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">System Information</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Are you sure you want to logout?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.bundle.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="js/jquery.easing.js"></script>


	<!-- Page level plugins -->
	<script src="js/jquery.dataTables.js"></script>
    <script src="js/dataTables.bootstrap4.js"></script>
	

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.js"></script>
	
	<script>
		$(document).ready(function() {
			$('#dataTable').DataTable({
				"order": [[3 , "asc" ]]
			});
		});
	</script>

</body>

</html>