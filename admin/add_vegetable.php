<?php
require_once '../db.php';

// കാറ്റഗറി എപ്പോഴും vegetables ആയിരിക്കും
$category = 'vegetables';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $subcategory = $_POST['subcategory'] ?? '';
    $price = $_POST['price'];
    $description = $_POST['description'] ?? '';
    
    // വെജിറ്റബിളിന് മാത്രമുള്ള ഫീൽഡുകൾ
    $net_quantity = $_POST['net_quantity'] ?? '';
    $mrp = $_POST['mrp'] ?? '';
    $offer = $_POST['offer'] ?? '';
    $imported = $_POST['imported'] ?? '';
    $product_type = $_POST['product_type'] ?? '';
    $good_for = $_POST['good_for'] ?? '';
    $top_nutrients = $_POST['top_nutrients'] ?? '';
    $dietary_preference = $_POST['dietary_preference'] ?? '';
    
    $target_dir = "../frontend/images/";
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO products 
            (name, category, subcategory, price, description, net_quantity, mrp, offer, imported, product_type, good_for, top_nutrients, dietary_preference, image_url) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
        $stmt->bind_param("sssdssssssssss", $name, $category, $subcategory, $price, $description, $net_quantity, $mrp, $offer, $imported, $product_type, $good_for, $top_nutrients, $dietary_preference, $image_name);
        
        if ($stmt->execute()) {
            header("Location: dashboard.php?category=vegetables&msg=Vegetable added successfully");
            exit();
        } else {
            $error = "Error adding product: " . $conn->error;
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
    <title>Add Vegetable | Admin</title>
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
                <h2>Add New Vegetable / Tuber</h2>
                
                <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                
                <div class="card shadow-sm mt-3" style="max-width: 700px;">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            
                            <div class="mb-3">
                                <label>Sub-Category</label>
                                <select name="subcategory" class="form-control" required>
                                    <option value="Vegetables">Vegetables</option>
                                    <option value="Tubers">Tubers</option>
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
                                <label>Net Quantity</label>
                                <input type="text" name="net_quantity" class="form-control" placeholder="e.g., 500g, 1kg">
                            </div>
                            
                            <div class="mb-3">
                                <label>MRP (₹)</label>
                                <input type="number" step="0.01" name="mrp" class="form-control">
                            </div>
                            
                            <div class="mb-3">
                                <label>Offer</label>
                                <input type="text" name="offer" class="form-control" placeholder="e.g., 10% OFF">
                            </div>
                            
                            <div class="mb-3">
                                <label>Imported</label>
                                <select name="imported" class="form-control">
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label>Product Type</label>
                                <input type="text" name="product_type" class="form-control" placeholder="e.g., Vegetable">
                            </div>
                            
                            <div class="mb-3">
                                <label>Good For</label>
                                <input type="text" name="good_for" class="form-control" placeholder="e.g., Gut Health">
                            </div>
                            
                            <div class="mb-3">
                                <label>Top Nutrients</label>
                                <input type="text" name="top_nutrients" class="form-control" placeholder="e.g., Fibre Rich">
                            </div>
                            
                            <div class="mb-3">
                                <label>Dietary Preference</label>
                                <input type="text" name="dietary_preference" class="form-control" placeholder="e.g., Veg">
                            </div>

                            <div class="mb-3">
                                <label>Description (Optional)</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Image Upload</label>
                                <input type="file" name="image" class="form-control" required>
                            </div>
                            
                            <button type="submit" class="btn btn-success">Save Vegetable</button>
                            <a href="dashboard.php?category=vegetables" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>