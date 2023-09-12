<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0 text-uppercase">Fecho de Caixa <span v-if="data_inicio"> No Período {{ data_inicio }}</span> <span v-if="data_final"> e {{ data_final }}</span></h4>
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
                <div class="col-12 col-md-3">
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

                <div class="col-12 col-md-3">
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

                <div class="col-12 col-md-3">
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

                <div class="col-12 col-md-3">
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
        
          <div class="col-md-3 col-sm-6 col-12" v-for="item in items.data" :key="item">
            <div
              class="info-box"
              style="background-color: #336699; color: #ffffff"
              title="OPERADOR()"
            >
              <span class="info-box-icon"><i class="fas fa-user"></i></span>
              <div class="info-box-content">
                <span class="info-box-number">{{ item.nome ?? 'Estudante' }}</span>
                <span class="info-box-text text-uppercase word-break">OPERADOR(A)</span>
                <span class="progress-description"> Total: {{ formatValor(item.total_arrecadado) }} </span>
                <a :href="`fecho/caixa-geral?operador=${item.fk_utilizador}&data_inicio=${data_inicio ?? ''}`" class="small-box-footer text-danger">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
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
                    <td>{{ dados_pagamentos.recibo }}</td>
                    <td>{{ dados_pagamentos.factura }}</td>
                    <td>{{ dados_pagamentos.operacao ?? " ---- " }}</td>
                    <td>{{ dados_pagamentos.operacao2 ?? " ---- " }}</td>
                  </tr>

                  <tr>
                    <th>Valor</th>
                    <th>Data Banco</th>
                    <th>Data Registro</th>
                    <th>Data Validação</th>
                  </tr>

                  <tr>
                    <td>{{ dados_pagamentos.valor }}</td>
                    <td>{{ formatData(dados_pagamentos.data) }}</td>
                    <td>{{ formatData(dados_pagamentos.data_registro) }}</td>
                    <td>{{ formatData(dados_pagamentos.data_validacao) }}</td>
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
                          dados_pagamentos.img1
                        "
                      >
                        {{ dados_pagamentos.img1 }}</a
                      >
                    </td>
                    <td colspan="2">
                      <a
                        target="_blink"
                        :href="
                          'https://mutue.ao/storage/documentos/' +
                          dados_pagamentos.img2
                        "
                      >
                        {{ dados_pagamentos.img2 }}</a
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
    "requests"
  ],
  components: {
    Link,
    Paginacao,
  },

  data() {
    return {
      estado_pagamento: "",
      operador: "",
      codigo_produto: "",
      mes_temp: "",
      anolectivo: 18,
      grau_academico: "",

      data_inicio: this.requests.data_inicio,
      data_final: this.requests.data_final,

      forma_pagamento: "",
      params: {},
      nomeUtilizador: "",

      dados_estudante: {},
      dados_pagamentos: {},
    };
  },
  mounted(){
    this.params.data_inicio = new Date().toISOString().substr(0, 10)
    this.updateData();
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

    operador: function (val) {
      this.params.operador = val;
      this.updateData();
    },
    mes_temp: function (val) {
      this.params.mes_temp = val;
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

    forma_pagamento: function (val) {
      this.params.forma_pagamento = val;
      this.updateData();
    },
  },
  methods: {
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/fecho-caixa", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },

    visualizar_detalhes(item) {
      this.$Progress.start();
      $(".table_estudantes").html("");
      axios
        .get("/visualizar-detalhes-pagamento/" + item.Codigo)
        .then((response) => {
          (this.dados_estudante = response.data.dados),
            (this.dados_pagamentos = response.data.detalhes),
            // this.estudante.push(response.data.matricula);
            // if (response.data.matricula === null) {
            //   sweetError("Ocorreu um errro");
            // } else {
            //   $("#model_estudante_matricula").modal("show");
            //   sweetSuccess("Estudante Encontrado com sucesso!");
            // }
            this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
          // sweetError("Estudante Não Encontrado!");
        });

      // this.dados_estudante.matricula = item.preinscricao.admissao.matricula.Codigo ?? '',
      // this.dados_estudante.nome = item.preinscricao.Nome_Completo ?? '',
      // this.dados_estudante.curso = item.preinscricao.admissao.matricula.curso.Designacao ?? '',
      // this.dados_estudantecampus = item.preinscricao.polo.designacao ?? '',
      // this.dados_estudante.contacto = item.preinscricao.Contactos_Telefonicos ?? '',

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
        "/fecho/caixa-geral/pdf-imprimir?o=" +
          this.operador +
          "&s=" +
          this.codigo_produto +
          "&g=" +
          this.grau_academico +
          "&a=" +
          this.anolectivo +
          "&ep=" +
          this.estado_pagamento +
          "&fp=" +
          this.forma_pagamento +
          "&di=" +
          this.data_inicio +
          "&df=" +
          this.data_final,
        "_blank"
      );
    },

    imprimirEXCEL() {
      window.open(
        "/fecho/caixa-geral/excel-imprimir?o=" +
          this.operador +
          "&s=" +
          this.codigo_produto +
          "&g=" +
          this.grau_academico +
          "&a=" +
          this.anolectivo +
          "&ep=" +
          this.estado_pagamento +
          "&fp=" +
          this.forma_pagamento +
          "&di=" +
          this.data_inicio +
          "&df=" +
          this.data_final,
        "_blank"
      );
    },
  },
};
</script>
