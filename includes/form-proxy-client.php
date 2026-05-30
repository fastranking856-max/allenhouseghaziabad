<?php
/**
 * Shared JS helpers for contact / newsletter / enquiry proxy responses.
 */
?>
<script>
window.cmsParseProxyJson = function (response) {
    return response.text().then(function (text) {
        var data = {};
        if (text) {
            try {
                data = JSON.parse(text);
            } catch (e) {
                throw new Error("Invalid server response. Please try again.");
            }
        }
        if (!response.ok || data.success === false) {
            var msg = data.message || "Submission failed. Please try again.";
            if (!data.message && data.errors && typeof data.errors === "object") {
                var keys = Object.keys(data.errors);
                if (keys.length && data.errors[keys[0]] && data.errors[keys[0]][0]) {
                    msg = data.errors[keys[0]][0];
                }
            }
            throw new Error(msg);
        }
        return data;
    });
};
</script>
