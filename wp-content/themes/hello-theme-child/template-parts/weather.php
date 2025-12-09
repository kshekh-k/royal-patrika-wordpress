<?php

/**
 * WeatherAPI — Full Weather Widget
 * Place inside: /your-theme/template-parts/weather-api.php
 * Use in templates by: include get_template_directory() . '/template-parts/weather-api.php';
 */
/* ----------------------------------------------------
    1. GET USER CITY FROM IP
---------------------------------------------------- */
function rp_get_user_city()
{
    $geo_url = 'https://ipapi.co/json/';

    $response = wp_remote_get($geo_url, ['timeout' => 5]);

    if (is_wp_error($response)) {
        return 'Mumbai';  // fallback city
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (!empty($data['city'])) {
        return sanitize_text_field($data['city']);
    }

    return 'Mumbai';  // fallback
}

/* ----------------------------------------------------
    CONFIGURATION
---------------------------------------------------- */
$city = rp_get_user_city();
$api_key = '36ebdb7b77f34036b7c181718253011';  // ← Replace with your key

/* ----------------------------------------------------
    FETCH WEATHER API DATA
---------------------------------------------------- */
function rp_get_weather_api_data($city, $api_key)
{
    $url = "https://api.weatherapi.com/v1/forecast.json?key={$api_key}&q={$city}&days=7&aqi=no&alerts=no";
    $response = wp_remote_get($url);

    if (is_wp_error($response))
        return false;

    return json_decode(wp_remote_retrieve_body($response), true);
}

$weather = rp_get_weather_api_data($city, $api_key);

if (!$weather) {
    echo "<p class='text-red-600'>Unable to fetch weather data.</p>";
    return;
}

/* ----------------------------------------------------
    TODAY’S WEATHER
---------------------------------------------------- */
$location = $weather['location']['name'];
$country = $weather['location']['country'];

$day_name = date('D', strtotime($weather['location']['localtime']));
$date = date('F j, Y', strtotime($weather['location']['localtime']));

$temp_c = round($weather['current']['temp_c']);
$cond = $weather['current']['condition']['text'];
$icon = 'https:' . $weather['current']['condition']['icon'];

?>

<!-- =========================================================
     TODAY WEATHER BLOCK
========================================================= -->
<div class="relative overflow-hidden">
    <div class="absolute inset-0 flex justify-center items-center overflow-hidden">
        <img src="<?php echo site_url(); ?>/wp-content/uploads/2025/12/modern-art-urban-skyline-atom.jpg"
            alt="Image by freepik.com id:1046158" class="object-cover max-w-md" />
    </div>
    <div class="relative z-10 bg-blue-700/70 p-5">
        <div class="flex justify-center items-center gap-2 text-white">
            <p class="text-3xl font-bold"><?= $temp_c ?>°C</p>
            <img src="<?= $icon ?>" alt="<?= $cond ?>" class="size-14">
        </div>
        <p class="text-white text-center text-sm"><?= $cond ?></p>

        <?php
/* ----------------------------------------------------
    WEEK FORECAST
---------------------------------------------------- */
$forecast_days = $weather['forecast']['forecastday'];
?>

        <!-- =========================================================
     WEEKLY FORECAST
========================================================= -->
        <div class="flex gap-1 justify-between py-3 -mx-5 px-5 bg-white/10 mt-5">
            <?php
        foreach ($forecast_days as $day):
            $day_label = date('D', strtotime($day['date']));
            $day_temp = round($day['day']['avgtemp_c']);
            $day_cond = $day['day']['condition']['text'];
            $day_icon = 'https:' . $day['day']['condition']['icon'];
            ?>


            <div class="flex flex-col gap-1 text-xs text-white text-center">
                <span class="font-semibold uppercase"><?= $day_label ?></span>
                <span class=""><?= $day_temp ?>°</span>
            </div>


            <?php endforeach; ?>
        </div>
        <div class="text-white mt-5">
            <h3 class="text-lg font-medium text-center">
                <?= $day_name ?>, <?= $date ?>
            </h3>

            <p class="text-base text-center mt-1">
                <?= $location ?>, <?= $country ?>
            </p>
        </div>
    </div>
</div>