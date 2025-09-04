<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Project Gallery</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #1e1e2f;
            color: #e0e0e0;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            margin: 0;
            padding: 20px;
        }

        .nav-bar {
            width: 100%;
            background-color: #2a2a3d;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,255,255,0.1);
            margin-bottom: 30px;
        }

        .nav-bar ul {
            list-style: none;
            display: flex;
            justify-content: center;
            padding: 0;
            margin: 0;
        }

        .nav-bar li {
            margin: 0 15px;
        }

        .nav-bar a {
            display: block;
            padding: 15px 20px;
            color: #00ffff;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s, color 0.3s;
            border-radius: 4px;
        }

        .nav-bar a:hover {
            background-color: #00ffff;
            color: #1e1e2f;
        }

        h1 {
            text-align: center;
            color: #00ffff;
            margin-bottom: 40px;
        }

        h2 {
            color: #00ffff;
            margin-top: 40px;
            margin-bottom: 20px;
            text-align: center;
        }

        .gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .card {
            background-color: #2a2a3d;
            border: 1px solid #444;
            border-radius: 8px;
            width: 300px;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0,255,255,0.1);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.02);
        }

        .card img {
            max-width: 100%;
            border-radius: 4px;
            cursor: pointer;
        }

        .card p {
            margin: 10px 0;
        }

        .card a {
            color: #00ffff;
            text-decoration: none;
            font-weight: bold;
        }

        .card a:hover {
            text-decoration: underline;
        }

        @media screen and (max-width: 600px) {
            body {
                padding: 10px;
            }

            .nav-bar ul {
                flex-direction: column;
                align-items: center;
            }

            .nav-bar li {
                margin: 10px 0;
            }

            .gallery {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>

<nav class="nav-bar">
    <ul>
        <li><a href="index.php">📁 Gallery</a></li>
        <li><a href="admin.php">🛠️ Admin Panel</a></li>
        <li><a href="edit.php">✏️ Edit Projects</a></li>
    </ul>
</nav>

<h1>📁 Project Gallery</h1>

<?php
$topics = $conn->query("SELECT * FROM topics ORDER BY name ASC");
while ($topic = $topics->fetch_assoc()) {
    echo "<h2>" . htmlspecialchars($topic['name']) . "</h2><div class='gallery'>";
    $tid = $topic['id'];
    $projects = $conn->query("SELECT * FROM projects WHERE topic_id=$tid ORDER BY created_at DESC");
    while ($row = $projects->fetch_assoc()) {
        echo "<div class='card'>
                <a href='project.php?id=" . $row['id'] . "'>

                    <img src='upload/" . htmlspecialchars($row['image']) . "' alt='Project Image'>
                </a>
                <p><strong>Description:</strong> " . htmlspecialchars($row['description']) . "</p>
                <p>" . htmlspecialchars($row['details']) . "</p>";
        if (!empty($row['document'])) {
            echo "<a href='files/" . htmlspecialchars($row['document']) . "' target='_blank'>📄 Download Document</a>";
        }
        echo "</div>";
    }
    echo "</div>";
}
?>

</body>
</html>
