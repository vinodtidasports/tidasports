// Career Page Popup
//console.log("common.js");

document.addEventListener('DOMContentLoaded', () => {
    // Open the dialog when the button is clicked
    function openDialog(event) {
        event.preventDefault(); // Prevent default action (e.g., navigation, if it's an anchor tag)
        
        const buttonParent = event.target.closest('.open-dialog-btn'); // Get the parent with the class 'open-dialog-btn'
        const buttonId = buttonParent ? buttonParent.id : 'No ID'; // Get the ID of the parent, if available
        
        // console.log("Button ID:", buttonId); // Log the parent button's ID
	// Set the value of select#input_5_5
	const selectElement = document.querySelector('select#input_5_5');
	console.log("element",selectElement);
	if (selectElement && buttonId) {
		console.log("condition matched");
    	selectElement.value = buttonId;
    	console.log("Value set in select#input_5_5:", selectElement.value); // Confirm value is set
	} else {
    	console.error("select#input_5_5 not found.");
	}
        const dialog = document.getElementById('gravityFormDialog');
        if (dialog) {
            dialog.showModal();
        }
    }

    // Close the dialog
    function closeDialog() {
        const dialog = document.getElementById('gravityFormDialog');
        if (dialog) {
            dialog.close();
        }
    }

    // Add event listener to all links inside '.open-dialog-btn'
    document.querySelectorAll('.open-dialog-btn a').forEach((link) => {
        link.addEventListener('click', openDialog);
    });
});
