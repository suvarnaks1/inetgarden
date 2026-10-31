/* ============ Golden Aura — main script ============
   Load order on every page:  main.js → plants.js → vegetable.js → vegetableseed.js → spices.js → animation.js
   Sections: 1 Config & helpers · 2 Cart · 3 Header/footer · 4 Page behaviour */

/* ===== 1. CONFIG & HELPERS ===== */
/* Image path + shared-defaults helpers used by the data files */
const wm = file => "https://commons.wikimedia.org/wiki/Special:FilePath/" + file;

const defineProducts = (defaults, items) =>
  items.map(item => ({ price: 0, rare: false, isNew: false, ...defaults, ...item }));

/* ---------- products + category helpers ---------- */
const PRODUCTS = [];   /* filled by plants.js, vegetable.js, vegetableseed.js, spices.js */

/* Which categories belong to which listing page */
const VEG_CATS   = ["Vegetables", "Tubers"];
const SPICE_CATS = ["Spice Plants", "Whole Spices"];
const SEED_CATS  = ["Gourd Seeds", "Pod & Bean Seeds", "Leafy Seeds", "Fruit Veg Seeds"];

const isVeg   = product => VEG_CATS.includes(product.cat);
const isSpice = product => SPICE_CATS.includes(product.cat);
const isSeed  = product => SEED_CATS.includes(product.cat);

/* Edible sections use kitchen wording instead of pet safety */
const isEdible = product => isVeg(product) || isSpice(product) || isSeed(product);

/* The listing page each product belongs to */
const sectionPage = product =>
  isSeed(product)  ? "vegetableseeds.html"
  : isSpice(product) ? "spices.html"
  : isVeg(product)   ? "vegetable.html"
  : "plants.html";

const sectionName = product =>
  isSeed(product)  ? "Vegetable Seeds"
  : isSpice(product) ? "Spices"
  : isVeg(product)   ? "Vegetable & Tubers"
  : "Plants";

const FALLBACK_IMAGE =
  "https://images.unsplash.com/photo-1497250681960-ef046c08a56e?auto=format&fit=crop&w=900&q=85";

const money = n =>
  "₹" + Number(n).toLocaleString("en-IN", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  });

const byId = id => PRODUCTS.find(p => p.id === Number(id));

function plantImage(url, name, className = "") {
  return `
    <img
      class="${className}"
      src="${url}"
      alt="${name}"
      loading="lazy"
      onerror="this.onerror=null;this.src='${FALLBACK_IMAGE}'"
    >
  `;
}

/* ===== 2. CART ===== */
const Cart = {
  get() {
    try {
      const stored = JSON.parse(localStorage.getItem("ga_cart"));
      if (!Array.isArray(stored)) return [];

      return stored
        .filter(item => byId(item.id) && Number(item.qty) > 0)
        .map(item => ({
          id: Number(item.id),
          qty: Math.max(1, Math.floor(Number(item.qty)))
        }));
    } catch (e) {
      return [];
    }
  },

  save(cart) {
    try {
      localStorage.setItem("ga_cart", JSON.stringify(cart));
    } catch (e) {
      console.warn("Unable to save cart to localStorage.", e);
    }
    this.badge();
  },

  add(id, qty = 1) {
    const product = byId(id);
    if (!product) return;

    const amount = Math.max(1, Math.floor(Number(qty) || 1));
    const cart = this.get();
    const item = cart.find(x => x.id === product.id);

    if (item) item.qty += amount;
    else cart.push({ id: product.id, qty: amount });

    this.save(cart);
  },

  set(id, qty) {
    const product = byId(id);
    if (!product) return;

    const amount = Math.max(1, Math.floor(Number(qty) || 1));
    this.save(this.get().map(item =>
      item.id === product.id ? { ...item, qty: amount } : item
    ));
  },

  remove(id) {
    this.save(this.get().filter(item => item.id !== Number(id)));
  },

  count() {
    return this.get().reduce((total, item) => total + item.qty, 0);
  },

  badge() {
    document.querySelectorAll(".cart-badge").forEach(badge => {
      badge.textContent = this.count();
    });
  }
};

function toast(message) {
  let element = document.querySelector(".toast");

  if (!element) {
    element = document.createElement("div");
    element.className = "toast";
    document.body.appendChild(element);
  }

  element.textContent = message;
  element.classList.add("show");

  clearTimeout(element._h);
  element._h = setTimeout(() => element.classList.remove("show"), 2000);
}

/* ===== 3. HEADER & FOOTER ===== */
const NAV = [
  ["index.html", "Home"],
  ["plants.php", "Plants"],
  ["vegetable.html", "Vegetable & Tubers"],
  ["vegetableseeds.html", "Vegetable Seeds"],
  ["spices.html", "Spices"],
  ["care.html", "Care Tips"],
  ["about.html", "About"],
  ["contact.html", "Contact"]
];

function buildHeader() {
  const header = document.getElementById("site-header");
  if (!header) return;

  let currentPage = location.pathname.split("/").pop() || "index.html";

  /* product pages highlight the section they belong to */
  if (currentPage === "product.html") {
    const item = byId(new URLSearchParams(location.search).get("id"));
    currentPage = item ? sectionPage(item) : "plants.html";
  }

  const links = NAV.map(([href, title]) => `
    <a class="nav-link ${currentPage === href ? "active" : ""}" href="${href}">${title}</a>
  `).join("");

  header.className = "site-header";

  header.innerHTML = `
    <div class="header-inner">
      <a class="logo" href="index.html">
        <img src="../images/logo-leaf.svg" alt="">
        <span>Golden Aura</span>
      </a>

      <button class="menu-toggle" aria-label="Toggle navigation"
        aria-expanded="false"><span class="bar"></span><span class="bar"></span><span class="bar"></span></button>

      <nav class="main-nav">
        ${links}
        <span class="nav-divider"></span>

        <a class="cart-link" href="cart.html" aria-label="Shopping cart">
          <svg viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="1.7"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M2 3h3l2.6 12.2a2 2 0 0 0 2 1.6h8.1a2 2 0 0 0 2-1.5L21 7H6"/>
            <circle cx="10" cy="20.5" r="1.4"/>
            <circle cx="18" cy="20.5" r="1.4"/>
          </svg>
          <span class="cart-label">Cart</span>
          <span class="cart-badge">0</span>
        </a>
      </nav>
    </div>
  `;

  const toggle = header.querySelector(".menu-toggle");
  const nav = header.querySelector(".main-nav");

  toggle.addEventListener("click", () => {
    const isOpen = nav.classList.toggle("open");
    toggle.setAttribute("aria-expanded", String(isOpen));
  });

  nav.querySelectorAll("a").forEach(a =>
    a.addEventListener("click", () => {
      nav.classList.remove("open");
      toggle.setAttribute("aria-expanded", "false");
    })
  );
}

function buildFooter() {
  const footer = document.getElementById("site-footer");
  if (!footer) return;

  const quickLinks = NAV.map(([href, title]) => `
    <li><a href="${href}">${title}</a></li>
  `).join("");

  footer.className = "site-footer";

  footer.innerHTML = `
    <div class="footer-grid">
      <div class="footer-about">
        <a class="logo" href="index.html">
          <img src="../images/logo-leaf.svg" alt="" style="filter:brightness(1.6)">
          <span>Golden Aura</span>
        </a>
        <p>
          Curating botanical magic for your home —
          rare, healthy plants delivered with care.
        </p>
      </div>

      <div>
        <h4>Quick Links</h4>
        <ul>${quickLinks}</ul>
      </div>

      <div class="news">
        <h4 class="gold">Stay Rooted in Style</h4>
        <p>
          Subscribe to get care tips and early access
          to new rare arrivals in our newsletter.
        </p>

        <form id="news-form" novalidate>
          <input type="email" id="news-email"
            placeholder="Email address" aria-label="Email address" required>
          <button type="submit">Subscribe</button>
        </form>

        <div class="social">
          <a href="#" aria-label="Facebook">
            <svg viewBox="0 0 24 24"><path d="M13.5 22v-8h2.7l.5-3.3h-3.2V8.6c0-.9.4-1.7 1.8-1.7h1.5V4.1c-.3 0-1.3-.1-2.4-.1-2.5 0-4.1 1.5-4.1 4.2v2.5H7.5V14h2.8v8z"/></svg>
          </a>
          <a href="#" aria-label="X">
            <svg viewBox="0 0 24 24"><path d="M17.8 3h3l-6.6 7.5L22 21h-6.1l-4.8-6.2L5.5 21h-3l7-8L2 3h6.2l4.3 5.7zm-1 16.2h1.7L7.3 4.7H5.5z"/></svg>
          </a>
          <a href="#" aria-label="Instagram">
            <svg viewBox="0 0 24 24"><path d="M12 7.3A4.7 4.7 0 1 0 12 16.7 4.7 4.7 0 0 0 12 7.3zm0 7.7a3 3 0 1 1 0-6 3 3 0 0 1 0 6zM17.2 5.6a1.1 1.1 0 1 0 0 2.2 1.1 1.1 0 0 0 0-2.2zM12 3c-2.4 0-2.7 0-3.7.1C5.5 3.2 3.2 5.5 3.1 8.3 3 9.3 3 9.6 3 12s0 2.7.1 3.7c.1 2.8 2.4 5.1 5.2 5.2 1 .1 1.3.1 3.7.1s2.7 0 3.7-.1c2.8-.1 5.1-2.4 5.2-5.2.1-1 .1-1.3.1-3.7s0-2.7-.1-3.7c-.1-2.8-2.4-5.1-5.2-5.2C14.7 3 14.4 3 12 3zm0 1.6c2.4 0 2.7 0 3.6.1 1.9.1 3.4 1.6 3.5 3.5.1 1 .1 1.2.1 3.6s0 2.6-.1 3.6c-.1 1.9-1.6 3.4-3.5 3.5-1 .1-1.2.1-3.6.1s-2.6 0-3.6-.1c-1.9-.1-3.4-1.6-3.5-3.5-.1-1-.1-1.2-.1-3.6s0-2.6.1-3.6c.1-1.9 1.6-3.4 3.5-3.5 1-.1 1.2-.1 3.6-.1z"/></svg>
          </a>
          <a href="#" aria-label="YouTube">
            <svg viewBox="0 0 24 24"><path d="M21.6 7.2a2.5 2.5 0 0 0-1.8-1.8C18.2 5 12 5 12 5s-6.2 0-7.8.4A2.5 2.5 0 0 0 2.4 7.2C2 8.8 2 12 2 12s0 3.2.4 4.8a2.5 2.5 0 0 0 1.8 1.8C5.8 19 12 19 12 19s6.2 0 7.8-.4a2.5 2.5 0 0 0 1.8-1.8c.4-1.6.4-4.8.4-4.8s0-3.2-.4-4.8zM10 15V9l5.2 3z"/></svg>
          </a>
        </div>
      </div>
    </div>

    <div class="copyright">
      © ${new Date().getFullYear()} Golden Aura. All rights reserved.
    </div>

    <img class="footer-leaf" src="images/leaf-decor.svg" alt="">
  `;

  document.getElementById("news-form").addEventListener("submit", event => {
    event.preventDefault();

    const input = document.getElementById("news-email");
    const email = input.value.trim();

    if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      toast("Thanks for subscribing! 🌿");
      input.value = "";
    } else {
      toast("Please enter a valid email address");
      input.focus();
    }
  });
}

/* ===== 4. PAGE BEHAVIOUR ===== */
/* ---------- product cards ---------- */
function cardHTML(product) {
  return `
    <article class="card">
      <a class="card-img-wrap" href="product.html?id=${product.id}">
        <div class="card-img">${plantImage(product.img, product.name)}</div>
      </a>

      <h3>${product.name}</h3>
      <p class="desc">${product.desc}</p>
      <div class="price">${money(product.price)}</div>

      <div class="card-actions">
        <button class="btn btn-fill" data-add="${product.id}">Add to Cart</button>
        <a class="btn btn-line" href="product.html?id=${product.id}">Learn More</a>
      </div>
    </article>
  `;
}

function renderCards(selector, products) {
  const element = document.querySelector(selector);
  if (!element) return;

  element.innerHTML = products.length
    ? products.map(cardHTML).join("")
    : '<p class="empty" style="grid-column:1/-1">No items match your search.</p>';
}

document.addEventListener("click", event => {
  const button = event.target.closest("[data-add]");
  if (!button) return;

  const product = byId(button.dataset.add);
  if (!product) return;

  Cart.add(product.id);
  toast(`${product.name} added to cart`);
});

/* ---------- carousel + accordion ---------- */
function initCarousel() {
  document.querySelectorAll(".carousel-wrap").forEach(wrap => {
    const carousel = wrap.querySelector(".carousel");
    if (!carousel) return;

    const getStep = () => {
      const card = carousel.querySelector(".card");
      return card ? card.getBoundingClientRect().width + 20 : 300;
    };

    wrap.querySelector(".car-btn.prev")?.addEventListener("click", () =>
      carousel.scrollBy({ left: -getStep(), behavior: "smooth" }));

    wrap.querySelector(".car-btn.next")?.addEventListener("click", () =>
      carousel.scrollBy({ left: getStep(), behavior: "smooth" }));
  });
}

function initAccordion() {
  document.querySelectorAll(".acc-q").forEach(question => {
    question.addEventListener("click", () => {
      const item = question.parentElement;
      const wasOpen = item.classList.contains("open");

      document.querySelectorAll(".acc-item").forEach(other =>
        other.classList.remove("open"));

      if (!wasOpen) item.classList.add("open");
    });
  });
}

/* ---------- listing pages ----------
   <div id="product-grid" data-group="plants"> → ornamental plants
   <div id="product-grid" data-group="veg">    → vegetables & tubers
   <div id="product-grid" data-group="seeds">  → vegetable seeds
   <div id="product-grid" data-group="spice">  → spices
   Category chips are built from the categories in that group.   */
function initListing() {
  const grid = document.getElementById("product-grid");
  if (!grid) return;

  const only = grid.dataset.only;
  const group = grid.dataset.group;
  let category = "All";
  let term = "";
  let sort = "default";

  const inGroup = product =>
    group === "veg" ? isVeg(product)
    : group === "seeds" ? isSeed(product)
    : group === "spice" ? isSpice(product)
    : group === "plants" ? !isEdible(product)
    : true;

  const cats = ["All", ...new Set(PRODUCTS.filter(inGroup).map(p => p.cat))];
  const chips = document.querySelector(".chips");

  if (chips) {
    chips.innerHTML = cats.map(cat => `
      <button class="chip ${cat === "All" ? "active" : ""}" data-cat="${cat}">${cat}</button>
    `).join("");

    chips.addEventListener("click", event => {
      const button = event.target.closest(".chip");
      if (!button) return;

      chips.querySelectorAll(".chip").forEach(chip => chip.classList.remove("active"));
      button.classList.add("active");
      category = button.dataset.cat;
      draw();
    });
  }

  function draw() {
    const products = PRODUCTS.filter(product =>
      inGroup(product) &&
      (only !== "rare" || product.rare) &&
      (category === "All" || product.cat === category) &&
      product.name.toLowerCase().includes(term)
    );

    if (sort === "low") products.sort((a, b) => a.price - b.price);
    else if (sort === "high") products.sort((a, b) => b.price - a.price);
    else if (sort === "az") products.sort((a, b) => a.name.localeCompare(b.name));

    renderCards("#product-grid", products);
  }

  document.getElementById("search")?.addEventListener("input", event => {
    term = event.target.value.trim().toLowerCase();
    draw();
  });

  document.getElementById("sort")?.addEventListener("change", event => {
    sort = event.target.value;
    draw();
  });

  draw();
}

/* ---------- product detail ---------- */
function initDetail() {
  const box = document.getElementById("detail");
  if (!box) return;

  const params = new URLSearchParams(location.search);
  const product = byId(params.get("id")) || PRODUCTS[0];

  document.title = `${product.name} | Golden Aura`;

  const breadcrumb = document.getElementById("crumb-name");
  if (breadcrumb) breadcrumb.textContent = product.name;

  const crumbList = document.getElementById("crumb-list");
  if (crumbList) {
    crumbList.href = sectionPage(product);
    crumbList.textContent = sectionName(product);
  }

  box.innerHTML = `
    <div class="detail-img">${plantImage(product.img, product.name)}</div>

    <div class="detail-info">
      <h1>${product.name}</h1>
      <div class="price">${money(product.price)}</div>
      <p>${product.desc}</p>

      <ul class="meta">
        <li><b>Category</b>${product.cat}</li>
        <li><b>Light</b>${product.light}</li>
        <li><b>Watering</b>${product.water}</li>
        <li><b>${isEdible(product) ? "Kitchen safety" : "Pet safety"}</b>${product.pet}</li>
        <li><b>Size</b>${product.size}</li>
      </ul>

      <div class="qty">
        <button id="minus" aria-label="Decrease quantity">−</button>
        <input id="q" type="number" value="1" min="1" step="1">
        <button id="plus" aria-label="Increase quantity">+</button>
      </div>

      <button class="btn btn-fill" id="add-detail">Add to Cart</button>
    </div>
  `;

  const quantity = document.getElementById("q");

  document.getElementById("minus").addEventListener("click", () => {
    quantity.value = Math.max(1, (Number(quantity.value) || 1) - 1);
  });

  document.getElementById("plus").addEventListener("click", () => {
    quantity.value = Math.max(1, Number(quantity.value) || 1) + 1;
  });

  document.getElementById("add-detail").addEventListener("click", () => {
    const amount = Math.max(1, Math.floor(Number(quantity.value) || 1));
    Cart.add(product.id, amount);
    toast(`${product.name} added to cart`);
  });

  /* related: same category first, then same section */
  let related = PRODUCTS.filter(item => item.id !== product.id && item.cat === product.cat);

  if (related.length < 4) {
    related = related.concat(
      PRODUCTS.filter(item =>
        item.id !== product.id &&
        item.cat !== product.cat &&
        sectionPage(item) === sectionPage(product)
      )
    );
  }

  renderCards("#related", related.slice(0, 4));
}

/* ---------- cart page ---------- */
function initCartPage() {
  const list = document.getElementById("cart-list");
  if (!list) return;

  const summary = document.getElementById("summary");

  function draw() {
    const items = Cart.get()
      .map(item => ({ ...byId(item.id), qty: item.qty }))
      .filter(item => item.id);

    if (!items.length) {
      list.innerHTML = `
        <div class="empty">
          <p style="font-size:18px;margin-bottom:14px">Your cart is empty 🌱</p>
          <a class="pill" href="plants.html">Start Shopping</a>
        </div>
      `;
      if (summary) summary.style.display = "none";
      return;
    }

    if (summary) summary.style.display = "";

    list.innerHTML = items.map(item => `
      <div class="cart-item">
        ${plantImage(item.img, item.name)}

        <div>
          <h3>${item.name}</h3>
          <div>${money(item.price)}</div>

          <div class="qty">
            <button data-m="${item.id}" aria-label="Decrease quantity">−</button>
            <input value="${item.qty}" data-i="${item.id}"
              type="number" min="1" step="1" aria-label="Quantity">
            <button data-p="${item.id}" aria-label="Increase quantity">+</button>
          </div>

          <button class="rm" data-r="${item.id}">Remove</button>
        </div>

        <div><b>${money(item.price * item.qty)}</b></div>
      </div>
    `).join("");

    const subtotal = items.reduce((total, item) => total + item.price * item.qty, 0);
    const shipping = subtotal >= 50 ? 0 : 5.99;

    const sub = document.getElementById("sub");
    const ship = document.getElementById("ship");
    const total = document.getElementById("total");

    if (sub) sub.textContent = money(subtotal);
    if (ship) ship.textContent = shipping ? money(shipping) : "Free";
    if (total) total.textContent = money(subtotal + shipping);
  }

  list.addEventListener("click", event => {
    const t = event.target;
    const find = id => Cart.get().find(entry => entry.id === Number(id));

    if (t.dataset.m) {
      const item = find(t.dataset.m);
      if (item) Cart.set(item.id, item.qty - 1);
      draw();
    } else if (t.dataset.p) {
      const item = find(t.dataset.p);
      if (item) Cart.set(item.id, item.qty + 1);
      draw();
    } else if (t.dataset.r) {
      Cart.remove(Number(t.dataset.r));
      draw();
    }
  });

  list.addEventListener("change", event => {
    const input = event.target;
    if (!input.dataset.i) return;

    Cart.set(Number(input.dataset.i), Number(input.value) || 1);
    draw();
  });

  document.getElementById("checkout")?.addEventListener("click", () => {
    toast("Demo store — checkout is not connected 🌿");
  });

  draw();
}

/* ---------- contact form ---------- */
function initContact() {
  const form = document.getElementById("contact-form");
  if (!form) return;

  form.addEventListener("submit", event => {
    event.preventDefault();

    let valid = true;

    const check = (id, test, message) => {
      const input = form.querySelector("#" + id);
      if (!input) return;

      const error = input.parentElement.querySelector(".err");

      if (!test(input.value.trim())) {
        if (error) error.textContent = message;
        valid = false;
      } else if (error) {
        error.textContent = "";
      }
    };

    check("name", v => v.length > 1, "Please enter your name");
    check("email", v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v), "Please enter a valid email");
    check("message", v => v.length >= 10, "Message should be at least 10 characters");

    if (valid) {
      toast("Message validated! 🌿");
      form.reset();
    }
  });
}

/* ---------- home page sections ---------- */
function initHome() {
  renderCards("#featured", PRODUCTS.filter(p => [1, 2, 3, 4].includes(p.id)));
  renderCards("#arrivals", [2, 3, 4, 6, 5, 9].map(byId).filter(Boolean));

  renderCards("#veg-featured", PRODUCTS.filter(p => [23, 24, 26, 31].includes(p.id)));
  renderCards("#veg-arrivals", PRODUCTS.filter(p => isVeg(p) && p.isNew));

  renderCards("#seed-featured", PRODUCTS.filter(p => [51, 52, 55, 57].includes(p.id)));
  renderCards("#seed-arrivals", PRODUCTS.filter(p => isSeed(p) && p.isNew));

  renderCards("#spice-featured", PRODUCTS.filter(p => [39, 40, 42, 45].includes(p.id)));
  renderCards("#spice-arrivals", PRODUCTS.filter(p => isSpice(p) && p.isNew));
}

/* ---------- initialize ---------- */
document.addEventListener("DOMContentLoaded", () => {
  buildHeader();
  buildFooter();
  Cart.badge();

  initHome();
  initCarousel();
  initAccordion();
  initListing();
  initDetail();
  initCartPage();
  initContact();
});
