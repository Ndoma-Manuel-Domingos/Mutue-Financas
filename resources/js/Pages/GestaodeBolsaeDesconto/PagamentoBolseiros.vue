<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-uppercase">Pagamento de Bolseiros</h1>
                    </div>
                    <div class="col-sm-6">
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <form @submit.prevent="submeterFormulario">
                    <div class="card card-light">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label for="" class="text-secondary">Ano Lectivo</label>
                                        <div class="input-group input-group">
                                            <select @change="search" v-model="form.anoLectivoBusca" class="form-control form-control-sm " :class="{ 'is-invalid': erroranoLectivoBusca }">
                                                <option value="">TODOS</option>
                                                <option :value="ano.Codigo" v-for="ano in props.anolectivos" :key="ano.Codigo">{{ ano.Designacao }}</option>
                                            </select>
                                        </div>
                                        <span class="text-danger d-block" v-if="erroranoLectivoBusca">{{ erroranoLectivoBusca }}</span>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label for="" class="text-secondary">Instituição</label>
                                        <div class="input-group input-group">
                                            <select @change="search" v-model="form.instituicaoBusca" class="form-control form-control-sm " :class="{ 'is-invalid': errorinstituicaoBusca }">
                                                <option value="">TODAS</option>
                                                <option :value="instituicao.codigo" v-for="instituicao in props.instituicoes" :key="instituicao.codigo">{{ instituicao.Instituicao }}</option>
                                            </select>
                                        </div>
                                        <span class="text-danger d-block" v-if="errorinstituicaoBusca">{{ errorinstituicaoBusca }}</span>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label for="" class="text-secondary">Forma de Pagamento</label>
                                        <div class="input-group input-group">
                                            <select v-model="form.forma_pagamento" class="form-control form-control-sm " :class="{ 'is-invalid': errorforma_pagamento }">
                                                <option value="">TODAS</option>
                                                <option :value="ano.Codigo" v-for="ano in props.forma_pagamentos" :key="ano.Codigo">{{ ano.descricao }}</option>
                                            </select>
                                        </div>
                                        <span class="text-danger d-block" v-if="errorforma_pagamento">{{ errorforma_pagamento }}</span>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label for="" class="text-secondary">Banco</label>
                                        <div class="input-group input-group">
                                            <select v-model="form.banco" class="form-control form-control-sm " :class="{ 'is-invalid': errorbanco }">
                                                <option value="">TODOS</option>
                                                <option :value="banco.pk_banco" v-for="banco in props.bancos" :key="banco.pk_banco">{{ banco.descricao }}</option>
                                            </select>
                                        </div>
                                        <span class="text-danger d-block" v-if="errorbanco">{{ errorbanco }}</span>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="" class="text-secondary">Selecione os meses a pagar</label>
                                        <div class="input-group input-group">
                                            <select v-model="form.mes" class="form-control form-control-sm " :class="{ 'is-invalid': errormes }">
                                                <option value="">TODOS</option>
                                                <option :value="mes.id" v-for="mes in props.mesTemps" :key="mes.id">{{ mes.designacao }}</option>
                                            </select>
                                        </div>
                                        <span class="text-danger d-block" v-if="errormes">{{ errormes }}</span>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Data do Banco</label>
                                        <input type="date" v-model="form.data_banco" class="form-control form-control-sm " :class="{ 'is-invalid': errordata_banco }"/>
                                        <span class="text-danger d-block" v-if="errordata_banco">{{ errordata_banco }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="far fa-save"></i> Aplicar
                            </button>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header " :class="{ 'bg-danger': errorestudante }">
                                <h3 class="card-title">
                                    <span :class="{ 'text-white': errorestudante }" v-if="errorestudante">Selecione um ou mais Estudantes na lista abaixo!</span>
                                </h3>
                                <div class="card-tools">
                                    <div class="input-group input-group-md" style="width: 150px">
                                        <input type="text" name="table_search" class="form-control form-control-sm  float-right" placeholder="Search" />
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
                                    <table  id="carregarTabelaEstudantes" style="width: 100%" class="table-sm table_estudantes table-bordered table-striped table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl">
                                        <thead>
                                            <tr>
                                                <th>Marcar</th>
                                                <th>Nº Matricula</th>
                                                <th>Nome Completo</th>
                                                <th>Curso</th>
                                                <th>Afectação</th>
                                                <th>Tipo de Bolsa</th>
                                                <th>Ano Lectivo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr :key="bolseiro.codigo" v-for="bolseiro in props.bolseiros.data">
                                                <td><input type="checkbox" :value="bolseiro.CodigoPreinscricao" @click="checked(bolseiro.CodigoPreinscricao)"></td>
                                                <td>{{ bolseiro.codigo }}</td>
                                                <td>{{ bolseiro.nome }}</td>
                                                <td>{{ bolseiro.curso }}</td>
                                                <td>{{ bolseiro.afectacao }}</td>
                                                <td>{{ bolseiro.bolsa }}</td>
                                                <td>{{ bolseiro.anoLectivo }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="card-footer">
                                <Link href="" class="text-secondary"> Total Registro: {{ props.bolseiros.total }}</Link>
                                <Paginacao
                                    :links="props.bolseiros.links"
                                    :prev="props.bolseiros.prev_page_url"
                                    :next="props.bolseiros.next_page_url"
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

    import { Link } from "@inertiajs/inertia-vue3"
    import { ref, defineProps, reactive,getCurrentInstance } from 'vue'
    import { Inertia } from '@inertiajs/inertia'
    import Paginacao from "../../Shared/Paginacao.vue";
    import { sweetSuccess, sweetError } from '../../components/Alert'

    const props = defineProps({
        anolectivos: Array,
        instituicoes: Array,
        forma_pagamentos: Array,
        bancos: Array,
        mesTemps: Array,
        filtros: Object,
        bolseiros: Object,
    });

    const internalInstance = getCurrentInstance();

    const estudantes = []

    const form = reactive({
        anoLectivoBusca: props.filtros.anoLectivoBusca ?? "",
        instituicaoBusca: props.filtros.instituicaoBusca ?? "",
        forma_pagamento: null,
        banco: null,
        mes: null,
        data_banco: null,
        estudante: null,
    })


    /**
     * errors
    */

    const erroranoLectivoBusca = ref("")
    const errorinstituicaoBusca = ref("")
    const errorforma_pagamento = ref("")
    const errorbanco = ref("")
    const errormes = ref("")
    const errordata_banco = ref("")
    const errorestudante = ref("")


    const search = function(){

        Inertia.get('/pagamentoBolseiros',
        {
            anoLectivoBusca: form.anoLectivoBusca,
            instituicaoBusca: form.instituicaoBusca,
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
        )
    }

    const checked = (codigo) => {
        estudantes.push(codigo)
    }

    const submeterFormulario = () => {
        form.estudante = estudantes

        Inertia.post('/pagamentoBolseiros', form, {
            onSuccess: (page) => {
                console.log(page)
                sweetSuccess("Instituição  cadastrado com sucesso")
                window.location.reload()
            },
            onError: (errors) => {
                console.log(errors)
                erroranoLectivoBusca.value = errors.anoLectivoBusca
                errorinstituicaoBusca.value = errors.instituicaoBusca
                errorforma_pagamento.value = errors.forma_pagamento
                errorbanco.value = errors.banco
                errormes.value = errors.mes
                errordata_banco.value = errors.data_banco
                errorestudante.value = errors.estudante
                sweetError("Ocorreu um erro ao tentar cadastrar a Instituição")
            }
        })
    }

</script>
