<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0 text-uppercase">Listar Estudantes por estado</h4>
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
                      <label>Ano Lectivo</label>
                      <div class="input-group">
                        <select
                          class="form-control form-control-sm"
                          v-model="ano_lectivo"
                        >
                          <option value="">Tados</option>
                          <option
                            :value="ano.Codigo"
                            v-for="(ano, index) in ano_lectivos"
                            :key="index"
                          >
                            {{ ano.Designacao }}
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 col-md-3">
                    <div class="form-group">
                      <label>Curso</label>
                      <div class="input-group">
                        <select
                          class="form-control form-control-sm"
                          v-model="curso"
                        >
                          <option value="">Tados</option>
                          <option
                            :value="cur.Codigo"
                            v-for="(cur, index) in cursos"
                            :key="index"
                          >
                            {{ cur.Designacao }}
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 col-md-3">
                    <div class="form-group">
                      <label>Periodo</label>
                      <div class="input-group">
                        <select
                          class="form-control form-control-sm"
                          v-model="turno"
                        >
                          <option value="">Tados</option>
                          <option
                            :value="clas.Codigo"
                            v-for="(clas, index) in turnos"
                            :key="index"
                          >
                            {{ clas.Designacao }}
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 col-md-3">
                    <div class="form-group">
                      <label>Estado</label>
                      <div class="input-group">
                        <select
                          class="form-control form-control-sm"
                          v-model="estado"
                        >
                          <option value="">Tados</option>
                          <option value="activo">Activo</option>
                          <option value="inactivo">Inactivo</option>
                          <option value="diplomado">Diplomado</option>
                          <option value="pendente">Pendente</option>

                          <!-- <option :value="item.codigo" v-for="(item, index) in estados" :key="index">
                              {{ item.designacao }}
                            </option> -->
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
                
            
            <div class="row">
              <div class="col-md-3 col-sm-6 col-12">
                <div
                  class="info-box"
                  style="background-color: #0CF25D; color: #ffffff"
                  title="TOTAL DE ESTUDANTES ACTIVOS"
                >
                  <span class="info-box-icon"
                    ><i class="fas fa-users"></i
                  ></span>
                  <div class="info-box-content">
                    <span class="info-box-text text-uppercase word-break">
                      <br />TOTAL DE ESTUDANTES ACTIVOS</span
                    >
                    <span class="info-box-number h4">{{ resultado.total_activo }}</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              
              <div class="col-md-3 col-sm-6 col-12">
                <div
                  class="info-box"
                  style="background-color: #F23030; color: #ffffff"
                  title="TOTAL DE ESTUDANTES INACTIVOS"
                >
                  <span class="info-box-icon"
                    ><i class="fas fa-users"></i
                  ></span>
                  <div class="info-box-content">
                    <span class="info-box-text text-uppercase word-break">
                      <br />TOTAL DE ESTUDANTES INACTIVOS</span
                    >
                    <span class="info-box-number h4">{{ resultado.total_inactivo }}</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              
              <div class="col-md-3 col-sm-6 col-12">
                <div
                  class="info-box"
                  style="background-color: #0099DD; color: #ffffff"
                  title="TOTAL DE ESTUDANTES DIPLOMADOS"
                >
                  <span class="info-box-icon"
                    ><i class="fas fa-users"></i
                  ></span>
                  <div class="info-box-content">
                    <span class="info-box-text text-uppercase word-break">
                      <br />TOTAL DE ESTUDANTES DIPLOMADOS</span
                    >
                    <span class="info-box-number h4">{{ resultado.total_diplomado }}</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              
              <div class="col-md-3 col-sm-6 col-12">
                <div
                  class="info-box"
                  style="background-color: #336699; color: #ffffff"
                  title="TOTAL DE ESTUDANTES PENDENTES"
                >
                  <span class="info-box-icon"
                    ><i class="fas fa-users"></i
                  ></span>
                  <div class="info-box-content">
                    <span class="info-box-text text-uppercase word-break">
                      <br />TOTAL DE ESTUDANTES PENDENTES</span
                    >
                    <span class="info-box-number h4">{{ resultado.total_pendente }}</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              
            </div>
            
          </div>
        </div>

        <div class="row">
          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-header bg-light">
                <h5>
                  <span class="float-left"
                    >TOTAL REGISTROS: {{ estudantes.total }}</span
                  >
                </h5>
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
                      <th>Tipo Aluno</th>
                      <th>Telefone</th>
                      <th>E-mail</th>
                      <th>Curso</th>
                      <th>Estado</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="item in estudantes.data" :key="item">
                      <td>{{ item.codigo ?? "" }}</td>
                      <td>{{ item.nome ?? "" }}</td>
                      <td>Tipo 1</td>
                      <td>{{ item.contacto ?? "--- --- ---" }}</td>
                      <td>{{ item.email ?? "exemplo@gmail.com" }}</td>
                      <td>{{ item.curso ?? "" }}</td>
                      <template v-if="item.estado == 'activo'">
                        <td class="text-uppercase" style="color: #0CF25D">{{ item.estado ?? "" }}</td>
                      </template>
                      <template v-else-if="item.estado == 'inactivo'">
                        <td class="text-uppercase" style="color: #F23030">{{ item.estado ?? "" }}</td>
                      </template>
                      <template v-else-if="item.estado == 'diplomado'">
                        <td class="text-uppercase" style="color: #0099DD">{{ item.estado ?? "" }}</td>
                      </template>
                      <template v-else-if="item.estado == 'pendente'">
                        <td class="text-uppercase" style="color: #336699">{{ item.estado ?? "" }}</td>
                      </template>
                      
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="card-footer">
                <Link href="" class="text-secondary">
                  TOTAL REGISTROS: {{ estudantes.total }}
                </Link>
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
import Paginacao from "../../Shared/Paginacao.vue";

export default {
  props: ["ano_lectivos", "cursos", "turnos", "estados", "estudantes", "resultado"],
  components: {
    Link,
    Paginacao,
  },
  data() {
    return {
      ano_lectivo: "",
      curso: "",
      turno: "",
      estado: "",

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

    ano_lectivo: function (val) {
      this.params.ano_lectivo = val;
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

    estado: function (val) {
      this.params.estado = val;
      this.updateData();
    },
  },

  methods: {
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/listar-estudantes-por-estados", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },

    imprimirPDF() {
      window.open(
        "/estudantes/inactivos/pdf?ano_inicio=" +
          this.ano_inicio +
          "&ano_final=" +
          this.ano_final +
          "&grau=" +
          this.grau +
          "&faculdade=" +
          this.faculdade +
          "&curso=" +
          this.curso,
        "_blank"
      );
    },

    imprimirEXCEL() {
      window.open(
        "/estudantes/inactivos/excel?ano_inicio=" +
          this.ano_inicio +
          "&ano_final=" +
          this.ano_final +
          "&grau=" +
          this.grau +
          "&faculdade=" +
          this.faculdade +
          "&curso=" +
          this.curso,
        "_blank"
      );
    },
  },
};
</script>
  