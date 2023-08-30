<?php

use App\Http\Controllers\AjudaSistemaController;
use App\Http\Controllers\AssociarBolsaInstituicaoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ControloPagamentoPropinaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstudanteController;
use App\Http\Controllers\GestaodeBolsaeDescontoController;
use App\Http\Controllers\InstituicaoController;
use App\Http\Controllers\InstituicaoRenunciaController;
use App\Http\Controllers\InstituicaoSemRenunciaController;
use App\Http\Controllers\PagamentoCorrentesController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RelatorioFinanceiroController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServicoEmonumentoController;
use App\Http\Controllers\TipoBolsaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthController::class, 'login'])
->middleware('guest');

Route::get('/login', [AuthController::class, 'login'])
->middleware('guest')
->name('login');

Route::post('/login', [AuthController::class, 'autenticacao'])->name('mf.login.post');

Route::group(["middleware" => "auth"], function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('mf.logout');

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('mf.dashboard');
    Route::get('/carregar-dados-estatisticos', [DashboardController::class, 'carregarDadosEstatisticao'])->name('mf.carregar-dados-estatisticos');
    // Route::get('/carregar-graficos', [DashboardController::class, 'carregarGraficos'])->name('mf.carregar-grafico');
    Route::get('/carregar-graficos-pagamentos-por-turnos', [DashboardController::class, 'carregarGraficosPagamentosPorTurnos'])->name('mf.carregar-grafico-pagamentos-por-turnos');
    Route::get('/carregar-graficos-pagamentos-por-meses', [DashboardController::class, 'carregarGraficosPagamentosPorMeses'])->name('mf.carregar-grafico-pagamentos-por-meses');
    Route::get('/carregar-graficos-estudantes-devedores', [DashboardController::class, 'carregarGraficosTotalEstudantesDevodores'])->name('mf.carregar-grafico-estudantes-devedores');
    Route::get('/carregar-graficos-estudantes-propinas-pagas', [DashboardController::class, 'carregarGraficosTotalEstudantesPropinasPagas'])->name('mf.carregar-grafico-estudantes-propinas-pagas');
    Route::get('/carregar-graficos-estudantes-bolseiros', [DashboardController::class, 'carregarGraficosEstudanteBolseiros'])->name('mf.carregar-grafico-estudantes-bolseiros');

    // controlo de pagamento de propinas
    Route::get('/estudantes/propina-pagar', [ControloPagamentoPropinaController::class, 'estudantePropinaPaga'])->name('mf.estudante-propina-paga');
    Route::get('/estudantes/carregar-propina-pagar', [ControloPagamentoPropinaController::class, 'estudanteCarregarPropinaPaga'])->name('mf.carregar-estudante-propina-paga');
    Route::get('/estudante/propina-pagar/pdf-imprimir', [ControloPagamentoPropinaController::class, 'pdfImprimirestudantePropinaPaga'])->name('mf.estudante-propina-paga-pdf');
    Route::get('/estudante/propina-pagar/excel-imprimir', [ControloPagamentoPropinaController::class, 'excelImprimirestudantePropinaPaga'])->name('mf.estudante-propina-pagas-excel');

    Route::get('/estudante/devedores/pdf-imprimir/{a?}/{m?}/{f?}/{c?}/{t?}', [ControloPagamentoPropinaController::class, 'ImprimirPDFestudanteDevedores'])->name('mf.estudante-devedores-propina-paga-pdf');
    Route::get('/estudante/devedores/excel-imprimir/{a?}/{m?}/{f?}/{c?}/{t?}', [ControloPagamentoPropinaController::class, 'excelImprimirestudanteDevedor'])->name('mf.estudante-devedores-propina-pagas-excel');

    Route::get('/estudantes/devedores', [ControloPagamentoPropinaController::class, 'estudanteDevedores'])->name('mf.estudante-devedores');

    Route::get('/pagamentos/propinas-por-mes/{id?}', [ControloPagamentoPropinaController::class, 'pagamentosPorMes'])->name('mf.estudante-por-mes');
    Route::get('/pagamentos/propinas-por-mes/pdf-imprimir/{id?}', [ControloPagamentoPropinaController::class, 'pdfImprimirpagamentosPorMes'])->name('mf.estudante-por-mes-pdf');
    Route::get('/pagamentos/propinas-por-mes/excel-imprimir/{id?}', [ControloPagamentoPropinaController::class, 'excelImprimirpagamentosPorMes'])->name('mf.estudante-por-mes-excel');

    Route::get('/estudantes/inactivos', [EstudanteController::class, 'estudanteInactivo'])->name('mf.estudante-inactivos');
    Route::get('/estudantes/inactivos/pdf', [EstudanteController::class, 'estudanteInactivoPdf'])->name('mf.estudante-inactivos-pdf');
    Route::get('/estudantes/inactivos/excel', [EstudanteController::class, 'estudanteInactivoExcel'])->name('mf.estudante-inactivos-excel');

    Route::get('/estudantes/finalista-inactivos', [EstudanteController::class, 'estudanteFinalistaInactivo'])->name('mf.estudante-finalista-inactivos');
    Route::get('/estudantes/finalista-inactivos/pdf', [EstudanteController::class, 'estudanteFinalistaInactivoPdf'])->name('mf.estudante-finalista-inactivos-pdf');
    Route::get('/estudantes/finalista-inactivos/excel', [EstudanteController::class, 'estudanteFinalistaInactivoExcel'])->name('mf.estudante-finalista-inactivos-excel');

    Route::get('/estudante/finalistas', [EstudanteController::class, 'estudanteFinalistas'])->name('mf.listar-estudante-finalista');
    Route::get('/estudantes/finalistas/pdf', [EstudanteController::class, 'estudanteFinalistasPdf'])->name('mf.estudante-finalistas-pdf');
    Route::get('/estudantes/finalistas/excel', [EstudanteController::class, 'estudanteFinalistasExcel'])->name('mf.estudante-finalista-excel');

    // pagamentos correntes
    Route::get('/consultar/operacao', [PagamentoCorrentesController::class, 'consultarOperacao'])->name('mf.consultar-operacao');
    Route::get('/consultar/comprovativo-operacao/{comprovativo?}', [PagamentoCorrentesController::class, 'consultarComprovativoOperacao'])->name('mf.consultar-comprovativo-operacao');
    Route::get('/consultar/operacao-detalhe', [PagamentoCorrentesController::class, 'consultarOperacaoDetalhe'])->name('mf.consultar-operacao-detalhe');

    Route::get('/consultar/actualizacao-saldo', [PagamentoCorrentesController::class, 'controleActualizacaoSaldo'])->name('mf.controle-actualizacao-saldo');
    Route::get('/consultar/actualizacao-saldo/detalhes/{id}', [PagamentoCorrentesController::class, 'controleActualizacaoSaldoDetalhe'])->name('mf.controle-actualizacao-saldo-detalhe');
    Route::get('/consultar/actualizacao-saldo-pdf/{o?}/{di?}/{df?}', [PagamentoCorrentesController::class, 'pdfImprimirControleActualizacaoSaldo'])->name('mf.controle-actualizacao-saldo-pdf');
    Route::get('/consultar/actualizacao-saldo-excel/{o?}/{di?}/{df?}', [PagamentoCorrentesController::class, 'excelImprimirControleActualizacaoSaldo'])->name('mf.controle-actualizacao-saldo-excel');
    Route::get('/visualizar/actualizacao-saldo/{id}', [PagamentoCorrentesController::class, 'visualizarActualizacaoSaldo'])->name('mf.visualizar-actualizacao-saldo');

    Route::get('/consultar/operacao-visualizar/{comprovativo}', [PagamentoCorrentesController::class, 'consultarOperacaoVisualizar'])->name('mf.consultar-operacao-visualizar');

    Route::get('/pagamento/porvalidar', [PagamentoCorrentesController::class, 'pagamentoPorValidar'])->name('mf.pagamento-por-validar');
    Route::get('/pagamento/validar-pagamento/store/{id}', [PagamentoCorrentesController::class, 'validarPagamentoStore'])->name('mf.validar.pagamento-store');
    Route::get('/pagamento/rejeitar-pagamento/store/{id}/{motivos?}', [PagamentoCorrentesController::class, 'rejeitarPagamentoStore'])->name('mf.rejeitar-pagamento-store');
    Route::get('/pagamento/porvalidar-pdf', [PagamentoCorrentesController::class, 'pdfImprimirPagamentoPorValidar'])->name('mf.pagamento-por-validar-pdf');
    Route::get('/pagamento/porvalidar-excel', [PagamentoCorrentesController::class, 'excelImprimirPagamentoPorValidar'])->name('mf.pagamento-por-validar-excel');

    Route::get('/talao/desuso', [PagamentoCorrentesController::class, 'talaoDesuso'])->name('mf.talao-desuso');
    Route::get('/talao/adicionar', [PagamentoCorrentesController::class, 'adicionarTalaoDesuso'])->name('mf.adicionar-talao-desuso');
    Route::post('/talao/adicionar', [PagamentoCorrentesController::class, 'storeTalaoDesuso'])->name('mf.store-talao-desuso');
    Route::get('/talao/desuso-pdf/{o?}/{di?}/{df?}', [PagamentoCorrentesController::class, 'pdfImprimirTalaoDesuso'])->name('mf.talao-desuso-pdf');
    Route::get('/talao/desuso-excel/{o?}/{di?}/{df?}', [PagamentoCorrentesController::class, 'excelImprimirTalaoDesuso'])->name('mf.talao-desuso-excel');


    Route::get('/fecho-caixa', [RelatorioFinanceiroController::class, 'fechoCaixa'])->name('mf.fecho-caixa');
    // Relatórios de pagamentos
    Route::get('/fecho/caixa-geral', [RelatorioFinanceiroController::class, 'fechoCaixaGeral'])->name('mf.fecho-caixa-geral');
    Route::get('/fecho/caixa-geral/pdf-imprimir', [RelatorioFinanceiroController::class, 'pdfImprimirGeral'])->name('mf.fecho-caixa-geral-pdf');
    Route::get('/fecho/caixa-geral/excel-imprimir', [RelatorioFinanceiroController::class, 'excelImprimirGeral'])->name('mf.fecho-caixa-geral-excel');

    Route::get('/fecho/caixa-mensalidades-geral', [RelatorioFinanceiroController::class, 'fechoCaixaMensalidade'])->name('mf.fecho-caixa-mensalidade-geral');

    Route::get('/fecho/caixa-utilizador', [RelatorioFinanceiroController::class, 'fechoCaixaUtilizador'])->name('mf.fecho-caixa-utilizador');
    Route::get('/fecho/caixa-utilizador/pdf-imprimir', [RelatorioFinanceiroController::class, 'pdfImprimirUtilizador'])->name('mf.fecho-caixa-utilizador-pdf');
    Route::get('/fecho/caixa-utilizador/excel-imprimir', [RelatorioFinanceiroController::class, 'excelImprimirUtilizador'])->name('mf.fecho-caixa-utilizador-excel');

    Route::get('/estudante/listar-estudante-isento', [RelatorioFinanceiroController::class, 'listarEstudanteIsento'])->name('mf.listar-estudante-isento');
    Route::get('/estudante/listar-estudante-isento/pdf-imprimir', [RelatorioFinanceiroController::class, 'pdfImprimirListarEstudanteIsento'])->name('mf.listar-estudante-isento-pdf');
    Route::get('/estudante/listar-estudante-isento/excel-imprimir', [RelatorioFinanceiroController::class, 'excelImprimirListarEstudanteIsento'])->name('mf.listar-estudante-isento-excel');

    Route::get('/relatorios/estudantes', [RelatorioFinanceiroController::class, 'index'])->name('mf.relatorio-estudantes-index');
    Route::get('/visualizar-detalhes-pagamento/{id}', [RelatorioFinanceiroController::class, 'visualizarDetalhesPagamento'])->name('mf.visualizar-detalhes-pagamento');

    Route::get('/relatorios/listar-estudantes', [RelatorioFinanceiroController::class, 'listarEstudantes'])->name('mf.relatorio-listar-estudantes');
    Route::get('/relatorios/listar-estudantes-imprimir-pdf', [RelatorioFinanceiroController::class, 'listarEstudantesImprimirPdf'])->name('mf.relatorio-listar-estudantes-imprimir-pdf');
    Route::get('/relatorios/listar-estudantes-imprimir-excel', [RelatorioFinanceiroController::class, 'listarEstudantesImprimirExcel'])->name('mf.relatorio-listar-estudantes-imprimir-excel');

    Route::get('/relatorios/listar-estudantes-credito-instituicao', [RelatorioFinanceiroController::class, 'listarEstudantesCreditoInstituicao'])->name('mf.listar-estudantes-credito-instituicao');
    Route::get('/relatorios/listar-estudantes-credito-instituicao-imprimir-pdf', [RelatorioFinanceiroController::class, 'listarEstudantesCreditoInstituicaoImprimirPdf'])->name('mf.listar-estudantes-credito-instituicao-imprimir-pdf');
    Route::get('/relatorios/listar-estudantes-credito-instituicao-imprimir-excel', [RelatorioFinanceiroController::class, 'listarEstudantesCreditoInstituicaoImprimirExcel'])->name('mf.listar-estudantes-credito-instituicao-imprimir-excel');

    Route::get('/relatorios/listar-estudantes-desconto', [RelatorioFinanceiroController::class, 'listarEstudantesDesconto'])->name('mf.relatorio-listar-estudantes-desconto');
    Route::get('/relatorios/listar-estudantes-desconto-imprimir-pdf', [RelatorioFinanceiroController::class, 'listarEstudantesDescontoImprimirPdf'])->name('mf.relatorio-listar-estudantes-desconto-imprimir-pdf');
    Route::get('/relatorios/listar-estudantes-desconto-imprimir-excel', [RelatorioFinanceiroController::class, 'listarEstudantesDescontoImprimirExcel'])->name('mf.relatorio-listar-estudantes-desconto-imprimir-excel');

    // preçario
    Route::get('/servicos-emonumentos', [ServicoEmonumentoController::class, 'index'])->name('mf.servicos-emonumentos.index');
    Route::get('/propinas', [ServicoEmonumentoController::class, 'propinas'])->name('mf.propinas');
    Route::put('/propinas/editar/{}', [ServicoEmonumentoController::class, 'updatePropinas'])->name('mf.updatePropinas');
    Route::get('/propinas/pdf-imprimir/{a?}/{p?}', [ServicoEmonumentoController::class, 'propinasPdfImprimir'])->name('mf.propinas-pdf');
    Route::get('/propinas/excel-imprimir/{a?}/{p?}', [ServicoEmonumentoController::class, 'propinasExcelImprimir'])->name('mf.propinas-excel');

    Route::get('/servicos', [ServicoEmonumentoController::class, 'servicos'])->name('mf.servicos');
    Route::post('/servicos/criar', [ServicoEmonumentoController::class, 'servicosCreate'])->name('mf.servicos-create');
    Route::get('/servicos/{id}', [ServicoEmonumentoController::class, 'editarServico'])->name('mf.editarServico');
    Route::put('/servicos/editar/{id}', [ServicoEmonumentoController::class, 'updateServico'])->name('mf.updateServico');
    Route::get('/servicos/pdf-imprimir', [ServicoEmonumentoController::class, 'servicosPdfImprimir'])->name('mf.servicos-pdf');
    Route::get('/servicos/excel-imprimir', [ServicoEmonumentoController::class, 'servicosExcelImprimir'])->name('mf.servicos-excel');

    // Gestao de Bolsa e desconto
    Route::get('/atribuicao/Bolsa', [GestaodeBolsaeDescontoController::class, 'atribuicaoBolsa'])->name('mf.atribuicao-bolsa');
    Route::get('/atribuicao/Bolsa-carregar-instituicoes', [GestaodeBolsaeDescontoController::class, 'atribuicaoBolsaCarregarInstituicao'])->name('mf.atribuicao-bolsa-carregar-instituicao');
    Route::get('/estatistica', [GestaodeBolsaeDescontoController::class, 'estatistica'])->name('mf.estatistica');

    Route::get('/listar-desconto', [GestaodeBolsaeDescontoController::class, 'listarDescontos'])->name('mf.Listar-Desconto');
    Route::post('/criar/desconto',[GestaodeBolsaeDescontoController::class,'StoreTipoDesconto'])->name('mf.store-tipo-desconto');
    Route::put('/actualizar/Desconto/{id}',[GestaodeBolsaeDescontoController::class,'EditarTipoDesconto'])->name('mf.update-Desconto');
    Route::delete('/eliminar/desconto/{id}',[GestaodeBolsaeDescontoController::class,'eliminarTipoDesconto'])->name('mf.delete-desconto');

    Route::get('/isencaodePagamentos', [GestaodeBolsaeDescontoController::class, 'isencaodePagamentos'])->name('mf.isencaodePagamentos');
    Route::get('/listagem/isencaodePagamentos/pdf-imprimir/{AnoLectivo?}/{Curso?}/{Instituicao?}/{TipoBolsa?}',[GestaodeBolsaeDescontoController::class, 'pdfImprimirisencaodePagamentos'])->name('mf.imprimir-insecao-pagamento');
    //bolseiros

    Route::get('/listar-bolseiros', [GestaodeBolsaeDescontoController::class, 'bolseiros'])->name('mf.listar-bolseiros');
    Route::get('/mudar-estado-bolseiros/{item}', [GestaodeBolsaeDescontoController::class, 'mudarEstadoBolseiros'])->name('mf.mudar-estado-bolseiros');
    Route::get('/editar-bolseiros/{item}', [GestaodeBolsaeDescontoController::class, 'editarBolseiros'])->name('mf.editar-bolseiros');
    Route::put('/update-bolseiros/{item?}', [GestaodeBolsaeDescontoController::class, 'updateBolseiros'])->name('mf.update-bolseiros');
    Route::get('/visualizar-bolseiros/{item}', [GestaodeBolsaeDescontoController::class, 'visualizarBolseiros'])->name('mf.visualizar-bolseiros');

    Route::get('/atribuicao/Desconto', [GestaodeBolsaeDescontoController::class, 'atribuicaoDesconto'])->name('mf.atribuicao-desconto');
    Route::get('/mudar-estado-desconto/{item}', [GestaodeBolsaeDescontoController::class, 'mudarEstadoDesconto'])->name('mf.mudar-estado-desconto');
    Route::get('/editar-desconto/{item}', [GestaodeBolsaeDescontoController::class, 'editarDesconto'])->name('mf.editar-desconto');
    Route::put('/update-desconto/{item?}', [GestaodeBolsaeDescontoController::class, 'updateDesconto'])->name('mf.update-desconto');
    Route::get('/visualizar-desconto/{item}', [GestaodeBolsaeDescontoController::class, 'visualizarDesconto'])->name('mf.visualizar-desconto');

    Route::get('/listagem/bolserios/pdf-imprimir',[GestaodeBolsaeDescontoController::class, 'pdfimprimirbolseiros'])->name('mf.imprimir-bolseiros');
    Route::get('/listagem/bolserios/excel-imprimir',[GestaodeBolsaeDescontoController::class, 'excelimprimirbolseiros'])->name('mf.imprimir-excel-bolseiros');

    Route::get('/pagamentoBolseiros', [GestaodeBolsaeDescontoController::class, 'pagamentoBolseiros'])->name('mf.pagamentoBolseiros');
    Route::post('/pagamentoBolseiros', [GestaodeBolsaeDescontoController::class, 'pagamentoBolseirosCreate'])->name('mf.pagamentoBolseirosCreate');

    Route::get('/instituicoes', [GestaodeBolsaeDescontoController::class, 'instituicoes'])->name('mf.instituicoes');
    Route::post('/instuicao/criar', [GestaodeBolsaeDescontoController::class,'novaInstituicao'])->name('mf.intituicao-criar');
    Route::get('/listagem/instiuicoes/pdf-imprimir',[GestaodeBolsaeDescontoController::class, 'pdfimprimirinstituicoes'])->name('mf.imprimir-instituicoes');
    Route::get('/listagem/instiuicoes/excel-imprimir',[GestaodeBolsaeDescontoController::class, 'excelImprimirIstuicoes'])->name('mf.imprimir-execel-instituicoes');

    Route::get('/instituicoes/pdf/{ins?}', [GestaodeBolsaeDescontoController::class, 'instituicoesPdf'])->name('mf.instituicoes-pdf');
    Route::get('/instituicoes/excel/{ins?}', [GestaodeBolsaeDescontoController::class, 'instituicoesExcel'])->name('mf.instituicoes-excel');
    Route::get('/instituicoes/{id}',[GestaodeBolsaeDescontoController::class,'editarInstituicao'])->name('mf.editarInstituicoes');
    Route::get('/instituicoes/tipo-bolsa',[GestaodeBolsaeDescontoController::class,'bolsaInstuicao'])->name('mf.tipo-bolsa-instuicao');
    Route::put('/instituicoes/update/{id}',[GestaodeBolsaeDescontoController::class,'updateInstituicao'])->name('mf.updateInstituicoes');

    Route::get('/consultar/estudante/{n}', [EstudanteController::class, 'show'])->name('mf.consultar-estudante');
    Route::post('/atribuir-bolsa/estudante', [EstudanteController::class, 'store'])->name('mf.atribuir-bolsa-estudante');
    Route::get('/atribuir-bolsa/confirmar-estudante/{n}', [EstudanteController::class, 'confirmar'])->name('mf.atribuir-bolsa-confirmar-estudante');

    Route::post('/atribuir-desconto/estudante', [EstudanteController::class, 'storeDesconto'])->name('mf.atribuir-desconto-estudante');
    Route::get('/atribuir-desconto/confirmar-estudante/{n}', [EstudanteController::class, 'confirmarDesconto'])->name('mf.atribuir-desconto-confirmar-estudante');


    Route::get('/estudantes/visualizar-perfil/{id}', [EstudanteController::class, 'visualizarPerfilEstudante'])->name('mf.estudante-visualizar-perfil');
    Route::get('/estudantes/carregar-estado/{id}', [EstudanteController::class, 'carregarEstado'])->name('mf.estudante-carregar-estado');
    Route::get('/estudantes/carregar-inscricoes/{id}/{ano?}', [EstudanteController::class, 'carregarInscricoes'])->name('mf.estudante-carregar-inscricao');
    Route::get('/estudantes/carregar-detalhe-facturas/{code}', [EstudanteController::class, 'carregarDetalheFactura'])->name('mf.estudante-detalhe-facturas');

    Route::get('/estudantes/carregar-facturas-estudantes', [EstudanteController::class, 'carregarFacturasEstudante'])->name('mf.estudante-facturas-estudantes');
    Route::get('/estudantes/carregar-facturas-estudantes-pdf', [EstudanteController::class, 'carregarFacturasEstudantePDF'])->name('mf.estudante-facturas-estudantes-pdf');
    Route::get('/estudantes/carregar-facturas-estudantes-excel', [EstudanteController::class, 'carregarFacturasEstudanteEXCEL'])->name('mf.estudante-facturas-estudantes-excel');


    Route::get('/estudantes/carregar-servicos-pagos', [EstudanteController::class, 'carregarServicoPagos'])->name('mf.estudante-carregar-servicos-pagos');
    Route::get('/estudantes/carregar-servicos-pagos-pdf', [EstudanteController::class, 'carregarServicoPagosPDF'])->name('mf.estudante-carregar-servicos-pagos-pdf');
    Route::get('/estudantes/carregar-servicos-pagos-excel', [EstudanteController::class, 'carregarServicoPagosEXCEL'])->name('mf.estudante-carregar-servicos-pagos-excel');


    Route::get('/estudantes/carregar-isencao-pagamento/{ano?}/{matricula}', [EstudanteController::class, 'carregarIsencaoPagamento'])->name('mf.estudante-carregar-isencao-pagamento');
    Route::get('/estudantes/carregar-isencao-pagamento-matricula/{matricula}', [EstudanteController::class, 'carregarIsencaoPagamentoMatricula'])->name('mf.estudante-carregar-isencao-pagamento-matricula');

    Route::post('/estudantes/carregar-isencao-multa-post', [EstudanteController::class, 'carregarIsencaoMultaPost'])->name('mf.estudante-carregar-isencao-multa-post');

    Route::post('/estudantes/carregar-isencao-pagamento-post', [EstudanteController::class, 'carregarIsencaoPagamentoPost'])->name('mf.estudante-carregar-isencao-pagamento-post');
    Route::post('/estudantes/remover-isencao-pagamento-post', [EstudanteController::class, 'removerIsencaoPagamentoPost'])->name('mf.estudante-remover-isencao-pagamento-post');
    Route::post('/estudantes/remover-factura-pagamento-post', [EstudanteController::class, 'removerFacturaPagamentoPost'])->name('mf.estudante-remover-factura-pagamento-post');

    Route::get('/estudantes/carregar-pagamentos', [EstudanteController::class, 'carregarPagamentos'])->name('mf.estudante-carregar-pagamentos');
    Route::get('/estudantes/carregar-pagamentos-pdf', [EstudanteController::class, 'carregarPagamentosPDF'])->name('mf.estudante-carregar-pagamentos-pdf');
    Route::get('/estudantes/carregar-pagamentos-excel', [EstudanteController::class, 'carregarPagamentosEXCEL'])->name('mf.estudante-carregar-pagamentos-excel');

    Route::get('/estudantes/carregar-tipo-movimentos', [EstudanteController::class, 'carregarTipoMovimentos'])->name('mf.estudante-carregar-tipo-movimento');
    Route::get('/estudantes/pesquisar-matricula/{id}', [EstudanteController::class, 'pesquisarNumeroMatricula'])->name('mf.estudante-pesquisar-matricula');


    Route::post('/estudantes/actualizar-saldo', [EstudanteController::class, 'actualizarSaldo'])->name('mf.estudante-actualizar-saldo');
    Route::get('/estudantes/carregar-actualizacoes-saldos', [EstudanteController::class, 'carregarActualizacoesSaldo'])->name('mf.estudante-carregar-actualizacoes-saldos');
    Route::get('/estudantes/carregar-actualizacoes-saldos-pdf', [EstudanteController::class, 'carregarActualizacoesSaldoPDF'])->name('mf.estudante-carregar-actualizacoes-saldos-pdf');
    Route::get('/estudantes/carregar-actualizacoes-saldos-excel', [EstudanteController::class, 'carregarActualizacoesSaldoEXCEL'])->name('mf.estudante-carregar-actualizacoes-saldos-excel');

    Route::get('/percentagem-aproveitamento', [PagamentoCorrentesController::class, 'percentagemAproveitamento'])->name('mf.percentagem-aproveitamento');

    Route::resource('instituicoes-renuncia', InstituicaoRenunciaController::class);
    Route::get('/instituicoes-renuncia-pdf', [PDFController::class, 'pdf'])->name('instituicoes-renuncia-pdf');
    Route::get('/instituicoes-renuncia-excel', [PDFController::class, 'excel'])->name('instituicoes-renuncia-excel');

    Route::resource('instituicoes-sem-renuncia', InstituicaoSemRenunciaController::class);
    Route::resource('todos-instituicoes', InstituicaoController::class);
    Route::get('/instituicoes-sem-renuncia-pdf', [PDFController::class, 'pdfSemRenuncia'])->name('instituicoes-sem-renuncia-pdf');
    Route::get('/instituicoes-sem-renuncia-excel', [PDFController::class, 'excelSemRenuncia'])->name('instituicoes-sem-renuncia-excel');

    Route::resource('associar-bolsas-instituicao', AssociarBolsaInstituicaoController::class);

    Route::resource('tipo-bolsas', TipoBolsaController::class);
    Route::get('listar-todas-bolsas-estudos', [TipoBolsaController::class, 'listar_bolsas'])->name('listar-bolsas-de-estudos');
    Route::get('/tipo-bolsas-pdf', [PDFController::class, 'pdfTipoBolsa'])->name('tipo-bolsas-pdf');
    Route::get('/tipo-bolsas-excel', [PDFController::class, 'excelTipoBolsa'])->name('tipo-bolsas-excel');


    // ROLES E PERMISSIOONS
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

     // Ajuda System
     Route::get('/ajuda-sistema', [AjudaSistemaController::class, 'Index'])->name('mf.Ajuda-sistema');
     Route::get('/baixar-manual', [AjudaSistemaController::class, 'baixarManualUtilizador']);
     
     Route::get('/estudante/pegar-descricao-bolseiro', [EstudanteController::class, 'getDescricaoTiposAlunos']);
     Route::get('/estudante/pega-bolseiro/{id_user}', [EstudanteController::class, 'descontoBolsa']);
     Route::get('/estudante/pega-finalista/{id_user}', [EstudanteController::class, 'finalista']);
     Route::get('/estudante/pega-preoco-propina/{polo}/{curso}', [EstudanteController::class, 'getPrecoPropina']);
     
     
     Route::get('/carregar/meses-anolectivo', [AjudaSistemaController::class, 'getMeses']);

});
