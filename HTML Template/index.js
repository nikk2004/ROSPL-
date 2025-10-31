$(document).ready(function () {

    // ---------- OWL CAROUSELS ----------
    $("#banner-area .owl-carousel").owlCarousel({ dots: true, items: 1 });
    $("#top-sale .owl-carousel").owlCarousel({
        loop: true, nav: true, dots: false,
        responsive: {0:{items:1},600:{items:3},1000:{items:5}}
    });
    $("#new-phones .owl-carousel").owlCarousel({
        loop: true, nav: false, dots: true,
        responsive: {0:{items:1},600:{items:3},1000:{items:5}}
    });
    $("#blogs .owl-carousel").owlCarousel({
        loop: true, nav: false, dots: true,
        responsive: {0:{items:1},600:{items:3}}
    });

    // ---------- ISOTOPE FILTER ----------
    var $grid = $(".grid").isotope({ itemSelector: '.grid-item', layoutMode: 'fitRows' });
    $(".button-group").on("click", "button", function () {
        $grid.isotope({ filter: $(this).attr('data-filter') });
        $(this).addClass('is-checked').siblings().removeClass('is-checked');
    });

    // ---------- QUANTITY CONTROLS ----------
    function updateQty($input, increment) {
        let val = parseInt($input.val());
        if (!isNaN(val)) {
            let newVal = val + increment;
            if (newVal >= 1 && newVal <= 10) $input.val(newVal).trigger("change");
        }
    }
    $(document).on("click", ".qty .qty-up", function () { updateQty($(this).siblings(".qty_input"), 1); });
    $(document).on("click", ".qty .qty-down", function () { updateQty($(this).siblings(".qty_input"), -1); });

    // ---------- CART SYSTEM ----------
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    function saveCart() { localStorage.setItem("cart", JSON.stringify(cart)); }

    function addToCart(product) {
        let existing = cart.find(item => item.id === product.id);
        if (existing) existing.qty++;
        else cart.push(product);
        saveCart(); displayCart();
        alert(`${product.name} added to cart!`);
    }

    $(".add-to-cart").click(function () {
        let product = {
            id: $(this).data("id"),
            name: $(this).data("name"),
            price: parseFloat($(this).data("price")),
            image: $(this).data("image"),
            qty: 1
        };
        addToCart(product);
    });

    function displayCart() {
        let cartContainer = $("#cart .cart-items");
        let subtotalEl = $("#deal-price");
        if (!cartContainer.length) return;
        cartContainer.empty();
        let subtotal = 0;
        cart.forEach((item, index) => {
            subtotal += item.price * item.qty;
            cartContainer.append(`
                <div class="row border-top py-3 mt-3">
                    <div class="col-sm-2"><img src="${item.image}" style="height:120px;" class="img-fluid"></div>
                    <div class="col-sm-6">
                        <h5>${item.name}</h5>
                        <div class="qty d-flex pt-2">
                            <button class="qty-down btn btn-sm btn-light" data-index="${index}">-</button>
                            <input type="text" value="${item.qty}" class="qty_input text-center" readonly>
                            <button class="qty-up btn btn-sm btn-light" data-index="${index}">+</button>
                        </div>
                    </div>
                    <div class="col-sm-2 text-right">
                        <h5>$${(item.price*item.qty).toFixed(2)}</h5>
                        <button class="btn btn-sm btn-danger remove" data-index="${index}">Remove</button>
                    </div>
                </div>
            `);
        });
        subtotalEl.text(subtotal.toFixed(2));
    }

    // Cart event handlers
    $(document).on("click", "#cart .qty-up", function () {
        let i = $(this).data("index"); cart[i].qty++; saveCart(); displayCart();
    });
    $(document).on("click", "#cart .qty-down", function () {
        let i = $(this).data("index"); if(cart[i].qty>1) cart[i].qty--; saveCart(); displayCart();
    });
    $(document).on("click", "#cart .remove", function () {
        let i = $(this).data("index"); cart.splice(i,1); saveCart(); displayCart();
    });

    // Initial cart display
    displayCart();
});
