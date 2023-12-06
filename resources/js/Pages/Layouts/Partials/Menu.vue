<template>
  <nav class="mt-2">
    <ul
      class="nav nav-pills nav-sidebar flex-column"
      data-widget="treeview"
      role="menu"
      data-accordion="false"
    >
      <li class="nav-item"
        v-if="user.auth.can['VALIDAR PAGAMENTOS']"
      >
        <Link
          href="/dashboard"
          class="nav-link"
          :class="{ active: $page.component == 'Dashboard' }"
        >
          <i class="nav-icon fas fa-home"></i>
          <p>Dashboard</p>
        </Link>
      </li>

      <li class="nav-item" title="CONTROLO DE PAGAMENTO DE MENSALIDADES">
        <a
          href="#"
          class="nav-link"
          :class="{ active: $page.component.startsWith('AreaFinanceira/') }"
        >
          <i class="nav-icon fas fa-chart-line"></i>
          <p>
            Área Financeira
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item" v-if="user.auth.can['ESTUDANTES COM MENSALIDADES PAGAS']" title="ESTUDANTES COM MENSALIDADES A PAGAR">
            <Link
              href="/estudantes/propina-pagar"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'AreaFinanceira/EstudantePropinasPaga',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Estud. Mensalidades Paga</p>
            </Link>
          </li>

          <li class="nav-item" title="ESTUDANTES DEVEDORES" v-if="user.auth.can['ESTUDANTES DEVEDORES']">
            <Link
              href="/estudantes/devedores"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'AreaFinanceira/EstudanteDevedores',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Estudantes Devedores</p>
            </Link>
          </li>

          <li class="nav-item" title="ESTUDANTES INACTIVOS" v-if="user.auth.can['ESTUDANTES INACTIVOS']">
            <Link
              href="/estudantes/inactivos"
              class="nav-link"
              :class="{
                active: $page.component == 'AreaFinanceira/EstudanteInactivo',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Estudantes Inactivos</p>
            </Link>
          </li>

          <li class="nav-item" title="ESTUDANTES FINALISTAS INACTIVOS" v-if="user.auth.can['ESTUDANTES FINALISTAS INACTIVOS']">
            <Link
              href="/estudantes/finalista-inactivos"
              class="nav-link"
              :class="{
                active:
                  $page.component ==
                  'AreaFinanceira/EstudanteFinalistaInactivo',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Estud. Finalistas Inactivos</p>
            </Link>
          </li>

          <li class="nav-item" title="CONSULTAR NÚMEROS DE OPERAÇÕES" v-if="user.auth.can['CONSULTAR NUMEROS DE OPERACOES']">
            <Link
              href="/consultar/operacao"
              class="nav-link"
              :class="{
                active: $page.component == 'AreaFinanceira/ConsultarOperacao',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Consult. Nº Operação</p>
            </Link>
          </li>

          <li class="nav-item" title="CONTROLO DE ACTUALIZAÇÕES DE SALDO" v-if="user.auth.can['CONTROLO DE ACTUALIZACOES DE SALDO']">
            <Link
              href="/consultar/actualizacao-saldo"
              class="nav-link"
              :class="{
                active:
                  $page.component ==
                  'AreaFinanceira/ControleActualizacaoSaldo',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Controlo Actual. Saldo</p>
            </Link>
          </li>

          <li class="nav-item" title="PAGAMENTOS POR VALIDAR" v-if="user.auth.can['VALIDAR PAGAMENTOS']">
            <Link
              href="/pagamento/porvalidar"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'AreaFinanceira/PagamentoPorValidar',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Pagamentos por Validar</p>
            </Link>
          </li>

          <li class="nav-item" title="TALAO EM DESUSO"  v-if="user.auth.can['TALAO EM DESUSO']">
            <Link
              href="/talao/desuso"
              class="nav-link"
              :class="{
                active: $page.component == 'AreaFinanceira/TalaoEmDesuso',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Talão em Desuso</p>
            </Link>
          </li>

          <li class="nav-item" title="SERVICO EMONUMENTOS"  v-if="user.auth.can['SERVICO EMONUMENTOS']">
            <Link
              href="/servicos-emonumentos"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'AreaFinanceira/ServicoEmonumento/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Serviços e Emolumen.</p>
            </Link>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a
          href="#"
          class="nav-link"
          title="CONFIRMAÇÕES E MATRICULAS"
          :class="{
            active: $page.component.startsWith('ConfirmacoesMatricula/'),
          }"
        >
          <i class="nav-icon fas fa-users"></i>
          <p>
            Confirmações e matriculas
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item" title="LISTAGEM GERAL ESTUDANTES" v-if="user.auth.can['LISTAGEM GERAL ESTUDANTES']">
            <Link
              href="/listagem-geral-estudantes"
              class="nav-link"
              :class="{
                active:
                  $page.component ==
                  'ConfirmacoesMatricula/ListagemGeralEstudantes',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Listagem geral de Estudantes</p>
            </Link>
          </li>
          
          <li class="nav-item" title="LISTAR ESTUDANTES MATRICULADOS" v-if="user.auth.can['LISTAR ESTUDANTES MATRICULADOS']">
            <Link
              href="/listar-estudantes-matriculados"
              class="nav-link"
              :class="{
                active:
                  $page.component ==
                  'ConfirmacoesMatricula/ListarEstudantesMatriculados',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Listar Estudantes Matriculados</p>
            </Link>
          </li>
          
          <li class="nav-item" title="LISTAR ESTUDANTES POR ESTADO" v-if="user.auth.can['LISTAR ESTUDANTES POR ESTADO']">
            <Link
              href="/listar-estudantes-por-estados"
              class="nav-link"
              :class="{
                active:
                  $page.component ==
                  'ConfirmacoesMatricula/listarEstudantesPorEstado',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Listar Estudantes Por Estado</p>
            </Link>
          </li>
          
          <!-- <li class="nav-item" title="LISTAR ESTUDANTES PERSONALIZADOS">
            <Link
              href="/listar-estudantes-personalizados"
              class="nav-link"
              :class="{
                active:
                  $page.component ==
                  'ConfirmacoesMatricula/ListarEstudantesPersonalizados',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Listar Estudantes Personalizados</p>
            </Link>
          </li> -->
          
          <!-- <li class="nav-item" title="LISTAR ESTUDANTES INSCRITOS EM UNIDADES CURRICULAR">
            <Link
              href="/listar-estudantes-inscritos-em-unidades-curricular"
              class="nav-link"
              :class="{
                active:
                  $page.component ==
                  'ConfirmacoesMatricula/ListarEstudantesInscritoEmUnidadeCurricular',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Listar Estudantes Inscrito em unidade curricular</p>
            </Link>
          </li> -->
          
        </ul>
      </li>
      
      <li class="nav-item">
        <a
          href="#"
          class="nav-link"
          title="PAGAMENTOS CORRENTES"
          :class="{
            active: $page.component.startsWith('GestaoCreditoInstituicional/'),
          }"
        >
          <i class="nav-icon fas fa-money-bill"></i>
          <p>
            Gestão de Crédito Educacional
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-home nav-icon"></i>
                <p>
                  Instituições
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item" title="LISTAR INSTITUICAO COM DESPESA" v-if="user.auth.can['LISTAR INSTITUICAO COM DESPESA']">
                  <Link
                    href="/instituicoes-renuncia"
                    class="nav-link"
                    :class="{
                      active:
                        $page.component ==
                        'GestaoCreditoInstituicional/Renuncia/Index',
                    }"
                  >
                    <i class="fas fa-building nav-icon"></i>
                    <p>Com Despesa</p>
                  </Link>
                </li>
                <li class="nav-item" title="LISTAR INSTITUICAO COM RECEITA" v-if="user.auth.can['LISTAR INSTITUICAO COM RECEITA']">
                  <Link
                    href="/instituicoes-sem-renuncia"
                    class="nav-link"
                    :class="{
                      active:
                        $page.component ==
                        'GestaoCreditoInstituicional/Sem-Renuncia/Index',
                    }"
                  >
                    <i class="far fa-building nav-icon"></i>
                    <p>Com Receita</p>
                  </Link>
                </li>

                <li class="nav-item" title="LISTAR INSTITUICAO" v-if="user.auth.can['LISTAR INSTITUICAO']">
                  <Link
                    href="/todos-instituicoes"
                    class="nav-link"
                    :class="{
                      active:
                        $page.component ==
                        'GestaoCreditoInstituicional/TodasInstiuicoes/Index',
                    }"
                  >
                    <i class="far fa-building nav-icon"></i>
                    <p>Todas Instituições</p>
                  </Link>
                </li>
              </ul>
            </li>

            <li class="nav-item" title="LISTAR TIPO DE BOLSA" v-if="user.auth.can['LISTAR TIPO DE BOLSA']">
              <Link
                href="/tipo-bolsas"
                class="nav-link"
                :class="{
                  active:
                    $page.component ==
                    'GestaoCreditoInstituicional/Tipobolsa/Index',
                }"
              >
                <i class="fas fa-folder nav-icon"></i>
                <p>Tipo Bolsas</p>
              </Link>
            </li>

            <li class="nav-item" title="ATRIBUIR BOLSAS" v-if="user.auth.can['ATRIBUIR BOLSAS']"> 
              <Link
                href="/atribuicao/Bolsa"
                class="nav-link"
                :class="{
                  active:
                    $page.component == 'GestaodeBolsaeDesconto/AtribuicaoBolsa',
                }"
              >
                <i class="fas fa-user-graduate nav-icon"></i>
                <p>Atribuição de Bolsa</p>
              </Link>
            </li>

            <li class="nav-item" title="LISTAR BOLSEIROS" v-if="user.auth.can['LISTAR BOLSEIROS']">
              <Link
                href="/listar-bolseiros"
                class="nav-link"
                :class="{
                  active:
                    $page.component ==
                    'GestaoCreditoInstituicional/ListarBolseiros',
                }"
              >
                <i class="far fa-circle nav-icon"></i>
                <p>Listar Bolseiros</p>
              </Link>
            </li>
        </ul>
      </li>

      <li class="nav-item">
        <a
          href="#"
          class="nav-link"
          title="GESTAO DE BOLSA E DESCONTO"
          :class="{
            active: $page.component.startsWith('GestaodeBolsaeDesconto'),
          }"
        >
          <i class="nav-icon fa fa-users"></i>
          <p>
            Gestão de Descontos
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item" title="ATRIBUIR DESCONTO" v-if="user.auth.can['ATRIBUIR DESCONTO']">
            <Link
              href="/atribuicao/Desconto"
              class="nav-link"
              :class="{
                active:
                  $page.component ==
                  'GestaodeBolsaeDesconto/AtribuicaoDesconto',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Atribuição de Desconto</p>
            </Link>
          </li>

          <li class="nav-item" title="LISTAR DESCONTO" v-if="user.auth.can['LISTAR DESCONTO']">
            <Link
              href="/listar-desconto"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'GestaodeBolsaeDesconto/ListarDesconto',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Listar Descontos</p>
            </Link>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a
          href="#"
          class="nav-link"
          title="FECHO DE CAIXA"
          :class="{
            active: $page.component.startsWith('RelatoriosPagamentos/Caixa'),
          }"
        >
          <i class="nav-icon fas fa-box"></i>
          <p>
            Fecho de Caixa
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item" title="FECHO DO CAIXA DIÁRIO"  v-if="user.auth.can['FECHO DO CAIXA DIARIO']">
              <Link
                href="/fecho-caixa"
                class="nav-link"
                :class="{
                  active:
                    $page.component == 'RelatoriosPagamentos/Caixa/FechoCaixa',
                }"
              >
                <i class="far fa-circle nav-icon"></i>
                <p>Fecho Caixa Diário</p>
              </Link>
            </li>

            <li class="nav-item" title="FECHO DO CAIXA GERAL" v-if="user.auth.can['FECHO DO CAIXA GERAL']">
              <Link
                href="/fecho/caixa-geral"
                class="nav-link"
                :class="{
                  active:
                    $page.component ==
                    'RelatoriosPagamentos/Caixa/FechoCaixaGeral',
                }"
              >
                <i class="far fa-circle nav-icon"></i>
                <p>Fecho Caixa Geral</p>
              </Link>
            </li>

            <li class="nav-item" title="FECHO DO CAIXA POR UTILIZADOR" v-if="user.auth.can['FECHO DO CAIXA POR UTILIZADOR']">
              <Link
                href="/fecho/caixa-utilizador"
                class="nav-link"
                :class="{
                  active:
                    $page.component ==
                    'RelatoriosPagamentos/Caixa/FechoCaixaUtilizador',
                }"
              >
                <i class="far fa-circle nav-icon"></i>
                <p>Fecho Caixa Utilizador</p>
              </Link>
            </li>
        </ul>
      </li>

      <li class="nav-item">
        <a
          href="#"
          class="nav-link"
          title="RELATÓRIOS FINANCEIROS"
          :class="{
            active: $page.component.startsWith('RelatoriosPagamentos/'),
          }"
        >
          <i class="nav-icon fas fa-chart-line"></i>
          <p>
            Relatórios
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item" title="LISTAR ESTUDANTES MATRICULADOS" v-if="user.auth.can['LISTAR ESTUDANTES MATRICULADOS']">
            <a
              href="#"
              class="nav-link"
              :class="{
                active: $page.component.startsWith(
                  'RelatoriosPagamentos/Estudantes'
                ),
              }"
            >
              <i class="fas fa-user-graduate nav-icon"></i>
              <p>
                Estudantes Matriculadas
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">
              <li class="nav-item" title="LISTAGEM GERAL ESTUDANTES" v-if="user.auth.can['LISTAGEM GERAL ESTUDANTES']">
                <Link
                  href="/relatorios/listar-estudantes"
                  class="nav-link"
                  :class="{
                    active:
                      $page.component ==
                      'RelatoriosPagamentos/Estudantes/ListarEstudantes',
                  }"
                >
                  <i class="far fa-circle nav-icon"></i>
                  <p>Listar Todos</p>
                </Link>
              </li>
            </ul>
          </li>

            <li class="nav-item"  title="LISTAR ESTUDANTES ISENTO" v-if="user.auth.can['LISTAR ESTUDANTES ISENTO']">
              <Link
                href="/estudante/listar-estudante-isento"
                class="nav-link"
                :class="{
                  active:
                    $page.component ==
                    'RelatoriosPagamentos/ListarEstudanteIsento',
                }"
              >
                <i class="far fa-circle nav-icon"></i>
                <p>Listar Estudantes Isento</p>
              </Link>
            </li>

            <li class="nav-item" title="LISTAR ESTUDANTES MATRICULADOS" v-if="user.auth.can['LISTAR ESTUDANTES MATRICULADOS']">
              <a
                href="#"
                class="nav-link"
                :class="{
                  active: $page.component.startsWith(
                    'RelatoriosPagamentos/Estudantes'
                  ),
                }"
              >
                <i class="fas fa-user-graduate nav-icon"></i>
                <p>
                  Estudantes Matriculadas
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item" title="LISTAGEM GERAL ESTUDANTES" v-if="user.auth.can['LISTAGEM GERAL ESTUDANTES']">
                  <Link
                    href="/relatorios/listar-estudantes"
                    class="nav-link"
                    :class="{
                      active:
                        $page.component ==
                        'RelatoriosPagamentos/Estudantes/ListarEstudantes',
                    }"
                  >
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listar Todos</p>
                  </Link>
                </li>

                <li class="nav-item" title="ESTUDANTES FINALISTAS" v-if="user.auth.can['ESTUDANTES FINALISTAS']">
                  <Link
                    href="/estudante/finalistas"
                    class="nav-link"
                    :class="{
                      active:
                        $page.component ==
                        'RelatoriosPagamentos/Estudantes/EstudanteFinalistas',
                    }"
                  >
                    <i class="far fa-circle nav-icon"></i>
                    <p>Estudantes Finalista</p>
                  </Link>
                </li>

                <li class="nav-item" title="LISTAR ESTUDANTES COM CREDITO INSTITUICIONAL" v-if="user.auth.can['LISTAR ESTUDANTES COM CREDITO INSTITUICIONAL'] || user.auth.can['LISTAR BOLSEIROS']">
                  <Link
                    href="/relatorios/listar-estudantes-credito-instituicao"
                    class="nav-link"
                    :class="{
                      active:
                        $page.component ==
                        'RelatoriosPagamentos/Estudantes/ListarEstudantesCreditoInstituicao',
                    }"
                  >
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listar Estudantes com crédito institucional</p>
                  </Link>
                </li>

                <li class="nav-item" title="LISTAR ESTUDANTES COM DESCONTOS" v-if="user.auth.can['LISTAR ESTUDANTES COM DESCONTOS']">
                  <Link
                    href="/relatorios/listar-estudantes-desconto"
                    class="nav-link"
                    :class="{
                      active:
                        $page.component ==
                        'RelatoriosPagamentos/Estudantes/ListarEstudantesDesconto',
                    }"
                  >
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listar estudantes com Desconto</p>
                  </Link>
                </li>
              </ul>
            </li>
        </ul>
      </li>

      <li class="nav-item">
        <a
          href="#"
          class="nav-link"
          title="GESTÃO DE PERMISSÕES"
          :class="{
            active: $page.component.startsWith('GestaoPermissions/'),
          }"
        >
          <i class="nav-icon fas fa-lock"></i>
          <p>
            Gestão Permissões
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item" title="LISTAR PERFIL" v-if="user.auth.can['LISTAR PERFIL']">
            <Link
              href="/roles"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'GestaoPermissions/roles/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Perfils</p>
            </Link>
          </li>

          <li class="nav-item" title="LISTAR PERMISSOES" v-if="user.auth.can['LISTAR PERMISSOES']">
            <Link
              href="/permissions"
              class="nav-link"
              :class="{
                active:
                  $page.component ==
                  'GestaoPermissions/permissions/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Permissões</p>
            </Link>
          </li>
          
          <li class="nav-item" title="UTILIZADORES" v-if="user.auth.can['LISTAR UTILIZADORES']">
            <Link
              href="/listagem-utilizadores"
              class="nav-link"
              :class="{
                active:
                  $page.component ==
                  'GestaoPermissions/utilizadores/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Utilizadores</p>
            </Link>
          </li>

        </ul>
      </li>

      <li class="nav-item" title="AJUDA">
        <Link
          href="/ajuda-sistema"
          class="nav-link"
          :class="{
            active: $page.component == 'Ajuda/Index',
          }"
        >
          <i class="fas fa-question-circle" aria-hidden="true"> </i>
          Ajuda
        </Link>
      </li>

      <div class="ml-auto">
        <ul class="navbar-nav">
          <li class="nav-item text-left">
            <Link
              class="nav-link btn btn-link btn-danger text-white"
              href="/logout"
              method="post"
              as="button"
              type="button"
            >
              <i class="fas fa-sign-out-alt"></i>
              Sair
            </Link>
          </li>
        </ul>
      </div>
      
    </ul>
  </nav>
</template>

<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/inertia-vue3";
import { Link } from "@inertiajs/inertia-vue3";
</script>

<script>
export default {
  data: () => ({
    perfil: "",
    user_roles: [],
    roles: [],
  }),

  computed: {
    user() {
      return this.$page.props.auth.user;
    },
  },
};
</script>


<style>
.nav-pills .nav-link.active,
.nav-pills .show > .nav-link {
  color: #fff;
  background-color: #52c7ed;
}
</style>
