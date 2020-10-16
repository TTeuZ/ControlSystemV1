<template>
  <v-container fluid>
    <autos :autos="autoEletrica" />
  </v-container>
</template>

<script>
import autos from '~/components/autoeletrica/AutoEletrica.vue'

export default {
  components: {
    autos
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
