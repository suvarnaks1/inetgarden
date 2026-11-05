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
  <meta name="description" content="Browse Golden Aura's exotic plants.">
  <meta name="theme-color" content="#31583b">

  <link rel="icon" href="../images/favicon.svg">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../css/style.css">

  <!-- കാർഡ് ഡിസൈൻ മാറ്റങ്ങൾക്കായുള്ള CSS -->
  <style>
    /* ഗ്രിഡ് ലേയൗട്ട് */
    #product-grid-php {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .product-card.php-card {
        border: 1px solid #b58d5a;
        border-radius: 8px;
        padding: 15px;
        background: #fff;
        display: flex;
        flex-direction: column;
        transition: box-shadow 0.3s ease;
    }
    .product-card.php-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    /* ഇമേജ് സ്റ്റൈൽ - വീതിയും ഉയരവും ഫിക്സ് ചെയ്തു */
    .card-img-wrap {
        width: 100%;
        height: 180px; /* ഇമേജിന്റെ ഉയരം ഇവിടെ മാറ്റാം */
        border-radius: 6px;
        overflow: hidden;
        margin-bottom: 15px;
    }
    .card-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* ഇമേജ് വലിഞ്ഞു പോകാതിരിക്കാൻ */
        display: block;
    }

    .product-card.php-card h3 {
        margin: 0 0 8px 0;
        font-size: 1.1rem;
        color: #000;
    }

    /* ഡിസ്ക്രിപ്ഷൻ വെട്ടിക്കുറയ്ക്കാൻ (2 വരികൾ മാത്രം കാണിക്കാൻ) */
    .product-card.php-card .desc {
        font-size: 0.85rem;
        color: #555;
        margin-bottom: 15px;
        line-height: 1.4;
        
        /* 2 വരികൾക്ക് ശേഷം ... വരാൻ */
        display: -webkit-box;
        -webkit-line-clamp: 2; 
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        
        min-height: 2.8em; /* ഉയരം എല്ലാ കാർഡിനും ഒരുപോലെ ആക്കാൻ */
    }

    .product-card.php-card .price {
        font-weight: bold;
        font-size: 1.1rem;
        margin-bottom: 15px;
        color: #000;
        margin-top: auto; /* വില താഴേക്ക് ഒട്ടിക്കാൻ */
    }

    .card-actions {
        display: flex;
        gap: 10px;
    }

    /* ബട്ടൺ സ്റ്റൈലുകൾ */
    .btn {
        padding: 8px 10px;
        border-radius: 4px;
        font-size: 0.9rem;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        flex: 1;
        transition: 0.2s;
    }
    .btn-fill {
        background-color: #9c6b3f;
        color: white;
        border: 1px solid #9c6b3f;
    }
    .btn-fill:hover {
        background-color: #7a5230;
    }
    .btn-line {
        background-color: white;
        color: #333;
        border: 1px solid #ddd;
    }
    .btn-line:hover {
        background-color: #f5f5f5;
        border-color: #bbb;
    }
  </style>
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

        <!-- പ്രൊഡക്ട് ഗ്രിഡ് -->
        <div id="product-grid-php">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    ?>
                    <div class="product-card php-card">
                        <div class="card-img-wrap">
                            <img src="../images/<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" loading="lazy">
                        </div>
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        
                        <!-- ഡിസ്ക്രിപ്ഷൻ (2 വരികൾ മാത്രം കാണിക്കും) -->
                        <p class="desc"><?php echo htmlspecialchars($row['description'] ?? 'Beautiful plant from Golden Aura.'); ?></p>
                        
                        <div class="price">₹<?php echo number_format($row['price'], 2); ?></div>

                        <div class="card-actions">
                            <button class="btn btn-fill" onclick="addToCart(<?php echo $row['id']; ?>)">Add to Cart</button>
                            <!-- ഇവിടെയാണ് മാറ്റം: product.php ലേക്ക് ലിങ്ക് ചെയ്യുന്നു -->
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

  <script src="../js/main.js"></script>
  
  <script>
    // സെർച്ച് ഫങ്ക്ഷണാലിറ്റി
    document.getElementById('search').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let cards = document.querySelectorAll('.php-card');
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