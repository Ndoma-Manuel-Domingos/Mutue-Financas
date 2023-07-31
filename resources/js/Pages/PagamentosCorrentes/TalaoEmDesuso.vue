<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-uppercase">Talão Em Desuso</h1>
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
        <div class="card">
          <div class="card-header">
            <Link :href="route('mf.adicionar-talao-desuso')"  class="btn btn-primary btn-sm">
              Adicionar Talão
            </Link>
          </div>
          <div class="card-body bg-light">
            <div class="row">

              <div class="col-12 col-md-3">
                <div class="form-group">
                  <label>Operador</label>
                  <select name="" id="" v-model="operador" class="form-control" @change="search">
                    <option value="">Todos Operadores</option>
                    <option :value="utilizador.utilizadores.pk_utilizador" v-for="utilizador in utilizadores" :key="utilizador">{{ utilizador.utilizadores.nome }} </option>
                  </select>
                </div>
              </div>


              <div class="col-12 col-md-3">
                <div class="form-group">
                  <label>Data Inicio:</label>
                  <input v-model="data_inicio" @keyup.enter="search" type="date" class="form-control"/>
                </div>
              </div>

              <div class="col-12 col-md-3">
                <div class="form-group">
                  <label>Data Final:</label>
                  <input v-model="data_final" @keyup.enter="search" type="date" class="form-control"/>
                </div>
              </div>

            </div>
          </div>

          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover text-nowrap">
                <thead>
                  <tr>
                    <th>Estudante</th>
                    <th>Nº da Operação Bancaria-1</th>
                    <th>Nº da Operação Bancaria-2</th>
                    <th>Valor Depositado</th>
                    <th>Utilizador</th>
                    <th>Data</th>
                    <th>Anexo</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="pagamento in items.data" :key="pagamento.Codigo">
                    <td>{{ pagamento.preinscricao.Nome_Completo ?? '===' }}</td>
                    <td>{{ pagamento.N_Operacao_Bancaria ?? '===' }}</td>
                    <td>{{ pagamento.N_Operacao_Bancaria2 ?? '===' }}</td>
                    <td>AOA - {{ pagamento.valor_depositado ?? '===' }}</td>
                    <td>{{ pagamento.utilizadores.nome ?? '===' }}</td>
                    <td>{{ pagamento.Data ?? '===' }}</td>
                    <td>{{ pagamento.Data ?? '===' }}</td>
                <!-- <td colspan="2"><a target="_blink" :href="'https://mutue.ao/storage/documentos/'+dados_pagamentos.img2">Visualizar Comprovativo</a></td> -->
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="card-footer">
            <Link href="" class="text-secondary">
              TOTAL REGISTROS: {{ props.items.total }}
            </Link>
            <Paginacao
              :links="props.items.links"
              :prev="props.items.prev_page_url"
              :next="props.items.next_page_url"
            />
          </div>

        </div>
      </div>
    </div>
  </MainLayouts>
</template>


<script setup>
  import { ref,  defineProps, getCurrentInstance} from 'vue'
  import { Inertia } from '@inertiajs/inertia'
  import { Link } from "@inertiajs/inertia-vue3"
  import Paginacao from '../../Shared/Paginacao.vue'

  const props = defineProps({
    items: Object,
    utilizadores: Array,
    total: {
      type: String
    },
    filtros: {
      type: Object
    },
  })

  const internalInstance = getCurrentInstance();

  const data_inicio = ref(props.filtros.data_inicio ?? new Date().toISOString().substr(0, 10));
  const data_final = ref(props.filtros.data_final ?? new Date().toISOString().substr(0, 10));
  const operador = ref(props.filtros.operador ?? null);


  const formatValor = (atual) => {
    const valorFormatado = Intl.NumberFormat("pt-br", {
      style: "currency",
      currency: "AOA",
    }).format(atual);
    return valorFormatado;
  }

  const search = _.throttle(function()
  {
    Inertia.get(route('mf.talao-desuso'),
      {operador: operador.value, data_inicio: data_inicio.value, data_final: data_final.value},
      {
        onBefore: () => {
          internalInstance.appContext.config.globalProperties.$Progress.start();
        },
        onSuccess: () => {
          internalInstance.appContext.config.globalProperties.$Progress.finish();
        }
      },
      {
        replace: true,
        preserveState: false
      }
    )
  }, 500)

  const imprimirPDF = () => {
    window.open("/talao/desuso-pdf/"+operador.value+"/"+data_inicio.value+"/"+data_final.value, "_blank");
  }

  const imprimirEXCEL = () => {
    window.open("/talao/desuso-excel/"+operador.value+"/"+data_inicio.value+"/"+data_final.value, "_blank");
  }

</script>
