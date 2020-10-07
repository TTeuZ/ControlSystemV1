<template>
  <v-container fluid>
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
          @click="selectedAutoEletrica = i"
          >{{ item[0].nome }}</v-btn
        >
      </div>
      <button id="add-auto" @click="modalDeCriacao = !modalDeCriacao">
        <v-icon class="add-icon"> mdi-plus </v-icon>
      </button>
    </div>

    <v-row
      v-if="autos.length != 0"
      class="ma-0 pa-0 all-infos"
      justify="center"
      align="flex-start"
    >
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
          <span class="infos-text">
            Responsável: {{ autos[selectedAutoEletrica][3][0].nome }}
          </span>
          <span class="infos-text">
            Telefone: {{ autos[selectedAutoEletrica][3][0].telefone }}
          </span>
        </div>
        <span class="type-title">
          {{ typeBtns[selectedType].toUpperCase() }}
        </span>
        <v-row
          class="ma-0 pa-0 esp-infos"
          justify="space-around"
          align="center"
        >
          <!-- Sessoes para cabo -->
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
          <!-- Sessoes para cabo -->
          <button id="add-item" @click="openAdd()">
            <v-icon class="add-icon"> mdi-plus </v-icon>
          </button>
        </v-row>
      </v-col>
    </v-row>

    <!-- Dialog de criação de equipamento -->
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
          <v-btn color="#43A047" text @click="modalDeCriacao = !modalDeCriacao">
            Cancelar
          </v-btn>
          <v-btn color="#43A047" text @click="newAuto()">
            Criar
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
    <!-- Dialog de criação de equipamento -->

    <!-- Dialog de atualizaçao de equipamento -->
    <v-dialog
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
    <!-- Dialog de atualização de equipamento -->

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

    <!-- Dialog para criação de cabos ou lacres -->
    <v-dialog
      v-if="autos.length != 0"
      v-model="modalAdd"
      max-width="800px"
      no-click-animation
      persistent
    >
      <v-card class="modal-card">
        <!-- para lacres -->
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
        <!-- para lacres -->
        <!-- para cabos -->
        <div v-if="selectedType == 1">
          <div class="modal-title-section">
            <span class="modal-title">
              ADICIONE UM CABO
            </span>
          </div>
        </div>
        <!-- para cabos -->
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
    <!-- Dialog para criação de cabos ou lacres -->

    <!-- Dialog para atualizacao de cabos ou lacres -->
    <v-dialog
      v-if="autos.length != 0"
      v-model="modalAttEquip"
      max-width="800px"
      no-click-animation
      persistent
    >
      <v-card class="modal-card">
        <!-- para lacres -->
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
        <!-- para lacres -->
        <!-- para cabos -->
        <div v-if="selectedType == 1">
          <div class="modal-title-section">
            <span class="modal-title">
              ADICIONE UM CABO
            </span>
          </div>
        </div>
        <!-- para cabos -->
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
    <!-- Dialog para atualizacao de cabos ou lacres -->
  </v-container>
</template>

<script>
export default {
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
      getAuto: [],
      selectId: 0,

      typeBtns: ['LACRES', 'CABOS'],
      selectedType: 0,

      modalAdd: false,
      modalInfoEquip: false,
      selectedEquip: 0,
      modalAttEquip: false,

      selectsForLacres: ['CO2 NOVO', 'CO2 ANTIGO'],
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
      },
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

  mounted() {
    this.totalLacres()
  },

  methods: {
    totalLacres() {
      console.log('entrou')
      const self = this
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
    },

    // função para criar uma auto eletrica juntamente com seu responsavel
    newAuto() {
      const autoEletrica = {
        nome: this.autoForm.nome.data,
        endereco: this.autoForm.endereco.data
      } // cria a variavel recevendo os dados do form
      console.log(autoEletrica)
      if ((autoEletrica.nome || autoEletrica.endereco) === '') {
        window.alert('Preencha os dados do auto elétrica') // se estiverem vazios fala para preencher
      } else {
        const responsavel = {
          nome: this.respForm.nome.data,
          telefone: this.respForm.telefone.data
        } // cria o objeto do responsavel
        console.log(responsavel)
        if ((responsavel.nome || responsavel.telefone) === '') {
          window.alert('Preencha os dados do responsavel') // se estiver vazio fala para preencher
        } else {
          this.$axios
            .post('autoeletrica', autoEletrica) // chama o axios criando a autoeletrica
            .then(() => {
              this.$axios.get('autoeletrica').then((response) => {
                this.getAuto = response.data // chama um get para pegar automaticamente todas as autoeletricas
                console.log(this.getAuto)
                this.getId(this.getAuto) // função que pega a ultima auto eletrica criada e pega seu id e aramzena numa variavel (selectId)
                const id = {
                  auto_eletrica_id: this.selectId // cria uma constante com a variavel auto_eletrica_id correta
                }
                console.log(id)
                Object.assign(responsavel, id) // adiciona no objeto responsavel.
                console.log(responsavel)
                this.$axios
                  .post('responsaveis', responsavel) // chama o axios criando o responsavel
                  .then(() => {})
                  .catch(({ response }) => {
                    const { mensagem } = !!response && response.data
                    this.$toast.error(mensagem, { duration: 5000 })
                  })
              })
              window.location.reload()
            })
            .catch(({ response }) => {
              const { mensagem } = !!response && response.data
              this.$toast.error(mensagem, { duration: 5000 })
            })
        }
      }
    },
    getId(item) {
      for (let i = 0; i <= item.length; i++) {
        if (item.length === 0) {
          this.selectId = 0
        }else if (i == item.length) { /*eslint-disable-line*/ 
          const select = item[i - 1]
          this.selectId = select[0].id
        }
      }
    },

    attAuto(id) {
      if (this.modalDeAtualizacao === false) {
        this.modalDeAtualizacao = true
        const autoSelecionada = this.autos[this.selectedAutoEletrica][0] /*eslint-disable-line*/
        const respSelecionado = this.autos[this.selectedAutoEletrica][3][0] /*eslint-disable-line*/
        this.autoForm.nome.data = autoSelecionada.nome
        this.autoForm.endereco.data = autoSelecionada.endereco
        this.respForm.nome.data = respSelecionado.nome
        this.respForm.telefone.data = respSelecionado.telefone
      } else {
        const autoAtualizada = {
          nome: this.autoForm.nome.data,
          endereco: this.autoForm.endereco.data
        }
        const respAtualizada = {
          nome: this.respForm.nome.data,
          telefone: this.respForm.telefone.data
        }
        this.$axios.put('autoeletrica/' + id, autoAtualizada)
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
    },

    openAdd() {
      this.selectId = this.autos[this.selectedAutoEletrica][0].id
      this.modalAdd = !this.modalAdd
    },

    whichAdd() {
      switch (this.selectedType) {
        case 0: {
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
          break
        }
        case 1: {
          console.log('cabos')
          break
        }
      }
    },

    infoEquip(item) {
      this.modalInfoEquip = true
      this.selectedEquip = item
    },

    sendToBad(id) {
      switch (this.selectedType) {
        case 0: {
          this.$axios
            .post('equip_change/' + id)
            .then(() => {
              window.location.reload()
            })
            .catch(({ response }) => {
              const { mensagem } = !!response && response.data
              this.$toast.error(mensagem, { duration: 5000 })
            })
          break
        }
        case 1: {
          console.log('cabos')
          break
        }
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

#add-item:focus {
  outline-style: none;
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
  justify-content: space-between;
  align-items: center;
  flex-flow: row;
  margin: 15px 15px;
}

.infos-text {
  font-family: 'Exo Regular';
  font-size: 20px;
  margin-bottom: 15px;
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
