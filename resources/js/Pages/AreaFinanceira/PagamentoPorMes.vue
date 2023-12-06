<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0 text-uppercase">Pagamentos do Mês de {{ mes_selecionado }}</h4>
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
            <div class="card mb-4">
              
              <div class="card-header bg-light">
                <h5>
                  <span class="float-left"
                    >TOTAL REGISTROS: {{ props.facturas.total }}</span
                  >
                  
                  <span class="float-right"
                    >VALOR TOTAL: {{ valor_total_facturas }}</span
                  >
                </h5>
              </div>
            
              <div class="card-body">
                <div class="table-responsive">
                  <table id="carregarTabelaEstudantes" style="width: 100%" class="table-sm table_estudantes table-bordered table-striped table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl">
                    <thead>
                      <tr>
                        <th>Matricula</th>
                        <th>Nome</th>
                        <th>Faculdade</th>
                        <th>Curso</th>
                        <th>Turno</th>
                        <th>Valor</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="factura in props.facturas.data" :key="factura.matricula">
                        <td><a :href="`/estudantes/visualizar-perfil/${factura.matricula}`" >{{ factura.matricula }}</a></td>
                        <td>{{ factura.aluno }}</td>
                        <td>{{ factura.faculdade }}</td>
                        <td>{{ factura.curso }}</td>
                        <td>{{ factura.turno }}</td>
                        <td>{{ formatValor(factura.valores) }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="card-footer">
                <Link href="" class="text-secondary">
                  TOTAL REGISTROS: {{ props.facturas.total }}
                </Link>
                <Paginacao 
                  :links="props.facturas.links"
                  :prev="props.facturas.prev_page_url"
                  :next="props.facturas.next_page_url"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

 
  </MainLayouts>
</template>
  

<script setup>
  import { ref, defineProps, getCurrentInstance } from 'vue'
  import { Link } from '@inertiajs/inertia-vue3'
  import Paginacao from '../../Shared/Paginacao.vue'

  const props = defineProps({
    facturas: {
      type: Object
    },
    valor_total_facturas: {
      type: String
    },
    mes_selecionado: {
      type: String,
    },
    mes_temp_id: {
      type: String,
    }
  })

  const internalInstance = getCurrentInstance();
  
  
  const formatValor = function(atual){
    const valorFormatado = Intl.NumberFormat("pt-br", {
        style: "currency",
        currency: "AOA",
    }).format(atual);

    return valorFormatado;
  }
              

  const imprimirPDF = () => {
    window.open("/pagamentos/propinas-por-mes/pdf-imprimir/"+props.mes_temp_id, "_blank");
  }

  const imprimirEXCEL = () => {
    window.open("/pagamentos/propinas-por-mes/excel-imprimir/"+props.mes_temp_id, "_blank");
  }
</script>
