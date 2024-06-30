import { ref } from 'vue'
import { TypeToast } from '../Types'
import { quickEnableRef } from './quickEnableRef'
import { Toast } from '../Classes'

export default function (message: string, type: TypeToast) {
  const show = ref(false)

  // fixme
  const toast = new Toast({
    value: message,
    type,
    isShow: show.value,
  })

  quickEnableRef(show)

  // todo global stating
  // toaster state !!!push!!! toast
}
