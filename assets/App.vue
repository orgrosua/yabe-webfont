<template>
    <h1 class="wp-heading-inline">{{ __('Yabe Webfont', 'yabe-webfont') }}</h1>
    <div class="yabe-webfont-top-menu p-2">
        <a href="https://rosua.com" target="_blank">Rosua.com</a> |
        <a href="https://rosua.com/news/" target="_blank">News</a> |
        <a href="https://l.suabahasa.dev/EyJym" target="_blank">Support</a> |
        <a href="https://l.suabahasa.dev/s7XEu" target="_blank">Lead developer's homepage</a> |
        <a aria-label="more plug-ins" href="https://rosua.org/" target="_blank">More plugins</a> -
        <span tabindex="0">Version: {{ yabeWebfont._version }}</span>
    </div>
    <hr class="wp-header-end">
    <h2 class="nav-tab-wrapper">
        <router-link :to="{ name: 'fonts.index' }" class="nav-tab" activeClass="nav-tab-active">Fonts</router-link>
        <router-link :to="{ name: 'settings' }" class="nav-tab" activeClass="nav-tab-active">Settings</router-link>
    </h2>
    <div class="yabe-webfont-content">
        <router-view></router-view>
    </div>
</template>

<script setup></script>

<style lang="scss">
#wpcontent {
    padding: 20px;
}

/** Notice */

#yabe-webfont-app {

    .notice,
    #lost-connection-notice {
        position: relative;
        box-sizing: border-box;
        min-height: 48px;
        margin: 8px 0 8px !important;
        padding: 13px 16px 12px 50px !important;
        background-color: #E7EFF9;
        border: 1px solid #9DBAEE;
        border-radius: theme('borderRadius.lg');
        box-shadow: 0px 1px 2px rgba(16, 24, 40, 0.1);
        color: #344054;

        &.update-nag {
            display: block;
            position: relative;
            width: calc(100% - 44px);
            margin: 48px 44px -32px 12px !important;
        }

        .button {
            height: auto;
            margin-left: 8px;
            padding: 0;
            border: none;
        }

        >div {
            margin: 0px;
        }

        p {
            flex: 1 0 auto;
            margin: 0;
            padding: 0;

            &.help {
                margin: 0px;
                padding: 0px;
                color: rgba(#344054, .7);
            }

        }

        // Dismiss button
        .notice-dismiss {
            position: absolute;
            top: 4px;
            right: 8px;

            &:before {
                content: '';
                display: block;
                position: relative;
                z-index: 600;
                width: 20px;
                height: 20px;
                background-color: #667085;
                border: none;
                border-radius: 0;
                mask-size: contain;
                mask-repeat: no-repeat;
                mask-position: center;
                mask-image: url('./images/icons/xmark-light.svg');
            }

            &:hover::before {
                background-color: #344054;
            }
        }

        // Icon base styling
        &:before {
            content: '';
            display: block;
            position: absolute;
            top: 15px;
            left: 18px;
            z-index: 600;
            width: 16px;
            height: 16px;
            margin-right: 8px;
            background-color: #fff;
            border: none;
            border-radius: 0;
            mask-size: contain;
            mask-repeat: no-repeat;
            mask-position: center;
            mask-image: url('./images/icons/circle-info-solid.svg');
        }

        &:after {
            content: '';
            display: block;
            position: absolute;
            top: 9px;
            left: 12px;
            z-index: 500;
            width: 28px;
            height: 28px;
            background-color: #2D69DA;
            border-radius: theme('borderRadius.md');
            box-shadow: 0px 1px 2px rgba(16, 24, 40, 0.1);
        }

        .local-restore {
            align-items: center;
            margin: -6px 0 0;
        }

    }

    .notice.is-dismissible {
        padding-right: 56px !important;
    }

    // Success notice
    .notice.notice-success {
        background-color: #EDF7EF;
        border-color: #B6DEB9;

        &:before {
            mask-image: url('./images/icons/circle-check-solid.svg');
        }

        &:after {
            background-color: #52AA59;
        }
    }

    // Warning notice
    .notice.notice-warning,
    #lost-connection-notice {
        background-color: #fbfde7;
        border-color: #e9c45d;

        &:before {
            mask-image: url('./images/icons/triangle-exclamation-solid.svg');
        }

        &:after {
            background-color: #F5A623;
        }
    }

    // Error notice
    .notice.notice-error,
    #lost-connection-notice {
        background-color: #F7EEEB;
        border-color: #F1B6B3;

        &:before {
            mask-image: url('./images/icons/hexagon-exclamation-solid.svg');
        }

        &:after {
            background-color: #D13737;
        }
    }
}
</style>