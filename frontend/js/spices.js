/* ============ Spices page data — Spice Plants, Whole Spices ============
   Load AFTER main.js. New spice category? Add its name to SPICE_CATS in main.js. */

/* Spice Plants — ids 39–44  (Spices page) */
PRODUCTS.push(...defineProducts(
  { cat: "Spice Plants", water: "Keep evenly moist", pet: "Safe in culinary amounts" },
  [
    { id: 39, name: "Black Pepper Vine (Kurumulaku)", isNew: true, light: "Partial shade", size: "Rooted cutting",
      img: wm("Piper%20nigrum%20-%20K%C3%B6hler%E2%80%93s%20Medizinal-Pflanzen-253.jpg"),
      desc: "The king of spices — a climbing vine that thrives on a support pole or tree in Kerala gardens." },
    { id: 40, name: "Cardamom Plant (Elakka)", isNew: true, light: "Shade to partial shade", size: "Seedling pack",
      img: wm("Elettaria%20cardamomum%20plant.jpg"),
      desc: "Fragrant green cardamom, grown in humid shade and harvested as aromatic pods." },
    { id: 41, name: "Clove Sapling (Grambu)", light: "Sun to partial shade", water: "Water regularly", size: "Grafted sapling",
      img: wm("Syzygium%20aromaticum%20buds.jpg"),
      desc: "An evergreen tropical tree whose dried flower buds give the classic clove spice." },
    { id: 42, name: "Nutmeg Sapling (Jathikka)", isNew: true, light: "Partial shade", size: "Grafted sapling",
      img: wm("Myristica%20fragrans%20fruit.jpg"),
      desc: "A tropical tree giving two spices — nutmeg from the seed and mace from its red covering." },
    { id: 43, name: "Cinnamon Sapling (Karuvapatta)", light: "Full sun to partial shade", water: "Water regularly", size: "Seedling pack",
      img: wm("Cinnamomum%20verum%20bark.jpg"),
      desc: "A hardy sapling grown for its aromatic inner bark and fragrant leaves." },
    { id: 44, name: "Vanilla Vine", light: "Filtered light", size: "Rooted cutting",
      img: wm("Vanilla%20planifolia%20flower.jpg"),
      desc: "A climbing orchid that produces vanilla pods, suited to shaded, humid gardens." }
  ]
));

/* Whole Spices — ids 45–50  (Spices page) */
PRODUCTS.push(...defineProducts(
  { cat: "Whole Spices", light: "Store away from light", water: "Keep dry", pet: "Safe in culinary amounts" },
  [
    { id: 45, name: "Whole Black Pepper (Kurumulaku)", isNew: true, size: "250 g", img: wm("Black%20pepper%20berries.jpg"),
      desc: "Bold, aromatic whole peppercorns, sun-dried and ideal for grinding fresh." },
    { id: 46, name: "Green Cardamom Pods (Elakka)", isNew: true, size: "100 g", img: wm("Cardamom%20pods.jpg"),
      desc: "Plump, fragrant green pods for chai, biryani, payasam and sweets." },
    { id: 47, name: "Whole Cloves (Grambu)", size: "100 g", img: wm("Cloves.jpg"),
      desc: "Intensely aromatic dried clove buds for curries, masalas and teas." },
    { id: 48, name: "Cinnamon Sticks (Karuvapatta)", size: "100 g", img: wm("Cinnamon%20sticks.jpg"),
      desc: "Sweet, warm cinnamon quills for stews, desserts and spiced drinks." },
    { id: 49, name: "Nutmeg & Mace (Jathikka & Jathipathri)", isNew: true, size: "50 g", img: wm("Nutmeg%20and%20mace.jpg"),
      desc: "Whole nutmeg with its delicate mace blades, to be grated fresh when needed." },
    { id: 50, name: "Dried Turmeric Fingers (Manjal)", size: "250 g", img: wm("Dried%20turmeric%20roots.jpg"),
      desc: "Sun-dried turmeric fingers, ready to grind into pure golden powder at home." }
  ]
));
