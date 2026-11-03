<?php
require_once '../db.php';

$preselected_category = isset($_GET['category']) ? $_GET['category'] : 'plants';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $description = $_POST['description'] ?? '';
    $pot_size = $_POST['pot_size'] ?? '';
    $sunlight = $_POST['sunlight'] ?? '';
    $benefits = $_POST['benefits'] ?? '';
    
    $target_dir = "../frontend/images/";
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO products (name, category, price, description, pot_size, sunlight, benefits, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsssss", $name, $category, $price, $description, $pot_size, $sunlight, $benefits, $image_name);
        
        if ($stmt->execute()) {
            header("Location: dashboard.php?category=$category&msg=Product added successfully");
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
                <h4 class="text-center mb-4">Golden Aura Admin</h4>
                <a href="dashboard.php">All Products</a>
                <a href="dashboard.php?category=plants">Plants</a>
                <a href="dashboard.php?category=vegetables">Vegetables & Tubers</a>
                <a href="dashboard.php?category=spices">Spices</a>
            </div>

            <div class="col-md-10 p-4">
                <h2>Add New Product</h2>
                
                <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                
                <div class="card shadow-sm mt-3" style="max-width: 700px;">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            
                            <!-- പ്ലാന്റുകൾക്കും സ്പൈസസിനും മാത്രം Category തിരഞ്ഞെടുക്കാം -->
                            <div class="mb-3">
                                <label>Category</label>
                                <select name="category" class="form-control" required>
                                    <option value="plants" <?php if($preselected_category == 'plants') echo 'selected'; ?>>Plants</option>
                                    <option value="spices" <?php if($preselected_category == 'spices') echo 'selected'; ?>>Spices</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Product Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Price (₹)</label>
                                <input type="number" step="0.01" name="price" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Plant & Pot Size</label>
                                <input type="text" name="pot_size" class="form-control" placeholder="e.g., 4 inch pot">
                            </div>
                            
                            <div class="mb-3">
                                <label>Sunlight Requirements</label>
                                <select name="sunlight" class="form-control">
                                    <option value="Bright indirect">Bright indirect</option>
                                    <option value="Full sun">Full sun</option>
                                    <option value="Partial shade">Partial shade</option>
                                    <option value="Low light">Low light</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label>Benefits</label>
                                <textarea name="benefits" class="form-control" rows="2" placeholder="e.g., Air purification"></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label>Description (Optional)</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Image Upload</label>
                                <input type="file" name="image" class="form-control" required>
                            </div>
                            
                            <button type="submit" class="btn btn-success">Save Product</button>
                            <a href="dashboard.php?category=<?php echo $preselected_category; ?>" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>