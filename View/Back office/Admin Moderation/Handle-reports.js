function handleAction(actionType) {
    let message = prompt("Enter a message for this action:");

    if(message === null) return; // user cancelled
    
    // Send to backend (example)
    fetch("save_solution.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({
            report_id: 1042,
            action: actionType,
            message: message
        })
    })
    .then(response => response.text())
    .then(data => alert("Solution saved successfully!"))
    .catch(err => alert("Error saving solution."));

}
