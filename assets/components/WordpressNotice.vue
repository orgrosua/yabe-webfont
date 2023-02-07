<template>
    <TransitionGroup name="notice">
        <div v-for="notice in notices" :key="notice.id" :class="`notice notice-${notice.type} is-dismissible`">
            <div v-html="notice.message"></div>
            <button type="button" @click="store.remove(notice.id)" class="notice-dismiss">
                <span class="screen-reader-text">{{ __('Dismiss this notice.', 'yabe-webfont') }}</span>
            </button>
        </div>
    </TransitionGroup>
</template>

<script setup>
import { storeToRefs } from 'pinia';
import { assetPath } from '../library/assetsHelper';
import { useWordpressNotice } from '../stores/wordpressNotice';

import xmarkLightSvg from '../images/icons/xmark-light.svg';
import circleCheckSolidSvg from '../images/icons/circle-check-solid.svg';
import circleInfoSolidSvg from '../images/icons/circle-info-solid.svg';
import triangleExclamationSolidSvg from '../images/icons/triangle-exclamation-solid.svg';
import hexagonExclamationSolidSvg from '../images/icons/hexagon-exclamation-solid.svg';

const store = useWordpressNotice();
const { notices } = storeToRefs(store);

const icon = {
    xmarkLight: `url('${assetPath(xmarkLightSvg)}')`,
    circleCheckSolid: `url('${assetPath(circleCheckSolidSvg)}')`,
    circleInfoSolid: `url('${assetPath(circleInfoSolidSvg)}')`,
    triangleExclamationSolid: `url('${assetPath(triangleExclamationSolidSvg)}')`,
    hexagonExclamationSolid: `url('${assetPath(hexagonExclamationSolidSvg)}')`,
};
</script>

<style scoped>
.notice-enter-active,
.notice-leave-active {
    transition: transform 0.4s, opacity 0.6s;
}

.notice-enter-from,
.notice-leave-to {
    opacity: 0;
    transform: scale(0.88);
}
</style>

<style lang="scss">
.awn-toast-icon {
    img {
        width: theme('width.11');
        height: theme('height.11');

        &.icon-tip,
        &.icon-async {
            filter: invert(52%) sepia(1%) saturate(0%) hue-rotate(13deg) brightness(96%) contrast(88%);
        }

        &.icon-info {
            filter: invert(39%) sepia(49%) saturate(960%) hue-rotate(160deg) brightness(88%) contrast(87%);
        }

        &.icon-success {
            filter: invert(35%) sepia(86%) saturate(448%) hue-rotate(56deg) brightness(104%) contrast(87%);
        }

        &.icon-warning {
            filter: invert(33%) sepia(73%) saturate(3587%) hue-rotate(30deg) brightness(105%) contrast(102%);
        }

        &.icon-alert {
            filter: invert(16%) sepia(62%) saturate(3834%) hue-rotate(355deg) brightness(93%) contrast(87%);
        }

        &.icon-async {
            animation: spin 2s linear infinite;

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }
        }
    }
}

/** Wordpress Notice */
// #yabe-webfont-app {

//     .notice,
//     #lost-connection-notice {
//         position: relative;
//         box-sizing: border-box;
//         min-height: 48px;
//         margin: 8px 0 8px !important;
//         padding: 13px 16px 12px 50px !important;
//         background-color: #E7EFF9;
//         border: 1px solid #9DBAEE;
//         border-radius: theme('borderRadius.lg');
//         box-shadow: 0px 1px 2px rgba(16, 24, 40, 0.1);
//         color: #344054;

//         &.update-nag {
//             display: block;
//             position: relative;
//             width: calc(100% - 44px);
//             margin: 48px 44px -32px 12px !important;
//         }

//         .button {
//             height: auto;
//             margin-left: 8px;
//             padding: 0;
//             border: none;
//         }

//         >div {
//             margin: 0px;
//         }

//         p {
//             flex: 1 0 auto;
//             margin: 0;
//             padding: 0;

//             &.help {
//                 margin: 0px;
//                 padding: 0px;
//                 color: rgba(#344054, .7);
//             }

//         }

//         // Dismiss button
//         .notice-dismiss {
//             position: absolute;
//             top: 4px;
//             right: 8px;

//             &:before {
//                 content: '';
//                 display: block;
//                 position: relative;
//                 z-index: 600;
//                 width: 20px;
//                 height: 20px;
//                 background-color: #667085;
//                 border: none;
//                 border-radius: 0;
//                 mask-size: contain;
//                 mask-repeat: no-repeat;
//                 mask-position: center;
//                 mask-image: v-bind('icon.xmarkLight');
//             }

//             &:hover::before {
//                 background-color: #344054;
//             }
//         }

//         // Icon base styling
//         &:before {
//             content: '';
//             display: block;
//             position: absolute;
//             top: 15px;
//             left: 18px;
//             z-index: 600;
//             width: 16px;
//             height: 16px;
//             margin-right: 8px;
//             background-color: #fff;
//             border: none;
//             border-radius: 0;
//             mask-size: contain;
//             mask-repeat: no-repeat;
//             mask-position: center;
//             mask-image: v-bind('icon.circleInfoSolid');
//         }

//         &:after {
//             content: '';
//             display: block;
//             position: absolute;
//             top: 9px;
//             left: 12px;
//             z-index: 500;
//             width: 28px;
//             height: 28px;
//             background-color: #2D69DA;
//             border-radius: theme('borderRadius.md');
//             box-shadow: 0px 1px 2px rgba(16, 24, 40, 0.1);
//         }

//         .local-restore {
//             align-items: center;
//             margin: -6px 0 0;
//         }

//     }

//     .notice.is-dismissible {
//         padding-right: 56px !important;
//     }

//     // Success notice
//     .notice.notice-success {
//         background-color: #EDF7EF;
//         border-color: #B6DEB9;

//         &:before {
//             mask-image: v-bind('icon.circleCheckSolid');
//         }

//         &:after {
//             background-color: #52AA59;
//         }
//     }

//     // Warning notice
//     .notice.notice-warning,
//     #lost-connection-notice {
//         background-color: #fbfde7;
//         border-color: #e9c45d;

//         &:before {
//             mask-image: v-bind('icon.triangleExclamationSolid');
//         }

//         &:after {
//             background-color: #F5A623;
//         }
//     }

//     // Error notice
//     .notice.notice-error,
//     #lost-connection-notice {
//         background-color: #F7EEEB;
//         border-color: #F1B6B3;

//         &:before {
//             mask-image: v-bind('icon.hexagonExclamationSolid');
//         }

//         &:after {
//             background-color: #D13737;
//         }
//     }
// }
</style>