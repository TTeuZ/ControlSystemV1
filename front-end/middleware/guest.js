/* eslint-disable */
export default function ({ store, redirect }) {
    // se o usuario estiver logado redireciona para o sistema
    if (store.state.auth.loggedIn === true) return redirect('/');
  }
  