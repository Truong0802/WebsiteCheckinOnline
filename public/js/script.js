var dropdown = document.querySelector('.dropdown');
var dropdownMenu = document.querySelector('.dropdown-menu');
var projectSelector = document.querySelector('.project-selector');

projectSelector.addEventListener('click', function()
{
    if (!dropdownMenu.classList.contains('show')) {
        dropdownMenu.classList.add('show');
    } else {
        dropdownMenu.classList.remove('show');
    }
});

document.addEventListener('click', function(event) {
    if (!dropdown.contains(event.target)) {
        dropdownMenu.classList.remove('show');
    }
});
