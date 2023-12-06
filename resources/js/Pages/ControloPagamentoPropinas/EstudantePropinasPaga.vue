<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-uppercase">Estudantes Com Mensalidades Pagas</h1>
          </div>
          <div class="col-sm-6">
            <a @click="imprimirPDF" class="btn btn-danger btn-sm float-sm-right mr-2"><i class="fas fa-file-pdf"></i> PDF</a>
            <a @click="imprimirEXCEL" class="btn btn-success btn-sm float-sm-right mr-2"><i class="fas fa-file-excel"></i> Excel</a>
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">

        <div class="row">
          <div class="col-12 col-md-8">
            <div class="card">
              <div class="card-header bg-light">
                <h5>Buscas Avançadas</h5>
              </div>
              <div class="card-body">
                <div class="row">

                  <div class="col-12 col-md-6">
                    <div class="form-group">
                      <label>Ano Lectivo</label>
                      <div class="input-group">
                        <select
                          @change="search"
                          v-model="searchAnoLectivo"
                          class="form-control form-control-sm "
                        >
                          <option :value="ano.Codigo" v-for="ano in anolectivos" :key="ano.Codigo">{{ ano.Designacao }}</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 col-md-6">
                    <div class="form-group">
                      <label>Mês/Parcela</label>
                      <div class="input-group">
                        <select
                          @change="search"
                          v-model="searchMes"
                          class="form-control form-control-sm "
                        >
                          <option value="">TODOS</option>
                          <option :value="mes.id" v-for="mes in mesTemps" :key="mes.id">{{ mes.designacao }}</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 col-md-3">
                    <div class="form-group">
                      <label>Faculdade</label>
                      <div class="input-group">
                        <select
                          @change="search"
                          v-model="searchFaculdade"
                          class="form-control form-control-sm "
                        >
                          <option value="">TODAS</option>
                          <option :value="faculdade.codigo" v-for="faculdade in faculdades" :key="faculdade.codigo"> {{ faculdade.designacao }}</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 col-md-3">
                    <div class="form-group">
                      <label>Curso</label>
                      <div class="input-group">
                        <select
                          @change="search"
                          v-model="searchCurso"
                          class="form-control form-control-sm "
                        >
                          <option value="">TODOS</option>
                          <option :value="curso.Codigo" v-for="curso in cursos" :key="curso.Codigo">{{ curso.Designacao }}</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 col-md-6">
                    <div class="form-group">
                      <label>Turno</label>
                      <div class="input-group">
                        <select
                          @change="search"
                          v-model="searchTurno"
                          class="form-control form-control-sm "
                        >
                          <option value="">TODOS</option>
                          <option :value="turno.Codigo" v-for="turno in turnos" :key="turno.Codigo">{{ turno.Designacao }}</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <!-- <button type="submit" class="btn btn-primary">
                  <i class="fa fa-search"></i>  Listar a busca
                </button> -->
              </div>
            </div>
          </div>

          <div class="col-12 col-md-4">
            <div class="card">
              <div class="card-body">
                <column-chart :colors="['#F07857', '#43A5BE']" :data="charts_turno" :stacked="false"></column-chart>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12 col-md-12">
            <div class="card mb-4">
              <div class="card-header">

              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="carregarTabelaEstudantes" style="width: 100%" class="table-sm table_estudantes table-bordered table-striped table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl">
                    <thead>
                      <tr>
                        <th>Nº Matricula</th>
                        <th>Nome</th>
                        <th>Faculdade</th>
                        <th>Curso</th>
                        <th>Turno</th>
                        <th>Mês/Parcela</th>
                        <th width="100px">Ver Perfil</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="factura in facturas.data" :key="factura.matricula">
                        <td><a :href="`/estudantes/visualizar-perfil/${factura.matricula}`">{{ factura.matricula }}</a></td>
                        <td><a :href="`/estudantes/visualizar-perfil/${factura.matricula}`">{{ factura.aluno }}</a></td>
                        <td>{{ factura.faculdade }}</td>
                        <td>{{ factura.curso }}</td>
                        <td>{{ factura.turno }}</td>
                        <td><a :href="`/pagamentos/propinas-por-mes/${factura.IdServico}`">{{ factura.servico }}</a></td>
                        <td>
                          <a :href="`/estudantes/visualizar-perfil/${factura.matricula}`" class="btn-sm btn-primary">
                            <i class="fas fa-user-graduate"></i>
                          </a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="card-footer">
                <Link href="" class="text-secondary">
                  TOTAL REGISTROS: {{ facturas.total }}
                </Link>
                <Paginacao
                  :links="facturas.links"
                  :prev="facturas.prev_page_url"
                  :next="facturas.next_page_url"
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

  import { Link } from '@inertiajs/inertia-vue3'
  import Paginacao from '../../Shared/Paginacao.vue'

  export default{
    props: [
      // "facturas"
    ],

    components() {
      Link,
      Paginacao
    },

    data() {

      return {
        searchAnoLectivo: 18,
        searchMes: "",
        searchFaculdade: "",
        searchCurso: "",
        searchTurno: "",

        anolectivos: [],
        mesTemps: [],
        turnos: [],
        faculdades: [],
        cursos: [],

        charts_turno: [],

        facturas: {}
      }

    },

    mounted(){
      this.search()
    },

    methods: {

      search (){
        this.$Progress.start();
        axios.get('/estudantes/carregar-propina-pagar', {
          params: {
            searchAnoLectivo: this.searchAnoLectivo,
            searchMes: this.searchMes,
            searchFaculdade: this.searchFaculdade,
            searchCurso: this.searchCurso,
            searchTurno: this.searchTurno
          }
        }).then((response) => {

          this.facturas = response.data.facturas

          this.anolectivos = response.data.anolectivos
          this.faculdades = response.data.faculdades
          this.mesTemps = response.data.mesTemps
          this.cursos = response.data.cursos
          this.turnos = response.data.turnos

          this.charts_turno = response.data.charts_turno

          this.$Progress.finish();

        }).catch((errors) => {
          this.$Progress.fail();
        });
      },

      imprimirPDF () {
        window.open("/estudante/propina-pagar/pdf-imprimir?searchAnoLectivo="+this.searchAnoLectivo+"&searchMes="+this.searchMes+"&searchFaculdade="+this.searchFaculdade+"&searchCurso="+this.searchCurso+"&searchTurno="+this.searchTurno, "_blank");
      },

      imprimirEXCEL () {
        window.open("/estudante/propina-pagar/excel-imprimir/?searchAnoLectivo="+this.searchAnoLectivo+"&searchMes="+this.searchMes+"&searchFaculdade="+this.searchFaculdade+"&searchCurso="+this.searchCurso+"&searchTurno="+this.searchTurno, "_blank");
      }
    }
  }
</script>
