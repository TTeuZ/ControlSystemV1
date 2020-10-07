<template>
  <v-container fluid>
    <autos :autos="autoEletrica" />
  </v-container>
</template>

<script>
import autos from '~/components/AutoEletrica.vue'

export default {
  components: {
    autos
  },

  asyncData(context) {
    return context.app.$axios
      .get('autoeletrica')
      .then((res) => ({ autoEletrica: res.data }))
  }
}
</script>
