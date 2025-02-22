//console.log("I am loading Saif. Coming from popup.js");

jQuery(document).ready(function ($) {
    if (popupData.image) {
        // Create the <dialog> HTML structure
        var popupHtml;

        // Check if URL is available to create a clickable image
        if (popupData.url) {
            popupHtml = `
                <dialog id="child-theme-popup-dialog" class="child-theme-popup">
		  <div class="child-theme-popup-wrapper">
                    <a href="${popupData.url}" title="${popupData.title}" target="${popupData.target}">
                        <img src="${popupData.image}" alt="Popup Image" class="child-theme-popup-image" />
                    </a>
                    <button id="child-theme-close-popup" class="child-theme-close-btn"><svg height="800px" width="800px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
	 viewBox="0 0 26 26" xml:space="preserve">
<g>
	<path style="fill:#030104;" d="M21.125,0H4.875C2.182,0,0,2.182,0,4.875v16.25C0,23.818,2.182,26,4.875,26h16.25
		C23.818,26,26,23.818,26,21.125V4.875C26,2.182,23.818,0,21.125,0z M18.78,17.394l-1.388,1.387c-0.254,0.255-0.67,0.255-0.924,0
		L13,15.313L9.533,18.78c-0.255,0.255-0.67,0.255-0.925-0.002L7.22,17.394c-0.253-0.256-0.253-0.669,0-0.926l3.468-3.467
		L7.221,9.534c-0.254-0.256-0.254-0.672,0-0.925l1.388-1.388c0.255-0.257,0.671-0.257,0.925,0L13,10.689l3.468-3.468
		c0.255-0.257,0.671-0.257,0.924,0l1.388,1.386c0.254,0.255,0.254,0.671,0.001,0.927l-3.468,3.467l3.468,3.467
		C19.033,16.725,19.033,17.138,18.78,17.394z"/>
</g>
</svg>		    </button>
                  </div>
		</dialog>
            `;
        } else {
            popupHtml = `
                <dialog id="child-theme-popup-dialog" class="child-theme-popup">
		  <div class="child-theme-popup-wrapper">
                    <img src="${popupData.image}" alt="Popup Image" class="child-theme-popup-image" />
                    <button id="child-theme-close-popup" class="child-theme-close-btn"><svg height="800px" width="800px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
	 viewBox="0 0 26 26" xml:space="preserve">
<g>
	<path style="fill:#030104;" d="M21.125,0H4.875C2.182,0,0,2.182,0,4.875v16.25C0,23.818,2.182,26,4.875,26h16.25
		C23.818,26,26,23.818,26,21.125V4.875C26,2.182,23.818,0,21.125,0z M18.78,17.394l-1.388,1.387c-0.254,0.255-0.67,0.255-0.924,0
		L13,15.313L9.533,18.78c-0.255,0.255-0.67,0.255-0.925-0.002L7.22,17.394c-0.253-0.256-0.253-0.669,0-0.926l3.468-3.467
		L7.221,9.534c-0.254-0.256-0.254-0.672,0-0.925l1.388-1.388c0.255-0.257,0.671-0.257,0.925,0L13,10.689l3.468-3.468
		c0.255-0.257,0.671-0.257,0.924,0l1.388,1.386c0.254,0.255,0.254,0.671,0.001,0.927l-3.468,3.467l3.468,3.467
		C19.033,16.725,19.033,17.138,18.78,17.394z"/>
</g>
</svg>		    </button>
                  </div>
		</dialog>
            `;
        }

        // Append the popup <dialog> to the body
        $('body').append(popupHtml);

        // Open the dialog automatically
        const dialog = document.getElementById('child-theme-popup-dialog');
        dialog.showModal();

        // Close the popup on button click
        $('#child-theme-close-popup').on('click', function() {
            dialog.close();
        });

        // Close the popup when clicking outside the dialog
        $(dialog).on('click', function (event) {
            if (event.target === dialog) {
                dialog.close();
            }
        });
    }
});
