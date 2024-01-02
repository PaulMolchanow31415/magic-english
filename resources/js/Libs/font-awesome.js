import { library } from '@fortawesome/fontawesome-svg-core'
import {
  faCheck,
  faCircleInfo,
  faDesktop,
  faMobileScreenButton,
  fas,
  faXmark,
} from '@fortawesome/free-solid-svg-icons'

library.add(fas, faDesktop, faMobileScreenButton, faCircleInfo, faXmark, faCheck)

// <font-awesome-icon :icon="['fas', 'check']" />
// <font-awesome-icon :icon="['fas', 'xmark']" />
// <font-awesome-icon :icon="['fas', 'circle-info']" />
// <font-awesome-icon :icon="['fas', 'desktop']" />
// <font-awesome-icon :icon="['fas', 'mobile-screen-button']" />
