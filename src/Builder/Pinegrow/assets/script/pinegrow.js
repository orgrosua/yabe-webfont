document.addEventListener('DOMContentLoaded', async function () {
    await new Promise(resolve => {
        let waitingForEditor = setInterval(() => {

            if (
                window.frames[0].pinegrow?.getSelectedPage() !== null
                && window.frames[0].pinegrow?.getSelectedPage() !== undefined
            ) {
                clearInterval(waitingForEditor);
                resolve();
            }
        }, 3000);
    });

    window.frames[0].pinegrow.getSelectedPage().addStylesheet(window.yabeWebfontPinegrow.stylesheet_url);

    await new Promise(resolve => {
        let waitingForFontFamily = setInterval(() => {
            try {
                if (window.frames[0].$.fn.crsa.defaults.rulesDefinition.sections.text.fields["font-family"] !== undefined) {
                    clearInterval(waitingForFontFamily);
                    resolve();
                }
            } catch (err) { }
        }, 100);
    });

    window.frames[0].$.fn.crsa.defaults.rulesDefinition.sections.text.fields["font-family"].options = function () {
        return window.yabeWebfontPinegrow.font_families || [];
    };
});