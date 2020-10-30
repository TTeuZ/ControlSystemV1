<template>
  <v-container>
    <v-row class="ma-0 pa-0 esp-infos" justify="space-around" align="center"> <!-- eslint-disable-line -->
      <v-col
        class="equip-state-box border"
        xl="5"
        lg="5"
        md="5"
        sm="12"
        xs="12"
        cols="12"
      >
        <span class="lacres-card-title">
          EM ESTOQUE
        </span>
        <div
          v-for="(equip, e) in autosAtt[selectedAutoEletrica][1]"
          :key="equip"
          style="width: 100%"
          @click="infoEquip(e)"
        >
          <div v-if="equip.situacao == '0'" class="equip">
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
        class="equip-state-box border"
        xl="5"
        lg="5"
        md="5"
        sm="12"
        xs="12"
        cols="12"
      >
        <span class="lacres-card-title">
          COM DEFEITOS
        </span>
        <div
          v-for="(equip, e) in autosAtt[selectedAutoEletrica][1]"
          :key="equip"
          style="width: 100%"
          @click="infoEquip(e)"
        >
          <div v-if="equip.situacao == '1'" class="equip">
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
      v-if="
        autosAtt.length != '0' &&
          autosAtt[selectedAutoEletrica][1].length != '0'
      "
      v-model="modalInfoEquip"
      max-width="800px"
      no-click-animation
      persistent
    >
      <v-card class="modal-card">
        <div class="modal-ajust">
          <div class="first">
            <span class="modal-text">
              Nome: {{ autosAtt[selectedAutoEletrica][1][selectedEquip].nome.toUpperCase() }} <!-- eslint-disable-line -->
            </span>
            <span class="modal-text">
              Tipo: {{ autosAtt[selectedAutoEletrica][1][selectedEquip].tipo.toUpperCase() }}<!-- eslint-disable-line -->
            </span>
          </div>
          <div class="first" v-if="autosAtt[selectedAutoEletrica][1][selectedEquip].situacao == 0"> <!-- eslint-disable-line -->
            <span class="modal-text"> Criado por: {{ autosAtt[selectedAutoEletrica][1][selectedEquip].user_name_created.toUpperCase() }} </span> <!-- eslint-disable-line -->
            <span class="modal-text">
              Data:
              {{
                autosAtt[selectedAutoEletrica][1][selectedEquip].created_at
                  .split(' ')[0]
                  .split('-')
                  .reverse()
                  .join('/') +
                  ' ' +
                  'às' +
                  ' ' +
                  autosAtt[selectedAutoEletrica][1][
                    selectedEquip
                  ].created_at.split(' ')[1]
              }}
            </span>
          </div>
          <div class="first" v-if="autosAtt[selectedAutoEletrica][1][selectedEquip].situacao == '1'"> <!-- eslint-disable-line -->
            <span class="modal-text"> Enviado p/ defeito por: {{ autosAtt[selectedAutoEletrica][1][selectedEquip].user_name_updated.toUpperCase() }} </span> <!-- eslint-disable-line -->
            <span class="modal-text">
              Data:
              {{
                autosAtt[selectedAutoEletrica][1][selectedEquip].updated_at
                  .split(' ')[0]
                  .split('-')
                  .reverse()
                  .join('/') +
                  ' ' +
                  'às' +
                  ' ' +
                  autosAtt[selectedAutoEletrica][1][
                    selectedEquip
                  ].updated_at.split(' ')[1]
              }}
            </span>
          </div>
        </div>
        <div class="btn-section">
          <v-btn
            v-if="
              autosAtt[selectedAutoEletrica][1][selectedEquip].situacao == '0'
            "
            color="#43A047"
            text
            @click="sendTo(autosAtt[selectedAutoEletrica][1][selectedEquip].id)"
          >
            para defeito
          </v-btn>
          <v-btn
            v-if="
              autosAtt[selectedAutoEletrica][1][selectedEquip].situacao == '1'
            "
            color="#43A047"
            text
            @click="sendTo(autosAtt[selectedAutoEletrica][1][selectedEquip].id)"
          >
            para estoque
          </v-btn>
          <v-btn color="#43A047" text @click="attEquip(autosAtt[selectedAutoEletrica][1][selectedEquip].id)"> <!-- eslint-disable-line -->
            atualizar
          </v-btn>
          <v-btn color="#43A047" text @click="delEquip(autosAtt[selectedAutoEletrica][1][selectedEquip].id)"> <!-- eslint-disable-line -->
            excluir
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
      v-if="
        autosAtt.length != '0' &&
          autosAtt[selectedAutoEletrica][1].length != '0'
      "
      v-model="modalAttEquip"
      max-width="800px"
      no-click-animation
      persistent
    >
      <v-card class="modal-card">
        <div v-if="selectedType == '0'" class="if-div">
          <div class="modal-title-section">
            <span class="modal-title">
              EDITE
              {{
                autosAtt[selectedAutoEletrica][1][
                  selectedEquip
                ].nome.toUpperCase()
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
          <v-btn color="#43A047" text @click="attEquip(autosAtt[selectedAutoEletrica][1][selectedEquip].id)"> <!-- eslint-disable-line -->
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
      autosAtt: this.autos,
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
      this.selectId = this.autosAtt[this.selectedAutoEletrica][0].id
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
            this.$axios.get('autoeletrica').then((res) => {
              this.autosAtt = res.data
              this.modalAdd = false
              this.equipForm.nome.data = ''
              this.equipForm.tipo.data = ''
            })
          })
          .catch(({ response }) => {
            this.$toast.error(response.data.mensagem, { duration: 5000 })
          })
      }
    },

    infoEquip(item) {
      this.modalInfoEquip = true
      this.selectedEquip = item
    },

    sendTo(id) {
      if (this.selectedType === 0) {
        this.$axios
          .post('equip_change/' + id)
          .then(() => {
            this.$axios.get('autoeletrica').then((res) => {
              this.autosAtt = res.data
              this.modalInfoEquip = false
            })
          })
          .catch(({ response }) => {
            this.$toast.error(response.data.mensagem, { duration: 5000 })
          })
      }
    },

    attEquip(id) {
      if (this.modalAttEquip === false) {
        const selecionado = this.autosAtt[this.selectedAutoEletrica][1][this.selectedEquip] /*eslint-disable-line*/
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
            this.$axios.get('autoeletrica').then((res) => {
              this.autosAtt = res.data
              this.modalInfoEquip = false
              this.modalAttEquip = false
            })
          })
          .catch(({ response }) => {
            this.$toast.error(response.data.mensagem, { duration: 5000 })
          })
      }
    },

    delEquip(id) {
      const ok = window.confirm(
        'Tem certeza que deseja excluir esse equipamento?'
      )
      if (ok) {
        this.$axios
          .delete('equip_auto/' + id)
          .then(() => {
            this.$axios.get('autoeletrica').then((res) => {
              this.autosAtt = res.data
              this.selectedEquip = 0
              this.modalInfoEquip = false
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

@media screen and (max-width: 961px) {
  .equip-state-box {
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
  .equip {
    flex-flow: column;
    align-items: center;
  }
  .equip-text {
    margin-bottom: 8px;
  }
  .modal-title {
    font-size: 24px;
  }
}

@media screen and (max-width: 500px) {
  .lacres-card-title {
    font-size: 18px;
  }
  .equip-text {
    font-size: 16px;
  }
  .modal-title {
    font-size: 20px;
  }
}
</style>
