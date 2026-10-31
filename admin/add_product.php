<?php
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    
    // ഇമേജ് അപ്‌ലോഡ് ചെയ്യുന്നു
    $target_dir = "../frontend/images/";
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO products (name, category, price, image_url) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $name, $category, $price, $image_name);
        
        if ($stmt->execute()) {
            header("Location: dashboard.php?msg=Product added successfully");
            exit();
        } else {
            $error = "Error adding product.";
        }
    } else {
        $error = "Sorry, there was an error uploading your file.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; background-color: #31583b; color: white; padding-top: 20px; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 10px 20px; }
        .sidebar a:hover { background-color: #24422c; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar">
                <h4 class="text-center mb-4">Golden Aura</h4>
                <a href="dashboard.php">Dashboard (Plants)</a>
                <a href="add_product.php">Add Product</a>
            </div>

            <div class="col-md-10 p-4">
                <h2>Add New Plant</h2>
                <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                
                <div class="card shadow-sm mt-3" style="max-width: 600px;">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label>Plant Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Category</label>
                                <select name="category" class="form-control" required>
                                    <option value="plants">Plants</option>
                                    <option value="vegetables">Vegetable & Tubers</option>
                                    <option value="spices">Spices</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Price (₹)</label>
                                <input type="number" step="0.01" name="price" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Image Upload</label>
                                <input type="file" name="image" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success">Save Product</button>
                            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>