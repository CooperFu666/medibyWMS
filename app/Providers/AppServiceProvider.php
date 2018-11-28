<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //  添加mysql日志监听器
//        DB::listen(
//            function ($sql) {
//                foreach ($sql->bindings as $i => $binding) {
//                    if ($binding instanceof \DateTime) {
//                        $sql->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
//                    } else {
//                        if (is_string($binding)) {
//                            $sql->bindings[$i] = "'$binding'";
//                        }
//                    }
//                }
//                // Insert bindings into query
//                $query = str_replace(array('%', '?'), array('%%', '%s'), $sql->sql);
//                $query = vsprintf($query, $sql->bindings) . ';';
////                $query .= ' ---> time:'.$sql->time;
//                $logFile = fopen(
//                    storage_path('logs' . DIRECTORY_SEPARATOR . date('Ymd') . '_query.log'),
//                    'a+'
//                );
//                fwrite($logFile, date('Y-m-d H:i:s') . ': ' . $query . PHP_EOL);
//                fclose($logFile);
//            }
//        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
