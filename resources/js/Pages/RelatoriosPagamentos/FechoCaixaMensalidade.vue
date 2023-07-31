<template>
  <MainLayouts>


    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-uppercase">Fecho de Caixa Mensalidades</h1>
          </div>
          <div class="col-sm-6">
            <a @click="imprimirPDF" class="btn btn-danger btn-sm float-sm-right mr-2"><i class="fas fa-file-pdf"></i> PDF</a>
            <a  @click="imprimirEXCEL" class="btn btn-success btn-sm float-sm-right mr-2"><i class="fas fa-file-excel"></i> Excel</a>
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <form action="">
          <div class="card card-light">
            <div class="card-header">Buscas Avançadas</div>
            <div class="card-body">
              <div class="row">


                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label for="" class="text-secondary">Ano Lectivo</label>
                    <div class="input-group input-group">
                      <select
                        v-model="anolectivo"
                        class="form-control"
                      >
                        <option value="">TODOS</option>
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


                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label for="" class="text-secondary">Tipo Serviços</label>
                    <div class="input-group input-group">
                      <select
                        v-model="codigo_produto"
                        class="form-control"
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
                        class="form-control"
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



                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label for="" class="text-secondary">Prestação</label>
                    <div class="input-group input-group">
                      <select
                        v-model="mes_temp"
                        class="form-control"
                      >
                        <option value="">TODAS</option>
                        <option
                          :value="mes.id"
                          v-for="mes in mesTemps"
                          :key="mes.id"
                        >
                          {{ mes.designacao }}
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
                        v-model="estado_pagamento"
                        class="form-control"
                      >
                        <option value="">TODOS</option>
                        <option value="0">Pendente</option>
                        <option value="1">Validado</option>
                        <option value="2">Rejeitado</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label for="" class="text-secondary"
                      >Forma de Pagamentos</label
                    >
                    <div class="input-group input-group">
                      <select
                        v-model="forma_pagamento"
                        class="form-control"
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
                    <label>Data Inicio Banco:</label>
                    <input type="date" @keyup.enter="search" v-model="data_inicio_banco" class="form-control" />
                  </div>
                </div>

                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label>Data Final Banco:</label>
                    <input type="date" @keyup.enter="search" class="form-control" v-model="data_final_banco" />
                  </div>
                </div>


                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label>Data Inicio Validação:</label>
                    <input type="date" @keyup.enter="search" v-model="data_inicio_validacao" class="form-control" />
                  </div>
                </div>

                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label>Data Final Validação:</label>
                    <input type="date" @keyup.enter="search" class="form-control" v-model="data_final_validacao" />
                  </div>
                </div>

                <div class="col-12 col-md-4">
                  <div class="form-group">
                    <label for="" class="text-secondary">Operador</label>
                    <div class="input-group input-group">
                      <select
                        v-model="operador"
                        class="form-control"
                      >
                        <option value="">TODOS</option>
                        <option
                          :value="utilizador.utilizadores.codigo_importado" v-for="utilizador in utilizadores" :key="utilizador.Codigo"
                        >
                          {{ utilizador.utilizadores.nome }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <!-- <Link
                :href="route('mf.fecho-caixa-geral')"
                class="btn btn-primary float-right"
              >
                <i class="fas fa-broom"></i>
                Limpar os campos
              </Link> -->
            </div>
          </div>
        </form>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-light">
                <h3 class="card-title"><strong>Resultado: </strong></h3>
              </div>

              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>Matricula</th>
                        <th>Operador</th>
                        <th>Validação Data</th>
                        <th>Data Banco</th>
                        <th>Data Registro</th>
                        <th>Valor:</th>
                        <th>Recibo</th>
                        <th>Forma Pagamento</th>
                        <th>Estado</th>
                        <th>Prestação</th>
                        <th>Grau</th>
                        <th>Ano Lectivo</th>
                        <th>Comprovativo</th>
                        <th width="100px">Acções</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="pagamento in items.data" :key="pagamento.id">
                        <td>{{pagamento.codigo }}</td>
                        <td>wewe</td>
                        <td>{{ formatData(pagamento.updated_at) }}</td>
                        <td>{{ formatData(pagamento.DataBanco)  }}</td>
                        <td>{{ formatData(pagamento.DataRegisto) }}</td>
                        <!-- <td>{{ pagamento.dataValidacaoPagamento }}</td> -->
                        <td>{{ formatValor(pagamento.valor_depositado) }}</td>
                        <td>{{ pagamento.Codigo }}</td>
                        <td>{{ pagamento.forma_pagamento }}</td>
                        <td
                          v-if="pagamento.estado == 1"
                          class="text-success"
                        >
                          Valido
                        </td>
                        <td
                          v-if="pagamento.estado == 2"
                          class="text-danger"
                        >
                          Rejeitado
                        </td>
                        <td
                          v-if="pagamento.estado == 0"
                          class="text-info"
                        >
                          Pendente
                        </td>
                        <td>
                          {{ pagamento.items.mes_temps ? pagamento.items.mes_temps.designacao : '' }}
                          <!-- <span v-for="item in pagamento.factura.items_factura" :key="item">
                            {{ item.mes_temp.designacao ?? '---' }}
                          </span> -->
                        </td>
                        <td>{{ pagamento.preinscricao.grau_academico.designacao ?? ''}}</td>
                        <td>{{ pagamento.anolectivo ? pagamento.anolectivo.Designacao : '' }}</td>
                        <td><a target="_blink" :href="'https://mutue.ao/storage/documentos/'+pagamento.nome_documento"> {{ pagamento.nome_documento }}</a></td>
                        <td>
                          <a class="btn-sm btn-success mr-1" @click="visualizar_detalhes(pagamento)">
                            <i class="fas fa-eye" title="VISUALIZAR DETALHES DO PAGAMENTO"> </i>
                          </a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
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

  <div class="modal fade" id="modalDetalhesPagamento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="exampleModalLabel">Datalhes do Pagamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                            <!-- <tr>
                                <th>Codigo</th>
                                <th>Designação</th>
                            </tr> -->
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
                              <td>{{ dados_pagamentos.operacao ?? ' ---- ' }}</td>
                              <td>{{ dados_pagamentos.operacao2 ?? ' ---- ' }}</td>
                            </tr>

                            <tr>
                              <th>Valor</th>
                              <th>Data Banco</th>
                              <th>Data Registro</th>
                              <th>Data Validação</th>
                            </tr>

                            <tr>
                              <td>{{ dados_pagamentos.valor }}</td>
                              <td>{{ formatData(dados_pagamentos.data_banco ) }}</td>
                              <td>{{ formatData(dados_pagamentos.data_registro)  }}</td>
                              <td>{{ formatData(dados_pagamentos.data_validacao)  }}</td>
                            </tr>

                            <tr>
                              <th colspan="2" class="bg-info">Comprovativo 01</th>
                              <th colspan="2" class="bg-info">Comprovativo 02</th>
                            </tr>

                            <tr>
                              <td colspan="2"><a target="_blink" :href="'https://mutue.ao/storage/documentos/'+dados_pagamentos.img1"> {{ dados_pagamentos.img1 }}</a></td>
                              <td colspan="2"><a target="_blink" :href="'https://mutue.ao/storage/documentos/'+dados_pagamentos.img2"> {{ dados_pagamentos.img2 }}</a></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
  </div>


  </MainLayouts>
</template>

<script>
  import { Link } from "@inertiajs/inertia-vue3"
  import Paginacao from '../../Shared/Paginacao.vue'

  export default{

    props: [
      "items",
      "anoLectivos",
      "grausAcademicos",
      "mesTemps",
      "formaPagamentos",
      "estadoPagamento",
      "tipoServicos",
      "utilizadores",
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
        data_inicio_banco: new Date().toISOString().substr(0, 10),
        data_final_banco: new Date().toISOString().substr(0, 10),
        data_inicio_validacao: new Date().toISOString().substr(0, 10),
        data_final_validacao: new Date().toISOString().substr(0, 10),
        forma_pagamento: "",
        params: {},
        nomeUtilizador: "",

        dados_estudante: {},
        dados_pagamentos: {}
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
      anolectivo: function(val) {
        this.params.anolectivo = val;
        this.updateData();
      },
      estado_pagamento: function(val) {
        this.params.estado_pagamento = val;
        this.updateData();
      },
      operador: function(val) {
        this.params.operador = val;
        this.updateData();
      },
      codigo_produto: function(val) {
        this.params.codigo_produto = val;
        this.updateData();
      },

      mes_temp: function(val) {
        this.params.mes_temp = val;
        this.updateData();
      },

      grau_academico: function(val) {
        this.params.grau_academico = val;
        this.updateData();
      },

      data_inicio_banco: function(val) {
        this.params.data_inicio_banco = val;
        this.updateData();
      },

      data_final_banco: function(val) {
        this.params.data_final_banco = val;
        this.updateData();
      },


      data_inicio_validacao: function(val) {
        this.params.data_inicio_validacao = val;
        this.updateData();
      },

      data_final_validacao: function(val) {
        this.params.data_final_validacao = val;
        this.updateData();
      },


      forma_pagamento: function(val) {
        this.params.forma_pagamento = val;
        this.updateData();
      },
    },
    methods: {
      updateData() {

        this.$Progress.start();
        this.$inertia.get("/fecho/caixa-mensalidades-geral", this.params, {
          preserveState: true,
          preverseScroll: true,
          onSuccess: () => {
            this.$Progress.finish();
          }
        });
      },

      visualizar_detalhes(item){
        this.$Progress.start();
        $(".table_estudantes").html("");
        axios
          .get("/visualizar-detalhes-pagamento/" + item.Codigo)
          .then((response) => {
            this.dados_estudante = response.data.dados,
            this.dados_pagamentos = response.data.detalhes,

            // this.estudante.push(response.data.matricula);
            // if (response.data.matricula === null) {
            //   sweetError("Ocorreu um errro");
            // } else {
            //   $("#model_estudante_matricula").modal("show");
            //   sweetSuccess("Estudante Encontrado com sucesso!");
            // }
            this.$Progress.finish();
          }).catch(() => {
            this.$Progress.fail();
            // sweetError("Estudante Não Encontrado!");
          });

        // this.dados_estudante.matricula = item.preinscricao.admissao.matricula.Codigo ?? '',
        // this.dados_estudante.nome = item.preinscricao.Nome_Completo ?? '',
        // this.dados_estudante.curso = item.preinscricao.admissao.matricula.curso.Designacao ?? '',
        // this.dados_estudantecampus = item.preinscricao.polo.designacao ?? '',
        // this.dados_estudante.contacto = item.preinscricao.Contactos_Telefonicos ?? '',

        $('#modalDetalhesPagamento').modal('show');
      },

      formatValor(atual) {
        const valorFormatado = Intl.NumberFormat("pt-br", {
          style: "currency",
          currency: "AOA",
        }).format(atual);
        return valorFormatado;
      },

      formatData(data_input){
        let data = new Date(data_input);
        let dataFormatada = ((data.getDate() )) + "-" + ((data.getMonth() + 1)) + "-" + data.getFullYear();

        return dataFormatada;
      },

      imprimirPDF() {
        window.open("/fecho/caixa-geral/pdf-imprimir?o="+this.operador+"&s="+this.codigo_produto+"&g="+this.grau_academico+"&a="+this.anolectivo+"&ep="+this.estado_pagamento+"&fp="+this.forma_pagamento+"&di="+this.data_inicio_banco+"&df="+this.data_final_banco, "_blank");
      },

      imprimirEXCEL() {
        window.open("/fecho/caixa-geral/excel-imprimir?o="+this.operador+"&s="+this.codigo_produto+"&g="+this.grau_academico+"&a="+this.anolectivo+"&ep="+this.estado_pagamento+"&fp="+this.forma_pagamento+"&di="+this.data_inicio_banco+"&df="+this.data_final_banco, "_blank");
      },

    }
  }


</script>
