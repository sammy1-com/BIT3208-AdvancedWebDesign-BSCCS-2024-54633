document.addEventListener('DOMContentLoaded', function () {

    var nav = document.getElementById('main-nav');
    if (nav) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 80) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    }

    var slides = document.querySelectorAll('.hero-slide');
    var dots = document.querySelectorAll('.hero-dot');
    var currentSlide = 0;

    function showSlide(index) {
        slides.forEach(function (s) { s.classList.remove('active'); });
        dots.forEach(function (d) { d.classList.remove('active'); });
        slides[index].classList.add('active');
        if (dots[index]) dots[index].classList.add('active');
        currentSlide = index;
    }

    if (slides.length > 0) {
        showSlide(0);
        setInterval(function () {
            var next = (currentSlide + 1) % slides.length;
            showSlide(next);
        }, 5000);
        dots.forEach(function (dot, i) {
            dot.addEventListener('click', function () { showSlide(i); });
        });
    }

    var searchTabs = document.querySelectorAll('.search-tab');
    var vehicleForm = document.getElementById('vehicle-search-form');
    var partForm = document.getElementById('part-search-form');

    searchTabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            searchTabs.forEach(function (t) { t.classList.remove('active'); });
            tab.classList.add('active');
            var target = tab.getAttribute('data-target');
            if (vehicleForm) vehicleForm.style.display = target === 'vehicle' ? 'flex' : 'none';
            if (partForm) partForm.style.display = target === 'part' ? 'flex' : 'none';
        });
    });

    var makeSelect = document.getElementById('make-select');
    var modelSelect = document.getElementById('model-select');

    if (makeSelect && modelSelect) {
        makeSelect.addEventListener('change', function () {
            var makeId = this.value;
            modelSelect.innerHTML = '<option value="">Model</option>';
            if (!makeId) return;
            fetch('/pitstop/ajax/get-models.php?make_id=' + makeId)
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    data.forEach(function (m) {
                        var opt = document.createElement('option');
                        opt.value = m.id;
                        opt.textContent = m.name;
                        modelSelect.appendChild(opt);
                    });
                });
        });
    }

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                if (entry.target.classList.contains('product-card')) {
                    entry.target.classList.add('revealed');
                }
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.reveal, .product-card').forEach(function (el) {
        observer.observe(el);
    });

    var qtyInput = document.getElementById('qty-input');
    var btnMinus = document.getElementById('qty-minus');
    var btnPlus = document.getElementById('qty-plus');

    if (qtyInput && btnMinus && btnPlus) {
        btnMinus.addEventListener('click', function () {
            var v = parseInt(qtyInput.value) || 1;
            if (v > 1) qtyInput.value = v - 1;
        });
        btnPlus.addEventListener('click', function () {
            var v = parseInt(qtyInput.value) || 1;
            qtyInput.value = v + 1;
        });
    }

    var cartForms = document.querySelectorAll('.update-cart-form');
    cartForms.forEach(function (form) {
        var input = form.querySelector('.cart-qty');
        if (input) {
            input.addEventListener('change', function () {
                form.submit();
            });
        }
    });

});
