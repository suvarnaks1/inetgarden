/* ============ Golden Aura — animation script ============
   Load LAST (after main.js and the data files).
   Drives the classes already defined in css/style.css:
   .reveal / .reveal-stagger  → .is-visible   (scroll reveal)
   .cart-badge.bump                           (cart count pulse)            */

(function () {
  "use strict";

  const reduceMotion =
    window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  /* Elements that should fade/slide in when scrolled into view */
  const REVEAL = [
    ".section-title",
    ".about-grid", ".stats", ".ab-stats",
    ".brand-panel",
    ".toolbar", ".veg-note",
    ".detail", ".cart-wrap",
    ".care-card", ".acc-item", ".contact-grid", "form"
  ];

  /* Grids whose children should appear one after another */
  const STAGGER = [
    ".grid", ".featured-grid", ".carousel"
  ];

  function markTargets() {
    REVEAL.forEach(sel =>
      document.querySelectorAll(sel).forEach(el => {
        if (el.closest(".site-header, .site-footer")) return;
        if (!el.classList.contains("reveal-stagger")) el.classList.add("reveal");
      })
    );

    STAGGER.forEach(sel =>
      document.querySelectorAll(sel).forEach(el => {
        el.classList.remove("reveal");
        el.classList.add("reveal-stagger");
      })
    );
  }

  function showAll() {
    document.querySelectorAll(".reveal, .reveal-stagger")
      .forEach(el => el.classList.add("is-visible"));
  }

  function initReveal() {
    markTargets();

    if (reduceMotion || !("IntersectionObserver" in window)) {
      showAll();
      return;
    }

    const observer = new IntersectionObserver((entries, obs) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add("is-visible");
        obs.unobserve(entry.target);
      });
    }, { threshold: 0.12, rootMargin: "0px 0px -40px 0px" });

    document.querySelectorAll(".reveal, .reveal-stagger")
      .forEach(el => observer.observe(el));

    /* safety net: never leave content hidden if the observer misfires */
    setTimeout(showAll, 4000);
  }

  /* Pulse the cart badge whenever the count changes */
  function initBadgeBump() {
    document.querySelectorAll(".cart-badge").forEach(badge => {
      new MutationObserver(() => {
        badge.classList.remove("bump");
        void badge.offsetWidth;          /* restart the animation */
        badge.classList.add("bump");
      }).observe(badge, { childList: true, characterData: true, subtree: true });
    });
  }

  /* main.js builds the header and cards on DOMContentLoaded and was loaded
     first, so its listener has already run by the time this one fires. */
  document.addEventListener("DOMContentLoaded", () => {
    initReveal();
    initBadgeBump();
  });
})();
