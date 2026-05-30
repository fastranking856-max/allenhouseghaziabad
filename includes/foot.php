<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.0.2/glide.js"></script>
<?php require_once __DIR__ . '/glide-safe-script.php'; glideSafeScript(); ?>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<script>
(function () {
    var DEBUG = /^(localhost|127\.0\.0\.1)/.test(window.location.hostname);
    if (window.performance && performance.mark) {
        performance.mark("ah:foot:init:start");
    }
    function byId(id) { return document.getElementById(id); }
    function onReady(fn) {
        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", fn, { once: true });
        } else {
            fn();
        }
    }

    function setLeadSource() {
        function getParam(name) {
            return new URLSearchParams(window.location.search).get(name);
        }
        var source = getParam("utm_source") || document.referrer || "";
        if (!source) source = "Website";
        else if (source.includes("google.")) source = "Google-Ads by Agency";
        else if (source.includes("facebook.")) source = "Facebook by Agency";
        else if (source.includes("instagram.")) source = "Instagram by Agency";

        if (!sessionStorage.getItem("leadSource")) {
            sessionStorage.setItem("leadSource", source);
        }
        var sourceInput = byId("source");
        if (sourceInput) {
            sourceInput.value = sessionStorage.getItem("leadSource") || "Website";
        }
    }

    function initScrollTop() {
        var scrollBtn = byId("scroll");
        if (!scrollBtn) return;
        var ticking = false;
        window.addEventListener("scroll", function () {
            if (ticking) return;
            ticking = true;
            requestAnimationFrame(function () {
                scrollBtn.style.display = window.scrollY > 100 ? "block" : "none";
                ticking = false;
            });
        }, { passive: true });
        scrollBtn.addEventListener("click", function () {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    }

    function initMarqueeGlide() {
        if (typeof Glide === "undefined") return;
        if (!document.querySelector(".glide-marque")) return;
        new Glide(".glide-marque", {
            type: "carousel",
            autoplay: 1,
            animationDuration: 4500,
            animationTimingFunc: "linear",
            perView: 3,
            breakpoints: {
                1024: { perView: 2 },
                640: { perView: 1, gap: 36 }
            }
        }).mount();
    }

    function initPopup() {
        var popup = byId("popupForm");
        var openBtn = byId("openPopup");
        var closeBtn = byId("closePopup");
        var sessionSelect = byId("esession");
        var gradeSelect = byId("egrade");
        var citySelect = byId("ecity");
        var apiLoaded = false;

        function closePopup() {
            if (popup) popup.classList.add("hidden");
        }
        function openPopup() {
            if (!popup) return;
            popup.classList.remove("hidden");
            if (apiLoaded) return;

            Promise.all([
                fetch("includes/ajax-session.php").then(function (r) { return r.text(); }),
                fetch("includes/grade-api.php").then(function (r) { return r.text(); }),
                fetch("includes/get-city.php").then(function (r) { return r.text(); })
            ]).then(function (responses) {
                if (sessionSelect) sessionSelect.innerHTML = responses[0];
                if (gradeSelect) gradeSelect.innerHTML = responses[1];
                if (citySelect) citySelect.innerHTML = responses[2];
                apiLoaded = true;
                if (DEBUG) console.log("[AH] Popup APIs loaded once");
            }).catch(function (err) {
                console.error("Popup API load failed:", err);
            });
        }

        window.openPopup = openPopup;
        window.closePopup = closePopup;

        if (openBtn) {
            openBtn.addEventListener("click", function (e) {
                e.preventDefault();
                openPopup();
            });
        }
        if (closeBtn) {
            closeBtn.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                closePopup();
            });
        }
        if (popup) {
            popup.addEventListener("click", function (e) {
                if (e.target === popup) closePopup();
            });
        }

        document.addEventListener("click", function (e) {
            var closeEl = e.target.closest("#closePopup");
            if (closeEl) {
                e.preventDefault();
                e.stopPropagation();
                closePopup();
            }
        });
    }

    function initEnquiryForm() {
        var form = byId("enquiryForm");
        if (!form) return;
        var submitBtn = byId("submitBtn");
        var regex = {
            name: /^[A-Za-z\s]+$/,
            mobile: /^[6-9]\d{9}$/,
            email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            pincode: /^[1-9][0-9]{5}$/
        };

        var student = byId("estudent_name");
        var parent = byId("eparent_name");
        var mobile = byId("emobile");
        var email = byId("eemail");
        var pincode = byId("epincode");
        var session = byId("esession");
        var grade = byId("egrade");
        var city = byId("ecity");

        function bindInputValidation(input, test, errorId, normalizer) {
            var errorEl = byId(errorId);
            if (!input || !errorEl) return;
            input.addEventListener("input", function () {
                if (typeof normalizer === "function") normalizer(input);
                errorEl.classList.toggle("hidden", !input.value || test(input.value));
            });
        }

        bindInputValidation(student, function (v) { return regex.name.test(v); }, "student-error");
        bindInputValidation(parent, function (v) { return regex.name.test(v); }, "parent-error");
        bindInputValidation(mobile, function (v) { return regex.mobile.test(v); }, "mobile-error", function (el) { el.value = el.value.replace(/\D/g, "").slice(0, 10); });
        bindInputValidation(email, function (v) { return regex.email.test(v); }, "email-error", function (el) { el.value = el.value.toLowerCase(); });
        bindInputValidation(pincode, function (v) { return regex.pincode.test(v); }, "pincode-error", function (el) { el.value = el.value.replace(/\D/g, "").slice(0, 6); });

        form.addEventListener("submit", function (e) {
            e.preventDefault();
            var payload = {
                session: (session && session.value || "").trim(),
                grade: (grade && grade.value || "").trim(),
                studentName: (student && student.value || "").trim(),
                parentName: (parent && parent.value || "").trim(),
                mobile: (mobile && mobile.value || "").trim(),
                email: (email && email.value || "").trim(),
                city: (city && city.value || "").trim(),
                pincode: (pincode && pincode.value || "").trim(),
                source: sessionStorage.getItem("leadSource") || "Website",
                source_type: "Website"
            };

            var valid = regex.name.test(payload.studentName)
                && regex.name.test(payload.parentName)
                && regex.mobile.test(payload.mobile)
                && regex.email.test(payload.email)
                && regex.pincode.test(payload.pincode)
                && payload.grade !== ""
                && payload.session !== ""
                && payload.city !== "";
            if (!valid) return;

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = "Submitting...";
            }

            fetch("proxy/admission-proxy", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(payload)
            })
            .then(function (response) {
                if (!response.ok) throw new Error("HTTP " + response.status);
                return response.json();
            })
            .then(function () {
                var success = byId("esuccessPopup");
                if (success) {
                    success.classList.remove("hidden");
                    setTimeout(function () { success.classList.add("hidden"); }, 5000);
                }
                if (window.closePopup) window.closePopup();
                form.reset();
                sessionStorage.removeItem("leadSource");
                if (DEBUG) console.log("[AH] Enquiry submitted");
            })
            .catch(function (err) {
                console.error("Enquiry form submit failed:", err);
            })
            .finally(function () {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = "Submit";
                }
            });
        });
    }

    function initCustomSelects() {
        var wrappers = document.querySelectorAll(".customSelect");
        wrappers.forEach(function (wrapper) {
            var realSelect = wrapper.querySelector("select");
            var display = wrapper.querySelector(".selected-text");
            var dropdown = wrapper.querySelector("div.absolute");
            var searchInput = dropdown ? dropdown.querySelector("input") : null;
            var optionsList = dropdown ? dropdown.querySelector("ul") : null;
            if (!realSelect || !display || !dropdown || !searchInput || !optionsList) return;

            function loadOptions(filter) {
                optionsList.innerHTML = "";
                Array.from(realSelect.options).forEach(function (opt) {
                    if (!opt.value) return;
                    if (opt.text.toLowerCase().indexOf((filter || "").toLowerCase()) === -1) return;
                    var li = document.createElement("li");
                    li.textContent = opt.text;
                    li.className = "p-2 hover:bg-gray-100 cursor-pointer";
                    li.dataset.value = opt.value;
                    optionsList.appendChild(li);
                });
            }

            display.parentElement.addEventListener("click", function () {
                dropdown.classList.toggle("hidden");
                searchInput.value = "";
                loadOptions("");
                searchInput.focus();
            });
            searchInput.addEventListener("input", function () {
                loadOptions(searchInput.value);
            });
            optionsList.addEventListener("click", function (e) {
                if (e.target.tagName !== "LI") return;
                realSelect.value = e.target.dataset.value;
                display.textContent = e.target.textContent;
                dropdown.classList.add("hidden");
            });
            document.addEventListener("click", function (e) {
                if (!wrapper.contains(e.target)) dropdown.classList.add("hidden");
            });
        });
    }

    onReady(function () {
        setLeadSource();
        initScrollTop();
        initMarqueeGlide();
        initPopup();
        initEnquiryForm();
        initCustomSelects();
        if (window.performance && performance.mark && performance.measure) {
            performance.mark("ah:foot:init:end");
            performance.measure("ah:foot:init", "ah:foot:init:start", "ah:foot:init:end");
        }
        if (DEBUG) console.log("[AH] Shared footer init complete");
    });
})();
</script>
