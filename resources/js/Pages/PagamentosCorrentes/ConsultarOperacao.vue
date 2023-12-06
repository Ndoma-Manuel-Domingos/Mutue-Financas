<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0 text-uppercase">Consultar Nº Operação</h4>
          </div>
          <div class="col-sm-6"></div>
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
                  <div class="col-12 col-md-12">
                    <div class="form-group">
                      <label>Pesquisar Número do comprovatovo</label>
                      <div
                        class="input-group date"
                        id="reservationdate"
                        data-target-input="nearest"
                      >
                        <input
                          type="text"
                          class="form-control form-control-sm "
                          @keyup.enter="buscar_comprovotivo"
                          placeholder="Pesquisar Número do comprovativo, introduz o número do compravativo aqui"
                          v-model="numero_comprovativo"
                        />
                        <div class="input-group-append">
                          <button class="btn btn-primary" @click="buscar_comprovotivo" @keyup.enter="buscar_comprovotivo">
                            <i class="fa fa-search"></i>  Listar a busca
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row" v-if="pagamentos.data">
          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table id="carregarTabelaEstudantes" style="width: 100%" class="table-sm table_estudantes table-bordered table-striped table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl">
                    <thead>
                      <tr>
                        <th>Estudante</th>
                        <th>Data Pagamento</th>
                        <th>Valor Depositado</th>
                        <th>Ano Lectivo</th>
                        <th>Tipo de Envio</th>
                        <th>Comprovativo</th>
                        <th width="100px">Mais Detalhes</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="pagamento in pagamentos.data" :key="pagamento.Codigo">
                        <td>{{ pagamento.preinscricao.Nome_Completo }}</td>
                        <td>{{ pagamento.Data }}</td>
                        <td>AOA {{ pagamento.valor_depositado }}</td>
                        <td>{{ pagamento.anolectivo.Designacao }}</td>
                        <td class="text-danger">{{ pagamento.canal.designacao }}</td>
                        <td>
                          <Link :href="`/consultar/operacao-visualizar/${pagamento.Codigo}`" class="btn-sm btn-primary">Visualizar</Link> 
                        </td>
                        <td>
                          <a @click="detalhe_pagamento(pagamento.Codigo)" class="btn-sm btn-primary">Mais detalhe <i class="fas fa-eye"></i></a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </div>

    <div class="modal fade" id="modal-xl" >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Detalhes do Pagamento</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            
            <table  id="carregarTabelaEstudantes" style="width: 100%" class="table-sm table_estudantes table-bordered table-striped table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl">
              <tbody>
                <tr>
                  <th colspan="6" class="bg-light">Dados do Estudante</th>
                </tr>
                <tr>
                  <td colspan="1" class="text-left"> Matricula:</td>
                  <td colspan="5" class="text-left"> <strong> 2222</strong> </td>
                </tr>
                <tr>
                  <td colspan="1" class="text-left"> Nome:</td>
                  <td colspan="5"> <strong>{{ estudante.Nome_Completo }}</strong> </td>
                </tr>
                <tr>
                  <td colspan="1" class="text-left"> Curso:</td>
                  <td colspan="5"> <strong>{{ estudante.curso }}</strong> </td>
                </tr>

                <tr>
                  <td colspan="1" class="text-left"> Campus: </td>
                  <td colspan="5"> <strong>{{ estudante.polo }}</strong> </td>
                </tr>

                <tr>
                  <td colspan="1" class="text-left"> Contacto: </td>
                  <td colspan="5"> <strong>{{ estudante.Contactos_Telefonicos }}</strong> </td>
                </tr>

                <tr>
                  <th colspan="6" class="bg-light">Detalhe dos Pagamentos</th>
                </tr>

                <tr>
                  <td colspan="1" class="text-right"> Codigo:</td>
                  <td colspan="1"> <strong>{{ codigo }}</strong> </td>

                  <td colspan="1" class="text-right"> Valor Depositado:</td>
                  <td colspan="1"> <strong>AOA {{ formatValor(valor) }}</strong> </td>

                  <td colspan="1" class="text-right"> Cod Comprovativo:</td>
                  <td colspan="1"> <strong>{{ comprovativo1 }}</strong> </td>
                </tr>


                <tr>
                  <th colspan="6" class="bg-light">Itens da Factura</th>
                </tr>

                <tr>
                  <th colspan="3">Descrição</th>
                  <th>Valor do Serviço</th>
                  <th>Ano Lectivo</th>
                  <th>Mês</th>
                </tr>

                <tr :key="item" v-for="item in data">
                  <td colspan="3">{{ item.Descricao }}</td>
                  <td>{{ item.Valor_Pago }}</td>
                  <td>{{ item.Ano }}</td>
                  <td>{{ item.Mes }}</td>
                </tr>

              </tbody>
            </table>

          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    
  </MainLayouts>
</template>

<script>  
  import { Link } from "@inertiajs/inertia-vue3"
  import { sweetSuccess, sweetError } from '../../components/Alert'
  
  export default{
    props: [
      "pagamentos",
      "estudantes",
      "filtros",
    ],
    components:{
      Link,
      sweetSuccess,
      sweetError
    },
    data() {
      return{
        numero_comprovativo: null,
        pagamentos: [],
        estudante: [],
        data: [],
        
        codigo: null,
        valor: null,
        comprovativo1: null,
        comprovativo2: null,
      }
    },
    
    methods: {
      buscar_comprovotivo: function(){
        this.$Progress.start();
        axios.get('/consultar/comprovativo-operacao', { params: { numero_comprovativo: this.numero_comprovativo } })
        .then((response)=>{
          this.pagamentos = response.data.pagamentos;
          
          this.$Progress.finish();
        })
        .catch((errors)=>{
            console.log(errors)
            this.$Progress.fail();
        })
      }, 
      
      detalhe_pagamento: function(value){
        this.$Progress.start();
        axios.get('/consultar/operacao-visualizar/'+value).then((response) => {
          this.estudante = response.data.estudante;
      
          this.codigo = response.data.dados.Codigo
          this.valor = response.data.dados.valor_depositado
          this.comprovativo1 = response.data.dados.N_Operacao_Bancaria
          this.comprovativo2 = response.data.dados.N_Operacao_Bancaria2
    
          $("#modal-xl").modal("show")
    
          this.data = response.data.items;
          
          this.$Progress.finish();
    
        })
        .catch(function(error){
          console.log(error)
          this.$Progress.fail(); 
        });
      },
    
      formatValor: (atual) => {
        const valorFormatado = Intl.NumberFormat("pt-br", {
          style: "currency",
          currency: "AOA",
        }).format(atual);
        return valorFormatado;
      }
    }
  }

</script>
