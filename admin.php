<?php
// admin.php - Dashboard admin untuk melihat pesan
require_once 'config.php';

// Simple authentication (untuk contoh, bisa dikembangkan lebih lanjut)
session_start();
$admin_password = 'admin123'; // Ganti dengan password yang aman

if (!isset($_SESSION['admin_logged_in'])) {
    if (isset($_POST['password']) && $_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        // Tampilkan form login
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin Login</title>
            <style>
                body { font-family: Arial, sans-serif; max-width: 400px; margin: 100px auto; padding: 20px; }
                input, button { width: 100%; padding: 10px; margin: 10px 0; }
                button { background: #007cba; color: white; border: none; cursor: pointer; }
                button:hover { background: #005a87; }
            </style>
        </head>
        <body>
            <h2>Admin Login</h2>
            <form method="POST">
                <input type="password" name="password" placeholder="Enter admin password" required>
                <button type="submit">Login</button>
            </form>
        </body>
        </html>
        <?php
        exit;
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// Handle mark as read
if (isset($_POST['mark_read'])) {
    $id = (int)$_POST['contact_id'];
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("UPDATE contacts SET status = 'read' WHERE id = ?");
    $stmt->execute([$id]);
}

// Handle delete
if (isset($_POST['delete'])) {
    $id = (int)$_POST['contact_id'];
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
    $stmt->execute([$id]);
}

// Ambil semua pesan
$pdo = getDBConnection();
$stmt = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC");
$contacts = $stmt->fetchAll();

// Hitung statistik
$total_messages = count($contacts);
$unread_messages = count(array_filter($contacts, function($contact) {
    return $contact['status'] === 'unread';
}));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Contact Messages</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: #333; color: white; padding: 1rem; display: flex; justify-content: space-between; align-items: center; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .stat-number { font-size: 2rem; font-weight: bold; color: #007cba; }
        .stat-label { color: #666; margin-top: 5px; }
        .messages-table { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; font-weight: 600; }
        .status-unread { background: #fff3cd; }
        .status-read { background: #d1ecf1; }
        .message-preview { max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .actions { display: flex; gap: 5px; }
        .btn { padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; font-size: 12px; }
        .btn-read { background: #28a745; color: white; }
        .btn-delete { background: #dc3545; color: white; }
        .btn:hover { opacity: 0.8; }
        .logout-btn { background: #dc3545; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; }
        .no-messages { text-align: center; padding: 40px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Admin Dashboard - Contact Messages</h1>
        <a href="?logout=1" class="logout-btn">Logout</a>
    </div>
    
    <div class="container">
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_messages; ?></div>
                <div class="stat-label">Total Messages</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $unread_messages; ?></div>
                <div class="stat-label">Unread Messages</div>
            </div>
        </div>
        
        <div class="messages-table">
            <?php if (empty($contacts)): ?>
                <div class="no-messages">
                    <h3>No messages yet</h3>
                    <p>Contact messages will appear here when someone submits the contact form.</p>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contacts as $contact): ?>
                            <tr class="<?php echo $contact['status'] === 'unread' ? 'status-unread' : 'status-read'; ?>">
                                <td><?php echo $contact['id']; ?></td>
                                <td><?php echo htmlspecialchars($contact['name']); ?></td>
                                <td><?php echo htmlspecialchars($contact['email']); ?></td>
                                <td class="message-preview" title="<?php echo htmlspecialchars($contact['message']); ?>">
                                    <?php echo htmlspecialchars($contact['message']); ?>
                                </td>
                                <td><?php echo date('Y-m-d H:i', strtotime($contact['created_at'])); ?></td>
                                <td>
                                    <span style="padding: 2px 8px; border-radius: 3px; font-size: 11px; 
                                          background: <?php echo $contact['status'] === 'unread' ? '#ffeaa7' : '#74b9ff'; ?>; 
                                          color: <?php echo $contact['status'] === 'unread' ? '#8b4513' : 'white'; ?>;">
                                        <?php echo ucfirst($contact['status']); ?>
                                    </span>
                                </td>
                                <td class="actions">
                                    <?php if ($contact['status'] === 'unread'): ?>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                                            <button type="submit" name="mark_read" class="btn btn-read">Mark Read</button>
                                        </form>
                                    <?php endif; ?>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this message?')">
                                        <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                                        <button type="submit" name="delete" class="btn btn-delete">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>