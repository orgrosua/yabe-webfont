import 'awesome-notifications/dist/style.css';
import AWN from 'awesome-notifications';

import iconAsync from '../images/icons/gear-solid.svg';
import iconSuccess from '../images/icons/circle-check-solid.svg';
import iconTip from '../images/icons/circle-question-solid.svg';
import iconInfo from '../images/icons/circle-info-solid.svg';
import iconWarning from '../images/icons/circle-exclamation-solid.svg';
import iconAlert from '../images/icons/triangle-exclamation-solid.svg';

export function useNotifier(options = {}) {
    return new AWN(Object.assign({
        icons: {
            prefix: ``,
            suffix: ``,

            tip: `<img class="icon-tip" src="${iconTip}"/>`,
            async: `<img class="icon-async" src="${iconAsync}"/>`,
            info: `<img class="icon-info" src="${iconInfo}"/>`,
            success: `<img class="icon-success" src="${iconSuccess}"/>`,
            warning: `<img class="icon-warning" src="${iconWarning}"/>`,
            alert: `<img class="icon-alert" src="${iconAlert}"/>`,

        }
    }, options));
}