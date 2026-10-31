<?php
require_once '../../db.php'; 

// 'plants' വിഭാഗത്തിലുള്ള പ്രൊഡക്റ്റുകൾ മാത്രം എടുക്കുന്നു
$sql = "SELECT * FROM products WHERE category = 'plants'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Plants | Golden Aura</title>
  <meta name="description" content="Browse Golden Aura's exotic caladiums, heliconias and begonias.">
  <meta name="theme-color" content="#31583b">

  <link rel="icon" href="../images/favicon.svg">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Infant:ital,wght@1,600&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../css/style.css">
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
            <input type="search" id="search" placeholder="Search plants…" aria-label="Search">
            <select id="sort" aria-label="Sort">
              <option value="default">Sort: Featured</option>
              <option value="az">Name: A–Z</option>
              <option value="low">Price: Low to High</option>
              <option value="high">Price: High to Low</option>
            </select>
          </div>
        </div>

        <!-- നിങ്ങളുടെ പഴയ ഡിസൈൻ തന്നെ ഇവിടെ വരുന്നു -->
        <div class="grid" id="product-grid-php">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    ?>
                    <div class="product-card php-card" style="display:flex; flex-direction:column;">
                        <div class="card-img-wrap">
                            <div class="card-img">
                                <img src="../images/<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" loading="lazy">
                            </div>
                        </div>
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p class="desc"><?php echo htmlspecialchars($row['description'] ?? 'Beautiful plant from Golden Aura.'); ?></p>
                        <div class="price">₹<?php echo number_format($row['price'], 2); ?></div>

                        <div class="card-actions">
                            <button class="btn btn-fill" onclick="addToCart(<?php echo $row['id']; ?>)">Add to Cart</button>
                            <!-- ഇവിടെയാണ് മാറ്റം വരുത്തിയത്: product.html -> product.php -->
                            <a class="btn btn-line" href="product.php?id=<?php echo $row['id']; ?>">Learn More</a>
                        </div>
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

  <!-- main.js ലോഡ് ചെയ്യുന്നു (ഇതിൽ Header, Footer, Cart എന്നിവ വരും) -->
  <script src="../js/main.js"></script>
  
  <script>
    // === സെർച്ച് ഫങ്ക്ഷണാലിറ്റി ===
    document.getElementById('search').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let cards = document.querySelectorAll('.php-card');
        cards.forEach(card => {
            let title = card.querySelector('h3').textContent.toLowerCase();
            card.style.display = title.includes(filter) ? "" : "none";
        });
    });

    // === സോർട്ട് ഫങ്ക്ഷണാലിറ്റി ===
    document.getElementById('sort').addEventListener('change', function() {
        let sortValue = this.value;
        let grid = document.getElementById('product-grid-php');
        let cards = Array.from(grid.getElementsByClassName('php-card'));

        cards.sort((a, b) => {
            let priceA = parseFloat(a.querySelector('.price').textContent.replace('₹', '').replace(',', ''));
            let priceB = parseFloat(b.querySelector('.price').textContent.replace('₹', '').replace(',', ''));
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