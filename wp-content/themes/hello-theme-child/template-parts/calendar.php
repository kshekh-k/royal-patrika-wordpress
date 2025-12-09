<?php
/**
 * Calendar Swiper Template — Standalone
 * Place file in your theme, example:
 * /wp-content/themes/your-theme/template-parts/calendar-swiper.php
 *
 * Usage:
 * <?php include get_template_directory() . '/template-parts/calendar-swiper.php'; ?>
 *
 * Notes:
 * - This file attempts to avoid caching for correct "today" highlight,
 *   but some server/CDN caches may still serve cached HTML. The JS fallback
 *   ensures today is highlighted client-side regardless.
 */

/* Safety */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* Prevent page caching for this request where possible */
if ( ! defined( 'DONOTCACHEPAGE' ) ) {
    define( 'DONOTCACHEPAGE', true );
}
if ( function_exists( 'nocache_headers' ) ) {
    nocache_headers();
}

/* ----------------------------------------------------------
   RENDER SINGLE MONTH (uses WordPress timezone)
----------------------------------------------------------- */
function ks_render_month( $year, $month ) {
    // Use WP DateTime so timezone is correct
    $first_day   = date( "N", strtotime( "$year-$month-01" ) ); // 1 (Mon) - 7 (Sun)
    $total_days  = date( "t", strtotime( "$year-$month-01" ) );

    $now = current_datetime(); // WP DateTime object
    $today_day   = (int) $now->format( 'j' );
    $today_month = (int) $now->format( 'n' );
    $today_year  = (int) $now->format( 'Y' );

    ob_start();
    ?>
   <div class="flex flex-col gap-5 pt-2.5">
        <h3 class="text-base font-semibold text-neutral-700 text-center">
            <?= esc_html( date( "F Y", strtotime( "$year-$month-01" ) ) ); ?>
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

               <?php for ($day = 1; $day <= $total_days; $day++): $is_today = ($day == $today_day && $month == $today_month && $year == $today_year); $class = $is_today
                        ? "bg-brand text-white font-semibold ks-today"
                        : "bg-neutral-100 text-neutral-900";
                ?>
                    <div class="py-2 <?= esc_attr( $class ); ?>" data-day="<?= esc_attr( $day ); ?>" data-month="<?= esc_attr( $month ); ?>" data-year="<?= esc_attr( $year ); ?>">
                        <?= esc_html( $day ); ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/* ----------------------------------------------------------
   HTML: Swiper container + navigation
----------------------------------------------------------- */
?>
<!-- generated at: <?= esc_html( current_time( 'mysql' ) ); ?> -->
 

<div class="ks-calendar-wrapper border border-neutral-200 p-3 relative">

    <!-- Navigation Buttons -->
    <button
        type="button"
        class="ks-prev-btn absolute left-3 top-3 size-9 flex justify-center items-center p-1 bg-neutral-100 hover:bg-brand transition hover:text-white text-neutral-700 z-10"
        aria-label="Previous month"
    >
        <i class="fa-solid fa-angle-left" aria-hidden="true"></i>
    </button>

    <button
        type="button"
        class="ks-next-btn absolute right-3 top-3 size-9 flex justify-center items-center p-1 bg-neutral-100 hover:bg-brand transition hover:text-white text-neutral-700 z-10"
        aria-label="Next month"
    >
        <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
    </button>

    <!-- Swiper -->
    <div class="swiper ks-calendar-swiper">
        <div class="swiper-wrapper">
            <?php
            // Generate months from -6 to +6 relative to current month (13 slides)
            for ( $i = -6; $i <= 6; $i++ ) {
                $ts    = strtotime( "first day of $i month" );
                $year  = date( 'Y', $ts );
                $month = (int) date( 'n', $ts );
                ?>
                <div class="swiper-slide">
                    <?= ks_render_month( $year, $month ); // already escaped inside function ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

 

<script>
(function () {
    document.addEventListener('DOMContentLoaded', function () {

        // Initialize Swiper with current month centered (index 6)
        var swiper = new Swiper(".ks-calendar-swiper", {
            slidesPerView: 1,
            spaceBetween: 20,
            allowTouchMove: true,
            loop: false,
            speed: 350,
            initialSlide: 6
        });

        // Prev / Next buttons
        var prevBtn = document.querySelector('.ks-prev-btn');
        var nextBtn = document.querySelector('.ks-next-btn');
        if (prevBtn) prevBtn.addEventListener('click', function () { swiper.slidePrev(); });
        if (nextBtn) nextBtn.addEventListener('click', function () { swiper.slideNext(); });

        /* ========================================
           CLIENT-SIDE "today" HIGHLIGHT (fallback)
           Ensures today is highlighted even if HTML was cached
         ======================================== */
        try {
            var now = new Date();
            var todayDay = now.getDate();
            var todayMonth = now.getMonth() + 1; // JS months 0-11
            var todayYear = now.getFullYear();

            // Find all date cells and apply highlight if matches today's date and month/year
            document.querySelectorAll('.ks-calendar-container .dates-grid > div').forEach(function (cell) {
                var cellDay = parseInt(cell.getAttribute('data-day'), 10);
                var cellMonth = parseInt(cell.getAttribute('data-month'), 10);
                var cellYear = parseInt(cell.getAttribute('data-year'), 10);

                if (!isNaN(cellDay) && !isNaN(cellMonth) && !isNaN(cellYear)) {
                    if (cellDay === todayDay && cellMonth === todayMonth && cellYear === todayYear) {
                        // add highlight classes (Tailwind-style). Adjust if your theme uses different classes.
                        cell.classList.add('bg-brand', 'text-white', 'font-semibold');
                    } else {
                        // ensure non-today items do not carry 'today' classes
                        cell.classList.remove('bg-brand', 'text-white', 'font-semibold');
                    }
                }
            });
        } catch (e) {
            // silently fail if anything unexpected
            console && console.error && console.error('Calendar highlight error:', e);
        }
    });
})();
</script>
