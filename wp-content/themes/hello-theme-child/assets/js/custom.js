 jQuery(document).ready(function ($) {

    // Sticky header

    $(window).scroll(function () {

        // Scroll to top

        var scrollTopSticky = $(".scroll-to-top");

        scrollTop = $(window).scrollTop();



        if (scrollTop >= 500) scrollTopSticky.addClass("sticky-visible");

        else scrollTopSticky.removeClass("sticky-visible");

        // Scroll to top

    });

});



// Scroll to top

function scrollToTop() {

    window.scrollTo(0, 0);

}

// Scroll to top



// Clock
function startHindiClock() {

    const daysHindi = [
        "इतवार", "सोमवार", "मंगल", "बुध", "जुमेरात", "जुमा", "शनिचर"
    ];

    const monthsHindi = [
        "जनवरी", "फ़रवरी", "मार्च", "अप्रैल", "मई", "जून",
        "जुलाई", "अगस्त", "सितंबर", "अक्टूबर", "नवंबर", "दिसंबर"
    ];

    function updateClock() {
        const now = new Date();

        const dayName = daysHindi[now.getDay()];
        const monthName = monthsHindi[now.getMonth()];

        const day = now.getDate();
        const year = now.getFullYear();

        let hours = now.getHours();
        let minutes = now.getMinutes();
        let seconds = now.getSeconds();

  // Determine AM/PM in Hindi
        let period = hours >= 12 ? "PM" : "AM";
          // Convert to 12-hour format
        hours = hours % 12;
        hours = hours ? hours : 12; // 0 = 12

        // Padding 0
        hours = hours < 10 ? "0" + hours : hours;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        const formattedDate = `${dayName}, ${day} ${monthName}, ${year}`;
        const formattedTime = `${hours}:${minutes}:${seconds} ${period}`;

        // Print inside any element
        document.getElementById("hindiDate").innerText = formattedDate;
        document.getElementById("hindiTime").innerText = formattedTime;
    }

    updateClock();              // Run instantly
    setInterval(updateClock, 1000); // Update every second
}

// Start clock
startHindiClock();


// // Modal
// document.addEventListener("DOMContentLoaded", function () {

//     const modal = document.getElementById('searchModal');
//     const openBtn = document.getElementById('openSearchModal');
//     const closeBtn = document.getElementById('closeModal');

//     // Open Modal
//     openBtn.addEventListener('click', () => {
//         modal.classList.remove('hidden');
//         modal.classList.add('flex');
//     });

//     // Close Modal
//     closeBtn.addEventListener('click', () => {
//         modal.classList.add('hidden');
//         modal.classList.remove('flex');
//     });

//     // Close when clicking outside the modal box
//     modal.addEventListener('click', (e) => {
//         if (e.target === modal) {
//             modal.classList.add('hidden');
//             modal.classList.remove('flex');
//         }
//     });
// });

var swiper = new Swiper(".trending-now", {
     loop: true,
     spaceBetween: 10,
      navigation: {
        nextEl: ".trending-button-next",
        prevEl: ".trending-button-prev",
      },
    });
var swiper = new Swiper(".home-top-10-news", {
     loop: true,
     spaceBetween: 1,
     pagination: { el: ".swiper-pagination", clickable: true },
      navigation: {
        nextEl: ".top-10-button-next",
        prevEl: ".top-10-button-prev",
      },
    });




