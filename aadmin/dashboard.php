<?php
session_start(); 

// Access for Staff Account only
if (!isset($_SESSION["user_id"]) || $_SESSION["account_type"] != "1") {
    
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Access Denied</title>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css'>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Access Denied',
                    text: 'Staff lang ang may access dito',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.history.back(); // Redirects back to the previous page
                    }
                });
            });
        </script>
    </head>
    <body>
    </body>
    </html>";
    exit();
}

// Include database connection
include '../connections.php';

// Queries
$total_residents_query = "SELECT COUNT(id) as total FROM users WHERE account_type != '1'"; // Exclude admin
$total_residents_result = $connections->query($total_residents_query);
$total_residents = $total_residents_result->fetch_assoc()['total'];

$pending_reports_query = "SELECT COUNT(*) as pending FROM blotter_report WHERE status = 'pending'";
$pending_reports_result = $connections->query($pending_reports_query);
$pending_reports = $pending_reports_result->fetch_assoc()['pending'];

$scheduled_meetings_query = "SELECT COUNT(*) as finished FROM blotter_report WHERE status = 'finished'";
$scheduled_meetings_result = $connections->query($scheduled_meetings_query);
$scheduled_meetings = $scheduled_meetings_result->fetch_assoc()['finished'];

$connections->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script defer src="script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .metric-container {
            border: 1px solid #ccc; 
            border-radius: 5px; 
            padding: 15px; 
            margin-bottom: 15px; 
            background-color: #f9f9f9; 
            width: calc(30% - 20px); 
        }

        .dashboard-metrics {
            display: flex; 
            flex-wrap: wrap; 
            gap: 20px; 
        }

        #content {
            padding: 20px; 
        }
    </style>
</head>
<body>
<?php include 'admin_sidenav.php'; ?> 
    <div id="content">
        <center><h1>Dashboard</h1></center>

        <hr>
       
        
        <!-- Dashboard content -->
        <div class="dashboard-metrics">
            <div class="metric-container">
                <h2>Total Residents</h2>
                <p><span id="total-residents"><?php echo $total_residents; ?></span></p>
            </div>
            <div class="metric-container">
                <h2>Pending Blotter Reports</h2>
                <p><span id="pending-reports"><?php echo $pending_reports; ?></span></p>
            </div>
            <div class="metric-container">
                <h2>Scheduled Meetings</h2>
                <p><span id="scheduled-meetings"><?php echo $scheduled_meetings; ?></span></p>
            </div>
            
        </div>
    </div>
</body>
</html>
