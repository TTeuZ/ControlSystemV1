<template>
  <v-app>
    <div id="nav-bar">
      <div>
        <span id="title"> PLK - MANUTENÇÃO </span>
      </div>
      <v-spacer />
      <div id="btns-section">
        <div v-for="btn in navBtns" :key="btn">
          <v-btn
            depressed
            class="hidden-md-and-down nav-item"
            rounded
            nuxt
            color="orange"
            :to="btn.link"
            active-class="navItemPermanent"
            ><span class="btn-title">{{ btn.label }}</span></v-btn
          >
        </div>
        <v-btn
          depressed
          class="hidden-md-and-down nav-item"
          rounded
          nuxt
          color="orange"
          @click="logout()"
          ><span class="btn-title"> sair </span></v-btn
        >
      </div>
      <v-app-bar-nav-icon
        id="navigation"
        class="hidden-lg-and-up"
        @click="drawer = !drawer"
      >
      </v-app-bar-nav-icon>
    </div>
    <v-navigation-drawer
      id="z-drawer"
      v-model="drawer"
      color="orange"
      app
      flat
      disable-resize-watcher
    >
      <div id="drawer-total">
        <v-list dense>
          <div v-for="drw in drwItens" :key="drw">
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title class="item-drawer">
                  <v-btn
                    depressed
                    color="orange"
                    nuxt
                    :to="drw.drawerLink"
                    class="text-capitalize"
                    light
                    active-class="navItemPermanent"
                  >
                    {{ drw.drawerLabel }}
                  </v-btn></v-list-item-title
                >
              </v-list-item-content>
            </v-list-item>
          </div>
          <v-btn
            depressed
            color="orange"
            class="text-capitalize ajuste"
            light
            @click="logout()"
          >
            sair
          </v-btn>
        </v-list>
      </div>
    </v-navigation-drawer>
    <nuxt></nuxt>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      drawer: false,

      navBtns: [
        {
          label: 'Home',
          link: '/'
        },
        {
          label: 'Estoque',
          link: '/estoque'
        },
        {
          label: 'Auto Eletricas',
          link: '/autoEletricas'
        },
        { label: 'Manutenção', link: '/manutencao' },
        {
          label: 'Histórico',
          link: '/historico'
        }
      ],

      drwItens: [
        {
          drawerLabel: 'Home',
          drawerLink: '/'
        },
        {
          drawerLabel: 'Estoque',
          drawerLink: '/estoque'
        },
        {
          drawerLabel: 'Auto Eletricas',
          drawerLink: '/autoEletricas'
        },
        { drawerLabel: 'Manutenção', drawerLink: '/manutencao' },
        {
          drawerLabel: 'Histórico',
          drawerLink: '/historico'
        }
      ]
    }
  },

  methods: {
    async logout() {
      await this.$auth.logout()
      this.$router.push('/login')
    }
  }
}
</script>

<style scoped>
#nav-bar {
  height: 80px;
  width: 100%;

  background-color: orange;

  display: flex;
  flex-flow: row;
  justify-content: start;
  align-items: center;
}

#title {
  font-size: 50px;
  color: black;
  font-family: 'Exo Regular';

  margin-left: 90px;
}

#btns-section {
  display: flex;
  flex-flow: row;

  margin-right: 80px !important;
}

.nav-item {
  font-family: 'Exo Regular';

  margin-left: 15px;
}

.navItemPermanent {
  background-color: black !important;
  color: orange;
}

#navigation {
  margin-right: 30px;
}

.ajuste {
  margin-left: 16px;
}

@media screen and (max-width: 1400px) {
  #title {
    font-size: 35px;
  }
}

@media screen and (max-width: 740px) {
  #title {
    font-size: 40px;
  }
}

@media screen and (max-width: 600px) {
  #title {
    font-size: 35px;
    margin-left: 20px;
  }
}

@media screen and (max-width: 520px) {
  #title {
    font-size: 30px;
  }
}

@media screen and (max-width: 444px) {
  #title {
    font-size: 20px;
  }
}

@media screen and (max-width: 352px) {
  #title {
    font-size: 16.5px;
  }
}
</style>
