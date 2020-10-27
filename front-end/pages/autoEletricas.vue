<template>
  <v-container fluid>
    <Autos :autos="autoEletrica" />
  </v-container>
</template>

<script>
import Autos from '~/components/autoeletrica/AutoEletrica.vue'

export default {
  components: {
    Autos
  },

  asyncData(context) {
    return context.app.$axios
      .get('autoeletrica')
      .then((res) => ({ autoEletrica: res.data }))
  },

  head() {
    return {
      title: 'Auto Elétricas'
    }
  }
}
</script>
