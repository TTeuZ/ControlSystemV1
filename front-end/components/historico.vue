<template>
  <v-container fluid>
    <v-row id="ajust-itens" class="ma-0 pa-0" justify="center" aling="center">
      <v-col
        id="selector-side"
        class="ma-0 pa-0"
        xl="4"
        lg="4"
        md="11"
        sm="11"
        xs="11"
        cols="11"
      >
        <div id="itens-list">
          <div
            v-for="(item, i) in equipamentos"
            id="list-item"
            :key="item"
            @click=";(showForm = true), (selectedEquip = i), filterStatus()"
          >
            <span v-if="item[0].done === 1" id="item-title">
              {{ item[0].name.toUpperCase() }}
            </span>
            <v-divider v-if="item[0].done === 1" id="divider" />
          </div>
        </div>
      </v-col>

      <v-col class="ma-0 pa-0" xl="7" lg="7" md="11" sm="11" xs="11" cols="11">
        <v-row id="info-side" class="ma-0 pa-0">
          <div id="title-section">
            <span id="info-title"> INFORMAÇÕES DO EQUIPAMENTO </span>
            <v-divider id="divider" />
          </div>

          <span v-if="showForm" id="equip-title">
            {{ equipamentos[selectedEquip][0].name.toUpperCase() }}
          </span>

          <v-col v-if="showForm" id="buttons" class="ma-o pa-0" cols="12">
            <v-btn
              class="form-btns"
              rounded
              depressed
              color="red"
              @click="excluirEquip()"
            >
              EXCLUIR EQUIPAMENTO
            </v-btn>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  props: {
    equipamentos: {
      type: Array,
      required: true
    },
    status: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      showForm: false,
      selectedEquip: '',
      statusFiltered: []
    }
  },

  methods: {
    excluirEquip() {
      const ok = window.confirm(
        'Você tem certeza que deseja excluir esse equipamento?'
      )

      if (ok) {
        this.$axios
          .delete('equipamento/' + this.equipamentos[this.selectedEquip][0].id)
          .then(() => {
            this.reload()
          })
      }
    },

    filterStatus() {
      return (this.statusFiltered = this.status.filter(
        (equip) =>
          equip.equipamento_id === this.equipamentos[this.selectedEquip][0].id
      ))
    },

    reload() {
      location.reload()
    }
  }
}
</script>

<style scoped>
#ajust-itens {
  margin-top: 40px !important;
}

#selector-side {
  display: flex;
  justify-content: center;
  align-items: center;

  flex-flow: column;
}

#itens-list {
  border: 1px solid black;
  width: 400px;
  height: auto;
  max-height: 680px;

  overflow: auto;

  box-shadow: 0px 0px 14px -2px rgba(0, 0, 0, 0.49);
}

#list-item {
  width: 100%;
  height: auto;

  display: flex;
  flex-flow: column;
  justify-content: center;
  align-items: center;
}

#divider {
  background-color: black;
  width: 100%;
}

#item-title {
  font-family: 'Exo Regular';
  font-size: 16px;

  padding-bottom: 15px !important;
  padding-top: 15px !important;
}

#info-side {
  border: 1px solid black;

  height: 690px;

  display: flex;

  box-shadow: 0px 0px 14px -2px rgba(0, 0, 0, 0.49);
}

#title-section {
  display: flex;
  flex-flow: column;

  width: 100%;
  height: 55px;

  justify-self: start;
}

#info-title {
  font-family: 'Exo Regular';
  font-size: 36px;

  margin-left: 20px;
}

#divider {
  background-color: black;
}

#equip-title {
  font-family: 'Exo Regular';
  font-size: 30px;

  width: 100%;
  height: 40px;

  margin-left: 30px;
}

#buttons {
  display: flex;
  flex-flow: row;
  justify-content: flex-end;
}

.form-btns {
  margin-right: 25px;
}

@media screen and (max-width: 1262px) {
  #itens-list {
    width: 100%;
    max-height: 240px;
  }

  #info-side {
    height: auto;
  }

  #buttons {
    margin: 20px 0;
  }

  #equip-title {
    margin-top: 15px;
    margin-bottom: 15px;
  }
}

@media screen and (max-width: 710px) {
  #info-title {
    font-size: 26px;
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

@media screen and (max-width: 530px) {
  #info-title {
    font-size: 20px;
  }
}

@media screen and (max-width: 430px) {
  #info-title {
    font-size: 15px;
    margin-left: 10px;
  }
}
</style>
