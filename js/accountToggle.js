document.getElementById('dropdownToggle').addEventListener('click', function() {
var menu = document.getElementById('dropdownMenu');
var icon = document.getElementById('dropdownToggle');

// Toggle visibility of the dropdown menu
menu.style.display = menu.style.display === 'block' ? 'none' : 'block';

// Toggle the icon rotation
icon.classList.toggle('rotate');
});

// Close the dropdown if clicked outside
window.addEventListener('click', function(event) {
var menu = document.getElementById('dropdownMenu');
var icon = document.getElementById('dropdownToggle');

// Close the dropdown if the click is outside the dropdown or icon
if (!menu.contains(event.target) && event.target !== icon) {
menu.style.display = 'none';
icon.classList.remove('rotate');
}
});

