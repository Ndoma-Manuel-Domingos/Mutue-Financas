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
                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label>Data Inicio:</label>
                    <input type="date" v-model="data_inicio" class="form-control"/>
                  </div>
                </div>

                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label>Data Final:</label>
                    <input type="date" class="form-control" v-model="data_final" />
                  </div>
                </div>

                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label for="" class="text-secondary">Tipo Serviço</label>
                    <div class="input-group input-group">
                      <select
                        v-model="codigo_produto"
                        class="form-control "
                      >
                        <option :value="servico.Codigo" v-for="servico in tipoServicos" :key="servico.Codigo">
                          {{ servico.Codigo }}{{ servico.Descricao }}
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
                        class="form-control "
                      >
                        <option value="">TODOS</option>
                        <option value="1">Validado</option>
                        <option value="2">Rejeitado</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-2">
                  <div class="form-group">
                    <label for="" class="text-secondary">Parcela</label>
                    <div class="input-group input-group">
                      <select
                        v-model="parcela_mes_temp"
                        class="form-control "
                      >
                        <option
                          :value="estado.id"
                          v-for="estado in mesTemps"
                          :key="estado.id"
                        >
                          {{ estado.designacao }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer"></div>
          </div>
        </form>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-light">
                <h3 class="card-title"></h3>
                <div class="card-tools">
                  <div class="input-group input-group-md" style="width: 150px">
                    <input
                      type="text"
                      name="table_search"
                      class="form-control float-right"
                      placeholder="Search"
                    />
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>Operador</th>
                        <th>Validação Data</th>
                        <th>Valor:</th>
                        <th>Recibo</th>
                        <th>Forma Pagamento</th>
                        <th>Estado</th>
                        <th>Ano Lectivo</th>
                        <th width="100px">Acções</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="pagamento in items.data" :key="pagamento.id">
                        <td>{{ pagamento.nomeUtilizador }}</td>
                        <td>{{ pagamento.dataValidacaoPagamento }}</td>
                        <td>{{ formatValor(pagamento.valorPagamento) }}</td>
                        <td>{{ pagamento.reciboPagamento }}</td>
                        <td>{{ pagamento.formaPagamento }}</td>
                        <td
                          v-if="pagamento.estadoPagamento == 1"
                          class="text-success"
                        >
                          Valido
                        </td>
                        <td
                          v-if="pagamento.estadoPagamento == 2"
                          class="text-danger"
                        >
                          Rejeitado
                        </td>
                        <td>{{ pagamento.AnoLectivoPagamento }}</td>
                        <td>
                          <a href="#" class="btn-sm btn-primary">
                            Ver Detalhe <i class="fas fa-eye"></i>
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
        "mesTemps",
        "formaPagamentos",
        "estadoPagamento",
        "tipoServicos",    
    ],
    components: {
      Link,
      Paginacao
    },
    data() {
      return {
        estado_pagamento: "",
        codigo_produto: "",
        parcela_mes_temp: "",
        data_inicio: new Date().toISOString().substr(0, 10),
        data_final: new Date().toISOString().substr(0, 10),
        
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
      estado_pagamento: function(val) {
        this.params.estado_pagamento = val;
        this.updateData();
      },
      codigo_produto: function(val) {
        this.params.codigo_produto = val;
        this.updateData();
      },
      
      parcela_mes_temp: function(val) {
        this.params.parcela_mes_temp = val;
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

    },
    methods: {
      formatValor(atual) {
        const valorFormatado = Intl.NumberFormat("pt-br", {
          style: "currency",
          currency: "AOA",
        }).format(atual);
        return valorFormatado;
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

