<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-uppercase">Fecho de Caixa Por Utilizador</h1>
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

                <!-- <div class="col-12 col-md-4">
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
                </div> -->

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
                
              </div>
            </div>
          </div>
        </form>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-light">
                <h3 class="card-title"><strong>Total Arrecadado: {{ formatValor(total) }}</strong></h3>
              </div>

              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>Operador</th>
                        <th>Validação Data</th>
                        <th>Data Banco</th>
                        <th>Data Registro</th>
                        <th>Serviço</th>
                        <th>Valor:</th>
                        <th>Recibo</th>
                        <th>Forma Pagamento</th>
                        <th>Estado</th>
                        <th>Grau</th>
                        <th width="100px">Acções</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="pagamento in items.data" :key="pagamento.Codigo">
                        <td>{{ pagamento.operador ?? "Estudante" }}</td>
                        <td>{{ formatData(pagamento.updated_at) }}</td>
                        <td>{{ formatData(pagamento.DataBanco)  }}</td>
                        <td>{{ formatData(pagamento.DataRegisto) }}</td>
                        <td>{{ pagamento.Descricao }}</td>
                        <td>{{ formatValor(pagamento.valor_depositado) }}</td>
                        <td>{{ pagamento.Codigo }}</td>
                        <td>{{ pagamento.forma_pagamento }}</td>
                        <td v-if="pagamento.estado == 1" class="text-success">Validado</td>
                        <td v-if="pagamento.estado == 0" class="text-info">Pendente</td>
                        <td v-if="pagamento.estado == 2" class="text-warning">Rejeitado</td>
                        <td>{{ pagamento.designacao }}</td>
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
    
    
  </MainLayouts>
</template>

<script>
  import { Link } from "@inertiajs/inertia-vue3"
  import Paginacao from '../../Shared/Paginacao.vue';  
  
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
      "total"
    ],
    components: {
      Link,
      Paginacao
    },
    data() {
      return {
        estado_pagamento: "",
        operador: "",
        codigo_produto: "",
        mes_temp: "",
        anolectivo: 18,
        grau_academico: "",
        
        data_inicio_banco: "",
        data_final_banco: "",
        data_inicio_validacao: "",
        data_final_validacao: "",

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
        this.$inertia.get("/fecho/caixa-utilizador", this.params, {
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

            this.$Progress.finish();
          }).catch(() => {
            this.$Progress.fail();
            // sweetError("Estudante Não Encontrado!");
          });
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
        window.open("/fecho/caixa-utilizador/pdf-imprimir?ep="+this.estado_pagamento+"&s="+this.codigo_produto+"&p="+this.parcela_mes_temp+"&di="+this.data_inicio+"&df="+this.data_final, "_blank");
      },
    
      imprimirEXCEL() {
        window.open("/fecho/caixa-utilizador/excel-imprimir?ep="+this.estado_pagamento+"&s="+this.codigo_produto+"&p="+this.parcela_mes_temp+"&di="+this.data_inicio+"&df="+this.data_final, "_blank");
      },
    
      updateData() {
        this.$Progress.start();
        this.$inertia.get("/fecho/caixa-utilizador", this.params, {
          preserveState: true,
          preverseScroll: true,
          onSuccess: () => {
            this.$Progress.finish(); 
          }
        });
      },     
    }
  
  }

</script>

