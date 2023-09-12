<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Estatística</h1>
                    </div>
                    <div class="col-sm-6">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">

                    <div class="card-header row">

                        <h6 class="col-10"> Ano Lectivo:  <span class="badge badge-pill badge-info">{{ ano_lectivo }}</span> </h6>
                        <select type="text" class="form-control form-control-sm  col-2" v-model="ano_lectivo_selecionado" @change="search">
                            <option :value="ano.Codigo" v-for="ano in anos_lectivos" :key="ano.Codigo">
                                {{ ano.Designacao }}
                            </option>
                        </select>
                    </div>
                    <div class="card-body">
                        <div class="content">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-3 col-sm-6 col-12">
                                        <div class="info-box" style="background-color: #336699;color: #ffffff;">
                                            <span class="info-box-icon"><i class="fas fa fa-graduation-cap"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">BOLSA ATRIBUIDA</span>
                                                <span class="info-box-number">{{ totalBolsa }}</span>

                                                <div class="progress">
                                                    <div class="progress-bar" style="width: 90%"></div>
                                                </div>
                                                <span class="progress-description">
                                                    Total de bolseiros
                                                </span>
                                            </div>

                                        </div>
                                        <!-- /.info-box -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-3 col-sm-6 col-12">
                                        <div class="info-box" style="background-color: #336699;color: #ffffff;">
                                            <span class="info-box-icon"><i class="fas fa fa-university"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">INSTITUIÇÕES</span>
                                                <span class="info-box-number">{{ totalinstituicacao }}</span>

                                                <div class="progress">
                                                    <div class="progress-bar" style="width: 40%"></div>
                                                </div>
                                                <span class="progress-description">
                                                    Total de Instituições

                                                </span>
                                            </div>
                                            <!-- /.info-box-content -->
                                        </div>
                                        <!-- /.info-box -->
                                    </div>
                                    <!-- /.col -->

                                    <!-- /.col -->
                                    <div class="col-md-3 col-sm-6 col-12">
                                        <div class="info-box" style="background-color: #336699;color: #ffffff;">
                                            <span class="info-box-icon"><i class="fas fa fa-archive"></i></span>

                                            <div class="info-box-content">
                                                <span class="info-box-text">BOLSAS INACTIVAS</span>
                                                <span class="info-box-number">{{ totalBolsainativa }}</span>

                                                <div class="progress">
                                                    <div class="progress-bar" style="width: 70%"></div>
                                                </div>
                                                <span class="progress-description">
                                                    Total Inativas
                                                </span>
                                            </div>
                                            <!-- /.info-box-content -->
                                        </div>
                                        <!-- /.info-box -->
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12">
                                        <div class="info-box" style="background-color: #336699;color: #ffffff;">
                                            <span class="info-box-icon"><i class="fas fa fa-archive"></i></span>

                                            <div class="info-box-content">
                                                <span class="info-box-text">BOLSAS ACTIVAS</span>
                                                <span class="info-box-number">{{ totalBolsaActiva }}</span>

                                                <div class="progress">
                                                    <div class="progress-bar" style="width: 70%"></div>
                                                </div>
                                                <span class="progress-description">
                                                    Total Activas
                                                </span>
                                            </div>
                                            <!-- /.info-box-content -->
                                        </div>
                                        <!-- /.info-box -->
                                    </div>
                                    <!-- /.col -->
                                </div>

                                <div class="row mt-3">
                                    <div class="col-12 col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                Gráfico estatístico
                                            </div>
                                            <div class="card-body">
                                                <div ref="grafico2" style="height: 300px;width: 100%;"></div>
                                            </div>
                                            <div class="card-footer">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                Gráfico estatístico
                                            </div>
                                            <div class="card-body">
                                                <div ref="line" style="height: 300px;width: 100%;"></div>
                                            </div>
                                            <div class="card-footer">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.row -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </MainLayouts>
</template>

<script setup>

import { ref, onMounted, defineProps, getCurrentInstance } from "vue";
import * as echarts from "echarts";
import { Inertia } from "@inertiajs/inertia";
const props = defineProps({
    totalBolsa: Array,
    totalinstituicacao: Array,
    totalBolsainativa: Array,
    totalBolsaActiva: Array,
    TipoBolsa: Array,
    anos_lectivos: Array,
    ano_lectivo:String,
    filtros: {
         type: Object,
     },


});



const ano_lectivo_selecionado = ref(props.filtros.ano_lectivo ?? 18);

const internalInstance = getCurrentInstance();
const search = function () {



    Inertia.get(
        route("mf.estatistica"),
        {

             ano_lectivo:ano_lectivo_selecionado.value,
        },
        {
            onBefore: () => {

                internalInstance.appContext.config.globalProperties.$Progress.start();
            },
            onSuccess: () => {
                internalInstance.appContext.config.globalProperties.$Progress.finish();
            },
        },
        {
            replace: false,
            preserveState: false,
        }
    );
};
const line = ref(null)
const grafico2 = ref(null)
//
onMounted(() => {

    const dadosGraficos = [
        { value: props.totalBolsa, name: 'Total de Bolseiros' },
        { value: props.totalBolsaActiva, name: 'Bolseiros Activos' },
        { value: props.totalBolsainativa, name: 'Bolseiros Inactivos' },

    ]

    const dadosBarraInstuicao = [
        { value: props.totalinstituicacao },


    ]

    const dadosBarraTipoBosla = [
        { value: props.TipoBolsa },


    ]

    if (line.value !== null) {
        const echart = echarts.init(line.value)
        echart.setOption({
            title: {
                text: 'Instituições/Tipo de Bolsa',
                subtext: 'Geral',
                left: 'center'
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                orient: 'vertical',
                left: 'left',

            },
            toolbox: {
                show: true,
                feature: {
                     dataView: { show: true, readOnly: false },
                    magicType: { show: true, type: ['line', 'bar'] },
                    restore: { show: true },
                    saveAsImage: { show: true }
                }
            },

            calculable: true,
            xAxis: [
                {
                    type: 'category',
                    // prettier-ignore
                    data: [2023, 2022, 2021, 2020, 2019, 2019],
                }
            ],
            yAxis: [
                {
                    type: 'value'
                }
            ],

            series: [
                {
                     name: 'Instituições',
                    type: 'bar',
                    data: dadosBarraInstuicao,
                    markPoint: {
                        data: [
                            { type: 'max', name: 'Max' },
                            { type: 'min', name: 'Min' }
                        ]
                    },

                },




                {
                    name: 'Tipo de Bolsa',
                    type: 'bar',
                    data: dadosBarraTipoBosla,


                    markPoint: {
                        data: [
                            { type: 'max', name: 'Max' },
                            { type: 'min', name: 'Min' }
                        ]
                    },
                    markLine: {
                        data: [{ type: 'average', name: 'Avg' }]
                    }
                }
            ]




        });
    }


    if (grafico2.value !== null) {
        const echart = echarts.init(grafico2.value)
        echart.setOption({
            title: {
                text: 'Estatísticas Geral ',
                subtext: 'BOLSEIROS',
                left: 'center'
            },

            tooltip: {

                trigger: 'item'
            },

            legend: {
                orient: 'vertical',
                left: 'left'
            },
            series: [
                {
                    name: 'Dados de Bolseiro',
                    type: 'pie',
                    radius: '58%',
                    data: dadosGraficos,

                    emphasis: {
                        itemStyle: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }
            ]


        })
    }
})
</script>
