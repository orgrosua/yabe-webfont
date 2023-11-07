document.addEventListener('DOMContentLoaded', async function () {
    // replace the content #font-family-group>div>textarea with the yabeFonts in json string
    document.querySelector('#font-family-group>div>textarea').value = JSON.stringify(yabeFonts);
});