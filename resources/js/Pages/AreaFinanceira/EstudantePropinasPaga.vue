<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0 text-uppercase">Estudantes Com Mensalidades Pagas</h4>
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
          <div class="col-12 col-md-12">
            <div class="card">

              <div class="card-body">
                <div class="row">

                  <div class="col-12 col-md-3">
                    <div class="form-group">
                      <label>Ano Lectivo</label>
                      <div class="input-group">
                        <select
                          v-model="searchAnoLectivo"
                          class="form-control form-control-sm select2"
                        >
                          <option value="">TODOS</option>
                          <option :value="ano.Codigo" v-for="ano in anolectivos" :key="ano.Codigo">{{ ano.Designacao }}</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 col-md-3">
                    <div class="form-group">
                      <label>Mês/Parcela</label>
                      <div class="input-group">
                        <select
                          v-model="searchMes"
                          class="form-control form-control-sm "
                        >
                          <option value="">TODOS</option>
                          <option :value="mes.id" v-for="mes in mesTemps" :key="mes.id">{{ mes.designacao }}</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 col-md-2">
                    <div class="form-group">
                      <label>Faculdade</label>
                      <div class="input-group">
                        <select
                          v-model="searchFaculdade"
                          class="form-control form-control-sm "
                        >
                          <option value="">TODAS</option>
                          <option :value="faculdade.codigo" v-for="faculdade in faculdades" :key="faculdade.codigo"> {{ faculdade.designacao }}</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 col-md-2">
                    <div class="form-group">
                      <label>Curso</label>
                      <div class="input-group">
                        <select
                          v-model="searchCurso"
                          class="form-control form-control-sm "
                        >
                          <option value="">TODOS</option>
                          <option :value="curso.Codigo" v-for="curso in cursos" :key="curso.Codigo">{{ curso.Designacao }}</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 col-md-2">
                    <div class="form-group">
                      <label>Turno</label>
                      <div class="input-group">
                        <select
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
                          
            </div>
          </div>

        </div>

        <div class="row">
          <div class="col-12 col-md-12">
            <div class="card mb-4">
              <div class="card-header bg-light">
                <h6>TOTAL ARRECADADO: {{ formatValor(valor_total_pagamentos) }}</h6>
              </div>
              <div class="card-body">
                <table id="carregarTabelaEstudantes" style="width: 100%" class="table-sm table_estudantes table-bordered table-striped table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl">
                  <thead>
                    <tr>
                      <th title="codigo do pagamentos">Codigo</th>
                      <th title="número da matricula do estudante">Matricula</th>
                      <th title="Nome do estudante">Nome</th>
                      <th title="faculdade que o estudante frequenta">Faculdade</th>
                      <th title="curso que o estudante frequenta">Curso</th>
                      <th title="turno que o estudante frequenta">Turno</th>
                      <th title="serviço que o estudante pagou">Mês/Parcela</th>
                      <th title="valor pago neste mês">Valor</th>
                      <th title="Ano Lectivo do pago neste mês">Ano Lectivo</th>
                      <th width="100px">Ver Perfil</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="factura in facturas.data" :key="factura.matricula">
                      <td>{{ factura.CodigoPagamento }}</td>
                      <td><a :href="route('mf.estudante-visualizar-perfil', factura.matricula)">{{ factura.matricula }}</a></td>
                      <td><a :href="route('mf.estudante-visualizar-perfil', factura.matricula)">{{ factura.aluno }}</a></td>
                      <td>{{ factura.faculdade }}</td>
                      <td>{{ factura.curso }}</td>
                      <td>{{ factura.turno }}</td>
                      <td><a :href="route('mf.estudante-por-mes', factura.IdServico)">{{ factura.servico }}</a></td>
                      <td>{{ formatValor(factura.valorPago) }}</td>
                      <td>{{ factura.anolectivo }}</td>
                      
                      <td>
                        <a :href="route('mf.estudante-visualizar-perfil', factura.matricula)" class="btn-sm btn-primary">
                          <i class="fas fa-user-graduate"></i>
                        </a>
                      </td>
                    </tr>
                    
                  </tbody>
                </table>
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

  import { Link } from '@inertiajs/inertia-vue3';
  import Paginacao from '../../Shared/Paginacao.vue';

  export default{
    props: [ "facturas", "anolectivos", "turnos", "faculdades", "mesTemps", "cursos", "pagamentos", "valor_total_pagamentos", "requests", "ano_lectivo_activo"],
    components: { Link, Paginacao },
    data() {
      return {
        searchAnoLectivo: this.ano_lectivo_activo.Codigo,
        searchMes: "",
        searchFaculdade: "",
        searchCurso: "",
        searchTurno: "",
        valor_total_facturas:"",

        params: {},

      }
    },

    watch: {
      options: function(val) {
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
      searchAnoLectivo: function(val) {
        this.params.searchAnoLectivo = val;
        this.updateData();
      },
      searchMes: function(val) {
        this.params.searchMes = val;
        this.updateData();
      },
      searchFaculdade: function(val) {
        this.params.searchFaculdade = val;
        this.updateData();
      },
      searchCurso: function(val) {
        this.params.searchCurso = val;
        this.updateData();
      },
      searchTurno: function(val) {
        this.params.searchTurno = val;
        this.updateData();
      }
    },

    methods: {
      updateData() {
        this.$Progress.start();
        this.$inertia.get("/estudantes/propina-pagar", this.params, {
          preserveState: true,
          preverseScroll: true,
          onSuccess: () => {
            this.$Progress.finish();
          }
        });
      },

      formatData(data_input){
        let data = new Date(data_input);
        let dataFormatada = ((data.getDate() )) + "-" + ((data.getMonth() + 1)) + "-" + data.getFullYear(); 
 
        return dataFormatada;
      },


      formatValor: function(atual){
        const valorFormatado = Intl.NumberFormat("pt-br", {
            style: "currency",
            currency: "AOA",
        }).format(atual);

        return valorFormatado;
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
