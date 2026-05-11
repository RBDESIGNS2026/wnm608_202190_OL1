<?php
session_start();
$page_title = "Services — Neon Kactus";

$services = [
  ["num" => "01", "title" => "Residential Plant Styling", "desc" => "We design botanical layouts for homes and apartments — from a single statement plant to a fully styled room. Includes site visit, plant selection, planter pairing, and white-glove install.", "price" => "From $250"],
  ["num" => "02", "title" => "Commercial &amp; Hospitality", "desc" => "Large-scale plantscaping for offices, hotels, restaurants, and retail. Custom proposals, ongoing maintenance contracts, and seasonal refreshes.", "price" => "Custom Quote"],
  ["num" => "03", "title" => "Plant Care &amp; Maintenance", "desc" => "Weekly, bi-weekly, or monthly visits from our care team. Watering, pruning, soil refresh, pest treatment, and health reporting — your plants stay thriving.", "price" => "From $95 / visit"],
  ["num" => "04", "title" => "Events &amp; Installations", "desc" => "Lush botanical moments for weddings, launches, and editorial shoots. Built on-site, broken down by us, photographed beautifully.", "price" => "From $1,200"],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?= $page_title ?></title>
<?php include "parts/meta.php"; ?>
<style>
.services-hero {
  position: relative;
  height: 60vh;
  min-height: 420px;
  overflow: hidden;
}
.services-hero img { width: 100%; height: 100%; object-fit: cover; display: block; }
.services-hero .overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to right, rgba(0,0,0,0.55), rgba(0,0,0,0.15));
}
.services-hero .hero-text {
  position: absolute; inset: 0;
  display: flex; align-items: flex-end;
}
.services-hero .hero-text .container { padding-bottom: 4rem; }
.services-hero h1 { color: #fff; margin: 0; }
.services-hero h1::after { background: var(--gold); }
.services-hero .label-accent { color: rgba(255,255,255,0.85); }
.label-accent { font-style: italic; }

.service-row {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1rem;
  padding: 2.5rem 0;
  border-top: 1px solid var(--stone);
  align-items: start;
}
@media (min-width: 768px) {
  .service-row { grid-template-columns: 70px 1fr 180px; gap: 2.5rem; }
}
.service-row:last-child { border-bottom: 1px solid var(--stone); }
.service-num {
  color: var(--gold);
  font-weight: 700;
  font-size: 0.75rem;
  letter-spacing: 0.2em;
}
.service-body p { color: #555; max-width: 600px; }
.service-price {
  color: var(--charcoal);
  font-weight: 600;
  font-size: 0.85rem;
  letter-spacing: 0.15em;
  text-transform: uppercase;
}
@media (min-width: 768px) { .service-price { text-align: right; } }

.process-section {
  background: var(--stone);
  padding: 5rem 1.5rem;
}
.process-inner { max-width: 1100px; margin: 0 auto; }
.step-num {
  display: inline-block;
  color: var(--gold);
  font-weight: 700;
  font-size: 0.7rem;
  letter-spacing: 0.2em;
  border-bottom: 1px solid var(--gold);
  padding-bottom: 4px;
  margin-bottom: 1rem;
}
.process-step { padding: 1rem 0.5rem; }
.process-step h3 { margin-bottom: 1.25rem; }
.process-step p { line-height: 1.9; }

.text-center { text-align: center; }
.text-center h2::after { margin-left: auto; margin-right: auto; }
.text-center > p, .text-center p.label-accent, .text-center p.lead-p { margin-left: auto; margin-right: auto; }
.process-inner.text-center > p { margin-left: auto; margin-right: auto; }
.lead-p { max-width: 700px; margin: 1.5rem auto 0; text-align: center; }

/* Force italic eyebrow labels site-wide on this page */
p.label-accent { font-style: italic !important; }

/* Luxury gallery using site grid */
.gallery-img {
  width: 100%;
  height: 360px;
  object-fit: cover;
  display: block;
}
.gallery-caption {
  text-align: center;
  margin-top: 0.75rem;
  color: #777;
  font-size: 0.75rem;
  letter-spacing: 0.2em;
  text-transform: uppercase;
}

.services-cta-band {
  background: var(--charcoal);
  color: #fff;
  text-align: center;
  padding: 5rem 1.5rem;
}
.services-cta-band h2 { color: #fff; }
.services-cta-band h2::after { margin-left: auto; margin-right: auto; }
.services-cta-band p { color: rgba(255,255,255,0.75); margin: 0 auto 2rem; max-width: 600px; }
.services-cta-band .btn-pill { background: #fff; color: var(--charcoal); }
</style>
</head>
<body>

<?php include "parts/navbar.php"; ?>

<section class="services-hero">
  <img src="../images/services-hero.jpg" alt="Plant styling service">
  <div class="overlay"></div>
  <div class="hero-text">
    <div class="container">
      <p class="label-accent">what we do</p>
      <h1>Plant Design</h1>
    </div>
  </div>
</section>

<section class="sg-section container text-center">
  <p class="label-accent">services</p>
  <h2>Start to Thrive</h2>
  <p class="lead-p">
    From a single statement piece to a full commercial install, our team brings the Neon Kactus eye to spaces of every scale. Below is a starting point — every project is shaped around you.
  </p>
</section>

<section class="sg-section container">
  <?php foreach ($services as $s): ?>
  <div class="service-row">
    <div class="service-num"><?= $s['num'] ?></div>
    <div class="service-body">
      <h3><?= $s['title'] ?></h3>
      <p><?= $s['desc'] ?></p>
    </div>
    <div class="service-price"><?= $s['price'] ?></div>
  </div>
  <?php endforeach; ?>
</section>

<!-- LUXURY GALLERY -->
<section class="sg-section container">
  <div class="grid gap">
    <div class="col-12 col-md-4">
      <img src="../images/about-hero.jpg" alt="Studio plant install" class="gallery-img">
      <p class="gallery-caption">The Studio</p>
    </div>
    <div class="col-12 col-md-4">
      <img src="../images/services-hero.jpg" alt="Plant care service" class="gallery-img">
      <p class="gallery-caption">Care &amp; Install</p>
    </div>
    <div class="col-12 col-md-4">
      <img src="../images/monstera.jpg" alt="Curated greenery" class="gallery-img">
      <p class="gallery-caption">Curated Greenery</p>
    </div>
  </div>
</section>

<section class="process-section">
  <div class="process-inner text-center">
    <p class="label-accent">how it works</p>
    <h2>Our Process</h2>
  </div>
  <div class="process-inner" style="margin-top: 3rem;">
    <div class="grid gap">
      <div class="col-12 col-md-3 process-step">
        <span class="step-num">STEP 01</span>
        <h3>Reach Out</h3>
        <p>Tell us about your space, your light, and the vibe you're after.</p>
      </div>
      <div class="col-12 col-md-3 process-step">
        <span class="step-num">STEP 02</span>
        <h3>Site Visit</h3>
        <p>We walk the space, take measurements, and listen to how you live or work.</p>
      </div>
      <div class="col-12 col-md-3 process-step">
        <span class="step-num">STEP 03</span>
        <h3>Proposal</h3>
        <p>You receive a curated plant + planter plan with timeline and pricing.</p>
      </div>
      <div class="col-12 col-md-3 process-step">
        <span class="step-num">STEP 04</span>
        <h3>Install &amp; Care</h3>
        <p>White-glove delivery, install, and an optional ongoing care plan.</p>
      </div>
    </div>
  </div>
</section>

<section class="services-cta-band">
  <p class="label-accent" style="color: var(--gold);">let's talk</p>
  <h2>Ready To Start?</h2>
  <p>Tell us a little about your space — we'll be in touch within two business days.</p>
  <a href="mailto:hello@neonkactus.shop" class="btn-pill">Request a Consultation</a>
</section>

<?php include "parts/footer.php"; ?>
</body>
</html>
