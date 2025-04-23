<?php
// Get the current page name for active navigation
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DYCI Tour System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .main-header {
            background-color: #000080;
            padding: 8px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            height: 80px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-left img {
            height: 65px;
            width: 65px;
            object-fit: contain;
            background-color: white;
            border-radius: 50%;
            padding: 3px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header-left span {
            color: white;
            font-size: 28px;
            font-weight: normal;
            font-family: Arial, sans-serif;
        }

        .header-right img {
            height: 65px;
            width: 65px;
            object-fit: contain;
            background-color: white;
            border-radius: 50%;
            padding: 3px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Ensure content starts below header */
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-color: #f5f5f5;
        }

        .content-wrapper {
            padding-top: 20px;
        }

        /* Debug outline for images */
        .main-header img {
            border: 1px solid rgba(255,255,255,0.1);
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="header-left">
            <img src="<?php echo isset($is_admin) ? '../' : ''; ?>assets/images/dyci-logo.png" alt="DYCI Logo">
            <span>Tour</span>
        </div>
        <div class="header-right">
            <img src="<?php echo isset($is_admin) ? '../' : ''; ?>assets/images/dyci-logo.png" alt="DYCI Logo">
        </div>
    </header>
    <div class="content-wrapper">
</body>
</html> 