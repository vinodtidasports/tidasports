// source --> https://tidasports.com/wp-content/themes/storefront-child/assets/js/custom-script.js?ver=6.5.3 
jQuery(function() {
    var ajaxReq = "ToCancelPrevReq";
    if(jQuery( "#location" ).length > 0){
        jQuery( "#location" ).autocomplete({
            source: function( request, response ) {
                ajaxReq = jQuery.ajax({
                    type: "POST",
                    url: params.ajaxurl,
                    dataType: "json",
                    beforeSend: function () {
                      if (ajaxReq != "ToCancelPrevReq") {
                        ajaxReq.abort();
                      }
                    },
                    data: {
                        action: "autocomplete_search",
                        q: request.term,
                        category:'product_cat',
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
        });
    }
    if(jQuery( "#sport" ).length > 0){
        jQuery( "#sport" ).autocomplete({
            source: function( request, response ) {
                ajaxReq = jQuery.ajax({
                    type: "POST",
                    url: params.ajaxurl,
                    dataType: "json",
                    beforeSend: function () {
                      if (ajaxReq != "ToCancelPrevReq") {
                        ajaxReq.abort();
                      }
                    },
                    data: {
                        action: "autocomplete_search",
                        q: request.term,
                        category:'sport',
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
        });
    }
    if(jQuery( "#wc_bookings_field_resource" ).length > 0){
        let slotButtons = document.querySelectorAll(".woocommerce-product-slots__slot-select");
        let venue_resource_value = document.querySelector("#wc_bookings_field_resource");
        slotButtons.forEach(function(slotButton) {
            slotButton.addEventListener('click', (e) => {
                e.preventDefault();
                resource_val = slotButton.getAttribute("value-data");
                jQuery('#wc_bookings_field_resource').val(resource_val).trigger('change');
                // venue_resource_value.onchange();
                // console.log(venue_resource_value.value);
            });
        });
    }
});