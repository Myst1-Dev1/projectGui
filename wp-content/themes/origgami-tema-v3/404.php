<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Origgami_Tema_V3
 */

get_header();
?>

	<header class="page-header">		
		<h1 class="page-title">Erro 404<br>Página não encontrada!</h1>
	</header>

	<div id="primary_secondary">
		<div id="primary" class="content-area">
			<main id="main" class="site-main max-width-800">

				<div class="alert alert-light" role="alert" style="text-align: center;">
					<p>Infelizmente não foi possível encontrar a página que você procura.<br>Ela pode ter sido removida ou pode ter ocorrido um erro de digitação.</p>
					<hr>
					<a href="<?php echo site_url(); ?>" class="btn btn-outline-dark btn-sm">Voltar ao Início</a> | <a href="javascript:void(0);" class="btn btn-outline-dark btn-sm abre-buscador">Fazer uma Busca</a>
				</div>

			</main><!-- #main -->
		</div><!-- #primary -->
	</div>

<?php
get_footer();
