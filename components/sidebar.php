<aside id="default-sidebar" class="fixed pl-5 py-3 h-full">
  <div class="w-[70px] flex justify-center items-center max-h-screen-xl py-5 bg-white h-full rounded-lg my-auto shadow-md shadow-gray-200">
    <ul class="w-full flex flex-col items-center font-medium py-3 rounded-lg">

      <!-- Home Link (Visible for all users) -->
      <div class="flex w-full justify-center items-center">
        <li class="my-5">
          <a href="home" class="menu">
            <svg class="text-[30px]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
              <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <rect width="7" height="7" x="3" y="3" rx="1" />
                <rect width="7" height="7" x="14" y="3" rx="1" />
                <rect width="7" height="7" x="14" y="14" rx="1" />
                <rect width="7" height="7" x="3" y="14" rx="1" />
              </g>
            </svg>
          </a>
        </li>
        <div id="home" class="hidden absolute right-0 h-[40px] border-[6px] border-transparent border-r-red-500 content-none rounded-lg"></div>
      </div>

      <!-- Attendance Overview Link (Visible for all users) -->
      <div class="flex w-full justify-center items-center">
        <li class="my-5">
          <a href="attendanceoverview" class="menu">
            <svg class="text-[30px]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16">
              <path fill="currentColor" fill-rule="evenodd" d="M1.5 8a6.5 6.5 0 1 1 7.348 6.445a.75.75 0 1 1-.194-1.487A5.001 5.001 0 1 0 4.5 11.57v-1.32a.75.75 0 0 1 1.5 0v3a.75.75 0 0 1-.75.75h-3a.75.75 0 0 1 0-1.5h1.06A6.48 6.48 0 0 1 1.5 8M8 4.25a.75.75 0 0 1 .75.75v2.625l1.033.775a.75.75 0 1 1-.9 1.2l-1.333-1a.75.75 0 0 1-.3-.6V5A.75.75 0 0 1 8 4.25" clip-rule="evenodd" />
            </svg>
          </a>
        </li>
        <div id="attendanceoverview" class="hidden absolute right-0 h-[40px] border-[6px] border-transparent border-r-red-500 content-none rounded-lg"></div>
      </div>

      <!-- Students Link (Visible for all users) -->
      <div class="flex w-full justify-center items-center">
        <li class="my-5">
          <a href="students" class="menu">
            <svg class="text-[35px]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256">
              <path fill="currentColor" d="m227.79 52.62l-96-32a11.85 11.85 0 0 0-7.58 0l-96 32A12 12 0 0 0 20 63.37a6 6 0 0 0 0 .63v80a12 12 0 0 0 24 0V80.65l23.71 7.9a67.92 67.92 0 0 0 18.42 85A100.36 100.36 0 0 0 46 209.44a12 12 0 1 0 20.1 13.11C80.37 200.59 103 188 128 188s47.63 12.59 61.95 34.55a12 12 0 1 0 20.1-13.11a100.36 100.36 0 0 0-40.18-35.92a67.92 67.92 0 0 0 18.42-85l39.5-13.17a12 12 0 0 0 0-22.76Zm-99.79-8L186.05 64L128 83.35L70 64ZM172 120a44 44 0 1 1-81.06-23.71l33.27 11.09a11.9 11.9 0 0 0 7.58 0l33.27-11.09A43.85 43.85 0 0 1 172 120" />
            </svg>
          </a>
        </li>
        <div id="students" class="hidden absolute right-0 h-[40px] border-[6px] border-transparent border-r-red-500 content-none rounded-lg"></div>
      </div>

      <?php if ($_SESSION['role'] === 'Faculty'): ?>
       <!-- Inventory Link -->
       <div class="flex w-full justify-center items-center">
          <li class="my-5">
            <a href="inventory" class="menu">
              <svg class="text-[30px]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                  <path d="M2.97 12.92A2 2 0 0 0 2 14.63v3.24a2 2 0 0 0 .97 1.71l3 1.8a2 2 0 0 0 2.06 0L12 19v-5.5l-5-3zM7 16.5l-4.74-2.85M7 16.5l5-3m-5 3v5.17m5-8.17V19l3.97 2.38a2 2 0 0 0 2.06 0l3-1.8a2 2 0 0 0 .97-1.71v-3.24a2 2 0 0 0-.97-1.71L17 10.5zm5 3l-5-3m5 3l4.74-2.85M17 16.5v5.17" />
                  <path d="M7.97 4.42A2 2 0 0 0 7 6.13v4.37l5 3l5-3V6.13a2 2 0 0 0-.97-1.71l-3-1.8a2 2 0 0 0-2.06 0zM12 8L7.26 5.15M12 8l4.74-2.85M12 13.5V8" />
                </g>
              </svg>
            </a>
          </li>
          <div id="inventory" class="hidden absolute right-0 h-[40px] border-[6px] border-transparent border-r-red-500 content-none rounded-lg"></div>
        </div>
      <?php endif; ?>

      <!-- Admin Only Links (Visible for Admin role only) -->
      <?php if ($_SESSION['role'] === 'Admin'): ?>

        <!-- Users Link -->
        <div class="flex w-full justify-center items-center">
          <li class="my-5">
            <a href="users" class="menu">
              <svg class="text-[30px]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                  <path d="M18 21a8 8 0 0 0-16 0" />
                  <circle cx="10" cy="8" r="5" />
                  <path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3" />
                </g>
              </svg>
            </a>
          </li>
          <div id="users" class="hidden absolute right-0 h-[40px] border-[6px] border-transparent border-r-red-500 content-none rounded-lg"></div>
        </div>

        <!-- Inventory Admin Link -->
        <div class="flex w-full justify-center items-center">
          <li class="my-5">
            <a href="inventoryadmin" class="menu">
              <svg class="text-[30px]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                  <path d="M2.97 12.92A2 2 0 0 0 2 14.63v3.24a2 2 0 0 0 .97 1.71l3 1.8a2 2 0 0 0 2.06 0L12 19v-5.5l-5-3zM7 16.5l-4.74-2.85M7 16.5l5-3m-5 3v5.17m5-8.17V19l3.97 2.38a2 2 0 0 0 2.06 0l3-1.8a2 2 0 0 0 .97-1.71v-3.24a2 2 0 0 0-.97-1.71L17 10.5zm5 3l-5-3m5 3l4.74-2.85M17 16.5v5.17" />
                  <path d="M7.97 4.42A2 2 0 0 0 7 6.13v4.37l5 3l5-3V6.13a2 2 0 0 0-.97-1.71l-3-1.8a2 2 0 0 0-2.06 0zM12 8L7.26 5.15M12 8l4.74-2.85M12 13.5V8" />
                </g>
              </svg>
            </a>
          </li>
          <div id="inventoryadmin" class="hidden absolute right-0 h-[40px] border-[6px] border-transparent border-r-red-500 content-none rounded-lg"></div>
        </div>

        <!-- General Report Link -->
        <div class="flex w-full justify-center items-center">
          <li class="my-5">
            <a href="generalreport" class="menu">
              <svg xmlns="http://www.w3.org/2000/svg" class="text-[32px]" width="1em" height="1em" viewBox="0 0 24 24">
                <path fill="currentColor" d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2m0 16H5V5h14zM9 17H7v-5h2zm4 0h-2V7h2zm4 0h-2v-7h2z" />
              </svg>
            </a>
          </li>
          <div id="generalreport" class="hidden absolute right-0 h-[40px] border-[6px] border-transparent border-r-red-500 content-none rounded-lg"></div>
        </div>

      <?php endif; ?>

    </ul>
  </div>
</aside>

<script>
  const path = window.location.pathname;
  const pathName = path.replace('/qrAttendance/', '');

  const links = document.querySelectorAll("a.menu");

  links.forEach(link => {

    if (pathName === link.getAttribute('href')) {
      link.classList.add("text-red-700");
      document.getElementById(pathName).classList.remove("hidden");;
    } else {
      link.classList.add("text-red-200");
    }
  });
</script>