<template>
  <v-container>
    <v-row class="ma-0 pa-0 esp-infos" justify="space-around" align="center">
      <v-col
        v-if="selectedType == 0"
        class="equip-state-box border"
        xl="5"
        lg="5"
        md="5"
        sm="11"
        xs="11"
        cols="11"
      >
        <span class="lacres-card-title">
          EM ESTOQUE
        </span>
        <div
          v-for="(equip, e) in autos[selectedAutoEletrica][1]"
          :key="equip"
          style="width: 100%"
          @click="infoEquip(e)"
        >
          <div v-if="equip.situacao == 0" class="equip">
            <span class="equip-text">
              {{ equip.nome.toUpperCase() }}
            </span>
            <span class="equip-text">
              {{ equip.tipo.toUpperCase() }}
            </span>
          </div>
        </div>
      </v-col>
      <v-col
        v-if="selectedType == 0"
        class="equip-state-box border"
        xl="5"
        lg="5"
        md="5"
        sm="11"
        xs="11"
        cols="11"
      >
        <span class="lacres-card-title">
          COM DEFEITOS
        </span>
        <div
          v-for="(equip, e) in autos[selectedAutoEletrica][1]"
          :key="equip"
          style="width: 100%"
          @click="infoEquip(e)"
        >
          <div v-if="equip.situacao == 1" class="equip">
            <span class="equip-text">
              {{ equip.nome.toUpperCase() }}
            </span>
            <span class="equip-text">
              {{ equip.tipo.toUpperCase() }}
            </span>
          </div>
        </div>
      </v-col>
      <button id="add-item" @click="openAdd()">
        <v-icon class="add-icon"> mdi-plus </v-icon>
      </button>
    </v-row>

    <!-- Dialog com todas as infos e funços para o lacre -->
    <v-dialog
      v-if="autos.length != 0 && autos[selectedAutoEletrica][1].length != 0"
      v-model="modalInfoEquip"
      max-width="800px"
      no-click-animation
      persistent
    >
      <v-card class="modal-card">
        <div class="modal-ajust">
          <div class="first">
            <span class="modal-text">
              Nome: {{ autos[selectedAutoEletrica][1][selectedEquip].nome.toUpperCase() }} <!-- eslint-disable-line -->
            </span>
            <span class="modal-text">
              Tipo: {{ autos[selectedAutoEletrica][1][selectedEquip].tipo.toUpperCase() }}<!-- eslint-disable-line -->
            </span>
          </div>
          <div class="first">
            <span class="modal-text">
              Criado por:
              {{ autos[selectedAutoEletrica][1][selectedEquip].user_name_created.toUpperCase() }} <!-- eslint-disable-line -->
            </span>
            <span class="modal-text">
              Data:
              {{
                autos[selectedAutoEletrica][1][selectedEquip].created_at
                  .split(' ')[0]
                  .split('-')
                  .reverse()
                  .join('/') +
                  ' ' +
                  'às' +
                  ' ' +
                  autos[selectedAutoEletrica][1][
                    selectedEquip
                  ].created_at.split(' ')[1]
              }}
            </span>
          </div>
        </div>
        <div class="btn-section">
          <v-btn
            v-if="autos[selectedAutoEletrica][1][selectedEquip].situacao == 0"
            color="#43A047"
            text
            @click="sendToBad(autos[selectedAutoEletrica][1][selectedEquip].id)"
          >
            para defeito
          </v-btn>
          <v-btn
            v-if="autos[selectedAutoEletrica][1][selectedEquip].situacao == 1"
            color="#43A047"
            text
            @click="sendToBad(autos[selectedAutoEletrica][1][selectedEquip].id)"
          >
            para estoque
          </v-btn>
          <v-btn color="#43A047" text @click="attEquip(autos[selectedAutoEletrica][1][selectedEquip].id)"> <!-- eslint-disable-line -->
            atualizar
          </v-btn>
          <v-btn color="#43A047" text @click="modalInfoEquip = !modalInfoEquip">
            sair
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
    <!-- Dialog com todas as infos e funços para o lacre -->
    <!-- Dialog para criação de lacres -->
    <v-dialog
      v-if="autos.length != 0"
      v-model="modalAdd"
      max-width="800px"
      no-click-animation
      persistent
    >
      <v-card class="modal-card">
        <div v-if="selectedType == 0" class="if-div">
          <div class="modal-title-section">
            <span class="modal-title">
              ADICIONE UM EQUIPAMENTO
            </span>
          </div>
          <div v-for="equip in equipForm" :key="equip" class="form">
            <v-text-field
              v-if="equip.type == 'text'"
              v-model="equip.data"
              :rules="equip.rules"
              color="cyan darken-2"
              :label="equip.label"
            />
            <v-select
              v-if="equip.type == 'select'"
              v-model="equip.data"
              :items="selectsForLacres"
              color="cyan darken-2"
              :label="equip.label"
            />
          </div>
        </div>
        <div class="btn-section">
          <v-btn color="#43A047" text @click="modalAdd = !modalAdd">
            Cancelar
          </v-btn>
          <v-btn color="#43A047" text @click="whichAdd()">
            Criar
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
    <!-- Dialog para criação de lacres -->
    <!-- Dialog para atualizacao de lacres -->
    <v-dialog
      v-if="autos.length != 0 && autos[selectedAutoEletrica][1].length != 0"
      v-model="modalAttEquip"
      max-width="800px"
      no-click-animation
      persistent
    >
      <v-card class="modal-card">
        <div v-if="selectedType == 0" class="if-div">
          <div class="modal-title-section">
            <span class="modal-title">
              EDITE
              {{
                autos[selectedAutoEletrica][1][selectedEquip].nome.toUpperCase()
              }}
            </span>
          </div>
          <div v-for="equip in equipForm" :key="equip" class="form">
            <v-text-field
              v-if="equip.type == 'text'"
              v-model="equip.data"
              :rules="equip.rules"
              color="cyan darken-2"
              :label="equip.label"
            />
            <v-select
              v-if="equip.type == 'select'"
              v-model="equip.data"
              :items="selectsForLacres"
              color="cyan darken-2"
              :label="equip.label"
            />
          </div>
        </div>
        <div class="btn-section">
          <v-btn color="#43A047" text @click="modalAttEquip = !modalAttEquip">
            Cancelar
          </v-btn>
          <v-btn color="#43A047" text @click="attEquip(autos[selectedAutoEletrica][1][selectedEquip].id)"> <!-- eslint-disable-line -->
            Atualizar
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
    <!-- Dialog para atualizacao de lacres -->
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
    selectedType: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      modalAdd: false,

      modalInfoEquip: false,
      selectedEquip: 0,
      modalAttEquip: false,
      selectsForLacres: ['CO2 NOVO', 'CO2 ANTIGO'],
      equipForm: {
        nome: {
          type: 'text',
          name: 'nome',
          label: 'Equipamento',
          data: '',
          rules: [(v) => !!v || 'Equipamento é necessário']
        },
        tipo: {
          type: 'select',
          name: 'tipo',
          label: 'Tipo',
          data: '',
          rules: [(v) => !!v || 'tipo é necessário']
        }
      }
    }
  },
  methods: {
    openAdd() {
      this.selectId = this.autos[this.selectedAutoEletrica][0].id
      this.modalAdd = !this.modalAdd
    },

    whichAdd() {
      if (this.selectedType === 0) {
        const equipamento = {
          nome: this.equipForm.nome.data,
          tipo: this.equipForm.tipo.data,
          auto_eletrica_id: this.selectId
        }
        this.$axios
          .post('equip_auto', equipamento)
          .then(() => {
            window.location.reload()
          })
          .catch(({ response }) => {
            const { mensagem } = !!response && response.data
            this.$toast.error(mensagem, { duration: 5000 })
          })
      }
    },

    infoEquip(item) {
      this.modalInfoEquip = true
      this.selectedEquip = item
    },

    sendToBad(id) {
      if (this.selectedType === 0) {
        this.$axios
          .post('equip_change/' + id)
          .then(() => {
            window.location.reload()
          })
          .catch(({ response }) => {
            const { mensagem } = !!response && response.data
            this.$toast.error(mensagem, { duration: 5000 })
          })
      }
    },

    attEquip(id) {
      if (this.modalAttEquip === false) {
        const selecionado = this.autos[this.selectedAutoEletrica][1][this.selectedEquip] /*eslint-disable-line*/
        this.modalAttEquip = true
        this.equipForm.nome.data = selecionado.nome
        this.equipForm.tipo.data = selecionado.tipo
      } else {
        const equipAtualizado = {
          nome: this.equipForm.nome.data,
          tipo: this.equipForm.tipo.data
        }
        this.$axios
          .put('equip_auto/' + id, equipAtualizado)
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

.equip-state-box {
  height: 100%;
  display: flex;
  justify-content: flex-start;
  align-items: center;
  flex-flow: column;
}

.if-div {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-flow: column;
}

.lacres-card-title {
  font-family: 'Exo Regular';
  font-size: 21px;
  width: 100%;
  border-bottom: 3px solid black;
  text-align: center;
}

.equip {
  width: 100%;
  border-bottom: 1px solid black;
  margin-top: 15px;
  display: flex;
  justify-content: space-around;
  cursor: pointer;
}

.equip-text {
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

.first {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-flow: row;
  padding-top: 20px !important;
  margin-right: 30px;
  margin-bottom: 15px;
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
