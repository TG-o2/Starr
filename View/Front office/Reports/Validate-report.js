const form = document.getElementById("reportForm");

// Validation flags
var reportedUserId = false;
var reportedPostId = false;
var reportedCommentId = false;
var reportedLessonId = false;

var reportReason = false;
var reportDescription = false;

// Function to validate text fields (min length 3)
function validateText(inputId, msgId) {
    const msg = document.getElementById(msgId);
    const input = document.getElementById(inputId);
    if (!input) return false;

    if (input.value.trim().length >= 3) {
        input.style.border = "2px solid #28a745"; 
        msg.style.color = "green";
        msg.innerText = "ID accepted";
        return true;
    } else if (input.value.trim().length > 0) {
        input.style.border = "2px solid #dc3545"; 
        msg.style.color = "red";
        msg.innerText = "ID minimum length is 3 characters";
        return false;
    } else {
        input.style.border = "";
        return false;
    }
}

function validateID(inputId, msgId) {
    const msg = document.getElementById(msgId);
    const input = document.getElementById(inputId);
    if (isNaN(input.value) || input.value > 9999 || input.value < 0) {
        input.style.border = "2px solid #dc3545"; 
        msg.style.color = "red";
        msg.innerText = "ID must be numeric";
        return false;
    }
    if (input.value.trim().length >= 3) {
        input.style.border = "2px solid #28a745"; 
        msg.style.color = "green";
        msg.innerText = "ID accepted";
        return true;
    } else if (input.value.trim().length > 0) {
        input.style.border = "2px solid #dc3545"; 
        msg.style.color = "red";
        msg.innerText = "ID minimum length is 3 characters";
        return false;
    } else {
        input.style.border = "";
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
        msg.style.color = "green";
        msg.innerText = "accepted";
        return true;
    } else {
        select.style.border = "2px solid #dc3545"; // red
        msg.style.color = "red";
        msg.innerText = "Please select a reason";
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

form.addEventListener("submit", function(event) {

    // Validate ID fields correctly
    reportedUserId = validateID("reported-user-id", "reported-user-id-msg");
    reportedPostId = validateID("reported-post-id", "reported-post-id-msg");
    reportedCommentId = validateID("reported-comment-id", "reported-comment-id-msg");
    reportedLessonId = validateID("reported-lesson-id", "reported-lesson-id-msg");

    // Validate reason + Type + description
    reportReason = validateSelect("report-reason", "reported-reason-msg");
    reportDescription = validateText("report-description", "reported-details-msg");
    
    // Check type validity
    let typeValid = false;
    if (reportType.value === "User" && reportedUserId) typeValid = true;
    else if (reportType.value === "Post" && reportedPostId) typeValid = true;
    else if (reportType.value === "Comment" && reportedCommentId) typeValid = true;
    else if (reportType.value === "Lesson" && reportedLessonId) typeValid = true;

    if (!reportReason || !reportDescription || !typeValid) {
        alert("You must fill at least one ID, make sure it matches the Reported Type and complete all required fields.");
        event.preventDefault();
    }else{
         alert("form is valid!");
    }
});

