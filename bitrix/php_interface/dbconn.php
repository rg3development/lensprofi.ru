<?define("DBPersistent", false);
$DBType = "mysql";
#$DBHost = "localhost";
#$DBHost = "linzprofy.mysql";
#$DBHost = "localhost";
#$DBHost = "linzprofy.mysql";
#$DBHost = "localhost";
#$DBHost = "linzprofy.mysql";
$DBHost = "localhost";






#$DBLogin = "root";
#$DBLogin = "linzprofy_mysql";
#$DBLogin = "root";
#$DBLogin = "linzprofy_mysql";
#$DBLogin = "root";
#$DBLogin = "linzprofy_mysql";
$DBLogin = "root";






#$DBPassword = "qwerty";
#$DBPassword = "48l3hzzg";
#$DBPassword = "root";
#$DBPassword = "48l3hzzg";
#$DBPassword = "root";
#$DBPassword = "48l3hzzg";
$DBPassword = "Rb9Ls64";






#$DBName = "lin";
#$DBName = "linzprofy_db";
#$DBName = "linzi";
#$DBName = "linzprofy_db";
#$DBName = "linzi";
#$DBName = "linzprofy_db";
$DBName = "lensprofi.rg3.su";






$DBDebug = true;
$DBDebugToFile = false;

@set_time_limit(600);

define("DELAY_DB_CONNECT", true);
define("CACHED_b_file", 3600);
define("CACHED_b_file_bucket_size", 10);
define("CACHED_b_lang", 3600);
define("CACHED_b_option", 3600);
define("CACHED_b_lang_domain", 3600);
define("CACHED_b_site_template", 3600);
define("CACHED_b_event", 3600);
define("CACHED_b_agent", 3660);
define("CACHED_menu", 3600);

define("BX_UTF", true);
define("BX_FILE_PERMISSIONS", 0644);
define("BX_DIR_PERMISSIONS", 0755);
@umask(~BX_DIR_PERMISSIONS);
define("BX_DISABLE_INDEX_PAGE", true);

?>
