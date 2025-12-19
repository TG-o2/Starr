// Clean & unified validation for Make-report
(function () {
    const form = document.getElementById("reportForm");
    if (!form) return;

    const el = {
        type: document.getElementById("reportType"),
        reason: document.getElementById("report-reason"),
        desc: document.getElementById("report-description"),
        msg: {
            type: document.getElementById("reported-type-msg"),
            reason: document.getElementById("reported-reason-msg"),
            desc: document.getElementById("reported-details-msg"),
        }
    };

    /* ---------- helpers ---------- */
    function ok(input, msg, text = "Accepted") {
        if (input) input.style.border = "2px solid #28a745";
        if (msg) { msg.style.color = "green"; msg.innerText = text; }
        return true;
    }
    function error(input, msg, text) {
        if (input) input.style.border = "2px solid #dc3545";
        if (msg) { msg.style.color = "red"; msg.innerText = text; }
        return false;
    }
    function clear(input, msg) {
        if (input) input.style.border = "";
        if (msg) msg.innerText = "";
    }
    function validateSelect(select, msg, text) {
        if (select && select.value && select.value !== "default") {
            return ok(select, msg);
        }
        return error(select, msg, text);
    }

    /* ---------- live feedback ---------- */
    el.type?.addEventListener("change", () =>
        validateSelect(el.type, el.msg.type, "Please select what you are reporting")
    );
    el.reason?.addEventListener("change", () =>
        validateSelect(el.reason, el.msg.reason, "Please select a reason")
    );

    /* ---------- submit ---------- */
    form.addEventListener("submit", (e) => {
        let valid = true;

        // Type
        valid = validateSelect(
            el.type,
            el.msg.type,
            "Please select what you are reporting"
        ) && valid;

        // Reason
        valid = validateSelect(
            el.reason,
            el.msg.reason,
            "Please select a reason"
        ) && valid;

        // Description (optional)
        if (el.desc) {
            const descVal = el.desc.value.trim();
            if (descVal) {
                ok(el.desc, el.msg.desc, "Thanks for the details");
            } else {
                clear(el.desc, el.msg.desc);
            }
        }

        if (!valid) {
            e.preventDefault();
            alert("Please complete the highlighted fields.");
        }
    });
})();