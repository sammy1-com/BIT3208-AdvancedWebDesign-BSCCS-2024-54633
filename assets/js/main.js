// ============================================================
// PitStop Parts — main.js (Week 3: JavaScript Basics)
// ============================================================

// ── 1. HERO SLIDESHOW ──────────────────────────────────────
(function () {
    var slides = document.querySelectorAll('.hero-slide');
    var dots   = document.querySelectorAll('.hero-dot');
    if (!slides.length) return;

    var current = 0;
    var interval;

    function goTo(index) {
        slides.forEach(function (s) { s.classList.remove('active'); });
        dots.forEach(function (d)  { d.classList.remove('active'); });
        current = (index + slides.length) % slides.length;
        slides[current].classList.add('active');
        if (dots[current]) dots[current].classList.add('active');
    }

    function next() { goTo(current + 1); }

    function start() { interval = setInterval(next, 4000); }
    function stop()  { clearInterval(interval); }

    // Auto-play
    goTo(0);
    start();

    // Dot click navigation
    dots.forEach(function (dot, i) {
        dot.addEventListener('click', function () {
            stop(); goTo(i); start();
        });
    });
})();

// ── 2. SEARCH TABS (By Vehicle / By Part) ─────────────────
(function () {
    var tabs  = document.querySelectorAll('.search-tab');
    var forms = { vehicle: document.getElementById('vehicle-search-form'),
                  part:    document.getElementById('part-search-form') };

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            tabs.forEach(function (t) { t.classList.remove('active'); });
            tab.classList.add('active');

            var target = tab.getAttribute('data-target');
            Object.keys(forms).forEach(function (key) {
                if (forms[key]) forms[key].style.display = key === target ? '' : 'none';
            });
        });
    });
})();

// ── 3. DYNAMIC MODEL DROPDOWN (Fetch API / AJAX) ──────────
(function () {
    var makeSelect  = document.getElementById('make-select');
    var modelSelect = document.getElementById('model-select');
    if (!makeSelect || !modelSelect) return;

    makeSelect.addEventListener('change', function () {
        var makeId = this.value;
        modelSelect.innerHTML = '<option value="">Loading...</option>';

        if (!makeId) {
            modelSelect.innerHTML = '<option value="">Model</option>';
            return;
        }

        // WEEK 3: Fetch API call to our PHP AJAX endpoint
        fetch('ajax/get-models.php?make_id=' + makeId)
            .then(function (res) { return res.json(); })
            .then(function (models) {
                modelSelect.innerHTML = '<option value="">Model</option>';
                models.forEach(function (m) {
                    var opt = document.createElement('option');
                    opt.value = m.id;
                    opt.textContent = m.name;
                    modelSelect.appendChild(opt);
                });
            })
            .catch(function () {
                modelSelect.innerHTML = '<option value="">Error loading models</option>';
            });
    });
})();

// ── 4. SCROLL REVEAL ANIMATIONS ────────────────────────────
(function () {
    var reveals = document.querySelectorAll('.reveal');
    if (!reveals.length) return;

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    reveals.forEach(function (el) { observer.observe(el); });
})();
