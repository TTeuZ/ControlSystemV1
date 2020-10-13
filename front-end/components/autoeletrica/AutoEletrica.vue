<template>
  <v-container fluid>
    <!-- Barra superior com as auto eletricas -->
    <div id="select-auto" class="border">
      <div v-for="(item, i) in autos" :key="item">
        <v-btn
          class="select-btn"
          depressed
          rounded
          color="orange"
          :class="{
            selectedColorClass: selectedAutoEletrica == i ? true : false
          }"
          @click=";(selectedAutoEletrica = i), totalLacres()"
          >{{ item[0].nome }}</v-btn
        >
      </div>
      <button id="add-auto" @click="modalDeCriacao = !modalDeCriacao">
        <v-icon class="add-icon"> mdi-plus </v-icon>
      </button>
    </div>
    <!-- Barra superior com as auto eletricas -->
    <v-row
      v-if="autos.length != 0"
      class="ma-0 pa-0 all-infos"
      justify="center"
      align="flex-start"
    >
      <!-- Barra lateral com as infos e opçoes para a auto eletrica -->
      <v-col
        class="ma-o pa-0 select-itens-box border"
        xl="2"
        lg="3"
        md="3"
        sm="11"
        xs="11"
        cols="11"
      >
        <span class="auto-title">
          {{ autos[selectedAutoEletrica][0].nome.toUpperCase() }}
        </span>
        <div v-for="(type, t) in typeBtns" :key="type">
          <v-btn
            depressed
            rounded
            color="orange"
            class="type-btn"
            :class="{ selectedColorClass: selectedType == t ? true : false }"
            @click="selectedType = t"
          >
            {{ type }}
          </v-btn>
        </div>
        <div id="all-infos-section">
          <span class="all-infos-text-title"> LACRES </span>
          <span class="all-infos-text"> Em Estoque: {{this.totalDeLacresOk}} </span> <!-- eslint-disable-line -->
          <span class="all-infos-text"> Com Defeito: {{this.totalDeLacresNOk}}</span> <!-- eslint-disable-line -->
          <span class="all-infos-text">TOTAL: {{ autos[selectedAutoEletrica][1].length }} </span> <!-- eslint-disable-line -->
          <span class="all-infos-text-title"> CABOS </span>
        </div>
        <div class="att-auto-btn">
          <v-btn
            class="type-btn"
            depressed
            rounded
            color="orange"
            @click="attAuto(autos[selectedAutoEletrica][0].id)"
            >Atualizar</v-btn
          >
        </div>
      </v-col>
      <!-- Barra lateral com as infos e opçoes para a auto eletrica -->
      <v-col
        class="ma-o pa-0 infos-box"
        xl="10"
        lg="9"
        md="9"
        sm="11"
        xs="11"
        cols="11"
      >
        <span class="type-title">
          INFORMAÇÕES DA AUTO ELÉTRICA
        </span>
        <div class="auto-infos-box">
          <span class="infos-text">
            Endereço:
            {{ autos[selectedAutoEletrica][0].endereco.toUpperCase() }}
          </span>
          <v-btn color="orange" @click="modalDeInfosResp = !modalDeInfosResp">
            Responsaveis
          </v-btn>
        </div>
        <span class="type-title">
          {{ typeBtns[selectedType].toUpperCase() }}
        </span>

        <Lacres
          :autos="autos"
          :selected-auto-eletrica="selectedAutoEletrica"
          :selected-type="selectedType"
        />
      </v-col>
    </v-row>
    <!-- Dialog de criação de auto-eletrica -->
    <v-dialog
      v-model="modalDeCriacao"
      max-width="800px"
      no-click-animation
      persistent
    >
      <v-card class="modal-card">
        <div class="modal-title-section">
          <span class="modal-title">
            ADICIONE UMA AUTO ELÉTRICA
          </span>
          <div v-for="auto in autoForm" :key="auto" class="form">
            <v-text-field
              v-model="auto.data"
              :rules="auto.rules"
              color="cyan darken-2"
              :label="auto.label"
            />
          </div>
        </div>
        <div class="btn-section">
          <v-btn color="#43A047" text @click="modalDeCriacao = !modalDeCriacao">
            Cancelar
          </v-btn>
          <v-btn color="#43A047" text @click="newAuto()">
            Criar
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
    <!-- Dialog de criação de auto-eletrica -->
    <!-- Dialog de atualizaçao de auto-eletrica -->
    <v-dialog
      v-if="autos.length != 0"
      v-model="modalDeAtualizacao"
      max-width="800px"
      no-click-animation
      persistent
    >
      <v-card class="modal-card">
        <div class="modal-title-section">
          <span class="modal-title">
            ATUALIZE {{ autos[selectedAutoEletrica][0].nome.toUpperCase() }}
          </span>
          <div v-for="auto in autoForm" :key="auto" class="form">
            <v-text-field
              v-model="auto.data"
              :rules="auto.rules"
              color="cyan darken-2"
              :label="auto.label"
            />
          </div>
        </div>
        <div class="btn-section">
          <v-btn
            color="#43A047"
            text
            @click="modalDeAtualizacao = !modalDeAtualizacao"
          >
            Cancelar
          </v-btn>
          <v-btn
            color="#43A047"
            text
            @click="attAuto(autos[selectedAutoEletrica][0].id)"
          >
            Atualizar
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
    <!-- Dialog de atualização de auto-eletrica -->
    <!-- Dialog de infos de responsavel -->
    <v-dialog
      v-if="autos.length != 0 && autos[selectedAutoEletrica][3].length != 0"
      v-model="modalDeInfosResp"
      max-width="800px"
      no-click-animation
      persistent
    >
      <v-card class="modal-card">
        <div class="modal-title-section">
          <div>
            <v-btn v-for="resps in autos[selectedAutoEletrica][3]" :key="resps">
              {{ resps.nome }}
            </v-btn>
          </div>
        </div>
        <div class="btn-section">
          <v-btn color="#43A047" text @click="modalDeInfosResp = !modalDeInfosResp"> <!-- eslint-disable-line -->
            Cancelar
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
    <!-- Dialog de infos de responsavel -->
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
          <v-btn color="#43A047" text @click="newResp()">
            Criar
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
    <!-- Dialog de atualizacao de responsavel -->
  </v-container>
</template>

<script>
import Lacres from '~/components/autoeletrica/Lacres.vue'
export default {
  components: {
    Lacres
  },
  props: {
    autos: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      selectedAutoEletrica: 0,
      totalDeLacresOk: 0,
      totalDeLacresNOk: 0,
      modalDeCriacao: false,
      modalDeCriacaoResp: false,
      modalDeAtualizacao: false,
      modalDeAtualizacaoResp: false,
      modalDeInfosResp: false,
      getAuto: [],
      selectId: 0,
      typeBtns: ['LACRES', 'CABOS'],
      selectedType: 0,
      autoForm: {
        nome: {
          name: 'nome',
          label: 'Nome',
          data: '',
          rules: [(v) => !!v || 'Nome é necessário']
        },
        endereco: {
          name: 'endereco',
          label: 'Endereço',
          data: '',
          rules: [(v) => !!v || 'Endereço é necessário']
        }
      },
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
    this.totalLacres()
  },

  methods: {
    totalLacres() {
      this.totalDeLacresOk = 0
      this.totalDeLacresNOk = 0
      const self = this
      if (this.autos.length !== 0) {
        if (self.autos[self.selectedAutoEletrica][1].length === 0) {
          self.totalDeLacresOk = 0
          self.totalDeLacresNOk = 0
        } else {
          self.autos[self.selectedAutoEletrica][1].forEach((item) => {
            if (item.situacao === 0) {
              self.totalDeLacresOk++
            } else {
              self.totalDeLacresNOk++
            }
          })
        }
      }
    },

    newAuto() {
      const autoEletrica = {
        nome: this.autoForm.nome.data,
        endereco: this.autoForm.endereco.data
      } // cria a variavel recevendo os dados do form
      if ((autoEletrica.nome || autoEletrica.endereco) === '') {
        window.alert('Preencha os dados do auto elétrica') // se estiverem vazios fala para preencher
      } else {
        this.$axios
          .post('autoeletrica', autoEletrica) // chama o axios criando a autoeletrica
          .then(() => {
            window.location.reload()
          })
          .catch(({ response }) => {
            const { mensagem } = !!response && response.data
            this.$toast.error(mensagem, { duration: 5000 })
          })
      }
    },

    // getId(item) {
    //   for (let i = 0; i <= item.length; i++) {
    //     if (item.length === 0) {
    //       this.selectId = 0
    //     }else if (i == item.length) { /*eslint-disable-line*/
    //       const select = item[i - 1]
    //       this.selectId = select[0].id
    //     }
    //   }
    // },

    attAuto(id) {
      if (this.modalDeAtualizacao === false) {
        this.modalDeAtualizacao = true
        const autoSelecionada = this.autos[this.selectedAutoEletrica][0] /*eslint-disable-line*/
        this.autoForm.nome.data = autoSelecionada.nome
        this.autoForm.endereco.data = autoSelecionada.endereco
      } else {
        const autoAtualizada = {
          nome: this.autoForm.nome.data,
          endereco: this.autoForm.endereco.data
        }
        this.$axios
          .put('autoeletrica/' + id, autoAtualizada)
          .then(() => {
            window.location.reload()
          })
          .catch(({ response }) => {
            const { mensagem } = !!response && response.data
            this.$toast.error(mensagem, { duration: 5000 })
          })
      }
    },

    attResp(id) {
      if (this.modalDeAtualizacaoResp === false) {
        this.modalDeAtualizacaoResp = true
        const respSelecionado = this.autos[this.selectedAutoEletrica][3][0] /*eslint-disable-line*/
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
            window.location.reload()
          })
          .catch(({ response }) => {
            const { mensagem } = !!response && response.data
            this.$toast.error(mensagem, { duration: 5000 })
          })
      }
    }
  }
}
</script>

<style scoped>
#add-auto {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  border: 1px solid black;
  background-color: black;
  box-shadow: 0px 0px 14px -2px rgba(0, 0, 0, 0.49);
  position: absolute;
  right: 38px;
}

.add-icon {
  color: orange;
}

.border {
  border: 1px solid black;
  box-shadow: 0px 0px 14px -2px rgba(0, 0, 0, 0.49);
}

#select-auto {
  display: flex;
  align-items: center;
  flex-flow: row;
  overflow: auto;
  min-height: 60px;
}
.select-btn {
  margin: 10px 15px;
}

.type-btn {
  margin: 10px 0px;
  width: 250px !important;
}

.att-auto-btn {
  position: absolute;
  bottom: 80px;
}

#all-infos-section {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  flex-flow: column;
  margin-left: 30px;
}

.all-infos-text-title {
  margin-top: 25px;
  width: 90%;
  font-family: 'Exo Regular';
  font-size: 18px;
  border-top: 1px solid black;
}

.all-infos-text {
  font-family: 'Exo Regular';
  font-size: 16px;
}

.selectedColorClass {
  background-color: black !important;
  color: orange;
}

.select-itens-box {
  margin-top: 15px;
  display: flex;
  justify-content: flex-start;
  align-items: center;
  flex-flow: column;
  height: 700px;
}

.auto-title {
  width: 100%;
  font-family: 'Exo Regular';
  font-size: 45px;
  text-align: center;
  line-height: 60px;
  border-bottom: 1px solid black;
}

.infos-box {
  display: flex;
  justify-content: flex-start;
  align-items: flex-start;
  flex-flow: column;
  margin-top: 15px;
}

.type-title {
  width: 100%;
  font-family: 'Exo Regular';
  font-size: 35px;
  margin-left: 15px;
  border-bottom: 1px solid black;
}

.auto-infos-box {
  width: 100%;
  display: flex;
  justify-content: space-around;
  align-items: center;
  flex-flow: row;
  margin: 15px 15px;
}

.infos-text {
  font-family: 'Exo Regular';
  font-size: 20px;
  margin-bottom: 15px;
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
</style>
