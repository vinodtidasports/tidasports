document.addEventListener("DOMContentLoaded", function () {
    const sliders = [
        { prevBtn: ".prev-btn", nextBtn: ".next-btn", wrapper: ".sliders-wrapper", slides: ".slider",autoAdvance: true },
        { prevBtn: ".venue-prev-btn", nextBtn: ".venue-next-btn", wrapper: ".venue-sliders-wrapper", slides: ".venue-slider",autoAdvance: true },
        { prevBtn: ".academy-prev-btn", nextBtn: ".academy-next-btn", wrapper: ".academy-sliders-wrapper", slides: ".academy-slider", autoAdvance: true }
    ];  
	if(jQuery(".sliders-wrapper").length > 0 || 
	jQuery(".venue-sliders-wrapper").length > 0 || 
	jQuery(".academy-sliders-wrapper").length > 0){
    sliders.forEach(slider => {
        const prevBtn = document.querySelector(slider.prevBtn);
        const nextBtn = document.querySelector(slider.nextBtn);
        const slidersWrapper = document.querySelector(slider.wrapper);
        const slides = document.querySelectorAll(slider.slides);
        const totalSlides = slides.length;
        const slidesInView = 4; // Number of slides to display at once
        let slideIndex = 0;
        let isAnimating = false;

        // Calculate width of each slide including margins
        const slideWidth = slides[0].offsetWidth + parseInt(getComputedStyle(slides[0]).marginRight);

        // Button event listeners
        prevBtn.addEventListener("click", () => {
            if (!isAnimating) {
                if (slideIndex === 0) {
                    slideIndex = totalSlides - slidesInView;
                    slidersWrapper.style.transition = "none";
                    slidersWrapper.style.transform = `translateX(-${slideIndex * slideWidth}px)`;
                    setTimeout(() => {
                        slidersWrapper.style.transition = "";
                        slideIndex--;
                        translateSlides();
                    }, 0);
                } else {
                    slideIndex--;
                    translateSlides();
                }
            }
        });

        nextBtn.addEventListener("click", () => {
            if (!isAnimating) {
                if (slideIndex === totalSlides - slidesInView) {
                    slideIndex = 0;
                    slidersWrapper.style.transition = "none";
                    slidersWrapper.style.transform = `translateX(-${slideIndex * slideWidth}px)`;
                    setTimeout(() => {
                        slidersWrapper.style.transition = "";
                        slideIndex++;
                        translateSlides();
                    }, 0);
                } else {
                    slideIndex++;
                    translateSlides();
                }
            }
        });

        // Function to translate slides
        function translateSlides() {
            isAnimating = true;
            slidersWrapper.style.transform = `translateX(-${slideIndex * slideWidth}px)`;
            setTimeout(() => {
                isAnimating = false;
            }, 500); // Adjust the duration to match the transition duration in CSS
        }

        // Automatically advance the slider if enabled
        if (slider.autoAdvance) {
            function autoAdvance() {
                nextBtn.click();
                setTimeout(autoAdvance, 3000); // Adjust the interval as needed (in milliseconds)
            }

            // Start auto-advancing the slider
            autoAdvance();
        }
    });
	}
});

jQuery(document).ready(function($) {
    $('.sign-in-button.logged-in').hover(
        function() {
            $(this).data('username', $(this).text().replace('Hi, ', '').trim());
            $(this).text('Edit Profile');
        },
        function() {
            var userName = $(this).data('username');
            $(this).text('Hi, ' + userName);
        }
    );
});


document.addEventListener('DOMContentLoaded', function() {
    jQuery(document).bind('gform_post_render', function(event, form_id) {
	jQuery('#input_' + 4 + '_3').attr('maxlength', 10);
	jQuery('#input_' + 2 + '_3').attr('maxlength', 10);
        /*
	jQuery('#input_' + 4 + '_3').on('input', function() {
	    //console.log("Yes Working")
            var phoneField = jQuery(this);
            var phoneValue = phoneField.val();
            var regex = /^\d{10}$/;
            if (!regex.test(phoneValue)) {
                phoneField.css('border-color', 'red');
                phoneField[0].setCustomValidity('Phone number must be exactly 10 digits.');
            } else {
                phoneField.css('border-color', '');
                phoneField[0].setCustomValidity('');
            }
        });
	*/
    });
});


document.addEventListener("DOMContentLoaded", function () {
  const accordionItems = document.querySelectorAll(".accordion-item");

  if (accordionItems.length > 0) {
    // Initialize the first accordion item as active
    const firstItem = accordionItems[0];
    firstItem.classList.add("active");
    const firstCollapse = firstItem.querySelector(".accordion-collapse");
    firstCollapse.classList.add("show");
    firstCollapse.setAttribute("aria-hidden", "false");
  }

  accordionItems.forEach((item) => {
    const header = item.querySelector(".accordion-header");
    const collapse = item.querySelector(".accordion-collapse");

    header.addEventListener("click", () => {
      // Close all other items if you want single-item behavior
      accordionItems.forEach((otherItem) => {
        if (otherItem !== item) {
          otherItem.classList.remove("active");
          const otherCollapse = otherItem.querySelector(".accordion-collapse");
          otherCollapse.classList.remove("show");
          otherCollapse.setAttribute("aria-hidden", "true");
        }
      });

      // Toggle the current item
      const isExpanded = item.classList.contains("active");
      item.classList.toggle("active", !isExpanded);
      collapse.classList.toggle("show", !isExpanded);
      collapse.setAttribute("aria-hidden", isExpanded ? "true" : "false");
    });
  });
});

