<template>
  <v-container>
    <v-dialog
      v-if="
        autosAtt.length != '0' &&
          autosAtt[selectedAutoEletrica][3].length != '0'
      "
      v-model="modalDeInfosResp"
      max-width="800px"
      no-click-animation
      persistent
    >
      <v-card class="modal-card">
        <div class="modal-title-section">
          <span class="modal-title">
            RESPONSÁVEIS
          </span>
          <div class="select-resp-section">
            <v-btn
              v-for="(resps, r) in autosAtt[selectedAutoEletrica][3]"
              :key="resps"
              depressed
              text-captalize
              class="select-btn"
              color="white"
              :class="{ respSelectColor: respSelect == r ? true : false }"
              @click="respSelect = r"
            >
              {{ resps.nome }}
            </v-btn>
          </div>
          <div class="resp-infos-section">
            <span class="modal-text">Nome: {{ autosAtt[selectedAutoEletrica][3][respSelect].nome }}</span> <!-- eslint-disable-line -->
            <span class="modal-text">Telefone: {{ autosAtt[selectedAutoEletrica][3][respSelect].telefone }}</span> <!-- eslint-disable-line -->
          </div>
        </div>
        <div class="btn-section">
          <v-btn color="#43A047" text @click="modalDeCriacaoResp = !modalDeCriacaoResp"> <!-- eslint-disable-line -->
            Adicionar
          </v-btn>
          <v-btn color="#43A047" text @click="attResp(autosAtt[selectedAutoEletrica][3][respSelect].id)"> <!-- eslint-disable-line -->
            Atualizar
          </v-btn>
          <v-btn color="#43A047" text @click="delResp(autosAtt[selectedAutoEletrica][3][respSelect].id)"> <!-- eslint-disable-line -->
            Excluir
          </v-btn>
          <v-btn color="#43A047" text @click="modalDeInfosResp = !modalDeInfosResp"> <!-- eslint-disable-line -->
            Sair
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
    <!-- Dialog de criação de responsavel -->
    <v-dialog
      v-model="modalDeCriacaoResp"
      max-width="800px"
      no-click-animation
      persistent
    >
      <v-card class="modal-card">
        <div class="modal-title-section">
          <span class="modal-title">
            RESPONSÁVEL
          </span>
          <div v-for="resp in respForm" :key="resp" class="form">
            <v-text-field
              v-model="resp.data"
              v-maska="resp.mask"
              :rules="resp.rules"
              color="cyan darken-2"
              :label="resp.label"
            />
          </div>
        </div>
        <div class="btn-section">
          <v-btn color="#43A047" text @click="modalDeCriacaoResp = !modalDeCriacaoResp"> <!-- eslint-disable-line -->
            Cancelar
          </v-btn>
          <v-btn color="#43A047" text @click="newResp()">
            Criar
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
    <!-- Dialog de criação de responsavel -->
    <!-- Dialog de atualizacao de responsavel -->
    <v-dialog
      v-model="modalDeAttResp"
      max-width="800px"
      no-click-animation
      persistent
    >
      <v-card class="modal-card">
        <div class="modal-title-section">
          <span class="modal-title">
            RESPONSÁVEL
          </span>
          <div v-for="resp in respForm" :key="resp" class="form">
            <v-text-field
              v-model="resp.data"
              v-maska="resp.mask"
              :rules="resp.rules"
              color="cyan darken-2"
              :label="resp.label"
            />
          </div>
        </div>
        <div class="btn-section">
          <v-btn color="#43A047" text @click="modalDeAttResp = !modalDeAttResp"> <!-- eslint-disable-line -->
            Cancelar
          </v-btn>
          <v-btn color="#43A047" text @click="attResp(autosAtt[selectedAutoEletrica][3][respSelect].id)"> <!-- eslint-disable-line -->
            Atualizar
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
    <!-- Dialog de atualizacao de responsavel -->
  </v-container>
</template>

<script>
export default {
  props: {
    autos: {
      type: Array,
      required: true
    },
    selectedAutoEletrica: {
      type: Number,
      required: true
    },
    modalDeInfosResp: {
      type: Number,
      required: true
    },
    modalDeCriacaoResp: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      autosAtt: this.autos,
      modalDeAttResp: false,
      respSelect: 0,
      respForm: {
        nome: {
          name: 'nome',
          label: 'Nome do responsável',
          data: '',
          rules: [(v) => !!v || 'Nome é necessário']
        },
        telefone: {
          name: 'telefone',
          label: 'Telefone',
          data: '',
          rules: [(v) => !!v || 'Telefone é necessário'],
          mask: '(##) #####-####'
        }
      }
    }
  },
  mounted() {
    this.firstResp()
  },
  methods: {
    firstResp() {
      if (
        this.autosAtt[this.selectedAutoEletrica] &&
        this.autosAtt[this.selectedAutoEletrica][3].length === '0'
      ) {
        this.modalDeCriacaoResp = true
      }
    },
    newResp() {
      const responsavel = {
        nome: this.respForm.nome.data,
        telefone: this.respForm.telefone.data,
        auto_eletrica_id: this.autos[this.selectedAutoEletrica][0].id
      } // cria a variavel recevendo os dados do form
      if ((responsavel.nome || responsavel.telefone) === '') {
        window.alert('Preencha os dados do responsável') // se estiverem vazios fala para preencher
      } else {
        this.$axios
          .post('responsaveis', responsavel) // chama o axios criando o responsavel
          .then(() => {
            this.$axios.get('autoeletrica').then((res) => {
              this.autosAtt = res.data
              this.modalDeCriacaoResp = false
              this.respForm.nome.data = ''
              this.respForm.telefone.data = ''
            })
          })
          .catch(({ response }) => {
            this.$toast.error(response.data.mensagem, { duration: 5000 })
          })
      }
    },
    attResp(id) {
      if (this.modalDeAttResp === false) {
        this.modalDeAttResp = true
        const respSelecionado = this.autos[this.selectedAutoEletrica][3][this.respSelect] /*eslint-disable-line*/
        this.respForm.nome.data = respSelecionado.nome
        this.respForm.telefone.data = respSelecionado.telefone
      } else {
        const respAtualizada = {
          nome: this.respForm.nome.data,
          telefone: this.respForm.telefone.data
        }
        this.$axios
          .put('responsaveis/' + id, respAtualizada)
          .then(() => {
            this.$axios.get('autoeletrica').then((res) => {
              this.autosAtt = res.data
              this.modalDeAttResp = false
              this.respForm.nome.data = ''
              this.respForm.telefone.data = ''
            })
          })
          .catch(({ response }) => {
            this.$toast.error(response.data.mensagem, { duration: 5000 })
          })
      }
    },

    delResp(id) {
      const ok = window.confirm(
        'Tem certeza que deseja excluir esse responsável?'
      )
      if (ok) {
        this.$axios
          .delete('responsaveis/' + id)
          .then(() => {
            this.$axios.get('autoeletrica').then((res) => {
              this.autosAtt = res.data
              this.respSelect = 0
            })
          })
          .catch(({ response }) => {
            this.$toast.error(response.data.mensagem, { duration: 5000 })
          })
      }
    }
  }
}
</script>

<style scoped>
.select-resp-section {
  width: 100%;
  padding-left: 35px;
  display: flex;
  justify-content: flex-start;
  align-items: flex-start;
}

.select-btn {
  margin-right: 10px;
}

.resp-infos-section {
  margin: 20px 0px;
  width: 100%;
  display: flex;
  justify-content: space-around;
  align-items: center;
  flex-flow: row;
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

.modal-text {
  font-family: 'Exo Regular';
  font-size: 20px;
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

.respSelectColor {
  background-color: orange !important;
}

@media screen and (max-width: 620px) {
  .modal-title {
    font-size: 25px;
  }
  .select-resp-section {
    flex-flow: column;
    align-items: center;
    padding-left: 0px;
    padding-top: 20px;
  }
  .select-btn {
    font-size: 13px;
  }
  .resp-infos-section {
    flex-flow: column;
  }
  .modal-text {
    margin-top: 5px;
    font-size: 17px;
  }
}

@media screen and (max-width: 400px) {
  .btn-section {
    flex-flow: column;
    padding-right: 0px;
  }
}
</style>
