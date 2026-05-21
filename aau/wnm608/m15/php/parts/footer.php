<!-- SITE FOOTER -->
<footer class="site-footer container">
  <div class="grid gap">
    <div class="col-12 col-md-3">
      <h3 class="footer-brand">NEON KACTUS®</h3>
      <p class="footer-desc">Premium plants &amp; planters for modern living.</p>
    </div>
    <div class="col-6 col-md-3">
      <h4 class="footer-heading">Shop</h4>
      <ul class="footer-links">
        <li><a href="product_list.php">All Plants</a></li>
        <li><a href="product_list.php">Indoor</a></li>
        <li><a href="product_list.php">Outdoor</a></li>
        <li><a href="product_list.php">Planters</a></li>
      </ul>
    </div>
    <div class="col-6 col-md-3">
      <h4 class="footer-heading">Help</h4>
      <ul class="footer-links">
        <li><a href="#">Care Guide</a></li>
        <li><a href="#">Shipping</a></li>
        <li><a href="#">Returns</a></li>
        <li><a href="#">FAQ</a></li>
      </ul>
    </div>
    <div class="col-12 col-md-3">
      <h4 class="footer-heading">Connect</h4>
      <ul class="footer-links">
        <li><a href="#">Instagram</a></li>
        <li><a href="#">Pinterest</a></li>
        <li><a href="#">Contact Us</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; <?= date("Y") ?> Neon Kactus. All rights reserved.</p>
    <p class="footer-staff">
      <?php
        // Resolve admin link relative to current script (works from /php/ and /php/admin/)
        $admin_href = (strpos($_SERVER['PHP_SELF'] ?? '', '/admin/') !== false) ? 'users.php' : 'admin/users.php';
      ?>
      <a href="<?= $admin_href ?>" class="staff-link">Staff Sign In →</a>
    </p>
  </div>
</footer>
