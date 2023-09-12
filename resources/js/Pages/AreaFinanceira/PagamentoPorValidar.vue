<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0 text-uppercase">Pagamento Por validar</h4>
                    </div>
                    <div class="col-sm-6">
                        <a @click="imprimirPDF" class="btn btn-danger btn-sm float-sm-right mr-2"><i
                                class="fas fa-file-pdf"></i> PDF</a>
                        <a @click="imprimirEXCEL" class="btn btn-success btn-sm float-sm-right mr-2"><i
                                class="fas fa-file-excel"></i> Excel</a>
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
                                
                                
                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <label for="" class="text-secondary">Ano Lectivo</label>
                                            <div class="input-group input-group">
                                                <select v-model="ano_lectivo"
                                                    class="form-control form-control-sm ">
                                                    <option value="">TODAS</option>
                                                    <option :value="item.Codigo" v-for="item in anos_lectivos"
                                                        :key="item.Codigo">{{ item.Designacao }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <label for="" class="text-secondary">Tipo Serviço</label>
                                            <div class="input-group input-group">
                                                <select v-model="tipo_servico" @change="search"
                                                    class="form-control form-control-sm  form-control" placeholder="Type your keywords here">
                                                    <option value="">TODOS</option>
                                                    <option :value="item.Codigo" v-for="item in servicos"
                                                        :key="item.Codigo">{{ item.Descricao }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <label for="" class="text-secondary">
                                                Forma de Pagamentos
                                            </label>
                                            <div class="input-group input-group">
                                                <select v-model="forma_pagamento" @change="search" class="form-control form-control-sm "
                                                    placeholder="Selecione a Forma de Pagamento">
                                                    <option value="">TODAS</option>
                                                    <option :value="forma.descricao" v-for="forma in formaPagamentos"
                                                        :key="forma.Codigo">{{ forma.descricao }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <label for="" class="text-secondary">
                                                Grau Acadêmico
                                            </label>
                                            <div class="input-group input-group">
                                                <select v-model="grau_academico" @change="search" class="form-control form-control-sm ">
                                                    <option value="">TODAS</option>
                                                    <option :value="graus.id" v-for="graus in grau" :key="graus.id">
                                                        {{graus.designacao}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <label>Data Inicio:</label>

                                            <input type="date" v-model="data_inicio" @change="search"
                                                class="form-control form-control-sm " />
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <label>Data Final:</label>
                                            <input type="date" v-model="data_final" @change="search" class="form-control form-control-sm " />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <input type="search" v-model="filtro_estudante" class="form-control" placeholder="Pesquisar estudante pelo codigo da Pre-inscrição, Nome, Bilheite">
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <button class="btn btn-primary" @click="pesquisar_estudante"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="carregarTabelaEstudantes" style="width: 100%" class="table-sm table_estudantes table-bordered table-striped table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl">
                                    <thead>
                                        <tr>
                                            <th>Matricula</th>
                                            <th>Estudante</th>
                                            <th>Factura</th>
                                            <th>Recibo</th>
                                            <th>Serviço</th>
                                            <th>Data Pagamento</th>
                                            <th>Data Inserção</th>
                                            <th>Valor Depositado</th>
                                            <th>Forma Pagamento</th>
                                            <th width="100px">Acções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="pagamento in items.data" :key="pagamento.Codigo">
                                            
                                            <td v-if="pagamento.matricula"><a :href="route('mf.estudante-visualizar-perfil', pagamento.matricula)">{{ pagamento.matricula }}</a></td>
                                            <td v-else>#####</td>
                                            
                                            <td v-if="pagamento.matricula"><a :href="route('mf.estudante-visualizar-perfil', pagamento.matricula)">{{ pagamento.Nome_Completo }}</a></td>
                                            <td v-else>{{ pagamento.Nome_Completo  }}</td>
                                            
                                            <td>{{ pagamento.codigo_factura }}</td>
                                            <td>{{ pagamento.Codigo }}</td>
                                            <td>{{ pagamento.servico }}</td>
                                            <td>{{ formatData(pagamento.DataBanco)  }}</td>
                                            <td>{{ pagamento.Data }}</td>
                                            <td>{{ formatValor(pagamento.valor_depositado) }}</td>
                                            <!-- <td>{{ pagamento.prestacao }}</td> -->
                                            <td>{{ pagamento.forma_pagamento }}</td>
                                            <td>
                                                <a class="btn-sm btn-success mr-1" @click="visualizar_detalhes(pagamento)">
                                                    <i class="fas fa-eye" title="VISUALIZAR DETALHES DO PAGAMENTO"> </i>
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
                                <Paginacao :links="items.links" :prev="items.prev_page_url"
                                    :next="items.next_page_url" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalDetalhesPagamento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-uppercase" id="exampleModalLabel">Datalhes do Pagamento</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="row">

                                    <div class="col-12 col-md-8">
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
                                                      <td>{{ formatValor(dados_pagamentos.valor) }}</td>
                                                      <td>{{ formatData(dados_pagamentos.data_banco )}}</td>
                                                      <td>{{ formatData(dados_pagamentos.data_registro)}}</td>
                                                      <td>{{ formatData(dados_pagamentos.data_validacao)}}</td>
                                                    </tr>

                                                    <tr>
                                                      <th colspan="4" class="bg-info">Comprovativo</th>
                                                    </tr>

                                                    <tr>
                                                      <td colspan="2"><a target="_blink" :href="'https://mutue.ao/storage/documentos/'+dados_pagamentos.img1">Visualizar Comprovativo</a></td>
                                                    </tr>
                                                    <!-- https://mutue.ao/storage/documentos/3011191692210191.pdf -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <!-- <img   height="400px" width="100%" class="img-circle"> -->
                                        <img :src="'https://mutue.ao/storage/documentos/'+dados_pagamentos.img1" alt="MUTUE FINANÇA" class="elevation-0" style="opacity: 1;width: 100%;height: 100%;"/>

                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <a @click="validar_pagamento(dados_pagamentos.recibo)" class="btn btn-success">Validar</a>
                                <a @click="rejeitar_pagamento(dados_pagamentos.recibo)" class="btn btn-danger">Rejeitar</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal fade" id="modalMotivo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-uppercase" id="exampleModalLabel">Motivos da Rejeição do Pagamento</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12 col-md-12">
                                            <div class="form-group">
                                                <label for="" class="text-secondary">
                                                    Motivos
                                                </label>
                                                <div class="input-group input-group">
                                                    <select v-model="movito_rejeicao" class="form-control form-control-sm ">
                                                        <option value="">Selecionar</option>
                                                        <option :value="motivo.id" v-for="motivo in motivos" :key="motivo.id">
                                                            {{motivo.designacao}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a @click="confirmar_rejeicao_pagamento(dados_pagamentos.recibo)" class="btn btn-success">Confirmar</a>
                                </div>
                            </form>
                            <div class="modal-footer">
                                <!-- <a @click="validar_pagamento(dados_pagamentos.recibo)" class="btn btn-success">Validar</a>
                                <a @click="rejeitar_pagamento(dados_pagamentos.recibo)" class="btn btn-danger">Rejeitar</a> -->
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
    import Paginacao from '../../Shared/Paginacao.vue'
    import { sweetSuccess, sweetError } from '../../components/Alert'

    export default {
        props: [
            "items",
            "prestacoes",
            "grau",
            "formaPagamentos",
            "servicos",
            "motivos",
            "anos_lectivos",
        ],
        components: {
            Link,
            Paginacao
        },
        
        data() {
            return {
                filtro_estudante: "",
                
                ano_lectivo: "",
                tipo_servico: "",
                forma_pagamento: "",
                prestacao: "",
                grau_academico: "",
                data_inicio: "",
                // data_inicio: new Date().toISOString().substr(0, 10),
                data_final: "",
                // data_final: new Date().toISOString().substr(0, 10),

                movito_rejeicao: "",
                params: {},
                
                dados_estudante: {},
                dados_pagamentos: {},

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

            tipo_servico: function(val) {
              this.params.tipo_servico = val;
              this.updateData();
            },

            forma_pagamento: function(val) {
              this.params.forma_pagamento = val;
              this.updateData();
            },

            prestacao: function(val) {
              this.params.prestacao = val;
              this.updateData();
            },

            grau_academico: function(val) {
              this.params.grau_academico = val;
              this.updateData();
            },

            data_inicio: function(val) {
              this.params.data_inicio = val;
              this.updateData();
            },

            data_final: function(val) {
              this.params.data_final = val;
              this.updateData();
            },
            
            ano_lectivo: function(val) {
              this.params.ano_lectivo = val;
              this.updateData();
            },
            
            filtro_estudante: function(val) {
              this.params.filtro_estudante = val;
              this.updateData();
            },

        },
            
        methods: {
            updateData() {
                this.$Progress.start();
                this.$inertia.get("/pagamento/porvalidar", this.params, {
                    preserveState: true,
                    preverseScroll: true,
                    onSuccess: () => {
                      this.$Progress.finish();
                    }
                })
            },
            
            pesquisar_estudante()
            {
                this.params.filtro_estudante
                
                updateData();
            },
            
            confirmar_rejeicao_pagamento(codigo)
            {
                // console.log(this.movito_rejeicao, codigo);
                 
                if(this.movito_rejeicao == ""){
                    Swal.fire({
                        title: "Atenção prezado(a)!",
                        text: "Não é possivel efectuar a rejeição deste pagamentos por não selecionou um movito, selecione um motivos tenta novamente!",
                        icon: "warning",
                        confirmButtonColor: "#3d5476",
                        confirmButtonText: "Ok",
                        onClose: () => { },
                      });
                  return 0;
                }
                 
                Swal.fire({
                  title: 'Atenção!',
                  text: "Que pretendes rejeitar este pagamento?",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Sim, Rejeitar!'
                }).then((result) => {
                  if (result.isConfirmed) {
                    this.$Progress.start();
                    axios
                        .get("../pagamento/rejeitar-pagamento/store/"+codigo+'/'+this.movito_rejeicao)
                        .then((response) => {
                          sweetSuccess("Pagamento Rejeitado com sucesso!")

                          $('#modalDetalhesPagamento').modal('hide');
                          $('#modalMotivo').modal('hide');
                          this.$Progress.finish();
                    }).catch(() => {
                          this.$Progress.fail();
                          sweetError("erro ao tentar rejeitar Pagmento !");
                    });
                  }
                })
            },

            visualizar_detalhes(item){
                this.$Progress.start();
                // $(".table_estudantes").html("");
                axios
                  .get("../visualizar-detalhes-pagamento/" + item.Codigo)
                  .then((response) => {

                    this.dados_estudante = response.data.dados,
                    this.dados_pagamentos = response.data.detalhes,

                    this.$Progress.finish();
                  }).catch(() => {
                    this.$Progress.fail();
                    // sweetError("Estudante Não Encontrado!");
                  });

                $('#modalDetalhesPagamento').modal('show');
            },

            validar_pagamento(item){

                Swal.fire({
                  title: 'Atenção!',
                  text: "Pretendes validar este pagamento?",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Sim, Validar!'
                }).then((result) => {
                  if (result.isConfirmed) {
                    this.$Progress.start();
                    axios
                      .get("../pagamento/validar-pagamento/store/" + item)
                      .then((response) => {
                        
                        if(response.data.code == 200){
                            sweetSuccess(response.data.descricao)
    
                            $('#modalDetalhesPagamento').modal('hide');
                            
                            this.$Progress.finish();
                            window.location.reload();
                        }else                        
                        if(response.data.code == 400){
                            sweetError(response.data.descricao)
                            this.$Progress.fail();
                        }else{
                            this.$Progress.fail();
                            sweetError("erro ao tentar validar Pagmento !"); 
                            this.$Progress.finish();
                        }
                       
                    }).catch(() => {
                        this.$Progress.fail();
                        sweetError("erro ao tentar validar Pagmento !");
                    });
                  }
                })
            },

            rejeitar_pagamento(item){
                $("#modalMotivo").modal('show');
                $('#modalDetalhesPagamento').modal('hide');
            },

            formatarData(data) {
              var partes = data.split(" ");
              var dataPartes = partes[0].split("-");
              var ano = dataPartes[0];
              var mes = dataPartes[1];
              var dia = dataPartes[2];

              return ano + "-" + mes + "-" + dia;
            },

            formatData(data_input){
                let data = new Date(data_input);
                let dataFormatada = ((data.getDate() )) + "-" + ((data.getMonth() + 1)) + "-" + data.getFullYear();

                return dataFormatada;
            },

            formatValor(atual) {
                const valorFormatado = Intl.NumberFormat("pt-br", {
                    style: "currency",
                    currency: "AOA",
                }).format(atual);
                return valorFormatado;
            },

            imprimirPDF(){
                window.open(`/pagamento/porvalidar-pdf?prestacao=${this.prestacao}&filtro_estudante=${this.filtro_estudante}&grau_academico=${this.grau_academico}&forma_pagamento=${this.forma_pagamento}&tipo_servico=${this.tipo_servico}&data_inicio=${this.data_inicio}&data_final=${this.data_final}`, "_blank");
            },

            imprimirEXCEL(){
                window.open(`/pagamento/porvalidar-excel?prestacao=${this.prestacao}&filtro_estudante=${this.filtro_estudante}&grau_academico=${this.grau_academico}&forma_pagamento=${this.forma_pagamento}&tipo_servico=${this.tipo_servico}&data_inicio=${this.data_inicio}&data_final=${this.data_final}`, "_blank");
            },

        }

    }
</script>
