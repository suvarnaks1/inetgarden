<?php
require_once '../../db.php'; 

$sql = "SELECT * FROM products WHERE category = 'plants'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Plants | Golden Aura</title>
  <link rel="stylesheet" href="../css/style.css">
  <!-- Bootstrap (ഉണ്ടെങ്കിൽ മാത്രം, ഇല്ലെങ്കിൽ ഒഴിവാക്കാം) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <header id="site-header"></header>
  <main>
    <section class="page-banner">
      <div>
        <h1>Our Plants</h1>
        <p>Exotic &amp; rare flowering and foliage plants</p>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <div class="toolbar">
          <div class="chips"></div>
          <div class="tools">
            <input type="search" id="search" placeholder="Search plants…">
            <select id="sort">
              <option value="default">Sort: Featured</option>
              <option value="az">Name: A–Z</option>
              <option value="low">Price: Low to High</option>
              <option value="high">Price: High to Low</option>
            </select>
          </div>
        </div>

        <!-- ഇവിടെയാണ് പ്ലാന്റുകൾ കാണിക്കുന്നത്. main.js-ലെ initListing() ഇതിൽ ഇടപെടില്ല -->
        <div class="grid" id="product-grid-php">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    ?>
                    <div class="product-card php-card">
                        <img src="../images/<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p class="price-text">₹<?php echo $row['price']; ?></p>
                        <!-- addToCart എന്നത് main.js-ൽ ഉള്ള ഒരു ഗ്ലോബൽ ഫങ്ക്ഷൻ ആണെങ്കിൽ ഇത് പ്രവർത്തിക്കും -->
                        <button onclick="addToCart(<?php echo $row['id']; ?>)">Add to Cart</button>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No plants available right now.</p>";
            }
            ?>
        </div>
      </div>
    </section>
  </main>

  <footer id="site-footer"></footer>

  <!-- main.js ലോഡ് ചെയ്യുന്നു -->
  <script src="../js/main.js"></script>
  
  <script>
    // സെർച്ച് ഫങ്ക്ഷണാലിറ്റി
    document.getElementById('search').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let cards = document.querySelectorAll('.php-card'); // main.js-ന്റെ .card അല്ല, നമ്മുടെ .php-card മാത്രം
        cards.forEach(card => {
            let title = card.querySelector('h3').textContent.toLowerCase();
            card.style.display = title.includes(filter) ? "" : "none";
        });
    });

    // സോർട്ട് ഫങ്ക്ഷണാലിറ്റി
    document.getElementById('sort').addEventListener('change', function() {
        let sortValue = this.value;
        let grid = document.getElementById('product-grid-php');
        let cards = Array.from(grid.getElementsByClassName('php-card'));

        cards.sort((a, b) => {
            let priceA = parseFloat(a.querySelector('.price-text').textContent.replace('₹', ''));
            let priceB = parseFloat(b.querySelector('.price-text').textContent.replace('₹', ''));
            let nameA = a.querySelector('h3').textContent;
            let nameB = b.querySelector('h3').textContent;

            if (sortValue === 'low') return priceA - priceB;
            if (sortValue === 'high') return priceB - priceA;
            if (sortValue === 'az') return nameA.localeCompare(nameB);
            return 0;
        });

        grid.innerHTML = "";
        cards.forEach(card => grid.appendChild(card));
    });
  </script>
</body>
</html>