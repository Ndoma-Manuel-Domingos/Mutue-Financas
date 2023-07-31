<template>
    <body class="hold-transition login-page"
        style="background-image: url('images/video01.mp4');
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
        ">
        <AuthLayouts>

            <video autoplay muted loop id="myVideo">
              <source src="images/video01.mp4" type="video/mp4">
            </video>

            <div class="login-box">
                <div class="card card-outline">
                    <!-- <h1 class="text-center pt-3" style="color: #006699">
                        <i class="fas fa-chart-line"></i>
                    </h1>  -->
                    <div class="card-header text-center mb-4 py-4">
                        <img src="~admin-lte/dist/img/log1.png"  alt="MUTUE FINANÇA" class="elevation-0" style="opacity: 0.8;width: 200px;height: 100px;"/>
                    </div>

                    <div class="card-body">
                        <form @submit.prevent="submit">
                            <h6 class="text-center text-danger pb-3" v-if="form.errors.acesso">Acesso Registro</h6>
                            <div class="col-12 mb-3">
                                <div class="input-group">
                                    <input type="text" v-model="form.email" :class="{'is-invalid' : form.errors.email}" class="form-control" placeholder="Email" />
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-envelope"></span>
                                        </div>
                                    </div>
                                </div>
                                <span v-if="form.errors.email" class="login-box-msg text-danger" >{{ form.errors.email }}</span>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="input-group">
                                    <input type="password" v-model="form.password" :class="{'is-invalid' : form.errors.password}"  class="form-control" placeholder="Password" />
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-lock"></span>
                                        </div>
                                    </div>
                                </div>
                                <span v-if="form.errors.password" class="login-box-msg text-danger" >{{ form.errors.password }}</span>
                            </div>

                            <div class="col-12">
                                <div class="row mt-5">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success btn-block">
                                            Entrar
                                        </button>
                                    </div>
                                    <!-- <div class="col-8">
                                    </div>
                         -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AuthLayouts>
    </body>
</template>

<script>
    import AuthLayouts from "./Layouts/AuthLayouts.vue";

    export default {
        layout: AuthLayouts,
    };
</script>

<script setup>
    import { useForm } from "@inertiajs/inertia-vue3";
    import { getCurrentInstance } from 'vue'

    const form = useForm({
        email: "",
        password: ""
    })

    const internalInstance = getCurrentInstance();

    const submit = () => {
        form.post(route("mf.login.post"), {
            onBefore: () => {
                internalInstance.appContext.config.globalProperties.$Progress.start();
            },
            onSuccess: () => {
                internalInstance.appContext.config.globalProperties.$Progress.finish();
                location.reload();
            },
            onError: () => {
                internalInstance.appContext.config.globalProperties.$Progress.fail();
            }
        })
    }
</script>

<style>
/* Style the video: 100% width and height to cover the entire window */
    #myVideo {
      position: fixed;
      right: 0;
      bottom: 0;
      min-width: 100%;
      min-height: 100%;
    }
</style>

