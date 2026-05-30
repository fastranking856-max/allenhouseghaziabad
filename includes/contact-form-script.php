<?php
if (!function_exists('site_base_url')) {
    require_once __DIR__ . '/environment.php';
}
$contactSiteBaseUrl = rtrim(
    ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http')
    . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
    . rtrim(site_base_url(), '/'),
    '/'
);
?>
<script>
(function () {
    var nameRegex = /^[A-Za-z\s]*$/;
    var mobileRegex = /^[6-9]\d{9}$/;
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    var maxName = 50;
    var proxyUrl = <?= json_encode($contactSiteBaseUrl . '/proxy/contact-proxy', JSON_UNESCAPED_SLASHES) ?>;

    var form = document.getElementById("contactForm");
    var nameIn = document.getElementById("cname");
    var nameErr = document.getElementById("cname-error");
    var nameLenErr = document.getElementById("cname-length-error");
    var mobIn = document.getElementById("cmobile");
    var mobErr = document.getElementById("mobileErrorss");
    var emailIn = document.getElementById("cemail");
    var emailErr = document.getElementById("emailErrorss");
    var submitBtn = document.getElementById("contactSubmitBtn");
    var successPopup = document.getElementById("successPopup");

    if (!form || !nameIn || !mobIn || !emailIn || !submitBtn) {
        return;
    }

    nameIn.addEventListener("input", function () {
        var v = this.value;
        if (!nameRegex.test(v)) {
            this.value = v.replace(/[^A-Za-z\s]/g, '');
            if (nameErr) nameErr.classList.remove("hidden");
        } else if (nameErr) {
            nameErr.classList.add("hidden");
        }
        if (this.value.length > maxName) {
            this.value = this.value.slice(0, maxName);
            if (nameLenErr) nameLenErr.classList.remove("hidden");
        } else if (nameLenErr) {
            nameLenErr.classList.add("hidden");
        }
    });

    nameIn.addEventListener("paste", function () {
        setTimeout(function () {
            nameIn.value = nameIn.value.replace(/[^A-Za-z\s]/g, '').slice(0, maxName);
            if (nameErr) nameErr.classList.toggle("hidden", nameRegex.test(nameIn.value));
            if (nameLenErr) nameLenErr.classList.toggle("hidden", nameIn.value.length <= maxName);
        }, 0);
    });

    mobIn.addEventListener("input", function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
        if (mobErr) mobErr.classList.toggle("hidden", mobileRegex.test(this.value) || this.value === "");
    });

    emailIn.addEventListener("input", function () {
        if (emailErr) emailErr.classList.toggle("hidden", emailRegex.test(this.value) || this.value === "");
    });

    form.addEventListener("submit", function (e) {
        e.preventDefault();
        submitBtn.disabled = true;
        submitBtn.textContent = "Submitting...";

        var name = nameIn.value.trim();
        var mobile = mobIn.value.trim();
        var email = emailIn.value.trim();
        var queryEl = document.getElementById("cquery");
        var messageEl = document.getElementById("cmessage");
        var query = queryEl ? queryEl.value : "";
        var message = messageEl ? messageEl.value.trim() : "";
        var ok = true;

        if (!nameRegex.test(name) || name === "") {
            if (nameErr) nameErr.classList.remove("hidden");
            ok = false;
        } else if (nameErr) {
            nameErr.classList.add("hidden");
        }

        if (name.length > maxName) {
            if (nameLenErr) nameLenErr.classList.remove("hidden");
            ok = false;
        } else if (nameLenErr) {
            nameLenErr.classList.add("hidden");
        }

        if (!mobileRegex.test(mobile)) {
            if (mobErr) mobErr.classList.remove("hidden");
            ok = false;
        } else if (mobErr) {
            mobErr.classList.add("hidden");
        }

        if (!emailRegex.test(email)) {
            if (emailErr) emailErr.classList.remove("hidden");
            ok = false;
        } else if (emailErr) {
            emailErr.classList.add("hidden");
        }

        if (!ok) {
            submitBtn.disabled = false;
            submitBtn.textContent = "Submit";
            return;
        }

        fetch(proxyUrl, {
            method: "POST",
            headers: { "Content-Type": "application/json", "Accept": "application/json" },
            body: JSON.stringify({ name: name, mobile: mobile, email: email, query: query, message: message })
        })
        .then(function (response) {
            return response.json().then(function (data) {
                if (!response.ok || data.success === false) {
                    throw new Error(data.message || "Submission failed");
                }
                return data;
            });
        })
        .then(function () {
            if (successPopup) {
                successPopup.classList.remove("hidden");
                setTimeout(function () { successPopup.classList.add("hidden"); }, 10000);
            }
            form.reset();
            [nameErr, nameLenErr, mobErr, emailErr].forEach(function (el) {
                if (el) el.classList.add("hidden");
            });
        })
        .catch(function (error) {
            alert(error.message || "Submission failed. Please try again.");
            console.error("Contact form error:", error);
        })
        .finally(function () {
            submitBtn.disabled = false;
            submitBtn.textContent = "Submit";
        });
    });
})();
</script>
