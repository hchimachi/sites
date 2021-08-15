<?php
/**
 * As configurações básicas do WordPress
 *
 * O script de criação wp-config.php usa esse arquivo durante a instalação.
 * Você não precisa usar o site, você pode copiar este arquivo
 * para "wp-config.php" e preencher os valores.
 *
 * Este arquivo contém as seguintes configurações:
 *
 * * Configurações do MySQL
 * * Chaves secretas
 * * Prefixo do banco de dados
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar estas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define( 'DB_NAME', 'Skate' );

/** Usuário do banco de dados MySQL */
define( 'DB_USER', 'admin' );

/** Senha do banco de dados MySQL */
define( 'DB_PASSWORD', 'Denchizik-2018' );

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
 * Você pode alterá-las a qualquer momento para invalidar quaisquer
 * cookies existentes. Isto irá forçar todos os
 * usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '%,yVA8IbwCoQFJ.X6e;q(A [n-e!y8}?$u+VPbXG%lPiE7<6p5T(+5h#[0gP~#$u' );
define( 'SECURE_AUTH_KEY',  '>;uQObY<j#`<Pg*C0,cX<C*`m},cKv~L!WK;MRL$8Ayi^=f0+hF3Qg!Xa;=q~eD}' );
define( 'LOGGED_IN_KEY',    'j0,u!`s;(IPjAzhLhlS`nZsD^yj78u[e(~0$C1R_&CyO?+TJl^H%NRNYKHYa!RB[' );
define( 'NONCE_KEY',        '*}.=oJ3L0cK^g`D;98f=LmP?=OEhoSKE5#y2x+Eihf<wz`UI%MH:WUYarn{&<0}x' );
define( 'AUTH_SALT',        'iK5lI)odTniAV:&`[0e~Rqgw6PrPq*;Xt/:t@ 5uMAUfr+9)ZhH[Tbj_Nq:yF_#>' );
define( 'SECURE_AUTH_SALT', '4ut~8vf%RQK;1N7bJ5j_0f(4BWckS/v=%z?F2H$?Tyr}o<x#lF3E;H>n+?Rc)NI5' );
define( 'LOGGED_IN_SALT',   '4</bOup2VmzSj%uBi1=y?za8Ru1bLRHu,0~oe;*v>j4QP3}%TU4yuL*m+TeR6`74' );
define( 'NONCE_SALT',       'w-s*DkoV?P$Tx_9rg[XjVbbI%S_lwl3Cx8~JV|8;pTV|`&MS@Z 6[vlS{O|rz]L7' );

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der
 * um prefixo único para cada um. Somente números, letras e sublinhados!
 */
$table_prefix = 'wp_';

/**
 * Para desenvolvedores: Modo de debug do WordPress.
 *
 * Altere isto para true para ativar a exibição de avisos
 * durante o desenvolvimento. É altamente recomendável que os
 * desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 *
 * Para informações sobre outras constantes que podem ser utilizadas
 * para depuração, visite o Codex.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Configura as variáveis e arquivos do WordPress. */
require_once ABSPATH . 'wp-settings.php';
