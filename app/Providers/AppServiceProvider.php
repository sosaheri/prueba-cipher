<?php

namespace App\Providers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadModuleMigrations();
        $this->mapModuleRoutes();
    }

    /**
     * Load module migrations for the application.
     */
    protected function loadModuleMigrations(): void
    {
        $modulesPath = base_path('app/Modules');

        if (File::isDirectory($modulesPath)) {
            foreach (File::directories($modulesPath) as $moduleDir) {
                $moduleMigrationsPath = $moduleDir . '/Database/Migrations';

                if (File::isDirectory($moduleMigrationsPath)) {
                    $this->loadMigrationsFrom($moduleMigrationsPath);
                    // \Log::info("Cargando migraciones para el módulo: " . basename($moduleDir) . " desde " . $moduleMigrationsPath);
                }
            }
        }
    }

    /**
     * Define the module routes for the application.
     */
    protected function mapModuleRoutes(): void
    {
        $modulesPath = base_path('app/Modules');

        if (File::isDirectory($modulesPath)) {
            foreach (File::directories($modulesPath) as $moduleDir) {
                $moduleName = basename($moduleDir);
                $moduleApiRoutesPath = $moduleDir . '/Routes/api.php';

                if ( File::exists($moduleApiRoutesPath)) {
                    Route::middleware('api') 
                        ->prefix('api')    
                        ->group($moduleApiRoutesPath); 
                    // \Log::info("Cargando rutas API para el módulo: " . $moduleName . " desde " . $moduleApiRoutesPath);
                }
            }
        }
    }
}
