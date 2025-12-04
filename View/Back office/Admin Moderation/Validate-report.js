const form = document.getElementById("reportForm");

// Validation flags
var reportedUserId = false;
var reportedPostId = false;
var reportedCommentId = false;
var reportedLessonId = false;

var reportReason = false;
var reportDescription = false;

// Function to validate text fields (min length 3)
function validateText(inputId) {
    const input = document.getElementById(inputId);
    if (!input) return false;

    if (input.value.trim().length >= 3) {
        input.style.border = "2px solid #28a745"; 
        return true;
    } else if (input.value.trim().length > 0) {
        input.style.border = "2px solid #dc3545"; 
        return false;
    } else {
        input.style.border = "";
        return false;
    }
}

// Function to validate select fields
function validateSelect(selectId) {
    const select = document.getElementById(selectId);
    if (!select) return false;

    if (select.value !== "" && select.value !== "default") {
        select.style.border = "2px solid #28a745"; // green
        return true;
    } else {
        select.style.border = "2px solid #dc3545"; // red
        return false;
    }
}


document.getElementById("reported-user-id")?.addEventListener("input", () => {
    reportedUserId = validateText("reported-user-id");
});
document.getElementById("reported-post-id")?.addEventListener("input", () => {
    reportedPostId = validateText("reported-post-id");
});
document.getElementById("reported-comment-id")?.addEventListener("input", () => {
    reportedCommentId = validateText("reported-comment-id");
});
document.getElementById("reported-lesson-id")?.addEventListener("input", () => {
    reportedLessonId = validateText("reported-lesson-id");
});

document.getElementById("report-reason")?.addEventListener("change", () => {
    reportReason = validateSelect("report-reason");
});

document.getElementById("report-description")?.addEventListener("input", () => {
    reportDescription = validateText("report-description");
});

// HANDLE FORM SUBMISSION
form.addEventListener("submit", function(event) {

    // Validate everything again before submission
    reportedUserId = validateText("reported-user-id");
    reportedPostId = validateText("reported-post-id");
    reportedCommentId = validateText("reported-comment-id");
    reportedLessonId = validateText("reported-lesson-id");
    reportReason = validateSelect("report-reason");
    reportDescription = validateText("report-description");

    
    if (
        !reportReason ||
        !reportDescription ||
        (
            !reportedUserId &&
            !reportedPostId &&
            !reportedCommentId &&
            !reportedLessonId
        )
    ) {
        alert("You must fill at least one ID, and complete all required fields.");
        event.preventDefault();
    }
});
