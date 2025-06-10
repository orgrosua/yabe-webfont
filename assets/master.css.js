import { initCSSRuntime } from '@master/css-runtime';

// animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
// @keyframes skeleton {
//     0%, 100% {
//       opacity: 1;
//     }
//     50% {
//       opacity: .5;
//     }
//   }

/** @type {import('@master/css').Config} */
const config = {
    important: true,
    scope: '#webfont-app',
    animations: {
        skeleton: {
            '0%, 100%': {
                opacity: 1,
                'animation-timing-function': 'cubic-bezier(0.4, 0, 0.6, 1)',
            },
            '50%': {
                opacity: 0.5,
                'animation-timing-function': 'cubic-bezier(0.4, 0, 0.6, 1)',
            },
        }
    }
};

const masterCSS = initCSSRuntime(config);

export {
    config,
    masterCSS,
};
