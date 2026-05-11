<?php
session_start();
$page_title = "About — Neon Kactus";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?= $page_title ?></title>
<?php include "parts/meta.php"; ?>
<style>
.about-hero {
  position: relative;
  height: 60vh;
  min-height: 420px;
  overflow: hidden;
  margin-bottom: 0;
}
.about-hero img { width: 100%; height: 100%; object-fit: cover; display: block; }
.about-hero .overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to bottom, rgba(0,0,0,0.45), rgba(0,0,0,0.15));
}
.about-hero .hero-text {
  position: absolute; inset: 0;
  display: flex; align-items: flex-end;
}
.about-hero .hero-text .container { padding-bottom: 4rem; }
.about-hero h1 { color: #fff; margin: 0; }
.about-hero h1::after { background: var(--gold); }
.about-hero .label-accent { color: rgba(255,255,255,0.85); }
.label-accent { font-style: italic; }

.founder-photo {
  width: 100%;
  max-height: 620px;
  object-fit: cover;
  display: block;
}
.founder-caption {
  text-align: center;
  margin-top: 1rem;
  color: #777;
  font-size: 0.85rem;
  letter-spacing: 0.15em;
  text-transform: uppercase;
}
.value-num {
  display: inline-block;
  color: var(--gold);
  font-weight: 700;
  font-size: 0.75rem;
  letter-spacing: 0.2em;
  border-bottom: 1px solid var(--gold);
  padding-bottom: 4px;
  margin-bottom: 1rem;
}
/* Centered section headings + paragraph helpers */
.text-center { text-align: center; }
.text-center h1, .text-center h2, .text-center h3, .text-center h4 { margin-left: auto; margin-right: auto; }
.text-center h2::after, .text-center h1::after, .text-center h3::after { margin-left: auto; margin-right: auto; }
.text-center > p, .text-center p.label-accent, .text-center p.lead-p { margin-left: auto; margin-right: auto; }
.lead-p {
  max-width: 700px;
  margin: 1.5rem auto 0;
  text-align: center;
}
.about-cta-band {
  background: var(--charcoal);
  color: #fff;
  text-align: center;
  padding: 5rem 1.5rem;
}
.about-cta-band h2 { color: #fff; }
.about-cta-band h2::after { margin-left: auto; margin-right: auto; }
.about-cta-band p { color: rgba(255,255,255,0.75); margin: 0 auto 2rem; max-width: 600px; }
.about-cta-band .btn-pill { background: #fff; color: var(--charcoal); }
</style>
</head>
<body>

<?php include "parts/navbar.php"; ?>

<section class="about-hero">
  <img src="../images/about-hero.jpg" alt="Inside the Neon Kactus studio">
  <div class="overlay"></div>
  <div class="hero-text">
    <div class="container">
      <p class="label-accent">our story</p>
      <h1>Rooted in Design</h1>
    </div>
  </div>
</section>

<section class="sg-section container text-center">
  <p class="label-accent">about neon kactus</p>
  <h2>A Curated Approach</h2>
  <p class="lead-p">
    Neon Kactus began as a quiet conversation between two friends who believed plants belonged at the center of modern living — not as decoration, but as design. Today, we curate uncommon greenery and the vessels that hold them, sourcing from small growers who share our standard of patience and craft.
  </p>
</section>

<section class="sg-section container">
  <div class="text-center">
    <p class="label-accent">the founders</p>
    <h2>Amara &amp; Lukas</h2>
  </div>

  <div class="grid gap" style="margin-top: 3rem;">
    <div class="col-12 col-md-7">
      <img src="../images/founders-couple.jpg" alt="Amara Okafor and Lukas Andersen, co-founders of Neon Kactus" class="founder-photo">
      <p class="founder-caption">Co-Founders · Partners in Life &amp; Plants</p>
    </div>
    <div class="col-12 col-md-5">
      <h3>The Story Behind The Brand</h3>
      <p>
        Amara and Lukas met at a botanical garden in Copenhagen and bonded over a shared belief that plants deserved better — better sourcing, better design, better care. A few years later, they opened Neon Kactus together.
      </p>
      <p>
        Amara, a former interior designer, leads creative direction and curates every plant and planter in the collection. Lukas brings a Scandinavian sensibility to operations, sourcing, and the white-glove experience that arrives at your door.
      </p>
      <p>
        Together, they run the studio as both a business and a home — proof that the things you love are best built alongside the people you love.
      </p>
    </div>
  </div>
</section>

<section class="sg-section container">
  <div class="text-center">
    <p class="label-accent">what we believe</p>
    <h2>Our Values</h2>
  </div>

  <div class="grid gap" style="margin-top: 3rem;">
    <div class="col-12 col-md-4 text-center">
      <span class="value-num">01 / Craft</span>
      <h3>Quality Over Quantity</h3>
      <p style="max-width: 320px;">Every plant and planter is hand-selected. If it doesn't meet our standard, it doesn't make the floor.</p>
    </div>
    <div class="col-12 col-md-4 text-center">
      <span class="value-num">02 / Care</span>
      <h3>Plants Are Partners</h3>
      <p style="max-width: 320px;">We back every order with care guides, a 30-day plant guarantee, and real human help when you need it.</p>
    </div>
    <div class="col-12 col-md-4 text-center">
      <span class="value-num">03 / Community</span>
      <h3>Local First</h3>
      <p style="max-width: 320px;">We partner with regional growers, use recyclable packaging, and reinvest in greener neighborhoods.</p>
    </div>
  </div>
</section>

<section class="about-cta-band">
  <p class="label-accent" style="color: var(--gold);">visit the collection</p>
  <h2>Come See What's Growing</h2>
  <p>Browse the current collection or get in touch about styling.</p>
  <a href="product_list.php" class="btn-pill">Shop the Collection</a>
</section>

<?php include "parts/footer.php"; ?>
</body>
</html>
