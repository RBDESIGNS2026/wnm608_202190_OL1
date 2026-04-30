// ============================================
// /js/templates.js
// UI templates (JS versions of the PHP product card)
// ============================================

function productCardTemplate(p) {
  var price = Number(p.price).toFixed(2);
  return (
    '<div class="col-12 col-md-4">' +
      '<a href="product_item.php?id=' + p.id + '" class="product-card-link">' +
        '<div class="product-card">' +
          '<div class="product-image-wrapper">' +
            '<img src="' + p.image + '" alt="' + p.title + '">' +
          '</div>' +
          '<div class="product-info">' +
            '<h3 class="product-name">' + p.title + '</h3>' +
            '<p class="label-accent" style="font-style:italic;opacity:.7;margin:0 0 .35rem;">' + (p.latin || '') + '</p>' +
            '<div class="display-flex flex-justify-between flex-align-center">' +
              '<p class="product-price">$' + price + '</p>' +
              '<span class="btn-pill btn-sm">View</span>' +
            '</div>' +
          '</div>' +
        '</div>' +
      '</a>' +
    '</div>'
  );
}

// Curried renderer ready to accept an array of products
var renderProducts = templator(productCardTemplate);
