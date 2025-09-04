<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
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

        .page-center {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 700px;
            margin: auto;
        }

        h2 {
            color: #00ffff;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            background: #2a2a3d;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,255,255,0.1);
            width: 100%;
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="file"],
        textarea,
        select {
            width: 100%;
            margin-top: 5px;
            padding: 8px;
            border-radius: 4px;
            border: none;
            background: #1e1e2f;
            color: #e0e0e0;
        }

        input[type="submit"] {
            margin-top: 15px;
            padding: 10px 20px;
            background: #00ffff;
            color: #1e1e2f;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .message {
            padding: 10px;
            background: #00ffff;
            color: #1e1e2f;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
            width: 100%;
        }

        @media screen and (max-width: 600px) {
            body {
                padding: 10px;
            }

            form {
                padding: 15px;
            }

            input[type="submit"] {
                width: 100%;
            }

            .nav-bar ul {
                flex-direction: column;
                align-items: center;
            }

            .nav-bar li {
                margin: 10px 0;
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

    <div class="page-center">
        <h2>Create a New Topic</h2>
        <form method="POST">
            <label>Topic Name:</label>
            <input type="text" name="topic_name" required>
            <input type="submit" name="create_topic" value="Create Topic">
        </form>

        <?php
        if (isset($_POST['create_topic'])) {
            $name = trim($_POST['topic_name']);
            $stmt = $conn->prepare("INSERT INTO topics (name) VALUES (?)");
            $stmt->bind_param("s", $name);
            if ($stmt->execute()) {
                echo "<div class='message'>✅ Topic '$name' created successfully!</div>";
            } else {
                echo "<div class='message'>⚠️ Error: Topic may already exist.</div>";
            }
        }
        ?>

        <h2>Upload a New Project</h2>
        <form action="admin.php" method="POST" enctype="multipart/form-data">
            <label>Topic:</label>
            <select name="topic_id" required>
                <option value="">-- Select Topic --</option>
                <?php
                $topics = $conn->query("SELECT * FROM topics ORDER BY name ASC");
                while ($t = $topics->fetch_assoc()) {
                    echo "<option value='{$t['id']}'>{$t['name']}</option>";
                }
                ?>
            </select>

            <label>Project Image:</label>
            <input type="file" name="image" required>

            <label>Short Description:</label>
            <textarea name="description" rows="3" required></textarea>

            <label>Detailed Information:</label>
            <textarea name="details" rows="5" required></textarea>

            <label>Supporting Document (PDF, DOCX, etc.):</label>
            <input type="file" name="document">

            <input type="submit" name="submit" value="Upload Project">
        </form>

        <?php
        if (isset($_POST['submit'])) {
            $topic_id = intval($_POST['topic_id']);
            $desc = $_POST['description'];
            $details = $_POST['details'];

            $imageName = $_FILES['image']['name'];
            $imageTmp = $_FILES['image']['tmp_name'];
            $imagePath = "upload/" . basename($imageName);

            $docName = $_FILES['document']['name'];
            $docTmp = $_FILES['document']['tmp_name'];
            $docPath = !empty($docName) ? "files/" . basename($docName) : null;

            if (!is_dir("upload")) mkdir("upload", 0777, true);
            if (!is_dir("files")) mkdir("files", 0777, true);

            move_uploaded_file($imageTmp, $imagePath);
            if ($docPath) {
                move_uploaded_file($docTmp, $docPath);
            }

            $stmt = $conn->prepare("INSERT INTO projects (image, description, details, document, topic_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssi", $imageName, $desc, $details, $docName, $topic_id);
            $stmt->execute();

            echo "<div class='message'>✅ Project uploaded successfully!</div>";
        }
        ?>
    </div>
</body>
</html>
