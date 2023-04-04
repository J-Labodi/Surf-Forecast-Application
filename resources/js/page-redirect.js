
// Get all the details links
var detailsLinks = document.getElementsByClassName('details-link');
// Add a click event listener to each link
for (var i = 0; i < detailsLinks.length; i++) {
    detailsLinks[i].addEventListener('click', function(event) {
        event.preventDefault();
        var name = this.getAttribute('data-name');
        // Redirect to the details page with the name value as a query parameter
        window.location.href = 'forecast.php?location=' + name + '&day=day0';
    });
}
