/*==================== DARK LIGHT THEME ====================*/
const themeButton = document.getElementById('theme-toggle')
const themeButtonMb = document.getElementById('theme-toggle-mb')
const lightTheme = 'light-theme'
const iconTheme = 'fa-moon'

// Previously selected topic (if user selected)
const selectedTheme = localStorage.getItem('selected-theme')
const selectedIcon = localStorage.getItem('selected-icon')

// We obtain the current theme that the interface has by validating the light-theme class
const getCurrentTheme = () => document.body.classList.contains(lightTheme) ? 'light' : 'dark'
const getCurrentIcon = () => themeButton.classList.contains(iconTheme) ? 'fa-sun' : 'fa-moon'

// We validate if the user previously chose a topic
if (selectedTheme) {
    // If the validation is fulfilled, we ask what the issue was to know if we activated or deactivated the dark
    document.body.classList[selectedTheme === 'light' ? 'add' : 'remove'](lightTheme)
    themeButton.classList[selectedIcon === 'fa-sun' ? 'add' : 'remove'](iconTheme)
}

// Activate / deactivate the theme manually with the button
themeButton.addEventListener('click', () => {
    // Add or remove the dark / icon theme
    document.body.classList.toggle(lightTheme)
    themeButton.classList.toggle(iconTheme)
    // We save the theme and the current icon that the user chose
    localStorage.setItem('selected-theme', getCurrentTheme())
    localStorage.setItem('selected-icon', getCurrentIcon())
})

if (themeButtonMb) {
    themeButtonMb.addEventListener('click', () => {
        // Add or remove the dark / icon theme
        document.body.classList.toggle(lightTheme)
        themeButton.classList.toggle(iconTheme)
        // We save the theme and the current icon that the user chose
        localStorage.setItem('selected-theme', getCurrentTheme())
        localStorage.setItem('selected-icon', getCurrentIcon())
    })
}

// Alerts
function showToast(iconType, message) {
    Swal.fire({
        toast: true,
        icon: iconType,
        title: message,
        timer: 5000,
        timerProgressBar: true,
        position: 'top',
        width: 500,
        showConfirmButton: false,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
}

function showAlert(iconType, message, buttonsCallback = null) {
    Swal.fire({
        icon: iconType,
        title: message,
        showConfirmButton: true,
    }).then(buttonsCallback);
}

function showAlertRedirect(iconType, message, buttonsCallback = null) {
    Swal.fire({
        icon: iconType,
        title: message,
        showConfirmButton: true,
    }).then(function() {
        window.location.href = buttonsCallback;
    });
}
