
// get the search input element
const searchInput = document.getElementById('search');

// add event listener for key press
searchInput.addEventListener('keypress', function(event) {
  // check if Enter key was pressed
  if (event.keyCode === 13) {
    // submit the form
    event.preventDefault();
    document.forms[0].submit();
  }
});


