   <!--Social Media Counters  -->
   <div class="space-y-4">
       <h3 class="text-xl text-center text-neutral-900 font-medium">Join <b class="font-bold">
               <?php
                                $counts = hs_get_social_counts();

                                $total_social_count = 
                                    ($counts['youtube'] ?? 0) +
                                    ($counts['facebook'] ?? 0) +
                                    ($counts['instagram'] ?? 0);

                                echo hs_format_count($total_social_count);
                            ?>
           </b> Followers</h3>

       <div class="grid grid-cols-3 gap-2">
           <a href="https://www.youtube.com/@royalpatrika" target="_blank"
               class="flex flex-1 items-stretch no-underline!">
               <div
                   class="items-center gap-2 bg-linear-to-r from-red-600 to-red-800 p-2 pt-4 flex flex-col text-center text-white flex-1 max-w-full">
                   <i class="fa-brands fa-youtube text-xl"></i>
                   <p class="font-bold"><?php echo hs_format_count($counts['youtube']); ?></p>
                   <p class="text-xs uppercase truncate max-w-full">Subscribers</p>
               </div>
           </a>
           <div
               class="items-center gap-2 bg-linear-to-r from-yellow-400 via-pink-500 to-purple-600 p-2 pt-4 flex flex-col text-center text-white">
               <i class="fa-brands fa-instagram text-xl"></i>
               <p class="font-bold"><?php echo hs_format_count($counts['facebook']); ?></p>
               <p class="text-xs uppercase truncate max-w-full">Followers</p>
           </div>
           <div
               class="items-center gap-2 bg-linear-to-r from-blue-600 to-blue-800 p-2 pt-4 flex flex-col text-center text-white">
               <i class="fa-brands fa-facebook-f text-xl"></i>
               <p class="font-bold"><?php echo hs_format_count($counts['instagram']); ?></p>
               <p class="text-xs uppercase truncate max-w-full">Followers</span>
           </div>
       </div>
   </div>