document.addEventListener('DOMContentLoaded', function() { // For drag and drop image
    let dropArea = document.querySelector('.dropArea');
    let fileInput = document.querySelector('.fileInput');

    if(dropArea){
      dropArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropArea.classList.add('border-4', 'border-solid','border-blue-300');
      });
    
      dropArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropArea.classList.remove('border-4', 'border-solid','border-blue-300');
      });
  
    if(fileInput){
          dropArea.addEventListener('drop', function(e) {
            e.preventDefault();
            dropArea.classList.remove('border-4', 'border-solid','border-green-300');
            fileInput.files = e.dataTransfer.files;
            previewFiles(fileInput.files);
          });

          fileInput.addEventListener('change', function(e) {
            previewFiles(fileInput.files);
          });
        
          function previewFiles(files) {
        
              let file = files[0];
              let reader = new FileReader();
        
              reader.onload = function(e) {
                let img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('absolute', 'w-full', 'h-full',);
                dropArea.appendChild(img);
              };
        
              reader.readAsDataURL(file);
            
          }
    }
    }
    
  });

// function validateForm() { // For form validation
//   var fname = document.forms["form"]["fname"].value;
//   var mname = document.forms["form"]["mname"].value;
//   var lname = document.forms["form"]["lname"].value;
//   var idnumber = document.forms["form"]["idnumber"].value;

//   
//   var numbersOnly = /^[0-9]+$/;

//   console.log(idnumber);
//   console.log(fname);

//   if (!lettersOnly.test(fname)) {
//       document.querySelector('#fname').classList.add('border-4','border-red-300');
//       document.querySelector('#firstname p').classList.remove('opacity-0');
//       return false;
//   }
//   if (!lettersOnly.test(mname)) {
//     document.querySelector('#mname').classList.add('border-4','border-red-300');
//     document.querySelector('#middlename p').classList.remove('opacity-0');
//     return false;
//   }
//   if (!lettersOnly.test(lname)) {
//     document.querySelector('#lname').classList.add('border-4','border-red-300');
//     document.querySelector('#lastname p').classList.remove('opacity-0');
//     return false;
//   }
//   if (!numbersOnly.test(idnumber)) {
//     document.querySelector('#idnumber').classList.add('border-4','border-red-300');
//     document.querySelector('#studentidnumber p').classList.remove('opacity-0');
//     return false;
//   }else{
//     document.querySelector('#idnumber').classList.remove('border-4','border-red-300');
//     document.querySelector('#studentidnumber p').classList.add('opacity-0');
//   }
  
//   return true;
// }

var lettersOnly =/^[a-zA-Z\s]*$/;

// Add event listeners to input fields for real-time validation
document.addEventListener('DOMContentLoaded', function() {
  var form = document.forms["form"];
  
  // Check if the form exists before proceeding
  if (form) {
    var fnameInput = form["fname"];
    var mnameInput = form["mname"];
    var lnameInput = form["lname"];
    var idnumberInput = form["idnumber"];

    // Add event listeners only if the element exists
    if (fnameInput) {
      fnameInput.addEventListener('input', function() {
        validateFirstName(fnameInput.value);
      });
    }

    if (mnameInput) {
      mnameInput.addEventListener('input', function() {
        validateMiddleName(mnameInput.value);
      });
    }

    if (lnameInput) {
      lnameInput.addEventListener('input', function() {
        validateLastName(lnameInput.value);
      });
    }

    if (idnumberInput) {
      idnumberInput.addEventListener('input', function() {
        validateIDNumber(idnumberInput.value);
      });
    }

    // Add a submit listener only if at least `fnameInput` or `idnumberInput` exists
    if (fnameInput || idnumberInput) {
      form.addEventListener('submit', function(event) {
        if ((fnameInput && !validateFirstName(fnameInput.value)) || 
            (idnumberInput && !validateIDNumber(idnumberInput.value))) {
          event.preventDefault(); 
        }
      });
    }
  }
});

// Validation functions
function validateFirstName(fname) {
  var fnameElement = document.querySelector('#fname');
  var fnameErrorElement = document.querySelector('#firstname p');

  if (!lettersOnly.test(fname)) {
    fnameElement.classList.add('border-2', 'border-red-300', 'focus:ring-red-500', 'focus:border-red-500');
    fnameErrorElement.classList.remove('opacity-0');
    return false;
  } else {
    fnameElement.classList.remove('border-2', 'border-red-300', 'focus:ring-red-500', 'focus:border-red-500');
    fnameErrorElement.classList.add('opacity-0');
    return true;
  }
}

function validateMiddleName(mname) {
  var fnameElement = document.querySelector('#mname');
  var fnameErrorElement = document.querySelector('#middlename p');

  if (!lettersOnly.test(mname)) {
    fnameElement.classList.add('border-2', 'border-red-300', 'focus:ring-red-500', 'focus:border-red-500');
    fnameErrorElement.classList.remove('opacity-0');
    return false;
  } else {
    fnameElement.classList.remove('border-2', 'border-red-300', 'focus:ring-red-500', 'focus:border-red-500');
    fnameErrorElement.classList.add('opacity-0');
    return true;
  }
}

function validateLastName(lname) {
  var fnameElement = document.querySelector('#lname');
  var fnameErrorElement = document.querySelector('#lastname p');

  if (!lettersOnly.test(lname)) {
    fnameElement.classList.add('border-2', 'border-red-300', 'focus:ring-red-500', 'focus:border-red-500');
    fnameErrorElement.classList.remove('opacity-0');
    return false;
  } else {
    fnameElement.classList.remove('border-2', 'border-red-300', 'focus:ring-red-500', 'focus:border-red-500');
    fnameErrorElement.classList.add('opacity-0');
    return true;
  }
}

function validateIDNumber(idnumber) {
  var numbersOnly = /^[0-9]+$/;
  var idnumberElement = document.querySelector('#idnumber');
  var idnumberErrorElement = document.querySelector('#studentidnumber p');

  if (!numbersOnly.test(idnumber)) {
    idnumberElement.classList.add('border-2', 'border-red-300', 'focus:ring-red-500', 'focus:border-red-500');
    idnumberErrorElement.classList.remove('opacity-0');
    return false;
  } else {
    idnumberElement.classList.remove('border-2', 'border-red-300', 'focus:ring-red-500', 'focus:border-red-500');
    idnumberErrorElement.classList.add('opacity-0');
    return true;
  }
}

function showPassword(){ // For show password
  var pwdInput = document.getElementById('password');
  var close = document.getElementById('eye_1');
  var open = document.getElementById('eye_2');

  if(pwdInput.type === "password"){
      close.style.display = "none";
      open.style.display = "block";
      pwdInput.type = "text";
      console.log(pwdInput.type);
      console.log(close.style.display);

  }else{
      open.style.display = "none";
      close.style.display = "block";
      pwdInput.type = "password";
      console.log(pwdInput.type);
      console.log(close.style.display);
  }

}


function updateThis(id){ 

}

function deleteThis(id){ 
  document.getElementById('yes').href = "./delete.php?id="+id;
}

function updateThisItem(id){ 

}

function deleteThisItem(id){ 
  document.getElementById('yes').href = "./delete.php?itemid="+id;
}

function zoomImage(path){ 
  document.getElementById('imageqr').src = path;
}

function showItemImage(path){ 
  document.getElementById('imageItem').src = path;
}

function deleteThisStudent(id){ 
  document.getElementById('yes').href = "./delete.php?studentid="+id;
}



// Function to show alert
function showAlert(message, type = 'success') {
  const alertContainer = document.getElementById('alert-container');
  const alertBox = document.createElement('div');

  // Determine the classes for success and error alerts
  let alertClass = '';
  let buttonClass = '';
  let iconPath = '';

  if (type === 'error') {
    alertClass = ['text-red-800', 'bg-red-50', 'border-t-4', 'border-red-300'];
    buttonClass = 'bg-red-50 text-red-500 hover:bg-red-200'; // Close button styles for error
    iconPath = `<path d="M10 2a8 8 0 1 1 0 16 8 8 0 0 1 0-16zm0 3a1 1 0 0 1 1 1v4a1 1 0 0 1-2 0V4a1 1 0 0 1 1-1zm0 7a1 1 0 0 1 1 1v2a1 1 0 0 1-2 0v-2a1 1 0 0 1 1-1z"/>`;
  } else if (type === 'success') {
    alertClass = ['text-green-800', 'bg-green-50', 'border-t-4', 'border-green-300'];
    buttonClass = 'bg-green-50 text-green-500 hover:bg-green-200'; // Close button styles for success
    iconPath = `<path d="M9 1a8 8 0 1 1 0 16 8 8 0 0 1 0-16zm0 3a1 1 0 0 1 1 1v4a1 1 0 0 1-2 0V4a1 1 0 0 1 1-1zm0 7a1 1 0 0 1 1 1v2a1 1 0 0 1-2 0v-2a1 1 0 0 1 1-1z"/>`;
  }

  // Add classes to the alertBox element
  alertBox.classList.add('flex', 'items-center', 'p-4', 'mb-4', 'rounded-lg');
  alertBox.classList.add(...alertClass);  // Spread the array of classes

  alertBox.innerHTML = `
    <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
        ${iconPath}
    </svg>
    <span class="sr-only">${type === 'error' ? 'Error' : 'Success'}</span>
    <div class="ml-3 text-sm font-medium">${message}</div>
    <button type="button" class="ml-auto ${buttonClass} rounded-lg focus:ring-2 focus:ring-blue-400 p-1.5 inline-flex items-center justify-center h-8 w-8" aria-label="Close">
        <span class="sr-only">Close</span>
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
        </svg>
    </button>
  `;

  alertContainer.appendChild(alertBox);

  // Add event listener to close the alert when the close button is clicked
  const closeButton = alertBox.querySelector('button');
  closeButton.addEventListener('click', () => {
      alertBox.remove();
  });

  // Optional: Automatically remove alert after 5 seconds
  setTimeout(() => {
      alertBox.remove();
  }, 5000);
}




function searchTable(tableId, inputId) {
  const searchTerm = document.getElementById(inputId).value.toLowerCase();

  const table = document.getElementById(tableId);
  const rows = table.getElementsByTagName('tr');
  
  for (let i = 1; i < rows.length; i++) {
      const cells = rows[i].getElementsByTagName('td');
      let matchFound = false;
      
      for (let j = 0; j < cells.length; j++) {
          const cellText = cells[j].textContent || cells[j].innerText;
          
          if (cellText.toLowerCase().includes(searchTerm)) {
              matchFound = true;
              break;
          }
      }
      
      if (matchFound) {
          rows[i].classList.remove('hidden');
      } else {
          rows[i].classList.add('hidden');
      }
  }
}

function toggleUpdateModal(id) {
  const updateModal = document.getElementById(`update${id}`);
  if (updateModal) {
      updateModal.classList.toggle('hidden');
  }
}


