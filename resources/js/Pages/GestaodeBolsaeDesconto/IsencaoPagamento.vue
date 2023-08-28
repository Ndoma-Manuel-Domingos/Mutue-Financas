<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-uppercase">Isenção de Pagamento</h1>
                    </div>
                    <div class="col-sm-6">
                        <a href="" class="btn btn-success btn-sm float-sm-right"><i class="fas fa-file-excel"></i>
                            Excel</a>
                            <a @click="imprimirPDF" href="" class="btn btn-danger btn-sm float-sm-right mr-2"><i class="fas fa-file-pdf"></i> PDF</a>
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
                                            <select @change="search" v-model="AnoLectivo" class="form-control form-control-sm  ">
                                                <option value=""> TODOS</option>
                                                <option :value="ano.Codigo" v-for="ano in props.anolectivos"
                                                    :key="ano.Codigo">
                                                    {{ ano.Designacao }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-2">
                                    <div class="form-group">
                                        <label for="" class="text-secondary">Instituição</label>
                                        <div class="input-group input-group">
                                            <select @change="search" v-model="Instituicao" class="form-control form-control-sm " >
                                                <option value=""> TODAS</option>
                                                <option :value="instituicao.codigo"
                                                    v-for="instituicao in props.instituicao" :key="instituicao.codigo">
                                                    {{ instituicao.Instituicao}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-2">
                                    <div class="form-group">
                                        <label for="" class="text-secondary">Tipo de Bolsa</label>
                                        <div class="input-group input-group">
                                            <select @change="search" v-model="TipoBolsa" class="form-control form-control-sm ">
                                                <option>TODAS</option>
                                                <option :value="tipoBolsa.codigo" v-for="tipoBolsa in props.bolsa" :key="tipoBolsa.codigo">
                                                    {{ tipoBolsa.designacao }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-2">
                                    <div class="form-group">
                                        <label for="" class="text-secondary">Serviço</label>
                                        <div class="input-group input-group">
                                            <select @change="search" v-model="Servico" class="form-control form-control-sm ">
                                                <option value=""> TODAS</option>
                                                <option :value="tipoServico.Codigo" v-for="tipoServico in props.tipoServico"
                                                    :key="tipoServico.Codigo">
                                                    {{ tipoServico.Descricao }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label for="" class="text-secondary">Motivo</label>
                                        <div class="input-group input-group">
                                            <select @change="search" v-model="Motivo" class="form-control form-control-sm ">
                                                <option value=""> TODAS</option>
                                                <option :value="motivoIsencao.Codigo" v-for="motivoIsencao in props.motivoIsencao"
                                                    :key="motivoIsencao.Codigo">
                                                    {{ motivoIsencao.Descricao }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h3 class="card-title"></h3>
                                <div class="card-tools">
                                    <div class="input-group input-group-md" style="width: 150px">
                                        <input type="text" name="table_search" class="form-control form-control-sm  float-right" placeholder="Search" />
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table  id="carregarTabelaEstudantes" style="width: 100%" class="table-sm table_estudantes table-bordered table-striped table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl">
                                        <thead>
                                            <tr>
                                                <th>Referencia</th>
                                                <th>Instituição</th>
                                                <th>Serviço</th>
                                                <th>Ano Lectivo</th>
                                                <th>Utilizador</th>
                                                <th>Data</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="insecaoPamento in props.insecaoPamento.data" :key="insecaoPamento.insecaoPamento">
                                                <td>{{insecaoPamento.referencia}}</td>
                                                <td>{{insecaoPamento.Instituicao}}</td>
                                                <td>{{insecaoPamento.servico}}</td>
                                                <td>{{insecaoPamento.Anolectivo}}</td>
                                                <td>{{insecaoPamento.usuario}}</td>
                                                <td>{{insecaoPamento.data}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="card-footer">
                                <Link href="" class="text-secondary">
                                TOTAL REGISTROS: {{ props.insecaoPamento.total }}
                                </Link>
                                <Paginacao
                                    :links="props.insecaoPamento.links"
                                    :prev="props.insecaoPamento.prev_page_url"
                                    :next="props.insecaoPamento.next_page_url"
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

    import { ref, defineProps, getCurrentInstance } from "vue";
    import { Inertia } from "@inertiajs/inertia";
    import { Link } from "@inertiajs/inertia-vue3";
    import Paginacao from "../../Shared/Paginacao.vue";


    const props = defineProps({
        anolectivos: Array,
        bolsa: Array,
        tipoBolsa: Array,
        tipoServico: Array,
        instituicao: Array,
        motivoIsencao:Array,
        filters: Object,
        insecaoPamento: {
            type: Object,
        },

    });

    const internalInstance = getCurrentInstance();

    const AnoLectivo = ref(props.filters.AnoLectivo ?? null);
    const Instituicao = ref(props.filters.Instituicao ?? null);
    const TipoBolsa = ref(props.filters.TipoBolsa ?? null);
    const Motivo = ref(props.filters.Motivo ?? null);
    const Servico= ref(props.filters.Servico?? null);


    const search = function () {
        Inertia.get(route("mf.isencaodePagamentos"),
            {
                AnoLectivo: AnoLectivo.value, Instituicao:Instituicao.value, TipoBolsa: TipoBolsa.value, Motivo: Motivo.value, Servico:Servico.value,
            },
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
        );
    };

    const imprimirPDF = () => {
        window.open("/listagem/isencaodePagamentos/pdf-imprimir/"+AnoLectivo.value+"/"+Instituicao.value+"/",+TipoBolsa.value+"/"+"_blank");
    }


</script>
