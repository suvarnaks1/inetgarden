<?php
require_once '../../db.php'; 

// URL-ൽ നിന്ന് പ്രൊഡക്റ്റ് ID എടുക്കുന്നു
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);
$product = $result->fetch_assoc();

// പ്രൊഡക്റ്റ് കണ്ടെത്തിയില്ലെങ്കിൽ
if (!$product) {
    die("Product not found!");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($product['name']); ?> | Golden Aura</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <header id="site-header"></header>
  <main>
    <div class="container">
      <div class="crumbs">
        <a href="index.html">Home</a> /
        <a href="<?php echo $product['category'] == 'plants' ? 'plants.php' : ($product['category'] == 'vegetables' ? 'vegetable.php' : 'spices.php'); ?>">
            <?php echo ucfirst($product['category']); ?>
        </a> /
        <span><?php echo htmlspecialchars($product['name']); ?></span>
      </div>

      <!-- പ്രൊഡക്റ്റ് ഡീറ്റെയിൽസ് -->
      <div class="detail">
        <div class="detail-img">
            <img src="../images/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>

        <div class="detail-info">
          <h1><?php echo htmlspecialchars($product['name']); ?></h1>
          <div class="price">₹<?php echo number_format($product['price'], 2); ?></div>
          <p><?php echo htmlspecialchars($product['description'] ?? 'Beautiful plant from Golden Aura.'); ?></p>

          <ul class="meta">
            <li><b>Category</b> <?php echo ucfirst($product['category']); ?></li>
            <li><b>Light</b> Bright indirect (Demo)</li>
            <li><b>Watering</b> Keep evenly moist (Demo)</li>
          </ul>

          <div class="qty">
            <button id="minus">−</button>
            <input id="q" type="number" value="1" min="1" step="1">
            <button id="plus">+</button>
          </div>

          <button class="btn btn-fill" onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
        </div>
      </div>
    </div>

    <!-- Related Products (അതേ കാറ്റഗറിയിലുള്ള മറ്റ് പ്ലാന്റുകൾ) -->
    <section class="section">
      <div class="container">
        <h2 class="section-title">You May Also Like</h2>
        <div class="grid">
            <?php
            $cat = $product['category'];
            $rel_sql = "SELECT * FROM products WHERE category = '$cat' AND id != $id LIMIT 4";
            $rel_result = $conn->query($rel_sql);
            
            if ($rel_result->num_rows > 0) {
                while($rel = $rel_result->fetch_assoc()) {
                    ?>
                    <div class="product-card">
                        <div class="card-img-wrap">
                            <div class="card-img">
                                <img src="../images/<?php echo htmlspecialchars($rel['image_url']); ?>" alt="<?php echo htmlspecialchars($rel['name']); ?>">
                            </div>
                        </div>
                        <h3><?php echo htmlspecialchars($rel['name']); ?></h3>
                        <div class="price">₹<?php echo number_format($rel['price'], 2); ?></div>
                        <div class="card-actions">
                            <button class="btn btn-fill" onclick="addToCart(<?php echo $rel['id']; ?>)">Add to Cart</button>
                            <a class="btn btn-line" href="product.php?id=<?php echo $rel['id']; ?>">Learn More</a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No related products found.</p>";
            }
            ?>
        </div>
      </div>
    </section>
  </main>

  <footer id="site-footer"></footer>

  <script src="../js/main.js"></script>
  
  <script>
    // Quantity increment/decrement
    const quantity = document.getElementById("q");
    document.getElementById("minus").addEventListener("click", () => {
        quantity.value = Math.max(1, (Number(quantity.value) || 1) - 1);
    });
    document.getElementById("plus").addEventListener("click", () => {
        quantity.value = Math.max(1, Number(quantity.value) || 1) + 1;
    });
  </script>
</body>
</html>