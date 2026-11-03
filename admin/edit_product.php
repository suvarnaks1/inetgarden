<?php
require_once '../db.php';

// URL-ൽ നിന്ന് പ്രൊഡക്റ്റ് ID എടുക്കുന്നു
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found!");
}

// ഫോം സബ്മിറ്റ് ചെയ്യുമ്പോൾ വിവരങ്ങൾ അപ്ഡേറ്റ് ചെയ്യുന്നു
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'] ?? '';
    $price = $_POST['price'];
    $description = $_POST['description'] ?? '';
    
    // ഇമേജ് മാറ്റണോ എന്ന് ചെക്ക് ചെയ്യുന്നു
    $image_name = $product['image_url'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../frontend/images/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }

    // വെജിറ്റബിളിന് മാത്രമുള്ള ഫീൽഡുകൾ (ഉണ്ടെങ്കിൽ മാത്രം)
    $net_quantity = $_POST['net_quantity'] ?? $product['net_quantity'];
    $mrp = $_POST['mrp'] ?? $product['mrp'];
    $offer = $_POST['offer'] ?? $product['offer'];
    $imported = $_POST['imported'] ?? $product['imported'];
    $product_type = $_POST['product_type'] ?? $product['product_type'];
    $good_for = $_POST['good_for'] ?? $product['good_for'];
    $top_nutrients = $_POST['top_nutrients'] ?? $product['top_nutrients'];
    $dietary_preference = $_POST['dietary_preference'] ?? $product['dietary_preference'];

    // പ്ലാന്റിന് മാത്രമുള്ള ഫീൽഡുകൾ
    $pot_size = $_POST['pot_size'] ?? $product['pot_size'];
    $sunlight = $_POST['sunlight'] ?? $product['sunlight'];
    $benefits = $_POST['benefits'] ?? $product['benefits'];

    $stmt = $conn->prepare("UPDATE products SET 
        name=?, category=?, subcategory=?, price=?, description=?, 
        net_quantity=?, mrp=?, offer=?, imported=?, product_type=?, good_for=?, top_nutrients=?, dietary_preference=?, 
        pot_size=?, sunlight=?, benefits=?, image_url=? 
        WHERE id=?");

    $stmt->bind_param("sssdsssssssssssssi", $name, $category, $subcategory, $price, $description, $net_quantity, $mrp, $offer, $imported, $product_type, $good_for, $top_nutrients, $dietary_preference, $pot_size, $sunlight, $benefits, $image_name, $id);

    if ($stmt->execute()) {
        header("Location: dashboard.php?category=$category&msg=Product updated successfully");
        exit();
    } else {
        $error = "Error updating product: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product | Admin</title>
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
                <h2>Edit Product (ID: <?php echo $id; ?>)</h2>
                
                <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                
                <div class="card shadow-sm mt-3" style="max-width: 700px;">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            
                            <div class="mb-3">
                                <label>Category</label>
                                <select name="category" class="form-control" required>
                                    <option value="plants" <?php if($product['category'] == 'plants') echo 'selected'; ?>>Plants</option>
                                    <option value="vegetables" <?php if($product['category'] == 'vegetables') echo 'selected'; ?>>Vegetable & Tubers</option>
                                    <option value="spices" <?php if($product['category'] == 'spices') echo 'selected'; ?>>Spices</option>
                                </select>
                            </div>

                            <!-- Vegetables-ന് മാത്രം Sub-Category കാണിക്കുക -->
                            <?php if($product['category'] == 'vegetables'): ?>
                            <div class="mb-3">
                                <label>Sub-Category</label>
                                <select name="subcategory" class="form-control" required>
                                    <option value="Vegetables" <?php if($product['subcategory'] == 'Vegetables') echo 'selected'; ?>>Vegetables</option>
                                    <option value="Tubers" <?php if($product['subcategory'] == 'Tubers') echo 'selected'; ?>>Tubers</option>
                                </select>
                            </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label>Product Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label>Price (₹)</label>
                                <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
                            </div>

                            <!-- Vegetables-ന് മാത്രം കൂടുതൽ ഫീൽഡുകൾ -->
                            <?php if($product['category'] == 'vegetables'): ?>
                                <div class="mb-3">
                                    <label>Net Quantity</label>
                                    <input type="text" name="net_quantity" class="form-control" value="<?php echo htmlspecialchars($product['net_quantity'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label>MRP (₹)</label>
                                    <input type="number" step="0.01" name="mrp" class="form-control" value="<?php echo $product['mrp'] ?? ''; ?>">
                                </div>
                                <div class="mb-3">
                                    <label>Offer</label>
                                    <input type="text" name="offer" class="form-control" value="<?php echo htmlspecialchars($product['offer'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label>Imported</label>
                                    <select name="imported" class="form-control">
                                        <option value="No" <?php if($product['imported'] == 'No') echo 'selected'; ?>>No</option>
                                        <option value="Yes" <?php if($product['imported'] == 'Yes') echo 'selected'; ?>>Yes</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Product Type</label>
                                    <input type="text" name="product_type" class="form-control" value="<?php echo htmlspecialchars($product['product_type'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label>Good For</label>
                                    <input type="text" name="good_for" class="form-control" value="<?php echo htmlspecialchars($product['good_for'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label>Top Nutrients</label>
                                    <input type="text" name="top_nutrients" class="form-control" value="<?php echo htmlspecialchars($product['top_nutrients'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label>Dietary Preference</label>
                                    <input type="text" name="dietary_preference" class="form-control" value="<?php echo htmlspecialchars($product['dietary_preference'] ?? ''); ?>">
                                </div>
                            <?php else: ?>
                                <!-- Plants & Spices-ന് വേണ്ടിയുള്ള ഫീൽഡുകൾ -->
                                <div class="mb-3">
                                    <label>Plant & Pot Size</label>
                                    <input type="text" name="pot_size" class="form-control" value="<?php echo htmlspecialchars($product['pot_size'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label>Sunlight Requirements</label>
                                    <select name="sunlight" class="form-control">
                                        <option value="Bright indirect" <?php if($product['sunlight'] == 'Bright indirect') echo 'selected'; ?>>Bright indirect</option>
                                        <option value="Full sun" <?php if($product['sunlight'] == 'Full sun') echo 'selected'; ?>>Full sun</option>
                                        <option value="Partial shade" <?php if($product['sunlight'] == 'Partial shade') echo 'selected'; ?>>Partial shade</option>
                                        <option value="Low light" <?php if($product['sunlight'] == 'Low light') echo 'selected'; ?>>Low light</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Benefits</label>
                                    <textarea name="benefits" class="form-control" rows="2"><?php echo htmlspecialchars($product['benefits'] ?? ''); ?></textarea>
                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label>Description (Optional)</label>
                                <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Image Upload (Leave empty to keep current)</label>
                                <input type="file" name="image" class="form-control">
                                <small>Current Image: <?php echo $product['image_url']; ?></small>
                            </div>
                            
                            <button type="submit" class="btn btn-success">Update Product</button>
                            <a href="dashboard.php?category=<?php echo $product['category']; ?>" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>