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

// function expandContainer(isForcused)
// {
//     var container = document.getElementById("comment-container");
//     if(isForcused)
//         container.style.alignItems = "flex-start";
//     else
//         container.style.alignItems = "center";
// }

var modal = document.getElementById("myModal1");
var btn1 = document.getElementById("open");
var closeBtn1 = document.getElementsByClassName("close")[0];

btn1.addEventListener("click", function()
{
	modal.style.display = "block";
});

closeBtn1.addEventListener("click", function()
{
	modal.style.display = "none";
});

window.addEventListener("click", function(event)
{
	if (event.target == modal)
    {
		modal.style.display = "none";
	}
});

const inputs = document.querySelectorAll('input');

inputs.forEach(input =>
{
    input.setAttribute('autocomplete', 'off')
    input.setAttribute('autocapitalize', 'off')
})
