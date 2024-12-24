<script src="https://unpkg.com/flowbite@1.5.1/dist/flowbite.js"></script>

<?php

date_default_timezone_set('Asia/Manila');

include "sidebar.php";
include "./connect.php";
?>

<nav class="absolute pt-3 w-full border-gray-200  px-[130px]">
  <div class=" px-[50px]  bg-white rounded-lg flex items-center justify-between mx-auto shadow-md shadow-gray-200">
    <div class=" w-auto flex justify-center items-center" id="navbar-multi-level">
      <p id="pathName" class="font-extrabold tracking-wide text-gray-900 text-[18px]"></p>
    </div>

    <div class="flex items-center flex-between font-poppins">

      <div class="flex w-full mr-5 gap-5">
        <!-- Dropdown for Year & Section -->
        <select name="section" id="section" class="cursor-pointer px-5 border-2 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 hover:bg-gray-50 w-[180px] py-2">
          <option selected disabled>Year&Section</option>
          <?php
          // Query to get Year & Section data
          $ys_query = "SELECT * FROM yr_sec";
          $ys_result = mysqli_query($conn, $ys_query);

          if ($ys_result && mysqli_num_rows($ys_result) > 0) {
            while ($ys_row = mysqli_fetch_assoc($ys_result)) {
              // Check if the session value matches the current row's ID
              $selected = isset($_SESSION['section']) && $_SESSION['section'] == $ys_row['id'] ? 'selected' : '';
          ?>
              <option value="<?php echo $ys_row['id']; ?>" <?php echo $selected; ?>>
                <?php echo htmlspecialchars($ys_row['year_and_sec']); ?>
              </option>
          <?php
            }
          }
          ?>
        </select>

        <!-- Dropdown for Group Number -->
        <select name="groupnumber" id="groupnumber" class="cursor-pointer px-5 border-2 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 hover:bg-gray-50 w-[130px] py-2">
          <option selected disabled>Group</option>
          <?php
          // Query to get Group Number data
          $gn_query = "SELECT * FROM group_no";
          $gn_result = mysqli_query($conn, $gn_query);

          if ($gn_result && mysqli_num_rows($gn_result) > 0) {
            while ($gn_row = mysqli_fetch_assoc($gn_result)) {
              // Check if the session value matches the current row's ID
              $selected = isset($_SESSION['groupnumber']) && $_SESSION['groupnumber'] == $gn_row['id'] ? 'selected' : '';
          ?>
              <option value="<?php echo $gn_row['id']; ?>" <?php echo $selected; ?>>
                <?php echo htmlspecialchars($gn_row['group_number']); ?>
              </option>
          <?php
            }
          }
          ?>
        </select>
      </div>


      <div class="border border-gray-300 content-none h-[30px] mr-[20px]"></div>
      <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">

        <button type="button" class=" py-2 flex justify-between align-center items-center text-sm rounded-full w-full" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
          <svg class="text-gray-900" xmlns="http://www.w3.org/2000/svg" width="40" viewBox="0 0 24 24">
            <g fill="currentColor" fill-rule="evenodd" clip-rule="evenodd">
              <path d="M16 9a4 4 0 1 1-8 0a4 4 0 0 1 8 0m-2 0a2 2 0 1 1-4 0a2 2 0 0 1 4 0" />
              <path d="M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11s11-4.925 11-11S18.075 1 12 1M3 12c0 2.09.713 4.014 1.908 5.542A8.99 8.99 0 0 1 12.065 14a8.98 8.98 0 0 1 7.092 3.458A9 9 0 1 0 3 12m9 9a8.96 8.96 0 0 1-5.672-2.012A6.99 6.99 0 0 1 12.065 16a6.99 6.99 0 0 1 5.689 2.92A8.96 8.96 0 0 1 12 21" />
            </g>
          </svg>
          <div class="px-3 text-nowrap">
            <p class="font-bold tracking-wide text-[16px] text-gray-900 text-start w-full"><?php echo $_SESSION['name']; ?></p>
            <p class="text-gray-500 tracking-wide text-start font-medium "><?php echo $_SESSION['role']; ?></p>
          </div>
        </button>

        <!-- Dropdown menu -->
        <div class="z-50 hidden my-4 text-base list-none divide-y divide-gray-100 rounded-lg shadow-md bg-emerald-950" id="user-dropdown">
          <ul class="py-2" aria-labelledby="user-menu-button">
            <li>
              <a href="profile" class="block px-10 py-2 text-gray-200 hover:bg-gray-100 hover:text-gray-800">Profile</a>
            </li>
            <li>
              <a href="changepassword" class="block px-10 py-2 text-gray-200 hover:bg-gray-100 hover:text-gray-800">Change Password</a>
            </li>
            <li>
              <a href="logout.php" class="block px-10 py-2 text-gray-200 hover:bg-gray-100 hover:text-gray-800">Sign out</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</nav>

<!-- For route name and display -->
<script>
  const route = window.location.pathname;
  var routeName = route.replace('/qrAttendance/', '');

  function upperInitial(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  if (routeName === "addstudent") {
    routeName = 'Add Student';
  } else if (routeName === "attendanceoverview") {
    routeName = 'Attendance Overview';
  } else if (routeName === "inventoryadmin") {
    routeName = 'Inventory Management';
  } else if (routeName === "additem") {
    routeName = 'Add Item';
  }

  let titleName = upperInitial(routeName);
  var titlePage = document.getElementById('pathName');


  titlePage.innerText = titleName;
</script>


<!-- If the dropdown is change create a session for it -->
<script>
  function updateSession(selectedElementId, newValue) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'session.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send(selectedElementId + '=' + encodeURIComponent(newValue));

    xhr.onload = function() {
      if (xhr.status == 200) {
        console.log('Session variable set successfully for ' + selectedElementId);
        console.log(xhr.responseText);
      } else {
        console.error('Error setting session variable.');
      }
    };
  }

  function reloadTable() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'attendanceLogs.php', true);

    xhr.readyState = function() {
      console.log(xhr.statusText);
      if (xhr.status == 200) {
        console.log('Table reloaded succesfully');
      } else {
        console.error('Error setting session variable.');
      }
    };
  }

  document.addEventListener('DOMContentLoaded', function() {
    // Select the first select element
    var selectedSection = document.getElementById('section');

    // Add change event listener
    selectedSection.addEventListener('change', function() {
      var selectedOption = selectedSection.value;
      updateSession('section', selectedOption);
      reloadTable();
    });

    // Select the second select element
    var selectedGroup = document.getElementById('groupnumber');

    // Add change event listener
    selectedGroup.addEventListener('change', function() {
      var selectedOption = selectedGroup.value;
      updateSession('groupnumber', selectedOption);
      reloadTable();
    });
  });
</script>