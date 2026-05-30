<?php
if (!function_exists('site_base_url')) {
    require_once __DIR__ . '/environment.php';
}
$newsletterButtonLabel = isset($newsletterButton) ? (string) $newsletterButton : 'Subscribe';
$newsletterSiteBaseUrl = rtrim(
    ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http')
    . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
    . rtrim(site_base_url(), '/'),
    '/'
);
?>
<script>
(function () {
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    var emailInput = document.getElementById("news-email");
    var emailError = document.getElementById("news-email-error");
    var submitBtn = document.getElementById("newsSubmit");
    var form = document.getElementById("newsForm");
    var popup = document.getElementById("NewsPopup");
    var buttonLabel = <?= json_encode($newsletterButtonLabel) ?>;
    var proxyUrl = <?= json_encode($newsletterSiteBaseUrl . '/proxy/newsletter-proxy', JSON_UNESCAPED_SLASHES) ?>;

    if (!emailInput || !emailError || !submitBtn || !form || !popup) {
        return;
    }

    emailInput.addEventListener("input", function () {
        this.value = this.value.toLowerCase().trim();
        if (!this.value) {
            emailError.classList.add("hidden");
        } else {
            emailError.classList.toggle("hidden", emailRegex.test(this.value));
        }
    });

    form.addEventListener("submit", function (e) {
        e.preventDefault();
        var email = emailInput.value.trim();
        if (!email) {
            emailError.textContent = "Please enter an email address.";
            emailError.classList.remove("hidden");
            return;
        }
        if (!emailRegex.test(email)) {
            emailError.textContent = "Please enter a valid email address.";
            emailError.classList.remove("hidden");
            return;
        }

        emailError.classList.add("hidden");
        submitBtn.disabled = true;
        submitBtn.textContent = "Subscribing...";

        fetch(proxyUrl, {
            method: "POST",
            headers: { "Content-Type": "application/json", "Accept": "application/json" },
            body: JSON.stringify({ email: email })
        })
        .then(function (response) {
            return response.json().then(function (data) {
                if (!response.ok || data.success === false) {
                    throw new Error(data.message || "Subscription failed");
                }
                return data;
            });
        })
        .then(function () {
            popup.classList.remove("hidden");
            setTimeout(function () { popup.classList.add("hidden"); }, 5000);
            form.reset();
        })
        .catch(function (error) {
            alert(error.message || "There was an error submitting the form. Please try again.");
            console.error("Newsletter error:", error);
        })
        .finally(function () {
            submitBtn.disabled = false;
            submitBtn.textContent = buttonLabel;
        });
    });
})();
</script>
