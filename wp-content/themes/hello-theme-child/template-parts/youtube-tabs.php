<?php

/**
 * YouTube Tabs Player Template
 * Usage: include this file in any template
 */

// CONFIG
// $api_key = 'AIzaSyB0SHwrWPm9VmU4k1E4EddJnnUMMScUCOQ';
// $channel_id = 'UCOGGkMTKIeRAFl_iht0Z-cQ';
$count = 4;
$filter = 'latest';  // latest | most_viewed | most_liked | reviews | search | recent
$q = '';  // search keyword if using filter="search"
$days = 7;  // for recent filter

// Fetch YouTube Data Function ------------------------------
function hs_get_filtered_youtube_videos_template($api_key, $channel_id, $count, $filter, $q, $days)
{
    $base_url = 'https://www.googleapis.com/youtube/v3/search';

    $params = [
        'key' => $api_key,
        'channelId' => $channel_id,
        'part' => 'snippet',
        'type' => 'video',
        'maxResults' => $count,
        'order' => 'date'
    ];

    switch ($filter) {
        case 'most_viewed':
            $params['order'] = 'viewCount';
            break;

        case 'most_liked':
            $params['order'] = 'rating';
            break;

        case 'reviews':
            $params['q'] = 'review';
            break;

        case 'search':
            $params['q'] = sanitize_text_field($q);
            break;

        case 'recent':
            $date = date(DATE_RFC3339, strtotime("-{$days} days"));
            $params['publishedAfter'] = $date;
            break;

        default:
            $params['order'] = 'date';
            break;
    }

    $url = $base_url . '?' . http_build_query($params);

    $response = wp_remote_get($url);
    if (is_wp_error($response))
        return [];

    return json_decode(wp_remote_retrieve_body($response), true);
}

// Fetch videos
$videos = hs_get_filtered_youtube_videos_template($api_key, $channel_id, $count, $filter, $q, $days);
if (empty($videos['items'])) {
    echo '<p>No videos found.</p>';
    return;
}

// First video for player
$first_video = $videos['items'][0]['id']['videoId'];

?>

<div class="md:grid md:grid-cols-12 flex flex-col border-5 border-rose-500 items-stretch">

    <!-- MAIN VIDEO PLAYER -->
    <div class="col-span-8 bg-slate-800 ">
        <div class="flex flex-col relative pb-[56.25%] h-full overflow-hidden">
        <iframe id="hs-main-player" class="absolute top-0 left-0 w-full h-full"
            src="https://www.youtube.com/embed/<?php echo $first_video; ?>" frameborder="0"
            allow="autoplay; encrypted-media" allowfullscreen></iframe>
    </div>
    </div>

    <!-- VIDEO TABS -->
    <div class="hs-tabs flex flex-col gap-2 col-span-4 border-t-5 md:border-t-0 md:border-l-5 border-rose-500 ">
        <div class="flex items-center justify-between bg-rose-500 p-3">
            <div class="space-y-1 ">
            <h3 class="text-white">Royal Patrika Channel</h3>
            <p class="text-sm text-white"><a href="https://www.youtube.com/@RoyalPatrika" class="no-underline! italic" target="_blank">Subscribe our channel</a></p>
        </div>

        <a href="https://www.youtube.com/@royalpatrika" target="_blank" class="text-sm font-semibold no-underline! py-2 flex justify-center text-center hover:text-brand hover:bg-white text-white transition bg-black/20 px-3">
                            View All
                        </a>
        </div>
       
        <div class="flex flex-col px-3 pb-3">
            <?php
            foreach ($videos['items'] as $video):
                if ($video['id']['kind'] !== 'youtube#video')
                    continue;

                $video_id = $video['id']['videoId'];
                $title = $video['snippet']['title'];
                $thumb = $video['snippet']['thumbnails']['medium']['url'];
                ?>
            <div class="hs-tab text-sm whitespace-nowrap gap-3 grid grid-cols-5 items-center even:bg-slate-800 odd:bg-slate-900 cursor-pointer w-full p-1 lg:p-2 [&.active>.v-thumb]:border-brand! transition hover:bg-slate-700 group"
                data-video="<?php echo esc_attr($video_id); ?>">
                <div class="relative h-14 lg:h-20 shrink-0 overflow-hidden flex justify-center items-center col-span-2 v-thumb border-2 border-transparent group-hover:border-brand transition">
                    <img src="<?php echo $thumb; ?>" class="object-cover min-h-full min-w-full max-w-20 lg:max-w-40" alt="<?php echo $title; ?>">
                    <div
                        class="flex justify-center items-center text-white flex-1 absolute inset-0 bg-black/50 group-hover:bg-brand/80 transition">
                        <i class="fa-brands fa-youtube text-2xl text-rose-500 group-hover:text-white transition"></i>
                    </div>
                </div>
                <div class="col-span-3">
                <h4 class="text-sm text-white truncate w-full block">
                    <?php echo esc_html($title); ?>
                </h4>
                <p class="text-xs text-white pt-2 hidden">
               <span><i class="fa-regular fa-eye"></i> <?php echo number_format($stats['viewCount']); ?> </span> <span class="w-5 text-center inline-block">|</span>
                <span><i class="fa-regular fa-thumbs-up"></i> <?php echo number_format($stats['likeCount']); ?></span>
            </p>
            </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const tabs = document.querySelectorAll(".hs-tab");
    const player = document.getElementById("hs-main-player");

    tabs.forEach(tab => {
        tab.addEventListener("click", () => {
            const videoId = tab.getAttribute("data-video");

            // Update iframe
            player.src = "https://www.youtube.com/embed/" + videoId + "?autoplay=1";

            // Active styling
            tabs.forEach(t => t.classList.remove("inactive"));
            tab.classList.add("active");
        });
    });

    // Set first tab active
    if (tabs.length > 0) {
        tabs[0].classList.add("active");
    }
});
</script>