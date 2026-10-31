<?php
require_once '../db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // ഡാറ്റാബേസിൽ നിന്ന് ഡിലീറ്റ് ചെയ്യുന്നു
    $conn->query("DELETE FROM products WHERE id = $id");
    
    header("Location: dashboard.php?msg=Product deleted successfully");
    exit();
}
?>