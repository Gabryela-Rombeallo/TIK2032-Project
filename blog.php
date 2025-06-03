<?php
/**
 * Dynamic Blog Page
 * Gabryela Rombeallo's Personal Homepage
 */

require_once 'config.php';

// Get database connection
$pdo = getConnection();

// Pagination settings
$articlesPerPage = 5;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($currentPage - 1) * $articlesPerPage;

// Get category filter
$category = isset($_GET['category']) ? sanitizeInput($_GET['category']) : '';

// Build query
$whereClause = "WHERE status = 'published'";
$params = [];

if ($category) {
    $whereClause .= " AND category = :category";
    $params[':category'] = $category;
}

// Get total articles count
$countQuery = "SELECT COUNT(*) FROM articles " . $whereClause;
$countStmt = $pdo->prepare($countQuery);
$countStmt->execute($params);
$totalArticles = $countStmt->fetchColumn();
$totalPages = ceil($totalArticles / $articlesPerPage);

// Get articles for current page
$query = "SELECT * FROM articles " . $whereClause . " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($query);

// Bind parameters
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':limit', $articlesPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$articles = $stmt->fetchAll();

// Get categories for filter
$categoriesQuery = "SELECT DISTINCT category FROM articles WHERE status = 'published' ORDER BY category";
$categoriesStmt = $pdo->query($categoriesQuery);
$categories = $categoriesStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - <?php echo SITE_TITLE; ?></title>
    <link rel="stylesheet" href="styles.css">
    <script src="javascript.js" defer></script>
    <style>
        .blog-filters {
            background-color: var(--white);
            padding: 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            box-shadow: var(--box-shadow);
            text-align: center;
        }
        
        .filter-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .filter-btn {
            padding: 0.5rem 1rem;
            background-color: var(--secondary-color);
            color: var(--text-color);
            text-decoration: none;
            border-radius: 20px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .filter-btn:hover, .filter-btn.active {
            background-color: var(--accent-color);
            color: var(--white);
            transform: translateY(-2px);
        }
        
        .article-meta {
            color: var(--light-text);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .article-category {
            background-color: var(--accent-color);
            color: var(--white);
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            display: inline-block;
            margin-right: 1rem;
        }
        
        .article-image {
            width: 100%;
            max-width: 300px;
            height: 200px;
            object-fit: cover;
            border-radius: var(--border-radius);
            margin-bottom: 1rem;
            float: right;
            margin-left: 1.5rem;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }
        
        .pagination a, .pagination span {
            padding: 0.5rem 1rem;
            background-color: var(--white);
            color: var(--text-color);
            text-decoration: none;
            border-radius: 5px;
            border: 2px solid var(--secondary-color);
            transition: all 0.3s ease;
        }
        
        .pagination a:hover {
            background-color: var(--accent-color);
            color: var(--white);
        }
        
        .pagination .current {
            background-color: var(--accent-color);
            color: var(--white);
        }
        
        .no-articles {
            text-align: center;
            padding: 3rem;
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        
        @media (max-width: 768px) {
            .article-image {
                float: none;
                margin-left: 0;
                margin-bottom: 1rem;
            }
        }
    </style>
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
            </ul>
        </nav>
    </header>
    
    <main>
        <h2>Latest Articles</h2>
        
        <!-- Category Filters -->
        <div class="blog-filters">
            <h3>Filter by Category</h3>
            <div class="filter-buttons">
                <a href="blog.php" class="filter-btn <?php echo empty($category) ? 'active' : ''; ?>">
                    All Articles
                </a>
                <?php foreach ($categories as $cat): ?>
                    <a href="blog.php?category=<?php echo urlencode($cat['category']); ?>" 
                       class="filter-btn <?php echo $category === $cat['category'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($cat['category']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Articles List -->
        <?php if (empty($articles)): ?>
            <div class="no-articles">
                <h3>No articles found</h3>
                <p>There are no articles in this category yet. Check back later!</p>
            </div>
        <?php else: ?>
            <?php foreach ($articles as $article): ?>
                <article>
                    <?php if ($article['image_url']): ?>
                        <img src="<?php echo htmlspecialchars($article['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($article['title']); ?>" 
                             class="article-image">
                    <?php endif; ?>
                    
                    <div class="article-meta">
                        <span class="article-category"><?php echo htmlspecialchars($article['category']); ?></span>
                        <span>By <?php echo htmlspecialchars($article['author']); ?></span>
                        <span>• <?php echo formatDate($article['created_at']); ?></span>
                    </div>
                    
                    <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                    
                    <p><?php echo nl2br(htmlspecialchars(truncateText($article['content'], 300))); ?></p>
                    
                    <a href="article.php?id=<?php echo $article['id']; ?>" 
                       style="color: var(--accent-color); text-decoration: none; font-weight: bold;">
                        Read More →
                    </a>
                    
                    <div style="clear: both;"></div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($currentPage > 1): ?>
                    <a href="blog.php?page=<?php echo $currentPage - 1; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?>">
                        ← Previous
                    </a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == $currentPage): ?>
                        <span class="current"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="blog.php?page=<?php echo $i; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($currentPage < $totalPages): ?>
                    <a href="blog.php?page=<?php echo $currentPage + 1; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?>">
                        Next →
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <!-- Stats -->
        <div style="text-align: center; margin-top: 2rem; color: var(--light-text); font-size: 0.9rem;">
            Showing <?php echo count($articles); ?> of <?php echo $totalArticles; ?> articles
            <?php if ($category): ?>
                in category "<?php echo htmlspecialchars($category); ?>"
            <?php endif; ?>
        </div>
    </main>
    
    <footer>
        <p>&copy; GABRYELA'S PERSONAL HOMEPAGE</p>
    </footer>
</body>
</html>