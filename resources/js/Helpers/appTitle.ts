// @ts-ignore
const appName = import.meta.env.VITE_APP_NAME

export default function (title?: string) {
  return title ? `${title} | ${appName}` : appName
}
