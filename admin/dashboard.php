<?php
require_once '../db.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Golden Aura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; background-color: #31583b; color: white; padding-top: 20px; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 10px 20px; }
        .sidebar a:hover, .sidebar a.active { background-color: #24422c; }
        .main-content { padding: 30px; }
        .table img { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <h4 class="text-center mb-4">Golden Aura Admin</h4>
                <a href="dashboard.php" class="<?php echo (!isset($_GET['category']) || $_GET['category'] == 'all') ? 'active' : ''; ?>">All Products</a>
                <a href="dashboard.php?category=plants" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'plants') ? 'active' : ''; ?>">Plants</a>
                <a href="dashboard.php?category=vegetables" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'vegetables') ? 'active' : ''; ?>">Vegetables & Tubers</a>
                <a href="dashboard.php?category=spices" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'spices') ? 'active' : ''; ?>">Spices</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <?php
                // URL-ൽ ഏത് category ആണ് ഉള്ളതെന്ന് നോക്കുന്നു
                $category = isset($_GET['category']) ? $_GET['category'] : 'all';
                $page_title = "All Products";
                
                if ($category == 'plants') $page_title = "Plants";
                if ($category == 'vegetables') $page_title = "Vegetables & Tubers";
                if ($category == 'spices') $page_title = "Spices";
                ?>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><?php echo $page_title; ?> List</h2>
                    
                    <!-- ഇവിടെയാണ് പ്രധാന മാറ്റം: Vegetables-ന് add_vegetable.php, മറ്റുള്ളവയ്ക്ക് add_product.php -->
                    <?php if($category == 'vegetables'): ?>
                        <a href="add_vegetable.php" class="btn btn-success">+ Add New Vegetable</a>
                    <?php else: ?>
                        <a href="add_product.php?category=<?php echo $category; ?>" class="btn btn-success">+ Add New <?php echo ucfirst($category); ?></a>
                    <?php endif; ?>
                </div>

                <?php if(isset($_GET['msg'])): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Subcategory</th>
                                    <th>Price (₹)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($category == 'all') {
                                    $sql = "SELECT * FROM products ORDER BY id DESC";
                                } else {
                                    $sql = "SELECT * FROM products WHERE category = '$category' ORDER BY id DESC";
                                }
                                
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        $subcat = isset($row['subcategory']) ? $row['subcategory'] : '-';
                                        echo "<tr>
                                            <td>{$row['id']}</td>
                                            <td><img src='../frontend/images/{$row['image_url']}' alt='img'></td>
                                            <td>{$row['name']}</td>
                                            <td>{$row['category']}</td>
                                            <td>{$subcat}</td>
                                            <td>₹{$row['price']}</td>
                                            <td>
    <a href='edit_product.php?id={$row['id']}' class='btn btn-sm btn-warning'>Edit</a>
    <a href='delete_product.php?id={$row['id']}&category={$category}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\");'>Delete</a>
</td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center'>No products found in this category.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>