const appName = import.meta.env.VITE_APP_NAME

export default function(title) {
  return `${title} | ${appName}`
}
