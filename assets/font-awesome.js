/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core';

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

/* import specific icons */
import {
    faBook,
    faUserHeadset,
    faEllipsisVertical,
    faRectanglePro,
    faFaceSmileHearts,
    faHourglassClock,
    faRss,
    faSpinner,
    faTriangleExclamation,
    faCircleInfo,
    faXmark,
    faCheck,
    faRocket,
    faRocketLaunch,
    faArrowUp19,
    faPenToSquare,
    faTrashCan,
    faFont,
    faDeleteLeft,
    faRankingStar,
    faCodePullRequest,
    faCalendarPen,
    faHandHoldingMagic,
    faGear,
    faPaintBrush,
} from '@fortawesome/pro-solid-svg-icons';

/* import specific icons */
import {
    faArrowUpRightFromSquare,
    faFileImport,
    faBroom,
} from '@fortawesome/pro-regular-svg-icons';

import {
    faNpm,
    faFacebook,
} from '@fortawesome/free-brands-svg-icons';

/* add icons to the library */
library.add(
    /** fas */
    faBook,
    faUserHeadset,
    faEllipsisVertical,
    faRectanglePro,
    faFaceSmileHearts,
    faHourglassClock,
    faRss,
    faSpinner,
    faTriangleExclamation,
    faCircleInfo,
    faXmark,
    faCheck,
    faRocket,
    faRocketLaunch,
    faArrowUp19,
    faPenToSquare,
    faTrashCan,
    faFont,
    faDeleteLeft,
    faRankingStar,
    faCodePullRequest,
    faCalendarPen,
    faHandHoldingMagic,
    faGear,
    faPaintBrush,

    /** far */
    faArrowUpRightFromSquare,
    faFileImport,
    faBroom,

    /** fab */
    faNpm,
    faFacebook,
);

export { library, FontAwesomeIcon };