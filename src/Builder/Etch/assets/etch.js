(function () {
    const etchConfig = window.yabeWebfontEtch || {};
    const stylesheetUrl = etchConfig.stylesheet_url;
    const fontFamilies = Array.isArray(etchConfig.font_families) ? etchConfig.font_families : [];

    const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms));

    const getIframeDocument = (iframe) => {
        if (!iframe) {
            return null;
        }

        try {
            return iframe.contentDocument || (iframe.contentWindow && iframe.contentWindow.document) || null;
        } catch (error) {
            return null;
        }
    };

    let lastEditorView = null;
    let isAutocompleteSettingUp = false;
    let pendingAutocompleteRefresh = false;
    let autocompleteRefreshTimer = null;

    const findAutocompletionConfig = (editorView) => {
        const staticValues = editorView?.state?.config?.staticValues;

        if (!Array.isArray(staticValues)) {
            return null;
        }

        for (let i = 0; i < staticValues.length; i++) {
            const val = staticValues[i];
            if (
                val
                && typeof val === 'object'
                && Array.isArray(val.override)
                && val.activateOnTyping !== undefined
                && val.maxRenderedOptions !== undefined
            ) {
                return val;
            }
        }

        return null;
    };

    const buildFontItems = () => fontFamilies
        .filter((font) => font && font.key && font.name)
        .map((font) => ({
            label: `YWF - ${font.name}`,
            type: 'variable',
            apply: `font-family: ${font.key};`,
            detail: '[Yabe Webfont]',
        }));

    const createFontCompletionSource = (fontItems) => function yabeWebfontEtchFontCompletionSource(context) {
        const word = context.matchBefore(/[\w-]*/);

        if (!word || (word.from === word.to && !context.explicit)) {
            return null;
        }

        const typed = word.text.toLowerCase();
        const filtered = fontItems.filter(item => item.label.toLowerCase().includes(typed));

        if (filtered.length === 0) {
            return null;
        }

        return {
            from: word.from,
            options: filtered,
            validFor: /^[\w-]*$/,
        };
    };

    const registerAutocomplete = (editorView) => {
        if (!editorView || lastEditorView === editorView) {
            return;
        }

        const autocompletionConfig = findAutocompletionConfig(editorView);

        if (!autocompletionConfig) {
            return;
        }

        if (!window.__yabeWebfontEtchFontCompletionSource) {
            const fontItems = buildFontItems();
            if (!fontItems.length) {
                return;
            }
            window.__yabeWebfontEtchFontCompletionSource = createFontCompletionSource(fontItems);
        }

        const completionSource = window.__yabeWebfontEtchFontCompletionSource;

        if (!autocompletionConfig.override.includes(completionSource)) {
            autocompletionConfig.override.push(completionSource);
        }

        lastEditorView = editorView;
    };

    const injectStylesheet = (doc) => {
        if (!doc || !stylesheetUrl) {
            return;
        }

        if (doc.getElementById('yabe-webfont-etch-stylesheet')) {
            return;
        }

        const link = doc.createElement('link');
        link.id = 'yabe-webfont-etch-stylesheet';
        link.rel = 'stylesheet';
        link.href = stylesheetUrl;
        doc.head.appendChild(link);
    };

    const waitForIframe = async (maxAttempts = 450, interval = 100) => {
        let attempts = 0;
        let iframe = document.getElementById('etch-iframe');

        while (attempts < maxAttempts) {
            const doc = getIframeDocument(iframe);

            if (iframe && doc && doc.head) {
                return {
                    iframe,
                    doc,
                };
            }

            await sleep(interval);
            iframe = document.getElementById('etch-iframe');
            attempts += 1;
        }

        return {
            iframe: null,
            doc: null,
        };
    };

    const ensureIframeStyles = async () => {
        if (!stylesheetUrl) {
            return;
        }

        const { iframe, doc } = await waitForIframe();

        if (!iframe || !doc) {
            return;
        }

        injectStylesheet(doc);

        if (!iframe.dataset.yabeWebfontEtchObserver) {
            iframe.dataset.yabeWebfontEtchObserver = 'true';
            iframe.addEventListener('load', () => {
                const nextDoc = getIframeDocument(iframe);
                injectStylesheet(nextDoc);
            });
        }
    };

    const waitForCssEditor = async (maxAttempts = 300, interval = 100) => {
        let attempts = 0;
        let contentEl = document.querySelector('.etch-css-editor .cm-editor .cm-content');

        while (attempts < maxAttempts) {
            if (contentEl && contentEl.cmView) {
                return contentEl;
            }

            await sleep(interval);
            contentEl = document.querySelector('.etch-css-editor .cm-editor .cm-content');
            attempts += 1;
        }

        return null;
    };

    const setupAutocomplete = async () => {
        if (!fontFamilies.length) {
            return;
        }

        if (isAutocompleteSettingUp) {
            pendingAutocompleteRefresh = true;
            return;
        }

        isAutocompleteSettingUp = true;

        try {
            const contentEl = await waitForCssEditor();

            if (!contentEl || !contentEl.cmView) {
                return;
            }

            const editorView = contentEl.cmView.rootView && contentEl.cmView.rootView.view
                ? contentEl.cmView.rootView.view
                : null;

            if (!editorView) {
                return;
            }

            registerAutocomplete(editorView);
        } finally {
            isAutocompleteSettingUp = false;
            if (pendingAutocompleteRefresh) {
                pendingAutocompleteRefresh = false;
                setupAutocomplete();
            }
        }
    };

    const scheduleAutocompleteRefresh = () => {
        if (autocompleteRefreshTimer) {
            return;
        }

        autocompleteRefreshTimer = setTimeout(() => {
            autocompleteRefreshTimer = null;
            setupAutocomplete();
        }, 150);
    };

    const hasEditorNodes = (nodes) => Array.from(nodes).some((node) => {
        if (!node || node.nodeType !== 1) {
            return false;
        }

        const element = node;
        if (element.matches('.cm-editor, .etch-css-editor, .cm-content')) {
            return true;
        }

        return typeof element.querySelector === 'function'
            ? element.querySelector('.cm-editor, .etch-css-editor, .cm-content')
            : false;
    });

    const observeCssEditor = () => {
        const observer = new MutationObserver((mutations) => {
            const shouldRefresh = mutations.some((mutation) => (
                hasEditorNodes(mutation.addedNodes) || hasEditorNodes(mutation.removedNodes)
            ));

            if (shouldRefresh) {
                scheduleAutocompleteRefresh();
            }
        });

        observer.observe(document, {
            subtree: true,
            childList: true,
        });
    };

    const observeEtchIframe = () => {
        const observer = new MutationObserver(() => {
            const iframe = document.getElementById('etch-iframe');
            if (iframe && !iframe.dataset.yabeWebfontEtchObserver) {
                ensureIframeStyles();
            }
        });

        observer.observe(document, {
            subtree: true,
            childList: true,
        });
    };

    const init = () => {
        ensureIframeStyles();
        setupAutocomplete();
        observeEtchIframe();
        observeCssEditor();
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
