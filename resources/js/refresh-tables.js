function refreshTable(day) {

  // Get the current query string
  var currentQuery = window.location.search;

  // Check if a day parameter already exists in the query string
  var hasDay = currentQuery.indexOf('day=') !== -1;

  // If the current query string is empty, set the new query string
  if (currentQuery === '') {
    location.search = '?' + 'day=' + day;
  }
  // If a day parameter already exists, replace its value with the new day value
  else if (hasDay) {
    var regex = new RegExp('(day=)[^&]*');
    var newQuery = currentQuery.replace(regex, '$1' + day);
    window.location.search = newQuery;
  }
  // Otherwise, append the new query string to the current query string
  else {
    // Append the new query string to the current query string
    var newQuery = currentQuery + '&day=' + day;

    // Get the current file name and path
    var currentPath = window.location.pathname.split('/').pop();

    // Reload the page with the updated query string
    window.location.href = currentPath + newQuery;
  }
}
  