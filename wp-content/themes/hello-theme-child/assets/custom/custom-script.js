$(function () {
  // Owl Carousel
  var owl = $(".owl-carousel");
  owl.owlCarousel({
    items: 1,
    margin: 0,
    loop: true,
    autoplay: true,
    nav: true,
    navText: ["<span class='fas fa-chevron-left'></span>", "<span class='fas fa-chevron-right'></span>"]
  });
});

$('.mobile-menu-main').change(function () {
  // Get the selected option value
  var selectedUrl = $(this).val();

  // Redirect to the selected URL
  if (selectedUrl) {
    window.location.href = selectedUrl;
  }
});

document.addEventListener('DOMContentLoaded', function () {
  lightGallery(document.getElementById('lightgallery'));

  const hamburger = document.getElementById('hamburger');
  const sidebar = document.getElementById('sidebar');
  const closeBtn = document.getElementById('close-btn');

  hamburger.addEventListener('click', function () {   
    sidebar.classList.toggle('active');
  });

  closeBtn.addEventListener('click', function () {    
    sidebar.classList.remove('active');
  });

  // Close the sidebar if clicking outside of it
  document.addEventListener('click', function (event) {
    if (!sidebar.contains(event.target) && !hamburger.contains(event.target)) {
     
      sidebar.classList.remove('active');
    }
  });
});

// Sub menu toggle
jQuery(document).ready(function ($) {
  // Hide all submenus initially (except the one with class "open")
  // $('.sub-menu').not('.open').hide();

  // // Click event listener for parent menu items
  // $("li.menu-item-has-children").click(function () {
  //   // Get the clicked menu item
  //   var clickedItem = $(this);

  //   // Toggle the visibility of its sub menu
  //   clickedItem.children("ul").slideToggle('fast');

  //   // Toggle an "active" class for styling purposes (optional)
  //   clickedItem.toggleClass("active");
  // });

  // Copy link
  $('.copy_text').click(function (e) {
    e.preventDefault();
    var copyText = $(this).attr('href');

    document.addEventListener('copy', function (e) {
      e.clipboardData.setData('text/plain', copyText);
      e.preventDefault();
    }, true);

    document.execCommand('copy');
    console.log('copied text : ', copyText);
    alert('copied text: ' + copyText);
  });
});

// Real-time clock
document.addEventListener('DOMContentLoaded', function () {
  function updateClock() {
    const now = new Date();
    const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const dayOfWeek = daysOfWeek[now.getDay()];
    const time = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric' });
    document.getElementById('real-time-clock').innerText = `${dayOfWeek} ${time}`;
  }
  setInterval(updateClock, 1000);
  updateClock();
});

// Sticky real-time clock
document.addEventListener('DOMContentLoaded', function () {
  function stickyUpdateClock() {
    const now = new Date();
    const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const dayOfWeek = daysOfWeek[now.getDay()];
    const time = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric' });
    document.getElementById('real-time-clock-sticky').innerText = `${dayOfWeek} ${time}`;
  }
  setInterval(stickyUpdateClock, 1000);
  stickyUpdateClock();
});

// Sticky header
document.addEventListener("DOMContentLoaded", function () {

const mainHeader = document.querySelector('.main-header');
const stickyHeader = document.querySelector('.sticky-header');
const googleAdsRight = document.querySelector('.google-ads.right');
const googleAdsLeft = document.querySelector('.google-ads.left');

  // Exit early if any element is missing
  if (!mainHeader || !stickyHeader || !googleAdsRight || !googleAdsLeft) return;


  window.addEventListener("scroll", function () {
    if (window.scrollY > 200) {
      mainHeader.style.top = "-100px";
      stickyHeader.classList.add("show-sticky-header");
      googleAdsRight.classList.add("google-ads-sticky");
      googleAdsLeft.classList.add("google-ads-sticky");
    } else {
      mainHeader.style.top = "0";
      stickyHeader.classList.remove("show-sticky-header");
      googleAdsRight.classList.remove("google-ads-sticky");
      googleAdsLeft.classList.remove("google-ads-sticky");
    }
  });

});


