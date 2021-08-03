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
          @click="
            ;(selectedAutoEletrica = i), (autoId = item[0].id), totalInfos()
          "
          >{{ item[0].nome }}</v-btn
        >
      </div>
      <button id="add-auto" @click="modalDeCriacao = !modalDeCriacao">
        <v-icon class="add-icon"> mdi-plus </v-icon>
      </button>
    </div>
    <!-- Barra superior com as auto eletricas -->
    <v-row
      v-if="autos.length != '0'"
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
          <span class="all-infos-text good-color"> Em Estoque: {{totalDeLacresOk}} </span> <!-- eslint-disable-line -->
          <span class="all-infos-text bad-color"> Com Defeito: {{totalDeLacresNOk}} </span> <!-- eslint-disable-line -->
          <span class="all-infos-text"> TOTAL: {{ autos[selectedAutoEletrica][1].length }} </span> <!-- eslint-disable-line -->
          <span class="all-infos-text-title"> CABOS AZUIS </span>
          <div class="all-infos-ajust">
            <span class="all-infos-text good-color"> Em Estoque: </span> <!-- eslint-disable-line -->
            <span class="all-infos-text good-color ml-2"> - Completo: {{ completoOK }} </span> <!-- eslint-disable-line -->
            <span class="all-infos-text good-color ml-2"> - Grande: {{ grandeOK }} </span> <!-- eslint-disable-line -->
            <span class="all-infos-text good-color ml-2"> - Pequeno: {{ pequenoOK }} </span> <!-- eslint-disable-line -->
          </div>
          <div class="all-infos-ajust">
            <span class="all-infos-text bad-color"> Com Defeito: </span> <!-- eslint-disable-line -->
            <span class="all-infos-text bad-color ml-2"> - Completo: {{ completoNOK }} </span> <!-- eslint-disable-line -->
            <span class="all-infos-text bad-color ml-2"> - Grande: {{ grandeNOK }} </span> <!-- eslint-disable-line -->
            <span class="all-infos-text bad-color ml-2"> - Pequeno: {{ pequenoNOK }} </span> <!-- eslint-disable-line -->
          </div>
          <span class="all-infos-text-title"> CABOS DE SENSORES </span>
          <div class="all-infos-ajust">
            <span class="all-infos-text good-color"> Em Estoque: </span> <!-- eslint-disable-line -->
            <span class="all-infos-text good-color ml-2"> - Novo: {{ novoOK }} </span> <!-- eslint-disable-line -->
            <span class="all-infos-text good-color ml-2"> - Antigo - A: {{ antAOK }} </span> <!-- eslint-disable-line -->
            <span class="all-infos-text good-color ml-2"> - Antigo - P: {{ antPOK }} </span> <!-- eslint-disable-line -->
          </div>
          <div class="all-infos-ajust">
            <span class="all-infos-text bad-color"> Com Defeito: </span> <!-- eslint-disable-line -->
            <span class="all-infos-text bad-color ml-2"> - Novo: {{ novoNOK }} </span> <!-- eslint-disable-line -->
            <span class="all-infos-text bad-color ml-2"> - Antigo - A: {{ antANOK }} </span> <!-- eslint-disable-line -->
            <span class="all-infos-text bad-color ml-2"> - Antigo - P: {{ antPNOK }} </span> <!-- eslint-disable-line -->
          </div>
          <span class="all-infos-text-title"> CABOS ALIMENTAÇÃO </span>
          <div class="all-infos-ajust">
            <span class="all-infos-text good-color"> Em Estoque: </span> <!-- eslint-disable-line -->
            <span class="all-infos-text good-color ml-2"> - Geral: {{ geralOK }} </span> <!-- eslint-disable-line -->
          </div>
          <div class="all-infos-ajust">
            <span class="all-infos-text bad-color"> Com Defeito: </span> <!-- eslint-disable-line -->
            <span class="all-infos-text bad-color ml-2"> - Geral: {{ geralNOK }} </span> <!-- eslint-disable-line -->
          </div>
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
          :class="{ hidden: selectedType !== 0 ? true : false }"
          :autos="autos"
          :lacres-log="lacresLog"
          :selected-auto-eletrica="selectedAutoEletrica"
          :auto-id="autoId"
          :selected-type="selectedType"
        />
        <CaboAzul
          ref="caboAzul"
          :class="{ hidden: selectedType !== 1 ? true : false }"
          :autos="autos"
          :cabos-log="cabosLog"
          :selected-auto-eletrica="selectedAutoEletrica"
          :auto-id="autoId"
          :selected-type="selectedType"
        />
        <CaboSensores
          ref="caboSensores"
          :class="{ hidden: selectedType !== 2 ? true : false }"
          :autos="autos"
          :cabos-log="cabosLog"
          :selected-auto-eletrica="selectedAutoEletrica"
          :auto-id="autoId"
          :selected-type="selectedType"
        />
        <CaboAlimentacao
          ref="caboAlimentacao"
          :class="{ hidden: selectedType !== 3 ? true : false }"
          :autos="autos"
          :cabos-log="cabosLog"
          :selected-auto-eletrica="selectedAutoEletrica"
          :auto-id="autoId"
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
      v-if="autos.length != '0'"
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
            @click="delAuto(autos[selectedAutoEletrica][0].id)"
          >
            Excluir
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
import CaboSensores from '~/components/autoeletrica/CaboSensores.vue'
import CaboAlimentacao from '~/components/autoeletrica/CaboAlimentacao.vue'
export default {
  components: {
    Lacres,
    Responsavel,
    CaboAzul,
    CaboSensores,
    CaboAlimentacao
  },
  props: {
    autos: {
      type: Array,
      required: true
    },
    lacresLog: {
      type: Array,
      required: true
    },
    cabosLog: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      selectedAutoEletrica: 0,
      autoId: 1,
      totalDeLacresOk: 0,
      totalDeLacresNOk: 0,
      modalDeCriacao: false,
      modalDeAtualizacao: false,
      // variaveis para responsavel, verificar se a auto eletrica ja tem responsavel ou nao e manda elas com true o false dependendo
      modalDeInfosResp: false,
      modalDeCriacaoResp: false,
      // variaveis para responsavel
      // variaveis para cabos
      completoOK: 0,
      grandeOK: 0,
      pequenoOK: 0,
      completoNOK: 0,
      grandeNOK: 0,
      pequenoNOK: 0,
      novoOK: 0,
      antAOK: 0,
      antPOK: 0,
      novoNOK: 0,
      antANOK: 0,
      antPNOK: 0,
      geralOK: 0,
      geralNOK: 0,
      // variaveis para cabos
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
    this.totalInfos()
  },

  methods: {
    veriResp(responsaveis) {
      if (responsaveis.length === 0) {
        this.modalDeCriacaoResp = !this.modalDeCriacaoResp
      } else {
        this.modalDeInfosResp = !this.modalDeInfosResp
      }
    },

    totalInfos() {
      this.$axios.get('autoeletrica').then((res) => {
        this.autos = res.data
      })
      this.modalDeInfosResp = false
      this.modalDeCriacaoResp = false
      this.totalDeLacresOk = 0
      this.totalDeLacresNOk = 0
      const self = this
      if (this.autos.length !== '0') {
        if (self.autos[self.selectedAutoEletrica][1].length === '0') {
          self.totalDeLacresOk = 0
          self.totalDeLacresNOk = 0
        } else {
          self.autos[self.selectedAutoEletrica][1].forEach((item) => {
            if (item.situacao === '0') {
              self.totalDeLacresOk++
            } else {
              self.totalDeLacresNOk++
            }
          })
        }
        this.$refs.caboAzul.getQuantidade(this.selectedAutoEletrica)
        this.$refs.caboSensores.getQuantidade(this.selectedAutoEletrica)
        this.$refs.caboAlimentacao.getQuantidade(this.selectedAutoEletrica)
        this.completoOK = this.$refs.caboAzul.completoOK
        this.grandeOK = this.$refs.caboAzul.grandeOK
        this.pequenoOK = this.$refs.caboAzul.pequenoOK
        this.completoNOK = this.$refs.caboAzul.completoNOK
        this.grandeNOK = this.$refs.caboAzul.grandeNOK
        this.pequenoNOK = this.$refs.caboAzul.pequenoNOK
        this.novoOK = this.$refs.caboSensores.novoOK
        this.antAOK = this.$refs.caboSensores.antAOK
        this.antPOK = this.$refs.caboSensores.antPOK
        this.novoNOK = this.$refs.caboSensores.novoNOK
        this.antANOK = this.$refs.caboSensores.antANOK
        this.antPNOK = this.$refs.caboSensores.antPNOK
        this.geralOK = this.$refs.caboAlimentacao.geralOK
        this.geralNOK = this.$refs.caboAlimentacao.geralNOK
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
            this.$toast.error(response.data.mensagem, { duration: 5000 })
          })
      }
    },

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
            this.$toast.error(response.data.mensagem, { duration: 5000 })
          })
      }
    },

    delAuto(id) {
      const ok = window.confirm(
        'Tem certeza que deseja excluir essa auto eletrica'
      )
      if (ok) {
        this.$axios
          .delete('autoeletrica/' + id)
          .then(() => {
            window.location.reload()
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
.hidden {
  display: none;
}
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

#all-infos-section {
  width: 80%;
  display: flex;
  justify-content: flex-start;
  align-items: flex-start;
  flex-flow: column;
  margin-left: 30px;
  overflow: auto;
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

.all-infos-ajust {
  display: flex;
  flex-flow: column;
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
    min-height: 85px;
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

@media screen and (max-width: 430px) {
  .btn-section {
    flex-flow: column-reverse;
    padding-right: 0px !important;
  }
}

@media screen and (max-width: 365px) {
  .modal-title {
    font-size: 16px;
  }
}
</style>
