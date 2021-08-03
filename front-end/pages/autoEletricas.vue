<template>
  <v-container fluid>
    <Autos :autos="autoEletrica" :lacres-log="lacreLog" :cabos-log="caboLog" />
  </v-container>
</template>

<script>
import Autos from '~/components/autoeletrica/AutoEletrica.vue'

export default {
  components: {
    Autos
  },

  async asyncData({ $axios }) {
    const [autoEletricaRes, lacresLogRes, cabosLogRes] = await Promise.all([
      $axios.get('autoeletrica'),
      $axios.get('log_equip_auto'),
      $axios.get('log_cabos')
    ])
    return {
      autoEletrica: autoEletricaRes.data,
      lacreLog: lacresLogRes.data,
      caboLog: cabosLogRes.data
    }
  },

  head() {
    return {
      title: 'Auto Elétricas'
    }
  }
}
</script>
