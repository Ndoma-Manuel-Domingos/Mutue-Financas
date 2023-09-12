<template>
  <div class="hold-transition sidebar-mini">
    <div class="wrapper">
      <vue-progress-bar></vue-progress-bar>

      <nav class="main-header class= navbar navbar-expand navbar-danger navbar-light" style="background: linear-gradient(to right, #55c9f2, #0c9979);">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
        </ul>

        <div class="mx-auto">
          <div class="input-group">
              <input class="form-control form-control-sm " type="search" @keyup.enter="search_estudante" v-model="search" placeholder="Pesquisar estudante por: Nº de Matricula,Nome,Telefone,BI" style="width: 450px;"/>
              <div class="input-group-append">
                <button class="btn btn-primary" type="button" @click="search_estudante" style="cursor: pointer;" >
                  <i class="fas fa-search"></i>
                </button>
              </div>
          </div>
        </div>


        <div class="ml-auto">

          <ul class="navbar-nav">
            <li class="nav-item">
              <Link
                class="nav-link btn btn-link text-danger"
                href="/logout"
                method="post"
                as="button"
                type="button"
              >
                <i class="fas fa-sign-out-alt"></i>
              </Link>
            </li>
          </ul>
        </div>
      </nav>

      <aside class="main-sidebar sidebar-secondary elevation-4">
        <Link :href="route('mf.dashboard')" class="brand-link" style="border-bottom: 1px solid #028bbf;">

         <img
         src="~admin-lte/dist/img/log1.png" class="img elevation-0" alt="User Image"
         style="width: 200px;height: 100px;"/>
        </Link>


        <div class="sidebar">
          <div class="user-panel mt-3 d-flex">
            <div class="image">
              <img
                src="~admin-lte/dist/img/user2-160x160.jpg"
                class="img-circle elevation-2"
                alt="User Image"
              />
            </div>
            <div class="info">
              <a href="#" class="d-block pb-1">{{ user.nome }}</a>
              <h6>{{ user.type_user }}</h6>
            </div>
        </div>

          <Menu />

        </div>
      </aside>

      <div class="content-wrapper">
        <slot />
      </div>
      <!-- <Footer /> -->
    </div>
  </div>

  <div class="modal fade" id="model_estudante_matricula">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">
            Estudantes Encontrados : {{ estudante.length }}
          </h4>
          <button type="button" class="close" @click="fecharModal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th>Nº Inscrição</th>
                <th>Nome</th>
                <th>Matricula</th>
                <th>Curso</th>
                <th>B.I</th>
                <th>Telefone</th>
                <th>Opções</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="est in estudante" :key="est.Codigo" class="table_estudantes">
                <td>{{ est.inscricao }}</td>
                <td>{{ est.Nome_Completo }}</td>
                <td>{{ est.Codigo }}</td>
                <td>{{ est.Designacao }}</td>
                <td>{{ est.Bilhete_Identidade }}</td>
                <td>{{ est.contacto_principal ?? "--- --- ---" }}</td>
                <td class="text-center">
                  <a :href="route('mf.estudante-visualizar-perfil', est.Codigo)" class="btn-sm btn-info" >
                    <i class="fas fa-user-graduate"></i>
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</template>

<script setup>
  import { computed } from "vue";
  import Menu from "./Partials/Menu.vue";
  import Footer from "./Partials/Footer.vue";
  import { usePage } from "@inertiajs/inertia-vue3";
  import { Link } from "@inertiajs/inertia-vue3";

  const user = computed(() => {
    return usePage().props.value.auth.user;
  });
</script>

<script>
  import { sweetSuccess, sweetError } from "../../components/Alert";

  export default {
    data() {
      return {
        estudante: [],
        search: null,
      };
    },

    methods: {

      function_enter(e) {
        console.dir(e);
      },

      search_estudante(e) {
        e.preventDefault();
        this.$Progress.start();
        $(".table_estudantes").html("");
        axios
          .get("/estudantes/pesquisar-matricula/" + this.search)
          .then((response) => {
            this.estudante = response.data.matricula;
            // this.estudante.push(response.data.matricula);
            if (response.data.matricula === null) {
              sweetError("Ocorreu um errro");
            } else {
              $("#model_estudante_matricula").modal("show");
              sweetSuccess("Estudante Encontrado com sucesso!");
            }
            this.$Progress.finish();
          }).catch((errors) => {
            this.$Progress.fail();
            sweetError("Estudante Não Encontrado!");
          });
      },

      fecharModal() {
        // this.estudante.push([]);
        $("#model_estudante_matricula").modal("hide");
      },
    },
  };
</script>
