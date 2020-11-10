<template>
  <v-container>
    <v-row class="ma-0 pa-0 esp-infos" justify="space-around" align="center">
      <v-col
        class="cabo-state-box border"
        xl="5"
        lg="5"
        md="5"
        sm="12"
        xs="12"
        cols="12"
        @click="openMod(0)"
      >
        <span class="cabos-card-title"> EM ESTOQUE </span>
        <div class="cabos">
          <span class="cabo-text"> Geral </span>
          <span class="cabo-text"> {{ geralOK }} </span>
        </div>
      </v-col>
      <v-col
        class="cabo-state-box border"
        xl="5"
        lg="5"
        md="5"
        sm="12"
        xs="12"
        cols="12"
        @click="openMod(1)"
      >
        <span class="cabos-card-title"> COM DEFEITOS </span>
        <div class="cabos">
          <span class="cabo-text"> Geral </span>
          <span class="cabo-text"> {{ geralNOK }} </span>
        </div>
      </v-col>
      <v-col id="log" cols="12">
        <Log
          :which-item="'CABO ALIMENTACAO'"
          :modal-log="modalLog"
          :cabos-log="cabosLog"
          :auto-eletrica-id="autoId"
        />
      </v-col>
      <button id="add-item" @click="openAdd()">
        <v-icon class="add-icon"> mdi-plus </v-icon>
      </button>
    </v-row>

    <!-- Dialog de criação de cabos de alimentacao -->
    <v-dialog
      v-if="autosAtt.length != '0'"
      v-model="modalAdd"
      max-width="800px"
      no-click-animation
      persistent
    >
      <v-card class="modal-card">
        <div class="if-div">
          <div class="modal-title-section">
            <span class="modal-title">
              ADICIONE UM CABO DE ALIMENTAÇÃO
            </span>
          </div>
          <div class="form">
            <v-text-field
              v-model="caboData.quantidade"
              :rules="[(v) => !!v || 'Quantidade é necessário']"
              color="cyan darken-2"
              label="Quantidade"
            />
            <v-select
              v-model="caboData.tipo"
              :items="caboTipos"
              color="cyan darken-2"
              label="Tipo"
            />
          </div>
        </div>
        <div class="btn-section">
          <v-btn color="#43A047" text @click="modalAdd = !modalAdd">
            Cancelar
          </v-btn>
          <v-btn color="#43A047" text @click="newCabo()">
            Adicionar
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
    <!-- Dialog de criação de cabos de alimentacao -->
    <!-- Dialog de modificaçoes -->
    <v-dialog
      v-if="autosAtt.length != '0'"
      v-model="modalDeModificacos"
      max-width="800px"
      no-click-animation
      persistent
    >
      <v-card class="modal-card">
        <div class="if-div">
          <div class="modal-title-section">
            <span class="modal-title">
              CABOS DE ALIMENTAÇÃO
            </span>
          </div>
          <div v-for="sel in selects" :key="sel" class="form">
            <v-select
              v-if="sel.type === 'select'"
              v-model="sel.data"
              :items="caboTipos"
              color="cyan darken-2"
              :label="sel.label"
            />
            <v-text-field
              v-if="sel.type == 'text'"
              v-model="sel.data"
              v-maska="sel.mask"
              :rules="sel.rules"
              color="cyan darken-2"
              :label="sel.label"
            />
          </div>
        </div>
        <div class="btn-section">
          <v-btn v-if="this.estado === 0" color="#43A047" text @click="delCabos(selects.tipo.data, selects.quant.data, caboAlimentacaoOK)"> <!-- eslint-disable-line -->
            Excluir
          </v-btn>
          <v-btn v-if="this.estado === 1" color="#43A047" text @click="delCabos(selects.tipo.data, selects.quant.data, caboAlimentacaoNOK)"> <!-- eslint-disable-line -->
            Excluir
          </v-btn>
          <v-btn v-if="this.estado === 0" color="#43A047" text @click="sendTo(selects.tipo.data, selects.quant.data, caboAlimentacaoOK)"> <!-- eslint-disable-line -->
            Para Defeito
          </v-btn>
          <v-btn v-if="this.estado === 1" color="#43A047" text @click="sendTo(selects.tipo.data, selects.quant.data, caboAlimentacaoNOK)"> <!-- eslint-disable-line -->
            Para Estoque
          </v-btn>
          <v-btn color="#43A047" text @click="modalDeModificacos = !modalDeModificacos"> <!-- eslint-disable-line -->
            Cancelar
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
    <!-- Dialog de modificaçoes -->
  </v-container>
</template>

<script>
import Log from '~/components/autoeletrica/Logs.vue'

export default {
  components: { Log },

  props: {
    autos: {
      type: Array,
      required: true
    },
    selectedAutoEletrica: {
      type: String,
      required: true
    },
    autoId: {
      type: String,
      required: true
    },
    cabosLog: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      autosAtt: this.autos,

      modalAdd: false,
      modalDeModificacos: false,
      estado: 0,
      selectId: 0,

      caboAlimentacaoOK: [],
      caboAlimentacaoNOK: [],

      geralOK: 0,

      geralNOK: 0,

      caboTipos: ['geral'],
      caboData: {
        tipo: '',
        quantidade: ''
      },

      selects: {
        quant: {
          type: 'text',
          label: 'Quantidade',
          mask: '###',
          rules: [(v) => !!v || 'Quantidade é necessário'],
          data: ''
        },
        tipo: {
          type: 'select',
          label: 'Tipo',
          data: ''
        }
      }
    }
  },
  methods: {
    openAdd() {
      this.selectId = this.autosAtt[this.selectedAutoEletrica][0].id
      this.modalAdd = !this.modalAdd
    },

    openMod(estado) {
      this.modalDeModificacos = !this.modalDeModificacos
      this.estado = estado
    },

    getQuantidade(auto) {
      this.selectedAutoEletrica = auto
      const cabos = this.autosAtt[this.selectedAutoEletrica][2]
      this.caboAlimentacaoOK = []
      this.caboAlimentacaoNOK = []
      this.geralOK = 0
      this.geralNOK = 0
      if (this.autos !== [] && cabos !== []) { /*eslint-disable-line*/
        cabos.forEach((item) => {
          const cabo = {
            tipo: item.tipo,
            id: item.id
          }
          if (item.nome === 'CABO ALIMENTACAO' && item.situacao === '0') {
            this.caboAlimentacaoOK.push(cabo)
            this.geralOK++
          } else if (item.nome === 'CABO ALIMENTACAO' && item.situacao === '1') { /*eslint-disable-line*/
            this.caboAlimentacaoNOK.push(cabo)
            this.geralNOK++
          }
        })
      }
    },

    newCabo() {
      const newCabo = {
        nome: 'CABO ALIMENTACAO',
        tipo: this.caboData.tipo,
        auto_eletrica_id: this.selectId
      }
      for (let i = 0; i < this.caboData.quantidade; i++) {
        this.$axios.post('cabos', newCabo).catch(({ response }) => {
          this.$toast.error(response.data.mensagem, { duration: 5000 })
        })
      }
      this.$axios.get('autoeletrica').then((res) => {
        this.autosAtt = res.data
        this.modalAdd = false
        this.caboData.tipo = ''
        this.caboData.quantidade = ''
        this.getQuantidade(this.selectedAutoEletrica)
      })
    },

    sendTo(type, quant, array) {
      let existentes = 0
      const ids = []
      array.forEach((item) => {
        if (item.tipo === type) {
          existentes++
          ids.push(item.id)
        }
      })
      if (existentes < quant) {
        window.alert('numero de cabos selecionados maior que os existentes!')
      } else {
        for (let i = 0; i < quant; i++) {
          this.$axios.post('cabo_change/' + ids[i]).catch(({ response }) => {
            this.$toast.error(response.data.mensagem, { duration: 5000 })
          })
        }
        this.$axios.get('autoeletrica').then((res) => {
          this.autosAtt = res.data
          this.modalDeModificacos = false
          this.selects.quant.data = ''
          this.selects.tipo.data = ''
          this.getQuantidade(this.selectedAutoEletrica)
        })
      }
    },

    delCabos(type, quant, array) {
      let existentes = 0
      const ids = []
      array.forEach((item) => {
        if (item.tipo === type) {
          existentes++
          ids.push(item.id)
        }
      })
      if (existentes < quant) {
        window.alert('numero de cabos selecionados maior que os existentes!')
      } else {
        for (let i = 0; i < quant; i++) {
          this.$axios.delete('cabos/' + ids[i]).catch(({ response }) => {
            this.$toast.error(response.data.mensagem, { duration: 5000 })
          })
        }
        this.$axios.get('autoeletrica').then((res) => {
          this.autosAtt = res.data
          this.modalDeModificacos = false
          this.selects.quant.data = ''
          this.selects.tipo.data = ''
          this.getQuantidade(this.selectedAutoEletrica)
        })
      }
    }
  }
}
</script>

<style scoped>
.add-icon {
  color: orange;
}

.border {
  border: 1px solid black;
  box-shadow: 0px 0px 14px -2px rgba(0, 0, 0, 0.49);
}

.esp-infos {
  width: 100%;
  margin-left: 15px !important;
  margin-top: 15px !important;
}

.cabo-state-box {
  height: 100%;
  display: flex;
  justify-content: flex-start;
  align-items: center;
  flex-flow: column;
  cursor: pointer;
}

.cabos-card-title {
  font-family: 'Exo Regular';
  font-size: 21px;
  width: 100%;
  border-bottom: 3px solid black;
  text-align: center;
}

.cabos {
  width: 100%;
  border-bottom: 1px solid black;
  margin-top: 15px;
  display: flex;
  justify-content: space-around;
}

.cabo-text {
  font-family: 'Exo Regular';
  font-size: 19px;
}

.modal-text {
  font-family: 'Exo Regular';
  font-size: 20px;
}

.modal-ajust {
  margin-left: 30px;
}

#add-item {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  border: 1px solid black;
  background-color: black;
  box-shadow: 0px 0px 14px -2px rgba(0, 0, 0, 0.49);
  position: absolute;
  right: 25px;
  bottom: 25px;
}

.modal-title-section {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-flow: column;
}

.if-div {
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

#log {
  width: 100%;
  max-height: 310px;
  overflow: auto;
}

@media screen and (max-width: 961px) {
  .cabo-state-box {
    margin-top: 30px;
  }
  #add-item {
    bottom: 5px;
    right: 5px;
  }
  .esp-infos {
    margin-left: 0px !important;
  }
}

@media screen and (max-width: 650px) {
  .cabos {
    flex-flow: column;
    align-items: center;
  }
  .cabo-text {
    margin-bottom: 8px;
  }
  .modal-title {
    font-size: 24px;
  }
}

@media screen and (max-width: 500px) {
  .cabos-card-title {
    font-size: 18px;
  }
  .cabo-text {
    font-size: 16px;
  }
  .modal-title {
    font-size: 20px;
  }
  .btn-section {
    flex-flow: column;
    padding-right: 0px !important;
  }
}
</style>
