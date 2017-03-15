<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'dev_sad');

/** Имя пользователя MySQL */
define('DB_USER', 'dev_sad');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', '12QWaszx@33');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '@/9G%OQBE}}kI1+SYs9I$}~8(GW*|H6l0M$VT{y,=jgVfZb^0)<tpeDd.96@SNl~');
define('SECURE_AUTH_KEY',  'X(<nEb@N!!J+Iuz@)VQ+Kq8+mNNHfEC-O^Yby2t%Z7e`VLLjKW-BMl @|]{qfd9U');
define('LOGGED_IN_KEY',    'cTo}@vv>wCZ|-1;`gKVl!tGdGfegJ$9NZq`HBnkKll?1G:#4bwrg{Q:N<S~>S[u2');
define('NONCE_KEY',        '+o_jfOzn^^N;IoETmu1_im~id@Ub)+X]ACdX*`+o)#ir]0.%VI7[ln-8`47qj9+|');
define('AUTH_SALT',        'h9I +6?2ny!(ij:L7V^|%ghJo n5D4f_xN(mCCa!K1>%HF$9Z7+@U@Z2^d1UN`V%');
define('SECURE_AUTH_SALT', '&RcYC+g6-ag:1-PImH/}FY.DY(Vy#t9`+|<k`$>e8=VUtBtUpCaH-R=C-a!@!@Cn');
define('LOGGED_IN_SALT',   '59uWYcX?,v-[|1#{L~T}~3(m-(.5_K7VN_o2KmigJpjg!%}(z-uC]:#Uny3g i%*');
define('NONCE_SALT',       '_*+9t^S}LQ9CZ[qhL_^bpZNzdyJ6davf^%2sfqS|fAu=:oZsz7-g9?p/<gQ|;d+-');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 * 
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');
