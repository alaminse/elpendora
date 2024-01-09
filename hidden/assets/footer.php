<script>
/*========== CHANGE THE TAB =============*/
const currentLocation = location.href;
const menuItem = document.querySelectorAll('header nav ul a');
const menuLength = menuItem.length
for (let i = 0; i < menuLength; i++) {
    if (menuItem[i].href === currentLocation) {
        menuItem[i].classList.remove('active-link');
        menuItem[i].classList.add('active-link');
    }
}
</script>
</body>
</html>