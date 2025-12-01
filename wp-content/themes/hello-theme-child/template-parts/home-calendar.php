<?php
/** 
 * SIMPLE CALENDAR TEMPLATE (NO functions.php NEEDED)
 */

if (!defined('ABSPATH')) exit;

 

/* ----------------------------------------------------
    CALENDAR RENDER FUNCTION
---------------------------------------------------- */
function ks_render_month($year, $month) {
    $first_day   = date("N", strtotime("$year-$month-01"));
    $total_days  = date("t", strtotime("$year-$month-01"));

    $today_day   = date("j");
    $today_month = date("n");
    $today_year  = date("Y");

    ob_start();
    ?>
          

             

        
<div class="flex flex-col gap-5 pt-2.5">
      <h3 class="text-base font-semibold text-neutral-700 text-center">
                    <?= date("F Y", strtotime("$year-$month-01")); ?>
            </h3>
            <div class="space-y-2">
        <!-- Days -->
        <div class="grid grid-cols-7 text-center font-medium text-xs text-neutral-700 uppercase">
            <div>Mon</div><div>Tue</div><div>Wed</div>
            <div>Thu</div><div>Fri</div><div>Sat</div><div>Sun</div>
        </div>

        <!-- Dates -->
         <div class="grid grid-cols-7 gap-1 text-center text-xs text-neutral-700">

             <?php for ($i = 1; $i < $first_day; $i++): ?>
                <div></div>
            <?php endfor; ?>

          <?php for ($day = 1; $day <= $total_days; $day++): 
                $is_today = ($day == $today_day && $month == $today_month && $year == $today_year);
                $class = $is_today 
                     ? "bg-brand text-white font-semibold" 
                    : "bg-neutral-100 text-neutral-900 ";
            ?>
                <div class="py-2 <?= $class ?>"><?= $day ?></div>
            <?php endfor; ?>

        </div>
    </div>
    </div>
  

  <?php
    return ob_get_clean();
}
?>
  
  <div class="ks-calendar-wrapper border border-neutral-200 p-3 relative">

    <!-- Navigation Buttons -->
    <button 
        class="ks-prev-btn absolute left-3 top-3 size-9 flex justify-center items-center p-1 bg-neutral-100 hover:bg-brand transition hover:text-white text-neutral-700 z-10">
        <i class="fa-solid fa-angle-left"></i>
    </button>

    <button 
        class="ks-next-btn absolute right-3 top-3 size-9 flex justify-center items-center p-1 bg-neutral-100 hover:bg-brand transition hover:text-white text-neutral-700 z-10">
        <i class="fa-solid fa-angle-right"></i>
    </button>

    <!-- Swiper -->
    <div class="swiper ks-calendar-swiper">
        <div class="swiper-wrapper">

            <?php
            
              for ($i = -6; $i <= +6; $i++) {

                $ts    = strtotime("first day of $i month");
                $year  = date("Y", $ts);
                $month = date("n", $ts);
            ?>
                <div class="swiper-slide">
                      <?= ks_render_month($year, $month); ?>
                </div>
            <?php } ?>

        </div>
    </div>

</div>
 
<!-- =====================================================
     SWIPER JS
====================================================== -->
<!-- <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script> -->

<script>
document.addEventListener("DOMContentLoaded", function() {

    const swiper = new Swiper(".ks-calendar-swiper", {
        slidesPerView: 1,
        spaceBetween: 20,
        allowTouchMove: true,
        loop: false,
        speed: 400,
        initialSlide: 6,
    });

    // Buttons
    document.querySelector(".ks-prev-btn").addEventListener("click", () => swiper.slidePrev());
    document.querySelector(".ks-next-btn").addEventListener("click", () => swiper.slideNext());

});
</script>