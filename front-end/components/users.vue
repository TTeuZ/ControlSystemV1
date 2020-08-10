<template>
  <v-container id="users-total" fluid>
    <span id="users-title">
      USUÁRIOS
    </span>

    <div id="users-table">
      <div v-for="user in usuarios" id="user" :key="user">
        <div id="info-side">
          <span class="user-info"> Nome: {{ user.name }} </span>
          <span class="user-info"> Email: {{ user.email }} </span>
        </div>
        <div id="delete-side">
          <v-icon id="delete-icon" @click=";(selected = user.id), deleteUser()">
            mdi-delete
          </v-icon>
        </div>
      </div>
    </div>
    <button id="add-user" @click="modal = !modal">
      <v-icon id="add-icon"> mdi-plus </v-icon>
    </button>

    <!-- Dialog de criação de usuario -->
    <v-dialog v-model="modal" max-width="800px" no-click-animation persistent>
      <v-card class="modal-card">
        <div class="modal-title-section">
          <span class="modal-title">
            ADICIONE UM USUÁRIO
          </span>
          <div class="form">
            <v-text-field
              v-model="novoUsuario.nome"
              :rules="[(v) => !!v || 'Nome é necessário']"
              color="cyan darken-2"
              label="Nome"
            />
            <v-text-field
              v-model="novoUsuario.email"
              :rules="[
                (v) => !!v || 'Email é necessário',
                (v) => v.includes('@') || 'Insira um email válido'
              ]"
              color="cyan darken-2"
              label="Email"
            />
            <v-text-field
              v-model="novoUsuario.senha"
              :rules="[(v) => !!v || 'Senha é necessário']"
              color="cyan darken-2"
              label="Senha"
            />
            <v-text-field
              v-model="novoUsuario.senhaConfirmada"
              color="cyan darken-2"
              label="Confirme a senha"
            />
          </div>
        </div>
        <div class="btn-section">
          <v-btn color="#43A047" text @click="close">
            Cancelar
          </v-btn>
          <v-btn color="#43A047" text @click="criaUsuario()">
            Salvar
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
    <!-- Dialog de criação de usuario -->
  </v-container>
</template>

<script>
export default {
  props: {
    usuarios: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      selected: '',
      modal: false,

      novoUsuario: {
        nome: '',
        email: '',
        senha: '',
        senhaConfirmada: ''
      }
    }
  },

  methods: {
    reload() {
      location.reload()
    },

    deleteUser() {
      const ok = window.confirm(
        'Você tem certeza que deseja excluir esse usuario?'
      )

      if (ok) {
        const usuarioDeletado = {
          id: this.selected
        }
        if (usuarioDeletado.id === 1) {
          window.alert('Usuario nao pode ser deletado')
        } else {
          this.$axios.post('deleta', usuarioDeletado).then(() => {
            this.reload()
          })
        }
      }
    },

    criaUsuario() {
      const usuario = {
        name: this.novoUsuario.nome,
        email: this.novoUsuario.email,
        password: this.novoUsuario.senha,
        password_confirmation: this.novoUsuario.senhaConfirmada
      }

      this.$axios
        .post('registro', usuario)
        .then(() => {
          this.close()
          this.reload()
        })
        .catch(({ response }) => {
          const { mensagem } = !!response && response.data
          this.$toast.error(mensagem, { duration: 5000 })
        })
    },

    close() {
      this.modal = false
    }
  }
}
</script>

<style scoped>
#users-total {
  display: flex;
  flex-flow: column;
  justify-content: center;
  align-items: center;
}

/* #ajust-posi {
  display: flex;
  flex-flow: column;
  justify-content: center;
  align-items: center;
} */

#users-title {
  font-family: 'Exo Regular';
  font-size: 20px;
}

#users-table {
  width: 100%;
  max-width: 550px;
  max-height: 600px;
  height: auto;

  border: 1px solid black;

  margin-top: 30px;

  align-self: center;

  overflow: auto;

  box-shadow: 0px 0px 14px -2px rgba(0, 0, 0, 0.49);
}

#user {
  width: 100%;
  height: 60px;

  border-bottom: 1px solid black;

  display: flex;
  flex-flow: row;
  align-items: center;
  justify-content: space-between;
}

#info-side {
  display: flex;
  flex-flow: column;
  justify-content: center;
  align-items: start;

  margin-left: 10px;
}

.user-info {
  font-family: 'Exo Regular';
  font-size: 16px;
}

#delete-icon {
  color: red;
  font-size: 2em;

  margin-right: 8px;
}

#add-user {
  width: 50px;
  height: 50px;
  border-radius: 50%;

  border: 1px solid black;

  margin-top: 25px;

  background-color: black;
}

#add-user:focus {
  outline-style: none;
}

#add-icon {
  color: orange;
}

.modal-title-section {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-flow: column;
}

.modal-title {
  font-family: 'Exo Regular';
  font-size: 30px;

  margin-top: 15px;
}

.form {
  width: 80%;

  padding-top: 20px;
  padding-bottom: 30px;
}

.btn-section {
  padding-bottom: 30px;
  padding-right: 30px;
  display: flex;
  justify-content: flex-end;
  align-items: center;
}

@media screen and (max-width: 1262px) {
  #users-table {
    max-height: 180px;
  }
}

@media screen and (max-width: 400px) {
  .user-info {
    font-size: 14px;
  }

  #delete-icon {
    font-size: 1.5em;
  }
}
</style>
