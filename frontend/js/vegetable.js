/* ============ Vegetable page data — Tubers, Vegetables ============
   Load AFTER main.js. Each block adds items to the shared PRODUCTS list. */

/* Tubers — ids 23–30  (Vegetable & Tubers page) */
PRODUCTS.push(...defineProducts(
  { cat: "Tubers", light: "Full sun", water: "Moderate", pet: "Cook before eating" },
  [
    { id: 23, name: "Elephant Foot Yam (Chena)", isNew: true, water: "Water regularly; avoid waterlogging",
      pet: "Handle raw corm with care — can irritate skin and mouth", size: "1 kg corm", img: wm("Amorphophallus%20paeoniifolius.jpg"),
      desc: "A large, starchy corm used in traditional Kerala curries and mezhukkuperatti." },
    { id: 24, name: "Cassava (Tapioca)", isNew: true, water: "Moderate; drought tolerant",
      pet: "Cook thoroughly before eating", size: "1 kg", img: wm("Manihot%20esculenta%20dsc07325.jpg"),
      desc: "A staple Kerala root tuber, ready to boil, mash or serve with fish curry." },
    { id: 25, name: "Colocasia (Chembu / Taro)", light: "Sun to partial shade", water: "Keep soil consistently moist",
      pet: "Cook well; raw taro irritates", size: "500 g", img: wm("Colocasia%20esculenta%20corms.jpg"),
      desc: "Soft, creamy taro corms that suit stews, thoran and roasted dishes." },
    { id: 26, name: "Greater Yam (Kachil)", isNew: true, water: "Moderate; trellis the vine",
      size: "1 kg", img: wm("Dioscorea%20alata.jpg"),
      desc: "A purple-fleshed yam, popular for festival dishes and sweet preparations." },
    { id: 27, name: "Chinese Potato (Koorka)", water: "Moderate; well-drained soil",
      size: "500 g", img: wm("Solenostemon%20rotundifolius%20tubers.jpg"),
      desc: "Small, aromatic tubers prized for the seasonal Koorka mezhukkupuratti." },
    { id: 28, name: "Sweet Potato (Madhura Kizhangu)", isNew: true, water: "Moderate; reduce before harvest",
      size: "1 kg", img: wm("Ipomoea%20batatas%20tubers.jpg"),
      desc: "Naturally sweet tubers for boiling, roasting or steaming." },
    { id: 29, name: "Arrowroot (Koova)", light: "Partial shade", water: "Keep evenly moist",
      pet: "Cook or process before eating", size: "500 g", img: wm("Maranta%20arundinacea%20rhizomes.jpg"),
      desc: "Fresh rhizomes used to extract fine arrowroot starch at home." },
    { id: 30, name: "Turmeric Rhizome (Manjal)", light: "Sun to partial shade", water: "Keep evenly moist",
      pet: "Safe in culinary amounts", size: "500 g", img: wm("Curcuma%20longa%20roots.jpg"),
      desc: "Fresh turmeric rhizomes for the kitchen garden or for planting." }
  ]
));

/* Vegetables — ids 31–38  (Vegetable & Tubers page) */
PRODUCTS.push(...defineProducts(
  { cat: "Vegetables", light: "Full sun", water: "Water regularly", pet: "Safe for pets in small amounts", size: "Seed packet" },
  [
    { id: 31, name: "Okra (Vendakka)", isNew: true, size: "Seedling pack", img: wm("Okra%20Plants.jpg"),
      desc: "A fast-growing vegetable with tender pods, perfect for sambar and fry." },
    { id: 32, name: "Brinjal (Vazhuthananga)", pet: "Leaves toxic to pets", size: "Seedling pack", img: wm("Solanum%20melongena%20plant.jpg"),
      desc: "Glossy purple fruits for curries and fries, grows well in grow-bags." },
    { id: 33, name: "Bitter Gourd (Pavakka)", isNew: true, water: "Keep evenly moist", img: wm("Momordica%20charantia%20fruit.jpg"),
      desc: "A climbing vine bearing crisp green fruits for traditional Kerala dishes." },
    { id: 34, name: "Snake Gourd (Padavalanga)", img: wm("Trichosanthes%20cucumerina.jpg"),
      desc: "A long, tender gourd that suits thoran and kootu; grows on a trellis." },
    { id: 35, name: "Drumstick (Muringa)", isNew: true, water: "Low once established", size: "Sapling bag", img: wm("Moringa%20oleifera%20pods.jpg"),
      desc: "A fast-growing tree with nutritious pods and leaves for sambar and thoran." },
    { id: 36, name: "Cowpea (Payar)", water: "Moderate", img: wm("Vigna%20unguiculata%20pods.jpg"),
      desc: "Long, tender beans that thrive in the Kerala monsoon." },
    { id: 37, name: "Amaranthus (Cheera)", isNew: true, water: "Keep evenly moist", img: wm("Amaranthus%20tricolor.jpg"),
      desc: "Red and green leafy cheera, harvestable within a few weeks of sowing." },
    { id: 38, name: "Ash Gourd (Kumbalanga)", img: wm("Benincasa%20hispida.jpg"),
      desc: "A large, cooling gourd used for olan, pulissery and kootu." }
  ]
));
