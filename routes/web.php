<?php

use App\Controllers\Admin\AdminAnuncioController;
use App\Controllers\Admin\AdminDashboardController;
use App\Controllers\Admin\AdminDenunciaController;
use App\Controllers\Admin\AdminUserController;
use App\Controllers\AnuncioController;
use App\Controllers\AuthController;
use App\Controllers\AvaliacaoController;
use App\Controllers\ChatController;
use App\Controllers\DenunciaController;
use App\Controllers\HomeController;
use App\Controllers\MediaController;
use App\Controllers\PerfilController;
use App\Controllers\PropostaController;

$router->get('/', [HomeController::class, 'index']);
$router->get('/uploads/{folder}/{filename}', [MediaController::class, 'show']);

$router->get('/login', [AuthController::class, 'showLogin'], ['guest']);
$router->post('/login', [AuthController::class, 'login'], ['guest']);
$router->get('/cadastro', [AuthController::class, 'showRegister'], ['guest']);
$router->post('/cadastro', [AuthController::class, 'register'], ['guest']);
$router->get('/verificar-email', [AuthController::class, 'verifyEmail']);
$router->post('/logout', [AuthController::class, 'logout'], ['auth']);

$router->get('/anuncios', [AnuncioController::class, 'index']);
$router->get('/anuncios/criar', [AnuncioController::class, 'create'], ['auth']);
$router->post('/anuncios', [AnuncioController::class, 'store'], ['auth']);
$router->get('/anuncios/{id}', [AnuncioController::class, 'show']);
$router->get('/anuncios/{id}/editar', [AnuncioController::class, 'edit'], ['auth']);
$router->post('/anuncios/{id}/atualizar', [AnuncioController::class, 'update'], ['auth']);
$router->post('/anuncios/{id}/pausar', [AnuncioController::class, 'pause'], ['auth']);
$router->post('/anuncios/{id}/remover', [AnuncioController::class, 'remove'], ['auth']);
$router->get('/meus-anuncios', [AnuncioController::class, 'my'], ['auth']);

$router->post('/anuncios/{id}/propostas', [PropostaController::class, 'store'], ['auth']);
$router->get('/propostas/enviadas', [PropostaController::class, 'sent'], ['auth']);
$router->get('/propostas/recebidas', [PropostaController::class, 'received'], ['auth']);
$router->get('/propostas/{id}', [PropostaController::class, 'show'], ['auth']);
$router->post('/propostas/{id}/aceitar', [PropostaController::class, 'accept'], ['auth']);
$router->post('/propostas/{id}/recusar', [PropostaController::class, 'reject'], ['auth']);
$router->post('/propostas/{id}/cancelar', [PropostaController::class, 'cancel'], ['auth']);
$router->post('/propostas/{id}/concluir', [PropostaController::class, 'complete'], ['auth']);

$router->get('/chat/{proposta_id}', [ChatController::class, 'show'], ['auth']);
$router->post('/chat/{proposta_id}/mensagens', [ChatController::class, 'store'], ['auth']);

$router->get('/perfil/editar', [PerfilController::class, 'edit'], ['auth']);
$router->post('/perfil/atualizar', [PerfilController::class, 'update'], ['auth']);
$router->get('/perfil/{id}', [PerfilController::class, 'show']);

$router->get('/avaliacoes/{proposta_id}/criar', [AvaliacaoController::class, 'create'], ['auth']);
$router->post('/avaliacoes/{proposta_id}', [AvaliacaoController::class, 'store'], ['auth']);

$router->get('/denuncias/criar', [DenunciaController::class, 'create'], ['auth']);
$router->post('/denuncias', [DenunciaController::class, 'store'], ['auth']);

$router->get('/admin', [AdminDashboardController::class, 'index'], ['admin']);
$router->get('/admin/usuarios', [AdminUserController::class, 'index'], ['admin']);
$router->post('/admin/usuarios/{id}/bloquear', [AdminUserController::class, 'block'], ['admin']);
$router->post('/admin/usuarios/{id}/desbloquear', [AdminUserController::class, 'unblock'], ['admin']);
$router->post('/admin/usuarios/{id}/promover', [AdminUserController::class, 'promote'], ['admin']);
$router->post('/admin/usuarios/{id}/rebaixar', [AdminUserController::class, 'demote'], ['admin']);

$router->get('/admin/anuncios', [AdminAnuncioController::class, 'index'], ['admin']);
$router->post('/admin/anuncios/{id}/remover', [AdminAnuncioController::class, 'remove'], ['admin']);

$router->get('/admin/denuncias', [AdminDenunciaController::class, 'index'], ['admin']);
$router->post('/admin/denuncias/{id}/analise', [AdminDenunciaController::class, 'review'], ['admin']);
$router->post('/admin/denuncias/{id}/resolver', [AdminDenunciaController::class, 'resolve'], ['admin']);
$router->post('/admin/denuncias/{id}/arquivar', [AdminDenunciaController::class, 'archive'], ['admin']);
