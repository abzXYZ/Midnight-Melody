function setCookie(name, value) {
    document.cookie = name + "=" + value + ";path=/";
}

function getCookie(name) {
    let nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function updateSettingsCookie() {
    let settings = [];
    document.querySelectorAll('input[name="settings[]"]:checked').forEach(function(checkbox) {
        settings.push(checkbox.value);
    });
    setCookie('client_settings', settings.join(','));
}

function getSettingValue(setting) {
    let settings = getCookie('client_settings');
    if (settings) {
        let settingsArray = settings.split(',');
        return settingsArray.includes(setting);
    }
    return false;
}

document.querySelectorAll('input[name="settings[]"]').forEach(function(checkbox) {
    checkbox.addEventListener('change', updateSettingsCookie);
});