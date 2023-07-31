<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-uppercase">Pagamento Por validar</h1>
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
            <div class="card">
              <div class="card-header bg-light"></div>
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-md-3">
                    <div class="form-group">
                      <label for="" class="text-secondary">Prestação</label>
                      <div class="input-group input-group">
                        <select
                          v-model="prestacao"
                          @change="search"
                          class="form-control form-control"
                          placeholder="Type your keywords here"
                        >
                          <option value="">TODAS</option>
                          <option :value="prestacao.id" v-for="prestacao in props.prestacoes" :key="prestacao.id">{{ prestacao.designacao }}</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 col-md-3">
                    <div class="form-group">
                      <label for="" class="text-secondary">
                        Forma de Pagamentos
                      </label>
                      <div class="input-group input-group">
                        <select
                          v-model="forma_pagamento"
                          @change="search"
                          class="form-control"
                          placeholder="Selecione a Forma de Pagamento"
                        >
                          <option value="">TODAS</option>
                          <option :value="forma.descricao" v-for="forma in props.formaPagamentos" :key="forma.Codigo">{{ forma.descricao }}</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 col-md-3">
                    <div class="form-group">
                      <label>Data Inicio:</label>
                      <input type="date" v-model="data_inicio" @keyup.enter="search" class="form-control"/>
                    </div>
                  </div>

                  <div class="col-12 col-md-3">
                    <div class="form-group">
                      <label>Data Final:</label>
                      <input type="date" v-model="data_final" @keyup.enter="search"  class="form-control"/>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <!-- <button type="submit" class="btn btn-primary">
                  <i class="fa fa-search"></i> Listar a busca
                </button> -->
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-header">
              </div>

              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>Matricula</th>
                        <th>Estudante</th>
                        <th>Factura</th>
                        <th>Recibo</th>
                        <th>Data Pagamento</th>
                        <th>Data Inserção no sistema</th>
                        <th>Valor Depositado</th>
                        <th>Prestação</th>
                        <th>Forma Pagamento</th>
                        <th width="100px">Acções</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="pagamento in props.items.data" :key="pagamento.Codigo">
                        <td>23523</td>
                        <td>{{ pagamento.Nome_Completo }}</td>
                        <td>{{ pagamento.codigo_factura }}</td>
                        <td>{{ pagamento.Codigo }}</td>
                        <td>{{ pagamento.DataBanco }}</td>
                        <td>{{ pagamento.Data }}</td>
                        <td>{{ formatValor(pagamento.valor_depositado)  }}</td>
                        <td>{{ pagamento.prestacao }}</td>
                        <td>{{ pagamento.forma_pagamento }}</td>
                        <td>
                          <a href="" class="btn btn-primary"><i class="fas fa-list"></i></a>
                        </td>
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

      </div>
    </div>
  </MainLayouts>
</template>


<script setup>

  import { Link } from "@inertiajs/inertia-vue3";
  import Paginacao from '../../Shared/Paginacao.vue'
  import { ref, defineProps, getCurrentInstance } from 'vue'
  import { Inertia } from '@inertiajs/inertia'

  const props = defineProps({
    items: Object,
    prestacoes: Array,
    formaPagamentos: Array,
    filtros: {
      type: Object
    },
  })


  const internalInstance = getCurrentInstance();

  const forma_pagamento = ref(props.filtros.forma_pagamento ?? null);
  const prestacao = ref(props.filtros.prestacao ?? null);
  const data_inicio = ref(props.filtros.data_inicio ?? new Date().toISOString().substr(0, 10));
  const data_final = ref(props.filtros.data_final ?? new Date().toISOString().substr(0, 10));

  const search = _.throttle(function()
  {
    Inertia.get(route('mf.pagamento-por-validar'),
      {prestacao: prestacao.value, forma_pagamento: forma_pagamento.value, data_inicio: data_inicio.value, data_final: data_final.value},
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

  const formatValor = (atual) => {
    const valorFormatado = Intl.NumberFormat("pt-br", {
      style: "currency",
      currency: "AOA",
    }).format(atual);
    return valorFormatado;
  }


  const imprimirPDF = () => {
    window.open("pagamento/porvalidar-pdf/"+prestacao.value+"/"+forma_pagamento.value+"/"+data_inicio.value+"/"+data_final.value, "_blank");
  }

  const imprimirEXCEL = () => {
    window.open("/pagamento/porvalidar-excel/"+prestacao.value+"/"+forma_pagamento.value+"/"+data_inicio.value+"/"+data_final.value, "_blank");
  }



</script>
