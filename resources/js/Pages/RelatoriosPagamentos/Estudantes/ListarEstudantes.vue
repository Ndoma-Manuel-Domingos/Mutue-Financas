<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0 text-uppercase">Listar Estudantes</h4>
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
        <div class="row">
          <div class="col-12">
            <form action="">
              <div class="card card-light">
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
                        <label for="" class="text-secondary">Estado</label>
                        <div class="input-group input-group">
                          <select
                            v-model="estado_matricula"
                            class="form-control form-control-sm"
                          >
                            <option value="" selected>TODOS</option>
                            <option value="activo">Activos</option>
                            <option value="inactivo">Inactivos</option>
                            <option value="diplomado">Diplomados</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-light">
                <h5>
                  <span class="float-left"
                    >TOTAL REGISTROS: {{ items.total }}</span
                  >
                </h5>
                <div class="card-tools">
                  <div class="input-group input-group-md" style="width: 450px">
                    <input
                      type="text"
                      v-model="input_search"
                      class="form-control form-control-sm float-right"
                      placeholder="Search"
                    />
                  </div>
                </div>
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
                      <th>Bilheite</th>
                      <th>Faculdade</th>
                      <th>Curso</th>
                      <th>Turno</th>
                      <th>Estado</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="item in items.data" :key="item.codigo">
                      <td>
                        <a
                          :href="`/estudantes/visualizar-perfil/${item.codigo}`"
                          >{{ item.codigo }}</a
                        >
                      </td>
                      <td>
                        <a
                          :href="`/estudantes/visualizar-perfil/${item.codigo}`"
                          >{{ item.nome }}</a
                        >
                      </td>
                      <td>{{ item.bilheite }}</td>
                      <td>{{ item.faculdade }}</td>
                      <td>{{ item.curso }}</td>
                      <td>{{ item.periodo }}</td>
                      <td class="text-uppercase">
                        {{ item.estado_matricula }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="card-footer">
                <Link href="" class="text-secondary">
                  Total Registros: {{ items.total }}
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
import Paginacao from "../../../Shared/Paginacao.vue";

export default {
  props: [
    "items",
    "anoLectivos",
    "faculdades",
    "cursos",
    "turnos",
    "ano_lectivo_activo",
  ],
  components: {
    Link,
    Paginacao,
  },
  data() {
    return {
      params: {},

      anolectivo: this.ano_lectivo_activo.Codigo,
      faculdade: "",
      curso: "",
      turno: "",
      estado_matricula: "",

      input_search: "",
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

    estado_matricula: function (val) {
      this.params.estado_matricula = val;
      this.updateData();
    },

    input_search: function (val) {
      this.params.input_search = val;
      this.updateData();
    },
  },
  methods: {
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/relatorios/listar-estudantes", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },
    imprimirPDF() {
      window.open(
        "/relatorios/listar-estudantes-imprimir-pdf?anolectivo=" +
          this.anolectivo +
          "&faculdade=" +
          this.faculdade +
          "&curso=" +
          this.curso +
          "&turno=" +
          this.turno,
        "_blank"
      );
    },

    imprimirEXCEL() {
      window.open(
        "/relatorios/listar-estudantes-imprimir-excel?anolectivo=" +
          this.anolectivo +
          "&faculdade=" +
          this.faculdade +
          "&curso=" +
          this.curso +
          "&turno=" +
          this.turno,
        "_blank"
      );
    },
  },
};
</script>
