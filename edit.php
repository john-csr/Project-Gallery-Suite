<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Projects</title>
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
            max-width: 600px;
            margin: 0 auto 30px;
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
            margin: 0 auto 20px;
            font-weight: bold;
            max-width: 600px;
            text-align: center;
        }

        .delete-link {
            display: inline-block;
            margin-top: 10px;
            color: #ff4d4d;
            text-decoration: none;
            font-weight: bold;
        }

        .delete-link:hover {
            text-decoration: underline;
        }

        .meta {
            opacity: 0.85;
            margin: 6px 0 0;
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

<h2>Edit or Delete Projects</h2>

<?php
$message = "";

// Delete project
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM projects WHERE id=$id");
    $message = "🗑️ Project deleted successfully.";
}

// Update project
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $desc = $conn->real_escape_string($_POST['description']);
    $details = $conn->real_escape_string($_POST['details']);

    $conn->query("UPDATE projects SET description='$desc', details='$details' WHERE id=$id");

    if (!empty($_FILES['new_image']['name'])) {
        $newImage = basename($_FILES['new_image']['name']);
        $tmpPath  = $_FILES['new_image']['tmp_name'];
        $targetDir = "upload/";
        $targetPath = $targetDir . $newImage;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if (move_uploaded_file($tmpPath, $targetPath)) {
            $conn->query("UPDATE projects SET image='$newImage' WHERE id=$id");
            $message = "✅ Project updated and image replaced.";
        } else {
            $message = "⚠️ Project updated, but failed to move new image.";
        }
    } else {
        if ($message === "") {
            $message = "✅ Project updated successfully.";
        }
    }
}

$result = $conn->query("SELECT * FROM projects ORDER BY id DESC");

if (!empty($message)) {
    echo "<div class='message'>" . htmlspecialchars($message) . "</div>";
}
?>

<?php if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">

            <label>Current Image:</label>
            <div class="meta">
                <?= htmlspecialchars($row['image'] ?: 'None') ?>
            </div>

            <label>Replace Image:</label>
            <input type="file" name="new_image" accept="image/*">

            <label>Document:</label>
            <div class="meta">
                <?= htmlspecialchars(!empty($row['document']) ? $row['document'] : 'None') ?>
            </div>

            <label>Short Description:</label>
            <textarea name="description" rows="3"><?= htmlspecialchars($row['description']) ?></textarea>

            <label>Detailed Information:</label>
            <textarea name="details" rows="5"><?= htmlspecialchars($row['details']) ?></textarea>

            <input type="submit" name="update" value="Update Project">
            <a href="edit.php?delete=<?= (int)$row['id'] ?>" class="delete-link" onclick="return confirm('Delete this project?');">Delete</a>
        </form>
    <?php } ?>
<?php else: ?>
    <div class="message">No projects found.</div>
<?php endif; ?>

</body>
</html>
