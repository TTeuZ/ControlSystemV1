<template>
  <v-container fluid>
    <estoque :itens="Estoque" />
  </v-container>
</template>

<script>
import estoque from '~/components/estoque'

export default {
  components: {
    estoque
  },

  asyncData(context) {
    return context.app.$axios
      .get('estoque')
      .then((res) => ({ Estoque: res.data }))
  }
}
</script>
