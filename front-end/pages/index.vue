<template>
  <v-container fluid class="ma-0 pa-0">
    <v-row class="ma-0 pa-0" justify="center" aling="center">
      <v-col class="users-side" xl="4" lg="4" md="11" sm="11" xs="11" cols="11">
        <users :usuarios="Users"
      /></v-col>
      <v-col xl="4" lg="4" md="11" sm="11" xs="11" cols="11">
        <equipNoEsc :equip="equipamento" />
      </v-col>
      <v-col xl="4" lg="4" md="11" sm="11" xs="11" cols="11">
        <buyList />
      </v-col>
    </v-row>
    <!-- <div id="footer">
      <span id="footer-title">PLK - Feito por Paulo Mateus Luza Alves</span>
    </div> -->
  </v-container>
</template>

<script>
import users from '~/components/users'
import equipNoEsc from '~/components/equipNoEsc'
import buyList from '~/components/buyList'

export default {
  components: {
    users,
    equipNoEsc,
    buyList
  },

  async asyncData({ $axios }) {
    const [equipamentoRes, statusRes, userRes] = await Promise.all([
      $axios.get('equipamento'),
      $axios.get('status'),
      $axios.get('usuarios')
    ])
    return {
      equipamento: equipamentoRes.data,
      status: statusRes.data,
      Users: userRes.data.dados
    }
  },

  middleware: 'auth'
}
</script>

<style scoped>
#footer {
  height: 80px;
  width: 100%;

  background-color: orange;

  display: flex;
  flex-flow: column;
  justify-content: center;
  align-items: center;

  position: absolute;
  bottom: 0%;
}

#footer-title {
  font-family: 'Exo Regular';
  font-size: 18px;
}

@media screen and (max-width: 350px) {
  #footer-title {
    font-size: 16px;
  }
}
</style>
