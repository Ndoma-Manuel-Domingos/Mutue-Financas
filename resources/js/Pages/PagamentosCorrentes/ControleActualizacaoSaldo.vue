<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-uppercase">Controle Actualização de Saldo</h1>
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
          </div>
          <div class="card-body bg-light">
            <div class="row">

              <div class="col-12 col-md-3">
                <div class="form-group">
                  <label>Operador</label>
                  <div
                    class="input-group date"
                    id="reservationdate"
                    data-target-input="nearest"
                  >
                  <select
                    v-model="operador"
                    @change="search"
                    class="form-control form-control-sm "
                    >
                    <option value="">Todos Operadores</option>
                    <option :value="utilizador.utilizadores.pk_utilizador" v-for="utilizador in props.utilizadores" :key="utilizador">{{ utilizador.utilizadores.nome }} </option>
                  </select>
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-4">
                <div class="form-group">
                  <label>Data Inicio:</label>
                  <input type="date"    @change="search"  v-model="data_inicio" class="form-control form-control-sm "/>
                </div>
              </div>

              <div class="col-12 col-md-4">
                <div class="form-group">
                  <label>Data Final:</label>
                  <input type="date"    @change="search"  v-model="data_final" class="form-control form-control-sm " />
                </div>
              </div>

            </div>
          </div>

          <div class="card-body">
            <div class="table-responsive">
              <table id="carregarTabelaEstudantes" style="width: 100%" class="table-sm table_estudantes table-bordered table-striped table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl">
                <thead>
                  <tr>
                    <th>Estudante</th>
                    <th>Data Actualização</th>
                    <th>Saldo Anterior</th>
                    <th>Saldo Actual</th>
                    <th>Criado Por</th>
                    <th>Ver Mais</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="saldo in props.saldos.data" :key="saldo.id">
                    <td>{{ saldo.aluno }}</td>
                    <td>{{ saldo.data_actualizacao }}</td>
                    <td>AOA <strong>{{ saldo.saldo_anterior }}</strong></td>
                    <td>AOA <strong>{{ saldo.saldo_actual }}</strong></td>
                    <td>{{ nome(saldo.nome) }}</td>
                    <td>
                      <a href="" @click="mostrar_detalhe(12)" class="btn-sm btn-primary">
                        Ver Mais
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="card-footer">
            <Paginacao
                :links="props.saldos.links"
                :prev="props.saldos.prev_page_url"
                :next="props.saldos.next_page_url"
            />
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-xl" >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Detalhes</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table  id="carregarTabelaEstudantes" style="width: 100%" class="table-sm table_estudantes table-bordered table-striped table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl">
                <tbody>
                  <tr>
                    <th colspan="6" class="bg-light">Dados</th>
                  </tr>
                  <tr>
                    <td colspan="1" class="text-left">Observação:</td>
                    <td colspan="5" class="text-left"> <strong> 2222</strong> </td>
                  </tr>
                  <tr>
                    <td colspan="1" class="text-left">Anexo:</td>
                    <td colspan="5"> <strong>asfasf</strong> </td>
                  </tr>
                </tbody>
              </table>
            </div>
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



<script setup>
  import { Link } from "@inertiajs/inertia-vue3"
  import Paginacao from "../../Shared/Paginacao.vue"
  import { ref, defineProps, getCurrentInstance } from 'vue'
  import { Inertia } from '@inertiajs/inertia'

  const props = defineProps({
    saldos: Object,
    utilizadores: Array,
    filtros: {
      type: Object
    },
  })


  const internalInstance = getCurrentInstance();
  const operador = ref(props.filtros.operador ?? null);
  const data_inicio = ref(props.filtros.data_inicio ?? new Date().toISOString().substr(0, 10));
  const data_final = ref(props.filtros.data_final ?? new Date().toISOString().substr(0, 10));

  const nome = (string) =>{
    let result = string.replace("\"", " ");
    let result2 = result.replace("\"", " ");
    return result2;
  }

  const search = _.throttle(function()
  {
    Inertia.get(route('mf.controle-actualizacao-saldo'),
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
        replace: false,
        preserveState: false
      }
    )
  }, 500)

  const mostrar_detalhe = (value) => {
    alert(value)
  }

  const imprimirPDF = () => {
    window.open("/consultar/actualizacao-saldo-pdf/"+operador.value+"/"+data_inicio.value+"/"+data_final.value, "_blank");
  }

  const imprimirEXCEL = () => {
    window.open("/consultar/actualizacao-saldo-excel/"+operador.value+"/"+data_inicio.value+"/"+data_final.value, "_blank");
  }


</script>
