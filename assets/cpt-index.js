import domReady from '@wordpress/dom-ready';

domReady(async () => {
    const fontImportBtn = document.createElement('a');
    fontImportBtn.classList.add('page-title-action');
    fontImportBtn.textContent = 'Import Google Fonts';
    fontImportBtn.href = yabeWebfontCptIndex.action_url;

    const addNewBtn = document.querySelector('body.post-type-bricks_fonts .wp-header-end');
    addNewBtn.insertAdjacentElement('beforebegin', fontImportBtn);
});