<template>
  <div>
    <v-row class="ma-0 pa-0" justify="center" align="center">
      <span class="login-title"> PLK </span>
      <span class="login-title"> MANUTENÇÃO </span>
    </v-row>
    <v-row class="ma-0 pa-0" justify="center" align="center"> </v-row>
    <input-auth v-model="email" placeholder="Usuario" />
    <input-auth v-model="password" placeholder="Senha" type="password" />
    <v-flex class="text-xs-right"> </v-flex>
    <v-row class="ma-0 pa-0" justify="center" aling="center">
      <v-btn id="login-btn" color="orange" @click="login">
        Entrar
      </v-btn>
    </v-row>
  </div>
</template>

<script>
import InputAuth from '~/components/inputs/InputAuth'

export default {
  components: {
    InputAuth
  },
  data() {
    return {
      email: '',
      password: ''
    }
  },
  mounted() {
    window.addEventListener('keydown', this.loginWithEnter)
  },
  destroyed() {
    window.removeEventListener('keydown', this.loginWithEnter)
  },
  methods: {
    loginWithEnter(e) {
      if (e.code === 'Enter') {
        this.login()
      }
    },

    async login() {
      this.$nuxt.$loading.start()
      await this.$auth
        .loginWith('local', {
          data: {
            email: this.email + '@plk.com.br',
            password: this.password
          }
        })
        .then(() => {
          const user = {
            name: this.email
          }
          this.$auth.setUser(user)
          this.$router.push('/')
        })
        .catch(({ response }) => {
          const { mensagem, errosSecundarios: erros } = response.data
          const listaErros = erros
            ? `\n ${Object.values(erros).join('\n')}`
            : ''
          this.$toast.error(`${mensagem}${listaErros}`, { duration: 5000 })
        })
        .finally(() => {
          this.$nuxt.$loading.finish()
        })
    }
  },
  head() {
    return {
      title: 'Login',
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: 'Página para fazer login no sistema'
        }
      ]
    }
  },
  layout: 'auth',
  middleware: 'guest'
}
</script>

<style scoped>
#login-btn {
  margin-top: 20px;
}

.login-title {
  font-size: 45px;
  font-family: 'Roboto';
}
</style>
