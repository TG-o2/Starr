const form = document.getElementById("reportForm");

// Validation flags
let reportedUserId = false;
let reportedPostId = false;
let reportedCommentId = false;
let reportedLessonId = false;

let reportReason = false;
let reportDescription = false;
let reportType = false;

// Function to validate text fields (min length 3)
function validateText(inputId, msgId) {
    const msg = document.getElementById(msgId);
    const input = document.getElementById(inputId);
    if (!input) return false;

    const val = input.value.trim();
    if (val.length >= 3) {
        input.style.border = "2px solid #28a745";
        if (msg) { msg.style.color = "green"; msg.innerText = "Accepted"; }
        return true;
    } else if (val.length > 0) {
        input.style.border = "2px solid #dc3545";
        if (msg) { msg.style.color = "red"; msg.innerText = "Minimum length is 3 characters"; }
        return false;
    } else {
        input.style.border = "";
        if (msg) { msg.innerText = ""; }
        return false;
    }
}

function validateID(inputId, msgId) {
    const msg = document.getElementById(msgId);
    const input = document.getElementById(inputId);
    if (!input) return false;

    const val = input.value.trim();
    if (val === "") {
        input.style.border = "";
        if (msg) { msg.innerText = ""; }
        return false;
    }

    const num = Number(val);
    if (isNaN(num) || num < 0 || num > 9999) {
        input.style.border = "2px solid #dc3545";
        if (msg) { msg.style.color = "red"; msg.innerText = "ID must be numeric and between 0-9999"; }
        return false;
    }

    if (val.length >= 3) {
        input.style.border = "2px solid #28a745";
        if (msg) { msg.style.color = "green"; msg.innerText = "ID accepted"; }
        return true;
    } else {
        input.style.border = "2px solid #dc3545";
        if (msg) { msg.style.color = "red"; msg.innerText = "ID minimum length is 3 characters"; }
        return false;
    }
}

// Function to validate select fields
function validateSelect(selectId, msgId) {
    const msg = document.getElementById(msgId);
    const select = document.getElementById(selectId);
    if (!select) return false;

    if (select.value !== "" && select.value !== "default") {
        select.style.border = "2px solid #28a745"; // green
        if (msg) { msg.style.color = "green"; msg.innerText = "accepted"; }
        return true;
    } else {
        select.style.border = "2px solid #dc3545"; // red
        if (msg) { msg.style.color = "red"; msg.innerText = "Please select a reason"; }
        return false;
    }
}

document.getElementById("reported-user-id")?.addEventListener("input", () => {
    reportedUserId = validateID("reported-user-id", "reported-user-id-msg");
});
document.getElementById("reported-post-id")?.addEventListener("input", () => {
    reportedPostId = validateID("reported-post-id", "reported-post-id-msg");
});
document.getElementById("reported-comment-id")?.addEventListener("input", () => {
    reportedCommentId = validateID("reported-comment-id", "reported-comment-id-msg");
});
document.getElementById("reported-lesson-id")?.addEventListener("input", () => {
    reportedLessonId = validateID("reported-lesson-id", "reported-lesson-id-msg");
});

document.getElementById("report-reason")?.addEventListener("change", () => {
    reportReason = validateSelect("report-reason", "reported-reason-msg");
});

document.getElementById("report-description")?.addEventListener("input", () => {
    reportDescription = validateText("report-description", "reported-details-msg");
});

document.getElementById("reportType")?.addEventListener("change", () => {
    reportType = validateSelect("reportType", "reported-type-msg");
});

if (form) {
    form.addEventListener("submit", function(event) {

        const type = document.getElementById("reportType")?.value || "";

        // Validate required fields
        const reasonValid = validateSelect("report-reason", "reported-reason-msg");
        const descValid   = validateText("report-description", "reported-details-msg");
        const typeValid   = validateSelect("reportType", "reported-type-msg");

        // Validate the correct ID depending on type
        let idValid = false;

        if (type === "user") {
            idValid = validateID("reported-user-id", "reported-user-id-msg");
        }
        else if (type === "post") {
            idValid = validateID("reported-post-id", "reported-post-id-msg");
        }
        else if (type === "comment") {
            idValid = validateID("reported-comment-id", "reported-comment-id-msg");
        }
        else if (type === "lesson") {
            idValid = validateID("reported-lesson-id", "reported-lesson-id-msg");
        }

        if (!typeValid || !idValid || !reasonValid ) {
            alert("Please fill the correct ID and all required fields.");
            event.preventDefault();
        } else {
            alert("Form is valid!");
        }
    });
}


