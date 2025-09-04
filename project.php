<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Project Details</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #1e1e2f;
            color: #e0e0e0;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background-color: #2a2a3d;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,255,255,0.1);
        }

        .project-image {
            display: block;
            max-width: 100%;
            margin: 0 auto 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,255,255,0.2);
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #00ffff;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        h1 {
            color: #00ffff;
            text-align: center;
            margin-bottom: 30px;
        }

        p {
            margin: 10px 0;
        }

        a.download {
            display: inline-block;
            margin-top: 20px;
            color: #00ffff;
            text-decoration: none;
            font-weight: bold;
        }

        a.download:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<a class="back-link" href="index.php">← Back to Gallery</a>

<div class="container">
<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM projects WHERE id=$id");
    if ($result && $result->num_rows > 0) {
        $project = $result->fetch_assoc();
        echo "<h1>📷 Project Preview</h1>";
        echo "<img class='project-image' src='upload/" . htmlspecialchars($project['image']) . "' alt='Project Image'>";
        echo "<p><strong>Description:</strong> " . htmlspecialchars($project['description']) . "</p>";
        echo "<p>" . htmlspecialchars($project['details']) . "</p>";
        if (!empty($project['document'])) {
            echo "<a class='download' href='files/" . htmlspecialchars($project['document']) . "' target='_blank'>📄 Download Document</a>";
        }
    } else {
        echo "<p>Project not found.</p>";
    }
} else {
    echo "<p>Invalid project ID.</p>";
}
?>
</div>

</body>
</html>
