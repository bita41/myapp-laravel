<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Custom / non-Laravel-native config
|--------------------------------------------------------------------------
| App-specific settings that are not part of Laravel core config.
*/

return [

    /*
    |--------------------------------------------------------------------------
    | DataTable elements per page
    |--------------------------------------------------------------------------
    | Default number of rows per page for DataTables (legacy / CI4 style).
    */
    'datatable_per_page' => env('DATATABLE_ELEMENTS_PER_PAGE', 50),

];
