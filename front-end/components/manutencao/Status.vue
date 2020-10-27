<template>
  <v-container>
    <v-row class="ma-0 pa-0" justify="center" align="center">
      <v-col
        v-if="showForm && selectedEquip !== 100"
        class="ma-0 pa-0 table-section"
        xl="6"
        lg="6"
        md="6"
        sm="12"
        xs="12"
        cols="12"
      >
        <div class="table-total">
          <span class="table-title">STATUS ATUAL</span>
          <div class="table">
            <div v-for="status in statusFiltered" :key="status">
              <div v-if="status.flag === '0'" class="status">
                <span class="status-text">
                  {{ status.info }}
                </span>
                <div>
                  <span class="status-text-time">
                    {{
                      status.created_at
                        .split(' ')[0]
                        .split('-')
                        .reverse()
                        .join('/') +
                        ' às ' +
                        status.created_at.split(' ')[1]
                    }}
                  </span>
                  <v-icon
                    class="icons"
                    @mouseover="user_created = true"
                    @mouseleave="user_created = false"
                  >
                    mdi-comment-question-outline
                  </v-icon>

                  <div v-if="user_created" class="hover-info">
                    <span class="hover-text">
                      Criado por: {{ status.user_name_created }}
                    </span>
                  </div>
                </div>
                <div class="change-flag">
                  <v-icon
                    class="change-icon"
                    @click=";(flagId = status.id), feito()"
                  >
                    mdi-arrow-right-bold
                  </v-icon>
                </div>
              </div>
            </div>
          </div>
        </div>
      </v-col>
      <v-col
        v-if="showForm && selectedEquip !== 100"
        class="ma-0 pa-0 table-section"
        xl="6"
        lg="6"
        md="6"
        sm="12"
        xs="12"
        cols="12"
      >
        <div class="table-total">
          <span class="table-title">FEITO</span>
          <div class="table">
            <div v-for="status in statusFiltered" :key="status">
              <div v-if="status.flag === '1'" class="status">
                <div class="change-flag">
                  <v-icon
                    class="change-icon"
                    @click=";(flagId = status.id), voltarStatus()"
                  >
                    mdi-arrow-left-bold
                  </v-icon>
                </div>
                <div>
                  <span class="status-text-time">
                    {{
                      status.updated_at
                        .split(' ')[0]
                        .split('-')
                        .reverse()
                        .join('/') +
                        ' às ' +
                        status.updated_at.split(' ')[1]
                    }}
                  </span>

                  <v-icon
                    class="icons"
                    @mouseover="user_updated = true"
                    @mouseleave="user_updated = false"
                  >
                    mdi-comment-question-outline
                  </v-icon>

                  <div v-if="user_updated" class="hover-info">
                    <span class="hover-text">
                      Finalizado por: {{ status.user_name_updated }}
                    </span>
                  </div>
                </div>
                <span class="status-text">
                  {{ status.info }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </v-col>
    </v-row>
    <!-- Dialog de criação de status -->
    <v-dialog
      v-model="statusModal"
      max-width="800px"
      no-click-animation
      persistent
    >
      <v-card class="modal-card">
        <div class="modal-title-section">
          <span class="modal-title">
            ADICIONE O STATUS
          </span>
          <div class="form">
            <v-text-field
              v-model="infoStatus"
              :rules="[(v) => !!v || 'Campo Obrigatório']"
              color="cyan darken-2"
              label="Status"
            />
          </div>
        </div>
        <div class="btn-section">
          <v-btn color="#43A047" text @click="statusModal = false">
            Cancelar
          </v-btn>
          <v-btn color="#43A047" text @click="criaStatus()">
            Salvar
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
    <!-- Dialog de criação de status -->
  </v-container>
</template>

<script>
export default {
  props: {
    showForm: {
      type: Boolean,
      required: true
    },
    selectedEquip: {
      type: Number,
      required: true
    },
    status: {
      type: Array,
      required: true
    },
    filteredEquip: {
      type: Array,
      required: true
    },
    statusModal: {
      type: Boolean,
      required: true
    }
  },

  data() {
    return {
      statusFiltered: [],
      infoStatus: '',
      flagId: '',

      user_created: false,
      user_updated: false
    }
  },

  methods: {
    filterStatus(equipId) {
      return (this.statusFiltered = this.status.filter(
        (equip) =>
          equip.equipamento_id === this.filteredEquip[equipId][0].id.toString()
      ))
    },

    feito() {
      const attFlag = {
        flag: 1
      }
      this.$axios.put('status/' + this.flagId, attFlag).then(() => {
        location.reload()
      })
    },

    voltarStatus() {
      const attFlag = {
        flag: 0
      }
      this.$axios.put('status/' + this.flagId, attFlag).then(() => {
        location.reload()
      })
    },

    criaStatus() {
      const infoStatus = {
        info: this.infoStatus,
        equipamento_id: this.filteredEquip[this.selectedEquip][0].id
      }
      this.$axios
        .$post('status', infoStatus)
        .then(() => {
          location.reload()
        })
        .catch(({ response }) => {
          this.$toast.error(response.data.mensagem, { duration: 5000 })
        })
    }
  }
}
</script>

<style scoped>
.table-section {
  display: flex;
  justify-content: center;
  align-items: center;
}

.table-total {
  display: flex;
  flex-flow: column;
  justify-content: center;
  align-items: center;
  width: 100%;
}

.table-title {
  font-family: 'Exo Regular';
  font-size: 23px;
}

.table {
  border: 1px solid black;
  width: 95%;
  height: 380px;
  overflow: auto;
}

#buttons {
  display: flex;
  flex-flow: row;
  justify-content: flex-end;
}

.form-btns {
  margin-right: 25px;
}

.status {
  width: 100%;
  height: auto;
  min-height: 40px;
  border-bottom: 1px solid black;
  display: flex;
  flex-flow: row;
  justify-content: space-between;
  align-items: center;
}

.status-text {
  padding: 0 10px;
  font-size: 18px;
  font-family: 'Exo Regular';
}

.status-text-time {
  font-size: 14px;
  font-family: 'Exo Regular';
}

.change-flag {
  height: 100%;
  width: 25px;
}

.change-icon {
  color: green;
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

.icons {
  color: black;
  font-size: 1em;
}

.hover-info {
  position: absolute;
  height: 20px;
  width: auto;
  background-color: white;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-left: 70px;
}

.hover-text {
  font-family: 'Exo Regular';
  font-size: 12px;
}

@media screen and (max-width: 1262px) {
  #buttons {
    margin: 20px 0;
  }
}

@media screen and (max-width: 962px) {
  .table-title {
    margin-top: 15px;
  }
}

@media screen and (max-width: 564px) {
  #buttons {
    flex-flow: column;
    justify-content: center;
    align-items: center;
  }
  .form-btns {
    margin-right: 0;
    margin-top: 20px;
    width: 200px;
  }
}

@media screen and (max-width: 430px) {
  .status {
    flex-flow: column;
  }
  .modal-title {
    font-size: 20px;
  }
}
</style>
