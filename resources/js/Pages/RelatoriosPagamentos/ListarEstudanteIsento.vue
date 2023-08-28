<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0 text-uppercase">Listar Estudantes Isentos</h4>
          </div>
          <div class="col-sm-6">
            <a
              @click="imprimirPDF"
              class="btn btn-danger btn-sm float-sm-right mr-2"
              ><i class="fas fa-file-pdf"></i> PDF</a
            >
            <a
              @click="imprimirEXCEL"
              class="btn btn-success btn-sm float-sm-right mr-2"
              ><i class="fas fa-file-excel"></i> Excel</a
            >
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <form action="">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-12 col-md-3">
                  <div class="form-group">
                    <label for="" class="text-secondary">Ano Lectivo</label>
                    <div class="input-group input-group">
                      <select
                        v-model="anolectivo"
                        class="form-control form-control-sm"
                      >
                        <!-- <option value="" selected>TODOS</option> -->
                        <option
                          :value="ano.Codigo"
                          v-for="ano in anoLectivos"
                          :key="ano.Codigo"
                        >
                          {{ ano.Designacao }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-3">
                  <div class="form-group">
                    <label for="" class="text-secondary">Faculdade</label>
                    <div class="input-group input-group">
                      <select
                        v-model="faculdade"
                        class="form-control form-control-sm"
                      >
                        <option value="" selected>TODAS</option>
                        <option
                          :value="faculdade.codigo"
                          v-for="faculdade in faculdades"
                          :key="faculdade.codigo"
                        >
                          {{ faculdade.designacao }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label for="" class="text-secondary">Curso</label>
                    <div class="input-group input-group">
                      <select
                        v-model="curso"
                        class="form-control form-control-sm"
                      >
                        <option value="" selected>TODOS</option>
                        <option
                          :value="curso.Codigo"
                          v-for="curso in cursos"
                          :key="curso.Codigo"
                        >
                          {{ curso.Designacao }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label for="" class="text-secondary">Turno</label>
                    <div class="input-group input-group">
                      <select
                        v-model="turno"
                        class="form-control form-control-sm"
                      >
                        <option value="" selected>TODOS</option>
                        <option
                          :value="turno.Codigo"
                          v-for="turno in turnos"
                          :key="turno.Codigo"
                        >
                          {{ turno.Designacao }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label for="" class="text-secondary"
                      >Tipo de Serviços</label
                    >
                    <div class="input-group input-group">
                      <select
                        v-model="servico"
                        class="form-control form-control-sm"
                      >
                        <option value="" selected>TODOS</option>
                        <option
                          :value="servico.Codigo"
                          v-for="servico in servicos"
                          :key="servico.Codigo"
                        >
                          {{ servico.Descricao }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-light">
                <h3 class="card-title">
                  <strong>LISTAGEM</strong>
                </h3>
                <div class="card-tools"></div>
              </div>

              <div class="card-body">
                  <table
                    id="carregarTabelaEstudantes"
                    style="width: 100%"
                    class="table-sm table_estudantes table-bordered table-striped table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl"
                  >
                    <thead>
                      <tr>
                        <th>Nº Matricula</th>
                        <th>Nome</th>
                        <th>Isento de:</th>
                        <th>Mês</th>
                        <th>Turno</th>
                        <th>Curso</th>
                        <th>Ano Lectivo</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="isencao in items.data" :key="isencao.codigo">
                        <td>{{ isencao.codigoMatricula }}</td>
                        <td>{{ isencao.nomeEstudante }}</td>
                        <td>{{ isencao.servicoIsencao }}</td>
                        <td>{{ isencao.mesIsencao }}</td>
                        <td>{{ isencao.turnoIsencao }}</td>
                        <td>{{ isencao.cursoIsencao }}</td>
                        <td>{{ isencao.Designacao }}</td>
                        <td>
                          <a
                            :href="
                              route(
                                'mf.estudante-visualizar-perfil',
                                isencao.codigoMatricula
                              )
                            "
                            class="btn-sm btn-primary"
                          >
                            <i class="fas fa-user-graduate"></i>
                          </a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
              </div>

              <div class="card-footer">
                <Link href="" class="text-secondary">
                  TOTAL REGISTROS: {{ items.total }}
                </Link>
                <Paginacao
                  :links="items.links"
                  :prev="items.prev_page_url"
                  :next="items.next_page_url"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayouts>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import Paginacao from "../../Shared/Paginacao.vue";

export default {
  props: ["items", "anoLectivos", "faculdades", "cursos", "turnos", "servicos"],
  components: {
    Link,
    Paginacao,
  },
  data() {
    return {
      anolectivo: 18,
      faculdade: "",
      curso: "",
      turno: "",
      servico: "",

      params: {},
    };
  },

  watch: {
    options: function (val) {
      this.params.page = val.page;
      this.params.page_size = val.itemsPerPage;
      if (val.sortBy.length != 0) {
        this.params.sort_by = val.sortBy[0];
        this.params.order_by = val.sortDesc[0] ? "desc" : "asc";
      } else {
        this.params.sort_by = null;
        this.params.order_by = null;
      }
      this.updateData();
    },
    anolectivo: function (val) {
      this.params.anolectivo = val;
      this.updateData();
    },
    faculdade: function (val) {
      this.params.faculdade = val;
      this.updateData();
    },

    curso: function (val) {
      this.params.curso = val;
      this.updateData();
    },

    turno: function (val) {
      this.params.turno = val;
      this.updateData();
    },

    servico: function (val) {
      this.params.servico = val;
      this.updateData();
    },
  },

  methods: {
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/estudante/listar-estudante-isento", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },

    imprimirPDF() {
      window.open(
        "/estudante/listar-estudante-isento/pdf-imprimir?a=" +
          this.anolectivo +
          "&f=" +
          this.faculdade +
          "&c=" +
          this.curso +
          "&t=" +
          this.turno +
          "&s=" +
          this.servico,
        "_blank"
      );
    },

    imprimirEXCEL() {
      window.open(
        "/estudante/listar-estudante-isento/excel-imprimir?a=" +
          this.anolectivo +
          "&f=" +
          this.faculdade +
          "&c=" +
          this.curso +
          "&t=" +
          this.turno +
          "&s=" +
          this.servico,
        "_blank"
      );
    },
  },
};
</script>
