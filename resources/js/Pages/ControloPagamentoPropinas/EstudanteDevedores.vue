<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-uppercase">Estudantes Devedores</h1>
                    </div>
                    <div class="col-sm-6">
                        <a @click="imprimirPDF" class="btn btn-danger btn-sm float-sm-right mr-2"><i
                                class="fas fa-file-pdf"></i> PDF</a>
                        <a @click="imprimirEXCEL" class="btn btn-success btn-sm float-sm-right mr-2"><i
                                class="fas fa-file-excel"></i> Excel</a>
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

                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <label>Ano Lectivo</label>
                                            <div class="input-group">
                                                <select @change="search" v-model="searchAnoLectivo" class="form-control">
                                                    <option :value="ano.Codigo" v-for="ano in anolectivos"
                                                        :key="ano.Codigo">{{ ano.Designacao }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>Mês/Parcela</label>
                                            <div class="input-group">
                                                <select @change="search" v-model="searchMes" class="form-control">
                                                    <option :value="mes.id" v-for="mes in mesTemps" :key="mes.id">{{
                                                        mes.designacao }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>Faculdade</label>
                                            <div class="input-group">
                                                <select @change="search" v-model="searchFaculdade" class="form-control">
                                                    <option value="">TODAS</option>
                                                    <option :value="faculdade.codigo" v-for="faculdade in faculdades"
                                                        :key="faculdade.codigo">{{ faculdade.designacao }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <label>Curso</label>
                                            <div class="input-group">
                                                <select @change="search" v-model="searchCurso" class="form-control">
                                                    <option value="">TODOS</option>
                                                    <option :value="curso.Codigo" v-for="curso in cursos"
                                                        :key="curso.Codigo">{{ curso.Designacao }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <label>Turno</label>
                                            <div class="input-group">
                                                <select @change="search" v-model="searchTurno" class="form-control">
                                                    <option value="">TODOS</option>
                                                    <option :value="turno.Codigo" v-for="turno in turnos"
                                                        :key="turno.Codigo">{{ turno.Designacao }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <!-- <button type="submit" class="btn btn-primary">
                  <i class="fa fa-search"></i>  Listar a busca
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
                                                <th>Nº Matricula</th>
                                                <th>Nome</th>
                                                <th>Faculdade</th>
                                                <th>Curso</th>
                                                <th>Turno</th>
                                                <th>Mês/Parcela</th>
                                                <th width="100px">Ver Perfil</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="factura in props.facturas.data" :key="factura.matricula">
                                                <td>{{ factura.matricula }}</td>
                                                <td>{{ factura.aluno }}</td>
                                                <td>{{ factura.faculdade }}</td>
                                                <td>{{ factura.curso }}</td>
                                                <td>{{ factura.turno }}</td>
                                                <td>{{ factura.servico }}</td>
                                                <td>
                                                    <a :href="route('mf.estudante-visualizar-perfil', factura.matricula)"
                                                        class="btn-sm btn-primary">
                                                        <i class="fas fa-user-graduate"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="card-footer">
                                <Link href="" class="text-secondary">
                                TOTAL REGISTROS: {{ props.facturas.total }}
                                </Link>
                                <Paginacao :links="props.facturas.links" :prev="props.facturas.prev_page_url"
                                    :next="props.facturas.next_page_url" />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </MainLayouts>
</template>


<script setup>

import { ref, defineProps, onMounted, getCurrentInstance } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Link } from '@inertiajs/inertia-vue3'
import Paginacao from '../../Shared/Paginacao.vue'


const props = defineProps({
    anolectivos: Array,
    mesTemps: Array,
    turnos: Array,
    faculdades: Array,

    cursos: Array,
    facturas: {
        type: Object
    },
    filters: Object
})

const internalInstance = getCurrentInstance();
const searchAnoLectivo = ref(props.filters.searchAnoLectivo ?? null)
const searchMes = ref(props.filters.searchMes ?? null)
const searchFaculdade = ref(props.filters.searchFaculdade ?? null)
const searchCurso = ref(props.filters.searchCurso ?? null)
const searchTurno = ref(props.filters.searchTurno ?? null)


const search = function () {
    Inertia.get(route('mf.estudante-devedores'), {
        searchAnoLectivo: searchAnoLectivo.value,
        searchMes: searchMes.value,
        searchFaculdade: searchFaculdade.value,
        searchCurso: searchCurso.value,
        searchTurno: searchTurno.value
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
        })
}


const imprimirPDF = () => {
    window.open("/estudante/propina-pagar/pdf-imprimir/" + searchAnoLectivo.value + "/" + searchMes.value + "/" + searchFaculdade.value + "/" + searchCurso.value + "/" + searchTurno.value, "_blank");
}

const imprimirEXCEL = () => {
    window.open("/estudante/propina-pagar/excel-imprimir/" + searchAnoLectivo.value + "/" + searchMes.value + "/" + searchFaculdade.value + "/" + searchCurso.value + "/" + searchTurno.value, "_blank");
}

</script>
