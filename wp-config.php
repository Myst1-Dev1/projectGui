<?php
/**
 * As configurações básicas do WordPress
 *
 * O script de criação wp-config.php usa esse arquivo durante a instalação.
 * Você não precisa user o site, você pode copiar este arquivo
 * para "wp-config.php" e preencher os valores.
 *
 * Este arquivo contém as seguintes configurações:
 *
 * * Configurações do MySQL
 * * Chaves secretas
 * * Prefixo do banco de dados
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/pt-br:Editando_wp-config.php
 *
 * @package WordPress
 */
// ** Configurações do MySQL - Você pode pegar estas informações
// com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define( 'DB_NAME', 'frelaMK' ); 
/** Usuário do banco de dados MySQL */
define( 'DB_USER', 'root' );
/** Senha do banco de dados MySQL */
define( 'DB_PASSWORD', '' );
/** Nome do host do MySQL */
define( 'DB_HOST', 'localhost' );
/** Charset do banco de dados a ser usado na criação das tabelas. */
define( 'DB_CHARSET', 'utf8mb4' );
/** O tipo de Collate do banco de dados. Não altere isso se tiver dúvidas. */
define( 'DB_COLLATE', '' );
/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las
 * usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org
 * secret-key service}
 * Você pode alterá-las a qualquer momento para desvalidar quaisquer
 * cookies existentes. Isto irá forçar todos os
 * usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '9BI4:!vh)(H?ZNo|_7|dh3O$cR|W(o;!`HW-oT8YgKAXP>ri5akyW{y]Ry3}wt(;');
define('SECURE_AUTH_KEY',  '84Fn$OU96!DDJbHiv5S<t^J=h%)dYxs+fFH*cg?[~;Ia`6g%p)r!CA{CKTo-]b)/');
define('LOGGED_IN_KEY',    'RmRePwv+)svi~cAf->{!H6P=URmpCp6d[Gq2kZCLXdwoXTq`--BucXb*ve|@9)u-');
define('NONCE_KEY',        'Tri,`f~jRiL|kdMfFLwh(f?XThXhe[[!^5!;]%~{UGln>zJ*ynJPR*scF-[lmL&#');
define('AUTH_SALT',        'qL;#MiJC9vpOOv3dc<q AcUa]vkH$eBm Ip`Bp(/N*.2>Q+K^fpHmfbbUKaJ{-pO');
define('SECURE_AUTH_SALT', 'RUXs?#6WeX=Z9agrg}e0OC8!x}i$b$;]U7?Ms%Q>+S~T;WuG*te;]({7l4i;+yL/');
define('LOGGED_IN_SALT',   'HVNR <X]o4.fp$1`s~.81M+(lzj+0=IQUPBX&6R7c*2@&e}Ehn^l#>Mq:TvN|=qf');
define('NONCE_SALT',       'NmPqI3`hT@Z>j0yhBO%2(_#~6r,jdG5gzASMJZT%yu[Bk!RM3-AQg`ruK/ur,8Qn');
/**#@-*/
/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der
 * para cada um um único prefixo. Somente números, letras e sublinhados!
 */
$table_prefix  = 'wp_';
/**
 * Para desenvolvedores: Modo debugging WordPress.
 *
 * Altere isto para true para ativar a exibição de avisos
 * durante o desenvolvimento. É altamente recomendável que os
 * desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 *
 * Para informações sobre outras constantes que podem ser utilizadas
 * para depuração, visite o Codex.
 *
 * @link https://codex.wordpress.org/pt-br:Depura%C3%A7%C3%A3o_no_WordPress
 */
define( 'WP_DEBUG', false );
// Enable Debug logging to the /wp-content/debug.log file
define( 'WP_DEBUG_LOG', false );
// Disable display of errors and warnings 
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );
// Use dev versions of core JS and CSS files (only needed if you are modifying these core files)
define( 'SCRIPT_DEBUG', true );

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
define('ABSPATH', dirname(__FILE__) . '/');

/* THIS IS CUSTOM CODE CREATED AT ZEROFRACTAL TO MAKE SITE ACCESS DYNAMIC */
$currenthost = "http://".$_SERVER['HTTP_HOST'];
$currentpath = preg_replace('@/+$@','',dirname($_SERVER['SCRIPT_NAME']));
$currentpath = preg_replace('/\/wp.+/','',$currentpath);
define('WP_HOME',$currenthost.$currentpath);
define('WP_SITEURL',$currenthost.$currentpath);
define('WP_CONTENT_URL', $currenthost.$currentpath.'/wp-content');
define('WP_PLUGIN_URL', $currenthost.$currentpath.'/wp-content/plugins');
define('DOMAIN_CURRENT_SITE', $currenthost.$currentpath );
@define('ADMIN_COOKIE_PATH', './');
@ini_set('upload_max_size' , '256M' );

//define( 'WP_HOME', 'http://example.com' );
//define( 'WP_SITEURL', 'http://example.com' );

/** Configura as variáveis e arquivos do WordPress. */
require_once(ABSPATH . 'wp-settings.php');
/** Configurações para Amazon */
//define('WP_TEMP_DIR', '/opt/bitnami/apps/wordpress/tmp');
//define('FS_METHOD', 'direct');
//  Disable pingback.ping xmlrpc method to prevent Wordpress from participating in DDoS attacks
//  More info at: https://docs.bitnami.com/?page=apps&name=wordpress&section=how-to-re-enable-the-xml-rpc-pingback-feature
// remove x-pingback HTTP header
add_filter('wp_headers', function($headers) {
    unset($headers['X-Pingback']);
    return $headers;
});
// disable pingbacks
add_filter( 'xmlrpc_methods', function( $methods ) {
        unset( $methods['pingback.ping'] );
        return $methods;
});
add_filter( 'auto_update_translation', '__return_false' );