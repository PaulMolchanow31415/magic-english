const captchaKey = import.meta.env.VITE_RECAPTCHA_KEY

export default {
  siteKey: captchaKey,
  loaderOptions: {
    autoHideBadge: true,
  },
}
