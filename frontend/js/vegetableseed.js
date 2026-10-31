/* ============ Vegetable Seeds data ============
   Load AFTER main.js. New seed category? Add its name to SEED_CATS in main.js. */

/* Vegetable Seeds — ids 51–58  (Vegetable Seeds page)
   New seed categories: add the category name to SEED_CATS in products.js */
PRODUCTS.push(...defineProducts(
  { light: "Full sun", water: "Keep evenly moist", pet: "For sowing — grow before eating", size: "Seed packet" },
  [
    { id: 51, name: "Okra Seeds (Vendakka)", cat: "Pod & Bean Seeds", isNew: true, img: wm("Okra%20Plants.jpg"),
      desc: "Quick-germinating okra seeds for tender pods in about two months." },
    { id: 52, name: "Bitter Gourd Seeds (Pavakka)", cat: "Gourd Seeds", isNew: true, img: wm("Momordica%20charantia%20fruit.jpg"),
      desc: "Climbing vine seeds for crisp green pavakka; give them a trellis." },
    { id: 53, name: "Snake Gourd Seeds (Padavalanga)", cat: "Gourd Seeds", img: wm("Trichosanthes%20cucumerina.jpg"),
      desc: "Long, tender gourds for thoran and kootu; grows well on a trellis." },
    { id: 54, name: "Ash Gourd Seeds (Kumbalanga)", cat: "Gourd Seeds", img: wm("Benincasa%20hispida.jpg"),
      desc: "Large, cooling gourds for olan, pulissery and kootu." },
    { id: 55, name: "Cowpea Seeds (Payar)", cat: "Pod & Bean Seeds", water: "Moderate", img: wm("Vigna%20unguiculata%20pods.jpg"),
      desc: "Long, tender beans that thrive in the Kerala monsoon." },
    { id: 56, name: "Drumstick Seeds (Muringa)", cat: "Pod & Bean Seeds", isNew: true, water: "Low once established", img: wm("Moringa%20oleifera%20pods.jpg"),
      desc: "Seeds for a fast-growing tree with nutritious pods and leaves." },
    { id: 57, name: "Amaranthus Seeds (Cheera)", cat: "Leafy Seeds", isNew: true, img: wm("Amaranthus%20tricolor.jpg"),
      desc: "Red and green leafy cheera, harvestable within weeks of sowing." },
    { id: 58, name: "Brinjal Seeds (Vazhuthananga)", cat: "Fruit Veg Seeds", img: wm("Solanum%20melongena%20plant.jpg"),
      desc: "Glossy purple brinjal seeds; start in trays and transplant to grow-bags." }
  ]
));
