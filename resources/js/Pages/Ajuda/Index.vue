<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0 text-uppercase">Ajuda</h4>
                    </div>

                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-12" id="accordion">
                    <div class="card card-primary card-outline">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseOne">
                            <div class="card-header">
                                <h4 class="card-title w-100">
                                    1. Instituições Sem Renuncia
                                </h4>
                            </div>
                        </a>
                        <div id="collapseOne" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.
                            </div>
                        </div>
                    </div>
                    <div class="card card-primary card-outline">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                            <div class="card-header">
                                <h4 class="card-title w-100">
                                    2. Instituições Com Renuncia
                                </h4>
                            </div>
                        </a>
                        <div id="collapseTwo" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur
                                ridiculus
                                mus.
                            </div>
                        </div>
                    </div>
                    <div class="card card-primary card-outline">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseThree">
                            <div class="card-header">
                                <h4 class="card-title w-100">
                                    3. Estudante Com Crédito Educacional
                                </h4>
                            </div>
                        </a>
                        <div id="collapseThree" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa
                                quis
                                enim.
                            </div>
                        </div>
                    </div>
                    <div class="card card-warning card-outline">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseFour">
                            <div class="card-header">
                                <h4 class="card-title w-100">
                                    4. Estudante Com Renuncia de Valor
                                </h4>
                            </div>
                        </a>
                        <div id="collapseFour" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.
                            </div>
                        </div>
                    </div>
                    <div class="card card-warning card-outline">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseFive">
                            <div class="card-header">
                                <h4 class="card-title w-100">
                                    5. Estudante Isentos
                                </h4>
                            </div>
                        </a>
                        <div id="collapseFive" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede
                                mollis pretium.
                            </div>
                        </div>
                    </div>
                    <div class="card card-warning card-outline">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseSix">
                            <div class="card-header">
                                <h4 class="card-title w-100">
                                    6. Estudante Finalistas Inactivos
                                </h4>
                            </div>
                        </a>
                        <div id="collapseSix" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend
                                tellus.
                            </div>
                        </div>
                    </div>
                    <!-- <div class="card card-danger card-outline">
                    <a class="d-block w-100" data-toggle="collapse" href="#collapseSeven">
                        <div class="card-header">
                            <h4 class="card-title w-100">
                                7. Aenean leo ligula
                            </h4>
                        </div>
                    </a>
                    <div id="collapseSeven" class="collapse" data-parent="#accordion">
                        <div class="card-body">
                            Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim.
                        </div>
                    </div>
                </div> -->
                    <!-- <div class="card card-danger card-outline">
                    <a class="d-block w-100" data-toggle="collapse" href="#collapseEight">
                        <div class="card-header">
                            <h4 class="card-title w-100">
                                8. Aliquam lorem ante
                            </h4>
                        </div>
                    </a>
                    <div id="collapseEight" class="collapse" data-parent="#accordion">
                        <div class="card-body">
                            Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut
                            metus varius laoreet.
                        </div>
                    </div>
                </div> -->
                    <div class="card card-danger card-outline">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseNine">
                            <div class="card-header">
                                <h4 class="card-title w-100">
                                    9.Manual de Utilizador
                                </h4>
                            </div>
                        </a>
                        <div id="collapseNine" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper
                                ultricies nisi.

                                <a @click="downloadPdf" class="btn btn-danger btn-sm mr-2"><i
                                        class="fas fa-file-pdf"></i>Baixar Agora </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </MainLayouts>
</template>

<script>
import { Method } from '@inertiajs/inertia';


export default {

    methods: {
    downloadPdf() {
      window.location = '/baixar-manual'
    },
  },
}
</script>
