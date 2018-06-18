<?php return array (
  'app' => 
  array (
    'name' => 'Laravel',
    'env' => 'local',
    'debug' => true,
    'url' => 'http://localhost',
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'key' => 'base64:Hg2Hjl7Ci4vhiv3TAbQwzcTi1b+DYGoeUqYGwxVSKqc=',
    'cipher' => 'AES-256-CBC',
    'log' => 'single',
    'log_level' => 'debug',
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Cookie\\CookieServiceProvider',
      6 => 'Illuminate\\Database\\DatabaseServiceProvider',
      7 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      8 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      9 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      10 => 'Illuminate\\Hashing\\HashServiceProvider',
      11 => 'Illuminate\\Mail\\MailServiceProvider',
      12 => 'Illuminate\\Notifications\\NotificationServiceProvider',
      13 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      14 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      15 => 'Illuminate\\Queue\\QueueServiceProvider',
      16 => 'Illuminate\\Redis\\RedisServiceProvider',
      17 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      18 => 'Illuminate\\Session\\SessionServiceProvider',
      19 => 'Illuminate\\Translation\\TranslationServiceProvider',
      20 => 'Illuminate\\Validation\\ValidationServiceProvider',
      21 => 'Illuminate\\View\\ViewServiceProvider',
      22 => 'Laravel\\Tinker\\TinkerServiceProvider',
      23 => 'App\\Providers\\AppServiceProvider',
      24 => 'App\\Providers\\AuthServiceProvider',
      25 => 'App\\Providers\\EventServiceProvider',
      26 => 'App\\Providers\\RouteServiceProvider',
      27 => 'Yajra\\Datatables\\DatatablesServiceProvider',
      28 => 'Elibyy\\TCPDF\\ServiceProvider',
      29 => 'Crabbly\\FPDF\\FpdfServiceProvider',
      30 => 'Barryvdh\\DomPDF\\ServiceProvider',
      31 => 'Barryvdh\\Snappy\\ServiceProvider',
      32 => 'Maatwebsite\\Excel\\ExcelServiceProvider',
      33 => 'Cornford\\Backup\\Providers\\BackupServiceProvider',
      34 => 'Spatie\\Backup\\BackupServiceProvider',
      35 => 'Backpack\\BackupManager\\BackupManagerServiceProvider',
      36 => 'BackupManager\\Laravel\\Laravel5ServiceProvider',
      37 => 'Codedge\\Fpdf\\FpdfServiceProvider',
      38 => 'Anhskohbo\\NoCaptcha\\NoCaptchaServiceProvider',
      39 => 'ConsoleTVs\\Charts\\ChartsServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Broadcast' => 'Illuminate\\Support\\Facades\\Broadcast',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Notification' => 'Illuminate\\Support\\Facades\\Notification',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Redis' => 'Illuminate\\Support\\Facades\\Redis',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'Datatables' => 'yajra\\Datatables\\Datatables',
      'PDF' => 'Elibyy\\TCPDF\\Facades\\TCPDF',
      'domPDF' => 'Barryvdh\\DomPDF\\ServiceProvider',
      'snappyPDF' => 'Barryvdh\\Snappy\\Facades\\SnappyPdf',
      'SnappyImage' => 'Barryvdh\\Snappy\\Facades\\SnappyImage',
      'Excel' => 'Maatwebsite\\Excel\\Facades\\Excel',
      'Backpack' => 'Backpack\\BackupManager\\BackupManagerServiceProvider',
      'Backup' => 'BackupManager\\Laravel\\Laravel5ServiceProvider',
      'Fpdf' => 'Codedge\\Fpdf\\Facades\\Fpdf',
      'Charts' => 'ConsoleTVs\\Charts\\Facades\\Charts',
    ),
  ),
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'session',
        'provider' => 'users',
      ),
      'api' => 
      array (
        'driver' => 'token',
        'provider' => 'users',
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\User',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'table' => 'password_resets',
        'expire' => 60,
      ),
    ),
  ),
  'backup-manager' => 
  array (
    'local' => 
    array (
      'type' => 'Local',
      'root' => '/Applications/MAMP/htdocs/edu_soft/storage/app',
    ),
    's3' => 
    array (
      'type' => 'AwsS3',
      'key' => '',
      'secret' => '',
      'region' => 'us-east-1',
      'bucket' => '',
      'root' => '',
    ),
    'gcs' => 
    array (
      'type' => 'Gcs',
      'key' => '',
      'secret' => '',
      'bucket' => '',
      'root' => '',
    ),
    'rackspace' => 
    array (
      'type' => 'Rackspace',
      'username' => '',
      'key' => '',
      'container' => '',
      'zone' => '',
      'endpoint' => 'https://identity.api.rackspacecloud.com/v2.0/',
      'root' => '',
    ),
    'dropbox' => 
    array (
      'type' => 'Dropbox',
      'token' => '',
      'key' => '',
      'secret' => '',
      'app' => '',
      'root' => '',
    ),
    'ftp' => 
    array (
      'type' => 'Ftp',
      'host' => '',
      'username' => '',
      'password' => '',
      'port' => 21,
      'passive' => true,
      'ssl' => true,
      'timeout' => 30,
      'root' => '',
    ),
    'sftp' => 
    array (
      'type' => 'Sftp',
      'host' => '',
      'username' => '',
      'password' => '',
      'port' => 21,
      'timeout' => 10,
      'privateKey' => '',
      'root' => '',
    ),
  ),
  'backup' => 
  array (
    'enabled' => true,
    'path' => '../storage/backup/',
    'filename' => 'backup-20180519-173103',
    'compress' => true,
    'processors' => 
    array (
      'mysql' => 
      array (
        'export' => '/usr/bin/',
        'restore' => '/usr/bin/',
      ),
      'pqsql' => 
      array (
        'export' => '/usr/bin/',
        'restore' => '/usr/bin/',
      ),
      'sqlite' => 
      array (
        'export' => NULL,
        'restore' => NULL,
      ),
      'sqlsrv' => 
      array (
        'export' => '/usr/bin/',
        'restore' => '/usr/bin/',
      ),
    ),
    'backup' => 
    array (
      'name' => 'http://localhost',
      'source' => 
      array (
        'files' => 
        array (
          'include' => 
          array (
            0 => '/Applications/MAMP/htdocs/edu_soft',
          ),
          'exclude' => 
          array (
            0 => '/Applications/MAMP/htdocs/edu_soft/vendor',
            1 => '/Applications/MAMP/htdocs/edu_soft/node_modules',
          ),
          'followLinks' => false,
        ),
        'databases' => 
        array (
          0 => 'mysql',
        ),
      ),
      'gzip_database_dump' => false,
      'destination' => 
      array (
        'filename_prefix' => '',
        'disks' => 
        array (
          0 => 'local',
        ),
      ),
    ),
    'notifications' => 
    array (
      'notifications' => 
      array (
        'Spatie\\Backup\\Notifications\\Notifications\\BackupHasFailed' => 
        array (
          0 => 'mail',
        ),
        'Spatie\\Backup\\Notifications\\Notifications\\UnhealthyBackupWasFound' => 
        array (
          0 => 'mail',
        ),
        'Spatie\\Backup\\Notifications\\Notifications\\CleanupHasFailed' => 
        array (
          0 => 'mail',
        ),
        'Spatie\\Backup\\Notifications\\Notifications\\BackupWasSuccessful' => 
        array (
          0 => 'mail',
        ),
        'Spatie\\Backup\\Notifications\\Notifications\\HealthyBackupWasFound' => 
        array (
          0 => 'mail',
        ),
        'Spatie\\Backup\\Notifications\\Notifications\\CleanupWasSuccessful' => 
        array (
          0 => 'mail',
        ),
      ),
      'notifiable' => 'Spatie\\Backup\\Notifications\\Notifiable',
      'mail' => 
      array (
        'to' => 'your@example.com',
      ),
      'slack' => 
      array (
        'webhook_url' => '',
        'channel' => NULL,
      ),
    ),
    'monitorBackups' => 
    array (
      0 => 
      array (
        'name' => 'http://localhost',
        'disks' => 
        array (
          0 => 'local',
        ),
        'newestBackupsShouldNotBeOlderThanDays' => 1,
        'storageUsedMayNotBeHigherThanMegabytes' => 5000,
      ),
    ),
    'cleanup' => 
    array (
      'strategy' => 'Spatie\\Backup\\Tasks\\Cleanup\\Strategies\\DefaultStrategy',
      'defaultStrategy' => 
      array (
        'keepAllBackupsForDays' => 7,
        'keepDailyBackupsForDays' => 16,
        'keepWeeklyBackupsForWeeks' => 8,
        'keepMonthlyBackupsForMonths' => 4,
        'keepYearlyBackupsForYears' => 2,
        'deleteOldestBackupsWhenUsingMoreMegabytesThan' => 5000,
      ),
    ),
  ),
  'broadcasting' => 
  array (
    'default' => 'log',
    'connections' => 
    array (
      'pusher' => 
      array (
        'driver' => 'pusher',
        'key' => '',
        'secret' => '',
        'app_id' => '',
        'options' => 
        array (
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
      'log' => 
      array (
        'driver' => 'log',
      ),
      'null' => 
      array (
        'driver' => 'null',
      ),
    ),
  ),
  'cache' => 
  array (
    'default' => 'file',
    'stores' => 
    array (
      'apc' => 
      array (
        'driver' => 'apc',
      ),
      'array' => 
      array (
        'driver' => 'array',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'cache',
        'connection' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => '/Applications/MAMP/htdocs/edu_soft/storage/framework/cache/data',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'persistent_id' => NULL,
        'sasl' => 
        array (
          0 => NULL,
          1 => NULL,
        ),
        'options' => 
        array (
        ),
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
    ),
    'prefix' => 'laravel',
  ),
  'database' => 
  array (
    'default' => 'mysql',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'database' => 'collegeDB',
        'prefix' => '',
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'collegeDB',
        'username' => 'heroes_user',
        'password' => 'Rc1052258',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => false,
        'engine' => NULL,
        'dump' => 
        array (
          'dump_binary_path' => '/Applications/MAMP/Library/bin/',
          0 => 'use_single_transaction',
          'timeout' => 300,
        ),
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'collegeDB',
        'username' => 'heroes_user',
        'password' => 'Rc1052258',
        'charset' => 'utf8',
        'prefix' => '',
        'schema' => 'public',
        'sslmode' => 'prefer',
      ),
      'sqlsrv' => 
      array (
        'driver' => 'sqlsrv',
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'collegeDB',
        'username' => 'heroes_user',
        'password' => 'Rc1052258',
        'charset' => 'utf8',
        'prefix' => '',
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'client' => 'predis',
      'default' => 
      array (
        'host' => '127.0.0.1',
        'password' => NULL,
        'port' => '6379',
        'database' => 0,
      ),
    ),
  ),
  'datatables' => 
  array (
    'search' => 
    array (
      'smart' => true,
      'case_insensitive' => true,
      'use_wildcards' => false,
    ),
    'index_column' => 'DT_Row_Index',
    'fractal' => 
    array (
      'includes' => 'include',
      'serializer' => 'League\\Fractal\\Serializer\\DataArraySerializer',
    ),
    'engines' => 
    array (
      'eloquent' => 'Yajra\\Datatables\\Engines\\EloquentEngine',
      'query' => 'Yajra\\Datatables\\Engines\\QueryBuilderEngine',
      'collection' => 'Yajra\\Datatables\\Engines\\CollectionEngine',
    ),
    'builders' => 
    array (
      'Illuminate\\Database\\Eloquent\\Relations\\Relation' => 'eloquent',
      'Illuminate\\Database\\Eloquent\\Builder' => 'eloquent',
      'Illuminate\\Database\\Query\\Builder' => 'query',
      'Illuminate\\Support\\Collection' => 'collection',
    ),
    'nulls_last_sql' => '%s %s NULLS LAST',
    'error' => NULL,
    'columns' => 
    array (
      'excess' => 
      array (
        0 => 'rn',
        1 => 'row_num',
      ),
      'escape' => '*',
      'raw' => 
      array (
        0 => 'action',
      ),
      'blacklist' => 
      array (
        0 => 'password',
        1 => 'remember_token',
      ),
      'whitelist' => '*',
    ),
    'json' => 
    array (
      'header' => 
      array (
      ),
      'options' => 0,
    ),
  ),
  'dompdf' => 
  array (
    'show_warnings' => false,
    'orientation' => 'portrait',
    'defines' => 
    array (
      'DOMPDF_FONT_DIR' => '/Applications/MAMP/htdocs/edu_soft/storage/fonts/',
      'DOMPDF_FONT_CACHE' => '/Applications/MAMP/htdocs/edu_soft/storage/fonts/',
      'DOMPDF_TEMP_DIR' => '/var/folders/h1/r2s6308x3z7_t59dtd6zpbd40000gq/T',
      'DOMPDF_CHROOT' => '/Applications/MAMP/htdocs/edu_soft',
      'DOMPDF_UNICODE_ENABLED' => true,
      'DOMPDF_ENABLE_FONT_SUBSETTING' => false,
      'DOMPDF_PDF_BACKEND' => 'CPDF',
      'DOMPDF_DEFAULT_MEDIA_TYPE' => 'screen',
      'DOMPDF_DEFAULT_PAPER_SIZE' => 'a4',
      'DOMPDF_DEFAULT_FONT' => 'serif',
      'DOMPDF_DPI' => 96,
      'DOMPDF_ENABLE_PHP' => false,
      'DOMPDF_ENABLE_JAVASCRIPT' => true,
      'DOMPDF_ENABLE_REMOTE' => true,
      'DOMPDF_FONT_HEIGHT_RATIO' => 1.100000000000000088817841970012523233890533447265625,
      'DOMPDF_ENABLE_CSS_FLOAT' => false,
      'DOMPDF_ENABLE_HTML5PARSER' => false,
    ),
  ),
  'excel' => 
  array (
    'cache' => 
    array (
      'enable' => true,
      'driver' => 'memory',
      'settings' => 
      array (
        'memoryCacheSize' => '32MB',
        'cacheTime' => 600,
      ),
      'memcache' => 
      array (
        'host' => 'localhost',
        'port' => 11211,
      ),
      'dir' => '/Applications/MAMP/htdocs/edu_soft/storage/cache',
    ),
    'properties' => 
    array (
      'creator' => 'Maatwebsite',
      'lastModifiedBy' => 'Maatwebsite',
      'title' => 'Spreadsheet',
      'description' => 'Default spreadsheet export',
      'subject' => 'Spreadsheet export',
      'keywords' => 'maatwebsite, excel, export',
      'category' => 'Excel',
      'manager' => 'Maatwebsite',
      'company' => 'Maatwebsite',
    ),
    'sheets' => 
    array (
      'pageSetup' => 
      array (
        'orientation' => 'portrait',
        'paperSize' => '9',
        'scale' => '100',
        'fitToPage' => false,
        'fitToHeight' => true,
        'fitToWidth' => true,
        'columnsToRepeatAtLeft' => 
        array (
          0 => '',
          1 => '',
        ),
        'rowsToRepeatAtTop' => 
        array (
          0 => 0,
          1 => 0,
        ),
        'horizontalCentered' => false,
        'verticalCentered' => false,
        'printArea' => NULL,
        'firstPageNumber' => NULL,
      ),
    ),
    'creator' => 'Maatwebsite',
    'csv' => 
    array (
      'delimiter' => ',',
      'enclosure' => '"',
      'line_ending' => '
',
      'use_bom' => false,
    ),
    'export' => 
    array (
      'autosize' => true,
      'autosize-method' => 'approx',
      'generate_heading_by_indices' => true,
      'merged_cell_alignment' => 'left',
      'calculate' => false,
      'includeCharts' => false,
      'sheets' => 
      array (
        'page_margin' => false,
        'nullValue' => NULL,
        'startCell' => 'A1',
        'strictNullComparison' => false,
      ),
      'store' => 
      array (
        'path' => '/Applications/MAMP/htdocs/edu_soft/storage/exports',
        'returnInfo' => false,
      ),
      'pdf' => 
      array (
        'driver' => 'DomPDF',
        'drivers' => 
        array (
          'DomPDF' => 
          array (
            'path' => '/Applications/MAMP/htdocs/edu_soft/vendor/dompdf/dompdf/',
          ),
          'tcPDF' => 
          array (
            'path' => '/Applications/MAMP/htdocs/edu_soft/vendor/tecnick.com/tcpdf/',
          ),
          'mPDF' => 
          array (
            'path' => '/Applications/MAMP/htdocs/edu_soft/vendor/mpdf/mpdf/',
          ),
        ),
      ),
    ),
    'filters' => 
    array (
      'registered' => 
      array (
        'chunk' => 'Maatwebsite\\Excel\\Filters\\ChunkReadFilter',
      ),
      'enabled' => 
      array (
      ),
    ),
    'import' => 
    array (
      'heading' => 'slugged',
      'startRow' => 1,
      'separator' => '_',
      'slug_whitelist' => '._',
      'includeCharts' => false,
      'to_ascii' => true,
      'encoding' => 
      array (
        'input' => 'UTF-8',
        'output' => 'UTF-8',
      ),
      'calculate' => true,
      'ignoreEmpty' => false,
      'force_sheets_collection' => false,
      'dates' => 
      array (
        'enabled' => true,
        'format' => false,
        'columns' => 
        array (
        ),
      ),
      'sheets' => 
      array (
        'test' => 
        array (
          'firstname' => 'A2',
        ),
      ),
    ),
    'views' => 
    array (
      'styles' => 
      array (
        'th' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'strong' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'b' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'i' => 
        array (
          'font' => 
          array (
            'italic' => true,
            'size' => 12,
          ),
        ),
        'h1' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 24,
          ),
        ),
        'h2' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 18,
          ),
        ),
        'h3' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 13.5,
          ),
        ),
        'h4' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'h5' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 10,
          ),
        ),
        'h6' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 7.5,
          ),
        ),
        'a' => 
        array (
          'font' => 
          array (
            'underline' => true,
            'color' => 
            array (
              'argb' => 'FF0000FF',
            ),
          ),
        ),
        'hr' => 
        array (
          'borders' => 
          array (
            'bottom' => 
            array (
              'style' => 'thin',
              'color' => 
              array (
                0 => 'FF000000',
              ),
            ),
          ),
        ),
      ),
    ),
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'cloud' => 's3',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => '/Applications/MAMP/htdocs/edu_soft/storage/app',
      ),
      'storage' => 
      array (
        'driver' => 'local',
        'root' => '/Applications/MAMP/htdocs/edu_soft/storage/app/public',
        'url' => 'http://localhost/storage',
        'visibility' => 'public',
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => '/Applications/MAMP/htdocs/edu_soft/public',
        'url' => 'http://localhost/public',
        'visibility' => 'public',
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => NULL,
        'secret' => NULL,
        'region' => NULL,
        'bucket' => NULL,
      ),
      'backups' => 
      array (
        'driver' => 'local',
        'root' => '/Applications/MAMP/htdocs/edu_soft/storage/app/public/backups',
      ),
    ),
  ),
  'laravel-backup' => 
  array (
    'backup' => 
    array (
      'name' => 'http://localhost',
      'source' => 
      array (
        'files' => 
        array (
          'include' => 
          array (
            0 => '/Applications/MAMP/htdocs/edu_soft',
          ),
          'exclude' => 
          array (
            0 => '/Applications/MAMP/htdocs/edu_soft/vendor',
            1 => '/Applications/MAMP/htdocs/edu_soft/storage',
          ),
        ),
        'databases' => 
        array (
          0 => 'mysql',
        ),
      ),
      'destination' => 
      array (
        'disks' => 
        array (
          0 => 'backups',
        ),
      ),
    ),
    'notifications' => 
    array (
      'handler' => 'Spatie\\Backup\\Notifications\\Notifier',
      'events' => 
      array (
        'whenBackupWasSuccessful' => 
        array (
          0 => 'log',
        ),
        'whenCleanupWasSuccessful' => 
        array (
          0 => 'log',
        ),
        'whenHealthyBackupWasFound' => 
        array (
          0 => 'log',
        ),
        'whenBackupHasFailed' => 
        array (
          0 => 'log',
          1 => 'mail',
        ),
        'whenCleanupHasFailed' => 
        array (
          0 => 'log',
          1 => 'mail',
        ),
        'whenUnHealthyBackupWasFound' => 
        array (
          0 => 'log',
          1 => 'mail',
        ),
      ),
      'mail' => 
      array (
        'from' => 'your@email.com',
        'to' => 'your@email.com',
      ),
      'slack' => 
      array (
        'channel' => '#backups',
        'username' => 'Backup bot',
        'icon' => ':robot:',
      ),
    ),
    'monitorBackups' => 
    array (
      0 => 
      array (
        'name' => 'http://localhost',
        'disks' => 
        array (
          0 => 'backups',
        ),
        'newestBackupsShouldNotBeOlderThanDays' => 1,
        'storageUsedMayNotBeHigherThanMegabytes' => 5000,
      ),
    ),
    'cleanup' => 
    array (
      'strategy' => 'Spatie\\Backup\\Tasks\\Cleanup\\Strategies\\DefaultStrategy',
      'defaultStrategy' => 
      array (
        'keepAllBackupsForDays' => 7,
        'keepDailyBackupsForDays' => 16,
        'keepWeeklyBackupsForWeeks' => 8,
        'keepMonthlyBackupsForMonths' => 4,
        'keepYearlyBackupsForYears' => 2,
        'deleteOldestBackupsWhenUsingMoreMegabytesThan' => 5000,
      ),
    ),
  ),
  'mail' => 
  array (
    'driver' => 'smtp',
    'host' => 'smtp.gmail.com',
    'port' => '587',
    'from' => 
    array (
      'address' => 'edusoftemail@gmail.com',
      'name' => 'EduSoft Email Server',
    ),
    'encryption' => 'tls',
    'username' => 'infopubtech',
    'password' => 'Rc1052258',
    'sendmail' => '/usr/sbin/sendmail -t',
    'pretend' => false,
  ),
  'queue' => 
  array (
    'default' => 'sync',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'retry_after' => 90,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => 'your-public-key',
        'secret' => 'your-secret-key',
        'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
        'queue' => 'your-queue-name',
        'region' => 'us-east-1',
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'retry_after' => 90,
      ),
    ),
    'failed' => 
    array (
      'database' => 'mysql',
      'table' => 'failed_jobs',
    ),
  ),
  'services' => 
  array (
    'mailgun' => 
    array (
      'domain' => NULL,
      'secret' => NULL,
    ),
    'ses' => 
    array (
      'key' => NULL,
      'secret' => NULL,
      'region' => 'us-east-1',
    ),
    'sparkpost' => 
    array (
      'secret' => NULL,
    ),
    'stripe' => 
    array (
      'model' => 'App\\User',
      'key' => NULL,
      'secret' => NULL,
    ),
  ),
  'session' => 
  array (
    'driver' => 'file',
    'lifetime' => 120,
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => '/Applications/MAMP/htdocs/edu_soft/storage/framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'store' => NULL,
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'laravel_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => false,
    'http_only' => true,
  ),
  'snappy' => 
  array (
    'pdf' => 
    array (
      'enabled' => true,
      'binary' => '/usr/local/bin/wkhtmltopdf',
      'timeout' => false,
      'options' => 
      array (
      ),
      'env' => 
      array (
      ),
    ),
    'image' => 
    array (
      'enabled' => true,
      'binary' => '/usr/local/bin/wkhtmltoimage',
      'timeout' => false,
      'options' => 
      array (
      ),
      'env' => 
      array (
      ),
    ),
  ),
  'tcpdf' => 
  array (
    'page_format' => 'A4',
    'page_orientation' => 'P',
    'page_units' => 'mm',
    'unicode' => true,
    'encoding' => 'UTF-8',
    'font_directory' => '',
    'image_directory' => '',
    'tcpdf_throw_exception' => false,
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => '/Applications/MAMP/htdocs/edu_soft/resources/views',
    ),
    'compiled' => '/Applications/MAMP/htdocs/edu_soft/storage/framework/views',
  ),
  0 => 'config/laravel-backup.php',
  'charts' => 
  array (
    'default' => 
    array (
      'type' => 'line',
      'library' => 'material',
      'element_label' => 'Element',
      'empty_dataset_label' => 'No Data Set',
      'empty_dataset_value' => 0,
      'title' => 'My Cool Chart',
      'height' => 400,
      'width' => 0,
      'responsive' => false,
      'background_color' => 'inherit',
      'colors' => 
      array (
      ),
      'one_color' => false,
      'template' => 'material',
      'legend' => true,
      'x_axis_title' => false,
      'y_axis_title' => NULL,
      'loader' => 
      array (
        'active' => true,
        'duration' => 500,
        'color' => '#000000',
      ),
    ),
    'templates' => 
    array (
      'material' => 
      array (
        0 => '#2196F3',
        1 => '#F44336',
        2 => '#FFC107',
      ),
      'red-material' => 
      array (
        0 => '#B71C1C',
        1 => '#F44336',
        2 => '#E57373',
      ),
      'indigo-material' => 
      array (
        0 => '#1A237E',
        1 => '#3F51B5',
        2 => '#7986CB',
      ),
      'blue-material' => 
      array (
        0 => '#0D47A1',
        1 => '#2196F3',
        2 => '#64B5F6',
      ),
      'teal-material' => 
      array (
        0 => '#004D40',
        1 => '#009688',
        2 => '#4DB6AC',
      ),
      'green-material' => 
      array (
        0 => '#1B5E20',
        1 => '#4CAF50',
        2 => '#81C784',
      ),
      'yellow-material' => 
      array (
        0 => '#F57F17',
        1 => '#FFEB3B',
        2 => '#FFF176',
      ),
      'orange-material' => 
      array (
        0 => '#E65100',
        1 => '#FF9800',
        2 => '#FFB74D',
      ),
    ),
    'assets' => 
    array (
      'global' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js',
        ),
      ),
      'canvas-gauges' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdn.rawgit.com/Mikhus/canvas-gauges/gh-pages/download/2.1.2/all/gauge.min.js',
        ),
      ),
      'chartist' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/chartist/0.10.1/chartist.min.js',
        ),
        'styles' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/chartist/0.10.1/chartist.min.css',
        ),
      ),
      'chartjs' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js',
        ),
      ),
      'fusioncharts' => 
      array (
        'scripts' => 
        array (
          0 => 'https://static.fusioncharts.com/code/latest/fusioncharts.js',
          1 => 'https://static.fusioncharts.com/code/latest/themes/fusioncharts.theme.fint.js',
        ),
      ),
      'google' => 
      array (
        'scripts' => 
        array (
          0 => 'https://www.google.com/jsapi',
          1 => 'https://www.gstatic.com/charts/loader.js',
          2 => 'google.charts.load(\'current\', {\'packages\':[\'corechart\', \'gauge\', \'geochart\', \'bar\', \'line\']})',
        ),
      ),
      'highcharts' => 
      array (
        'styles' => 
        array (
        ),
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/highcharts/5.0.7/highcharts.js',
          1 => 'https://cdnjs.cloudflare.com/ajax/libs/highcharts/5.0.7/js/modules/offline-exporting.js',
          2 => 'https://cdnjs.cloudflare.com/ajax/libs/highmaps/5.0.7/js/modules/map.js',
          3 => 'https://cdnjs.cloudflare.com/ajax/libs/highmaps/5.0.7/js/modules/data.js',
          4 => 'https://code.highcharts.com/mapdata/custom/world.js',
        ),
      ),
      'justgage' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.6/raphael.min.js',
          1 => 'https://cdnjs.cloudflare.com/ajax/libs/justgage/1.2.2/justgage.min.js',
        ),
      ),
      'morris' => 
      array (
        'styles' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css',
        ),
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.6/raphael.min.js',
          1 => 'https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js',
        ),
      ),
      'plottablejs' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js',
          1 => 'https://cdnjs.cloudflare.com/ajax/libs/plottable.js/2.8.0/plottable.min.js',
        ),
        'styles' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/plottable.js/2.2.0/plottable.css',
        ),
      ),
      'progressbarjs' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/progressbar.js/1.0.1/progressbar.min.js',
        ),
      ),
      'c3' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js',
          1 => 'https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.min.js',
        ),
        'styles' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.min.css',
        ),
      ),
      'echarts' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/echarts/3.6.2/echarts.min.js',
        ),
      ),
      'amcharts' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/amcharts/3.21.2/amcharts.js',
          1 => 'https://cdnjs.cloudflare.com/ajax/libs/amcharts/3.21.2/serial.js',
          2 => 'https://cdnjs.cloudflare.com/ajax/libs/amcharts/3.21.2/plugins/export/export.min.js',
          3 => 'https://cdnjs.cloudflare.com/ajax/libs/amcharts/3.21.2/themes/light.js',
        ),
        'styles' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/amcharts/3.21.2/plugins/export/export.css',
        ),
      ),
    ),
  ),
  'backpack' => 
  array (
    'backupmanager' => 
    array (
      'backup' => 
      array (
        'name' => 'http://localhost',
        'source' => 
        array (
          'files' => 
          array (
            'include' => 
            array (
              0 => '/Applications/MAMP/htdocs/edu_soft',
            ),
            'exclude' => 
            array (
              0 => '/Applications/MAMP/htdocs/edu_soft/vendor',
              1 => '/Applications/MAMP/htdocs/edu_soft/storage',
            ),
          ),
          'databases' => 
          array (
            0 => 'mysql',
          ),
        ),
        'destination' => 
        array (
          'disks' => 
          array (
            0 => 'backups',
          ),
        ),
      ),
      'cleanup' => 
      array (
        'strategy' => 'Spatie\\Backup\\Tasks\\Cleanup\\Strategies\\DefaultStrategy',
        'defaultStrategy' => 
        array (
          'keepAllBackupsForDays' => 7,
          'keepDailyBackupsForDays' => 16,
          'keepWeeklyBackupsForWeeks' => 8,
          'keepMonthlyBackupsForMonths' => 4,
          'keepYearlyBackupsForYears' => 2,
          'deleteOldestBackupsWhenUsingMoreMegabytesThan' => 5000,
        ),
      ),
      'monitorBackups' => 
      array (
        0 => 
        array (
          'name' => 'http://localhost',
          'disks' => 
          array (
            0 => 'backups',
          ),
          'newestBackupsShouldNotBeOlderThanDays' => 1,
          'storageUsedMayNotBeHigherThanMegabytes' => 5000,
        ),
      ),
      'notifications' => 
      array (
        'handler' => 'Spatie\\Backup\\Notifications\\Notifier',
        'events' => 
        array (
          'whenBackupWasSuccessful' => 
          array (
            0 => 'log',
          ),
          'whenCleanupWasSuccessful' => 
          array (
            0 => 'log',
          ),
          'whenHealthyBackupWasFound' => 
          array (
            0 => 'log',
          ),
          'whenBackupHasFailed' => 
          array (
            0 => 'log',
            1 => 'mail',
          ),
          'whenCleanupHasFailed' => 
          array (
            0 => 'log',
            1 => 'mail',
          ),
          'whenUnHealthyBackupWasFound' => 
          array (
            0 => 'log',
            1 => 'mail',
          ),
        ),
        'mail' => 
        array (
          'from' => 'your@email.com',
          'to' => 'your@email.com',
        ),
        'slack' => 
        array (
          'channel' => '#backups',
          'username' => 'Backup bot',
          'icon' => ':robot:',
        ),
      ),
    ),
  ),
  'captcha' => 
  array (
    'secret' => NULL,
    'sitekey' => NULL,
    'options' => 
    array (
      'timeout' => 2.0,
    ),
  ),
  'image' => 
  array (
    'driver' => 'gd',
  ),
  'fpdf' => 
  array (
    'orientation' => 'P',
    'unit' => 'mm',
    'size' => 'A4',
  ),
);
