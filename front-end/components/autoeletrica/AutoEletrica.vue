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
        sm="12"
        xs="12"
        cols="12"
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
            @click=";(modalDeInfosResp = false), (selectedType = t)"
          >
            {{ type }}
          </v-btn>
        </div>
        <div id="all-infos-section">
          <span class="all-infos-text-title"> LACRES </span>
          <span class="all-infos-text good-color"> Em Estoque: {{this.totalDeLacresOk}} </span> <!-- eslint-disable-line -->
          <span class="all-infos-text bad-color"> Com Defeito: {{this.totalDeLacresNOk}} </span> <!-- eslint-disable-line -->
          <span class="all-infos-text"> TOTAL: {{ autos[selectedAutoEletrica][1].length }} </span> <!-- eslint-disable-line -->
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
        sm="12"
        xs="12"
        cols="12"
      >
        <span class="type-title">
          INFORMAÇÕES DA AUTO ELÉTRICA
        </span>
        <div class="auto-infos-box">
          <span class="infos-text">
            Endereço:
            {{ autos[selectedAutoEletrica][0].endereco.toUpperCase() }}
          </span>
          <v-btn
            color="orange"
            @click="veriResp(autos[selectedAutoEletrica][3])"
          >
            Responsaveis
          </v-btn>
        </div>
        <span class="type-title">
          {{ typeBtns[selectedType].toUpperCase() }}
        </span>
        <Lacres
          v-if="selectedType === 0"
          :autos="autos"
          :selected-auto-eletrica="selectedAutoEletrica"
          :selected-type="selectedType"
        />
        <CaboAzul
          v-if="selectedType === 1"
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
    <Responsavel
      :autos="autos"
      :selected-auto-eletrica="selectedAutoEletrica"
      :modal-de-infos-resp="modalDeInfosResp"
      :modal-de-criacao-resp="modalDeCriacaoResp"
    />
    <!-- Dialog de infos de responsavel -->
  </v-container>
</template>

<script>
import Lacres from '~/components/autoeletrica/Lacres.vue'
import Responsavel from '~/components/autoeletrica/Responsavel.vue'
import CaboAzul from '~/components/autoeletrica/CaboAzul.vue'
export default {
  components: {
    Lacres,
    Responsavel,
    CaboAzul
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
      modalDeAtualizacao: false,
      // variaveis para responsavel, verificar se a auto eletrica ja tem responsavel ou nao e manda elas com true o false dependendo
      modalDeInfosResp: false,
      modalDeCriacaoResp: false,
      // variaveis para responsavel
      getAuto: [],
      selectId: 0,
      typeBtns: ['LACRES', 'CABO AZUL', 'CABO SENSORES', 'CABO ALIMENTAÇÃO'],
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
      }
    }
  },

  mounted() {
    this.totalLacres()
  },

  methods: {
    veriResp(responsaveis) {
      if (responsaveis.length === 0) {
        this.modalDeCriacaoResp = !this.modalDeCriacaoResp
      } else {
        this.modalDeInfosResp = !this.modalDeInfosResp
      }
    },

    totalLacres() {
      this.modalDeInfosResp = false
      this.modalDeCriacaoResp = false
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

/* .att-auto-btn {
  position: absolute;
  bottom: 80px;
} */

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

.bad-color {
  color: red;
}

.good-color {
  color: green;
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

@media screen and (max-width: 840px) {
  #select-auto {
    flex-flow: column;
  }
  #add-auto {
    top: 120px;
  }
}

@media screen and (max-width: 650px) {
  .auto-title {
    font-size: 30px;
  }
  .type-title {
    font-size: 30px;
    margin-left: 0px;
  }
  .auto-infos-box {
    flex-flow: column;
    margin-left: 0px;
  }
  .infos-text {
    font-size: 16px;
  }
}

@media screen and (max-width: 500px) {
  .modal-title {
    font-size: 20px;
  }
}

@media screen and (max-width: 365px) {
  .modal-title {
    font-size: 16px;
  }
}
</style>
