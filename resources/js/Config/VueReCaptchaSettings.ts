import { IReCaptchaOptions } from 'vue-recaptcha-v3/dist/IReCaptchaOptions'

// @ts-ignore
const captchaKey = import.meta.env.VITE_RECAPTCHA_KEY

export default {
  siteKey: captchaKey,
  loaderOptions: {
    autoHideBadge: true,
  },
} as IReCaptchaOptions
