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

document.addEventListener("DOMContentLoaded", function()
{
    const toggleButton = document.getElementById("toggleButton");
    const leftPanel = document.getElementById("left-panel");
    const main = document.getElementById("main");
    const footer = document.getElementById("page-footer");

    toggleButton.addEventListener("click", function()
    {
        leftPanel.classList.toggle("collapsed");
        main.classList.toggle("collapsed");
        footer.classList.toggle("collapsed");
    });
});

function expandContainer(isForcused)
{
    var container = document.getElementById("comment-container");
    if(isForcused)
        container.style.alignItems = "flex-start";
    else
        container.style.alignItems = "center";
}
