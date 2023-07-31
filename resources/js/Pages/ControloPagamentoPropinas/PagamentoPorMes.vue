<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-uppercase">Pagamentos do Mês de {{ mes_selecionado }}</h1>
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
              <div class="card-header">
                <h5>Volar Total: {{ valor_total_facturas }} AOA</h5>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>Mês/Parcela</th>
                        <th>Nome</th>
                        <th>Faculdade</th>
                        <th>Curso</th>
                        <th>Turno</th>
                        <!-- <th width="100px">Detalhe</th> -->
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="factura in props.facturas.data" :key="factura.matricula">
                        <td>{{ factura.servico }}</td>
                        <td>{{ factura.aluno }}</td>
                        <td>{{ factura.faculdade }}</td>
                        <td>{{ factura.curso }}</td>
                        <td>{{ factura.turno }}</td>
                        <!-- <td>
                          <a :href="route('mf.estudante-visualizar-perfil', factura.matricula)" class="btn-sm btn-primary">
                            <i class="fas fa-info"></i> Detalhes do Pagamento
                          </a>
                        </td> -->
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

  const imprimirPDF = () => {
    window.open("/pagamentos/propinas-por-mes/pdf-imprimir/"+props.mes_temp_id, "_blank");
  }

  const imprimirEXCEL = () => {
    window.open("/pagamentos/propinas-por-mes/excel-imprimir/"+props.mes_temp_id, "_blank");
  }
</script>
