<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-uppercase">Estudantes Finalistas</h1>
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
          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-md-3">
                    <div class="form-group">
                      <label for="" class="text-secondary">Ano Lectivo</label>

                      <div class="input-group">
                        <select v-model="anolectivo" class="form-control">
                          <option
                            :value="item.Codigo"
                            v-for="item in anolectivos"
                            :key="item.Codigo"
                          >
                            {{ item.Designacao }}
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 col-md-3">
                    <div class="form-group">
                      <label for="" class="text-secondary">Curso</label>

                      <div class="input-group">
                        <select v-model="searchCurso" class="form-control">
                          <option value="">TODOS</option>
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

                  <div class="col-12 col-md-3">
                    <div class="form-group">
                      <label>Turno</label>
                      <div class="input-group">
                        <select v-model="searchTurno" class="form-control">
                          <option value="">TODOS</option>
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
                </div>
              </div>
              <div class="card-footer"></div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-header">
                <h5>TOTAL REGISTROS: {{ estudantes.total }}</h5>
              </div>

              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>Matricula</th>
                        <th>N.BI</th>
                        <th>Estudante</th>
                        <th>Curso</th>
                        <th>Periodo</th>
                        <th>Cadeira do Curso</th>
                        <th>Cadeira Concluidas</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="item in estudantes.data" :key="item.matricula">
                        <td><a :href="route('mf.estudante-visualizar-perfil', item.matricula)">{{ item.matricula }}</a></td>
                        <td>{{ item.bilhete }}</td>
                        <td>{{ item.nome }}</td>
                        <td>{{ item.curso }}</td>
                        <td>{{ item.turno }}</td>
                        <td>{{ item.qdtCadeirasCurso }}</td>
                        <td>{{ item.qtdCadeirasConcluidas }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="card-footer">
                <Paginacao
                  :links="estudantes.links"
                  :prev="estudantes.prev_page_url"
                  :next="estudantes.next_page_url"
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
  props: ["estudantes", "cursos", "turnos", "anolectivos", "ano_lectivo_activo"],

  components: { Link, Paginacao },
  data() {
    return {
      searchCurso: "",
      searchTurno: "",
      anolectivo: this.ano_lectivo_activo.Codigo,

      params: {},
    };
  },

  watch: {
    searchCurso: function (val) {
      this.params.searchCurso = val;
      this.updateData();
    },
    searchTurno: function (val) {
      this.params.searchTurno = val;
      this.updateData();
    },
    anolectivo: function (val) {
      this.params.anolectivo = val;
      this.updateData();
    },

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
  },
  methods: {
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/estudante/finalistas", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },
    
    imprimirPDF() {
      window.open("/estudantes/finalistas/pdf?searchCurso="+this.searchCurso+"&searchTurno="+this.searchTurno+"&anolectivo="+this.anolectivo, "_blank");
    },
  
    imprimirEXCEL() {
      window.open("/estudantes/finalistas/excel?searchCurso="+this.searchCurso+"&searchTurno="+this.searchTurno+"&anolectivo="+this.anolectivo, "_blank");
    }
    
  },
};
</script>
