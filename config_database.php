<?php
// config/database.php - File konfigurasi database
<?php
$servername = "localhost";
$username = "root";  // Username default XAMPP
$password = "";      // Password default XAMPP (kosong)
$dbname = "gabryela_blog";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

// blog.php - File utama blog (menggantikan blog.html)
<?php
require_once 'config/database.php';

// Ambil semua artikel dari database
try {
    $stmt = $pdo->prepare("SELECT * FROM articles ORDER BY created_at DESC");
    $stmt->execute();
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $articles = [];
    $error_message = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Dynamic Content</title>
    <link rel="stylesheet" href="styles.css">
    <script src="javascript.js" defer></script>
</head>
<body>
    <header>
        <h1>Blog</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="gallery.html">Gallery</a></li>
                <li><a href="blog.php" class="active">Blog</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="admin.php">Admin</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <h2>Latest Articles</h2>
        
        <?php if (isset($error_message)): ?>
            <div class="error-message">
                <p><?php echo htmlspecialchars($error_message); ?></p>
            </div>
        <?php endif; ?>

        <?php if (empty($articles)): ?>
            <div class="no-articles">
                <p>Belum ada artikel yang tersedia. <a href="admin.php">Tambah artikel pertama</a></p>
            </div>
        <?php else: ?>
            <?php foreach ($articles as $article): ?>
                <article>
                    <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                    <div class="article-meta">
                        <span class="category"><?php echo htmlspecialchars($article['category']); ?></span>
                        <span class="date"><?php echo date('d M Y', strtotime($article['created_at'])); ?></span>
                    </div>
                    <p><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
                    <?php if (!empty($article['author'])): ?>
                        <div class="author">
                            <small>By: <?php echo htmlspecialchars($article['author']); ?></small>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
    
    <footer>
        <p>&copy; GABRYELA'S PERSONAL HOMEPAGE</p>
    </footer>
</body>
</html>

// admin.php - Panel admin untuk mengelola artikel
<?php
require_once 'config/database.php';

// Handle form submission
if ($_POST) {
    if (isset($_POST['add_article'])) {
        // Add new article
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $category = trim($_POST['category']);
        $author = trim($_POST['author']);
        
        if (!empty($title) && !empty($content)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO articles (title, content, category, author) VALUES (?, ?, ?, ?)");
                $stmt->execute([$title, $content, $category, $author]);
                $success_message = "Artikel berhasil ditambahkan!";
            } catch(PDOException $e) {
                $error_message = "Error: " . $e->getMessage();
            }
        } else {
            $error_message = "Title dan Content harus diisi!";
        }
    } elseif (isset($_POST['delete_article'])) {
        // Delete article
        $article_id = (int)$_POST['article_id'];
        try {
            $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
            $stmt->execute([$article_id]);
            $success_message = "Artikel berhasil dihapus!";
        } catch(PDOException $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    }
}

// Get all articles for management
try {
    $stmt = $pdo->prepare("SELECT * FROM articles ORDER BY created_at DESC");
    $stmt->execute();
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $articles = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Blog Management</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .admin-panel {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-section, .articles-section {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(137, 207, 240, 0.4);
        }
        .article-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            background: #f9f9f9;
        }
        .article-actions {
            margin-top: 10px;
        }
        .btn-delete {
            background: #ff6b6b;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-delete:hover {
            background: #ff5252;
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Panel</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="gallery.html">Gallery</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="admin.php" class="active">Admin</a></li>
            </ul>
        </nav>
    </header>

    <div class="admin-panel">
        <?php if (isset($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Form untuk menambah artikel -->
        <div class="form-section">
            <h2>Tambah Artikel Baru</h2>
            <form method="POST">
                <label for="title">Judul Artikel:</label>
                <input type="text" id="title" name="title" required>

                <label for="category">Kategori:</label>
                <select id="category" name="category">
                    <option value="Teknologi">Teknologi</option>
                    <option value="Seni">Seni</option>
                    <option value="Olahraga">Olahraga</option>
                    <option value="Lifestyle">Lifestyle</option>
                    <option value="Pendidikan">Pendidikan</option>
                    <option value="Travel">Travel</option>
                    <option value="Lainnya">Lainnya</option>
                </select>

                <label for="author">Penulis:</label>
                <input type="text" id="author" name="author" placeholder="Nama penulis (opsional)">

                <label for="content">Konten Artikel:</label>
                <textarea id="content" name="content" rows="10" required placeholder="Tulis konten artikel di sini..."></textarea>

                <button type="submit" name="add_article">Tambah Artikel</button>
            </form>
        </div>

        <!-- Daftar artikel yang sudah ada -->
        <div class="articles-section">
            <h2>Kelola Artikel (<?php echo count($articles); ?> artikel)</h2>
            
            <?php if (empty($articles)): ?>
                <p>Belum ada artikel.</p>
            <?php else: ?>
                <?php foreach ($articles as $article): ?>
                    <div class="article-item">
                        <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                        <p><strong>Kategori:</strong> <?php echo htmlspecialchars($article['category']); ?></p>
                        <p><strong>Penulis:</strong> <?php echo htmlspecialchars($article['author'] ?: 'Anonymous'); ?></p>
                        <p><strong>Tanggal:</strong> <?php echo date('d M Y H:i', strtotime($article['created_at'])); ?></p>
                        <p><?php echo substr(htmlspecialchars($article['content']), 0, 150) . '...'; ?></p>
                        
                        <div class="article-actions">
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                                <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                                <button type="submit" name="delete_article" class="btn-delete">Hapus</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>&copy; GABRYELA'S PERSONAL HOMEPAGE - Admin Panel</p>
    </footer>
</body>
</html>

// install_database.php - Script untuk membuat database dan tabel
<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
    // Connect tanpa database name dulu
    $pdo = new PDO("mysql:host=$servername", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS gabryela_blog");
    echo "Database 'gabryela_blog' berhasil dibuat!<br>";
    
    // Connect to the new database
    $pdo = new PDO("mysql:host=$servername;dbname=gabryela_blog", $username, $password);
    
    // Create articles table
    $sql = "CREATE TABLE IF NOT EXISTS articles (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        category VARCHAR(100) DEFAULT 'Lainnya',
        author VARCHAR(100) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    echo "Tabel 'articles' berhasil dibuat!<br>";
    
    // Insert sample data
    $sample_articles = [
        [
            'title' => 'Perkembangan AI di Era Modern',
            'content' => 'Artificial Intelligence (AI) telah mengalami perkembangan yang sangat pesat dalam beberapa tahun terakhir. Teknologi ini tidak hanya mengubah cara kita bekerja, tetapi juga cara kita berinteraksi dengan dunia digital. Dari asisten virtual hingga sistem rekomendasi, AI telah menjadi bagian integral dari kehidupan sehari-hari kita.',
            'category' => 'Teknologi',
            'author' => 'Gabryela'
        ],
        [
            'title' => 'Seni Digital: Masa Depan Kreativitas',
            'content' => 'Seni digital telah membuka pintu baru bagi para seniman untuk mengekspresikan kreativitas mereka. Dengan berbagai tools dan software yang tersedia, seniman dapat menciptakan karya yang menakjubkan dengan lebih mudah dan efisien. Teknologi seperti digital painting, 3D modeling, dan augmented reality telah merevolusi dunia seni.',
            'category' => 'Seni',
            'author' => 'Gabryela'
        ],
        [
            'title' => 'Manfaat Olahraga Rutin untuk Kesehatan Mental',
            'content' => 'Olahraga tidak hanya bermanfaat untuk kesehatan fisik, tetapi juga memiliki dampak positif yang luar biasa pada kesehatan mental. Aktivitas fisik secara teratur dapat membantu mengurangi stress, meningkatkan mood, dan meningkatkan kualitas tidur. Mari kita jaga kesehatan tubuh dan pikiran dengan berolahraga secara konsisten.',
            'category' => 'Olahraga',
            'author' => 'Gabryela'
        ]
    ];
    
    foreach ($sample_articles as $article) {
        $stmt = $pdo->prepare("INSERT INTO articles (title, content, category, author) VALUES (?, ?, ?, ?)");
        $stmt->execute([$article['title'], $article['content'], $article['category'], $article['author']]);
    }
    
    echo "Sample data berhasil ditambahkan!<br>";
    echo "<br><strong>Setup selesai!</strong><br>";
    echo "Silakan akses <a href='blog.php'>blog.php</a> untuk melihat blog<br>";
    echo "Atau akses <a href='admin.php'>admin.php</a> untuk mengelola artikel";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>