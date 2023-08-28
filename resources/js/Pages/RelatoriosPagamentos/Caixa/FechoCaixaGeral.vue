<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0 text-uppercase">Fecho de Caixa Geral</h4>
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
          <div class="card card-light">
            <div class="card-body">
              <div class="row">
                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label for="" class="text-secondary">Operador</label>
                    <div class="input-group input-group">
                      <select
                        v-model="operador"
                        class="form-control form-control-sm"
                      >
                        <option value="">TODOS</option>
                        <option
                          :value="utilizador.utilizadores.codigo_importado"
                          v-for="utilizador in utilizadores"
                          :key="utilizador.Codigo"
                        >
                          {{ utilizador.utilizadores.nome }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label for="" class="text-secondary">Tipo Serviços</label>
                    <div class="input-group input-group">
                      <select
                        v-model="codigo_produto"
                        class="form-control form-control-sm"
                      >
                        <option value="">TODOS</option>
                        <option
                          :value="servico.Codigo"
                          v-for="servico in tipoServicos"
                          :key="servico.Codigo"
                        >
                          {{ servico.Descricao }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label for="" class="text-secondary">Grau</label>
                    <div class="input-group input-group">
                      <select
                        v-model="grau_academico"
                        class="form-control form-control-sm"
                      >
                        <option value="">TODOS</option>
                        <option
                          :value="ano.id"
                          v-for="ano in grausAcademicos"
                          :key="ano.id"
                        >
                          {{ ano.designacao }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>


                <!-- <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label for="" class="text-secondary">Estado</label>
                    <div class="input-group input-group">
                      <select
                        v-model="estado_pagamento"
                        class="form-control form-control-sm"
                      >
                        <option value="">TODOS</option>
                        <option value="0">Pendente</option>
                        <option value="1">Validado</option>
                        <option value="2">Rejeitado</option>
                      </select>
                    </div>
                  </div>
                </div> -->

                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label for="" class="text-secondary"
                      >Forma de Pagamentos</label
                    >
                    <div class="input-group input-group">
                      <select
                        v-model="forma_pagamento"
                        class="form-control form-control-sm"
                      >
                        <option value="">TODAS</option>
                        <option
                          :value="forma.descricao"
                          v-for="forma in formaPagamentos"
                          :key="forma.Codigo"
                        >
                          {{ forma.descricao }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label>Data Inicio:</label>
                    <input
                      type="date"
                      @keyup.enter="search"
                      v-model="data_inicio"
                      class="form-control form-control-sm"
                    />
                  </div>
                </div>

                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label>Data Final:</label>
                    <input
                      type="date"
                      @keyup.enter="search"
                      class="form-control form-control-sm"
                      v-model="data_final"
                    />
                  </div>
                </div>

              </div>
            </div>
          </div>
        </form>

        <div class="row">
          <div class="col-12">
            <div class="card">
              
              <div class="card-header">
                  <h5>TOTAL ARRECADADO: {{ formatValor(total) }}</h5>
              </div>
            
              <div class="card-body">
                  <table
                    id="carregarTabelaEstudantes"
                    style="width: 100%"
                    class="table-sm table_estudantes table-bordered table-striped table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl"
                  >
                    <thead>
                      <tr>
                        <th>Operador</th>
                        <th>Data</th>
                        <th>Valor:</th>
                        <th>Recibo</th>
                        <th>Forma Pagamento</th>
                        <th>Estado</th>
                        <th width="100px">Acções</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="pagamento in items.data"
                        :key="pagamento.Codigo"
                      >
                        <td>{{ pagamento.operador_novos ? pagamento.operador_novos.nome : "Estudante" }}</td>
                        <td>{{ formatData(pagamento.DataRegisto) }}</td>
                        <td>{{ formatValor(pagamento.valor_depositado) }}</td>
                        <td>{{ pagamento.Codigo }}</td>
                        <td>{{ pagamento.forma_pagamento }}</td>
                        <td v-if="pagamento.estado == 1" class="text-success">
                          Validado
                        </td>
                        <td v-if="pagamento.estado == 0" class="text-info">
                          Pendente
                        </td>
                        <td v-if="pagamento.estado == 2" class="text-warning">
                          Rejeitado
                        </td>
                        <td>
                          <a
                            class="btn-sm btn-success mr-1"
                            @click="visualizar_detalhes(pagamento)"
                          >
                            <i
                              class="fas fa-eye"
                              title="VISUALIZAR DETALHES DO PAGAMENTO"
                            >
                            </i>
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

    <div
      class="modal fade"
      id="modalDetalhesPagamento"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-uppercase" id="exampleModalLabel">
              Datalhes do Pagamento
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <div class="table-responsive">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th colspan="4" class="bg-info">Dados do Estudante</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th>Codigo da Matricula</th>
                    <td>{{ dados_estudante.codigo }}</td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <th>Nome do Estudante</th>
                    <td>{{ dados_estudante.nome }}</td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <th>Curso</th>
                    <td>{{ dados_estudante.curso }}</td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <th>Campus</th>
                    <td>{{ dados_estudante.campus }}</td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <th>Contacto</th>
                    <td>{{ dados_estudante.contacto }}</td>
                    <td></td>
                    <td></td>
                  </tr>

                  <tr>
                    <th colspan="4" class="bg-info">Datalhes do Pagamentos</th>
                  </tr>

                  <tr>
                    <th>Recibo</th>
                    <th>Factura Referente</th>
                    <th>Operação</th>
                    <th>Operação2</th>
                  </tr>

                  <tr>
                    <td>{{ pagamento_detalhes.Codigo }}</td>
                    <td>{{ pagamento_detalhes.codigo_factura }}</td>
                    <td>{{ pagamento_detalhes.N_Operacao_Bancaria ?? " ---- " }}</td>
                    <td>{{ pagamento_detalhes.N_Operacao_Bancaria2 ?? " ---- " }}</td>
                  </tr>

                  <tr>
                    <th>Valor</th>
                    <th>Data Banco</th>
                    <th>Data Registro</th>
                    <th>Data Validação</th>
                  </tr>

                  <tr>
                    <td>{{ formatValor(pagamento_detalhes.valor_depositado) }}</td>
                    <td>{{ formatData(pagamento_detalhes.DataBanco) }}</td>
                    <td>{{ formatData(pagamento_detalhes.DataRegisto) }}</td>
                    <td>{{ formatData(pagamento_detalhes.updated_at) }}</td>
                  </tr>
                  
                  
                 <tr>
                    <th colspan="4" class="bg-info">Items do Pagamento</th>
                  </tr>
                  
                  <tr>
                    <th>#</th>
                    <th>Serviço</th>
                    <th>Quantidade</th>
                    <th>Valor</th>
                  </tr>
                  
                  <tr v-for="item2 in pagamento_detalhes.detalhes" :key="item2">
                    <td>{{ item2.codigo ?? '' }}</td>
                    <td>{{ item2.servico.Descricao ?? '' }}</td>
                    <td>{{ item2.Quantidade ?? '' }}</td>
                    <td>{{ formatValor(item2.Valor_Total ?? 0) }}</td>
                  </tr> 

                  <tr>
                    <th colspan="2" class="bg-info">Comprovativo 01</th>
                    <th colspan="2" class="bg-info">Comprovativo 02</th>
                  </tr>
                  

                  <tr>
                    <td colspan="2">
                      <a
                        target="_blink"
                        :href="
                          'https://mutue.ao/storage/documentos/' +
                          pagamento_detalhes.nome_documento
                        "
                      >
                        {{ pagamento_detalhes.nome_documento }}</a
                      >
                    </td>
                    <td colspan="2">
                      <a
                        target="_blink"
                        :href="
                          'https://mutue.ao/storage/documentos/' +
                          pagamento_detalhes.nome_documento2
                        "
                      >
                        {{ pagamento_detalhes.nome_documento2 }}</a
                      >
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer"></div>
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
    "grausAcademicos",
    "mesTemps",
    "formaPagamentos",
    "estadoPagamento",
    "tipoServicos",
    "utilizadores",
    "requests",
    "total"
  ],
  components: {
    Link,
    Paginacao,
  },
  data() {
    return {
      estado_pagamento: this.requests.estado_pagamento ?? "",
      operador: this.requests.operador ?? "",
      codigo_produto: this.requests.codigo_produto ?? "",
      mes_temp: this.requests.codigo_produto ?? "",
      anolectivo: this.requests.anolectivo ?? 21,
      grau_academico: this.requests.grau_academico ?? "" ,

      data_inicio: this.requests.data_inicio ?? new Date().toISOString().substr(0, 10),
      data_final: this.requests.data_final ?? "",

      forma_pagamento: this.requests.forma_pagamento ?? "",
      params: {},
      nomeUtilizador: "",
      
      pagamento_detalhes: [],

      dados_estudante: {},
      dados_pagamentos: {},
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
    estado_pagamento: function (val) {
      this.params.estado_pagamento = val;
      this.updateData();
    },
    operador: function (val) {
      this.params.operador = val;
      this.updateData();
    },
    codigo_produto: function (val) {
      this.params.codigo_produto = val;
      this.updateData();
    },

    mes_temp: function (val) {
      this.params.mes_temp = val;
      this.updateData();
    },

    grau_academico: function (val) {
      this.params.grau_academico = val;
      this.updateData();
    },

    data_inicio: function (val) {
      this.params.data_inicio = val;
      this.updateData();
    },

    data_final: function (val) {
      this.params.data_final = val;
      this.updateData();
    },

    data_inicio_validacao: function (val) {
      this.params.data_inicio_validacao = val;
      this.updateData();
    },

    data_final_validacao: function (val) {
      this.params.data_final_validacao = val;
      this.updateData();
    },

    forma_pagamento: function (val) {
      this.params.forma_pagamento = val;
      this.updateData();
    },
  },
  methods: {
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/fecho/caixa-geral", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },

    visualizar_detalhes(item) {
      this.$Progress.start();
      // $(".table_estudantes").html("");
      axios
        .get("/visualizar-detalhes-pagamento/" + item.Codigo)
        .then((response) => {
          this.dados_estudante = response.data.dados,
          this.dados_pagamentos = response.data.detalhes,
          
          this.pagamento_detalhes = response.data.pagamento_detalhes;
          
          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
          // sweetError("Estudante Não Encontrado!");
        });
      $("#modalDetalhesPagamento").modal("show");
    },

    formatValor(atual) {
      const valorFormatado = Intl.NumberFormat("pt-br", {
        style: "currency",
        currency: "AOA",
      }).format(atual);
      return valorFormatado;
    },

    formatData(data_input) {
      let data = new Date(data_input);
      let dataFormatada =
        data.getDate() + "-" + (data.getMonth() + 1) + "-" + data.getFullYear();

      return dataFormatada;
    },

    imprimirPDF() {
      window.open(
        "/fecho/caixa-geral/pdf-imprimir?operador=" +
          this.operador +
          "&codigo_produto=" +
          this.codigo_produto +
          "&grau_academico=" +
          this.grau_academico +
          "&anolectivo=" +
          this.anolectivo +
          "&forma_pagamento=" +
          this.forma_pagamento +
          "&data_inicio=" +
          this.data_inicio +
          "&data_final=" +
          this.data_final,
        "_blank"
      );
    },

    imprimirEXCEL() {
      window.open(
        "/fecho/caixa-geral/excel-imprimir?operador=" +
          this.operador +
          "&codigo_produto=" +
          this.codigo_produto +
          "&grau_academico=" +
          this.grau_academico +
          "&anolectivo=" +
          this.anolectivo +
          "&forma_pagamento=" +
          this.forma_pagamento +
          "&data_inicio=" +
          this.data_inicio +
          "&data_final=" +
          this.data_final,
        "_blank"
      );
    },
  },
};
</script>
