var dropdown = document.querySelector('.dropdown');
var dropdownMenu = document.querySelector('.dropdown-menu');
var projectSelector = document.querySelector('.project-selector');

document.addEventListener('click', function(event) {
    if (!dropdown.contains(event.target)) {
        dropdownMenu.classList.remove('show');
    }
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
