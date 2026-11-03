<?php
require_once '../../db.php'; 

// 'vegetables' വിഭാഗത്തിലുള്ള എല്ലാ പ്രൊഡക്റ്റുകളും എടുക്കുന്നു
$sql = "SELECT * FROM products WHERE category = 'vegetables'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Vegetable & Tubers | Golden Aura</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <header id="site-header"></header>
  <main>
    <section class="page-banner">
      <div>
        <h1>Vegetable &amp; Tubers</h1>
        <p>Tubers, gourds and greens from the kitchen garden</p>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <div class="toolbar">
          <!-- Subcategory ഫിൽട്ടർ (Chips) -->
          <div class="chips">
            <button class="chip active" data-cat="All">All</button>
            <button class="chip" data-cat="Vegetables">Vegetables</button>
            <button class="chip" data-cat="Tubers">Tubers</button>
          </div>
          
          <div class="tools">
            <input type="search" id="search" placeholder="Search vegetables…">
            <select id="sort">
              <option value="default">Sort: Featured</option>
              <option value="az">Name: A–Z</option>
              <option value="low">Price: Low to High</option>
              <option value="high">Price: High to Low</option>
            </select>
          </div>
        </div>

        <div class="grid" id="product-grid-php">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    ?>
                    <!-- ഓരോ പ്രൊഡക്റ്റിനും subcategory ഡാറ്റ-ആട്രിബ്യൂട്ട് നൽകുന്നു -->
                    <div class="product-card php-card" data-subcat="<?php echo htmlspecialchars($row['subcategory'] ?? ''); ?>">
                        <div class="card-img-wrap">
                            <div class="card-img">
                                <img src="../images/<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            </div>
                        </div>
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <div class="price">₹<?php echo number_format($row['price'], 2); ?></div>
                        <div class="card-actions">
                            <button class="btn btn-fill" onclick="addToCart(<?php echo $row['id']; ?>)">Add to Cart</button>
                            <a class="btn btn-line" href="product.php?id=<?php echo $row['id']; ?>">Learn More</a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No vegetables available right now.</p>";
            }
            ?>
        </div>
      </div>
    </section>
  </main>

  <footer id="site-footer"></footer>

  <script src="../js/main.js"></script>
  
  <script>
    // Subcategory ഫിൽട്ടർ (Vegetables / Tubers)
    let currentSubcat = "All";
    document.querySelectorAll('.chip').forEach(chip => {
        chip.addEventListener('click', function() {
            document.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            currentSubcat = this.dataset.cat;
            filterAndSort();
        });
    });

    // സെർച്ച് ഫങ്ക്ഷണാലിറ്റി
    document.getElementById('search').addEventListener('keyup', filterAndSort);

    // സോർട്ട് ഫങ്ക്ഷണാലിറ്റി
    document.getElementById('sort').addEventListener('change', filterAndSort);

    function filterAndSort() {
        let grid = document.getElementById('product-grid-php');
        let cards = Array.from(grid.getElementsByClassName('php-card'));
        let filterText = document.getElementById('search').value.toLowerCase();
        let sortValue = document.getElementById('sort').value;

        // 1. ഫിൽട്ടർ ചെയ്യുന്നു
        cards.forEach(card => {
            let title = card.querySelector('h3').textContent.toLowerCase();
            let subcat = card.dataset.subcat;

            let matchSearch = title.includes(filterText);
            let matchSubcat = (currentSubcat === "All" || subcat === currentSubcat);

            if (matchSearch && matchSubcat) {
                card.style.display = "";
            } else {
                card.style.display = "none";
            }
        });

        // 2. കാണുന്ന കാർഡുകൾ മാത്രം സോർട്ട് ചെയ്യുന്നു
        let visibleCards = cards.filter(card => card.style.display !== "none");

        visibleCards.sort((a, b) => {
            let priceA = parseFloat(a.querySelector('.price').textContent.replace('₹', '').replace(',', ''));
            let priceB = parseFloat(b.querySelector('.price').textContent.replace('₹', '').replace(',', ''));
            let nameA = a.querySelector('h3').textContent;
            let nameB = b.querySelector('h3').textContent;

            if (sortValue === 'low') return priceA - priceB;
            if (sortValue === 'high') return priceB - priceA;
            if (sortValue === 'az') return nameA.localeCompare(nameB);
            return 0;
        });

        // സോർട്ട് ചെയ്ത കാർഡുകൾ വീണ്ടും ചേർക്കുന്നു
        grid.innerHTML = "";
        cards.forEach(card => grid.appendChild(card));
    }
  </script>
</body>
</html>