<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class MakeModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea una nueva estructura de módulo con carpetas básicas (Controllers, Models, Services, Requests, Resources, Routes).';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $moduleName = $this->argument('name');
        $moduleName = ucfirst($moduleName);
        $basePath = app_path('Modules/' . $moduleName);

        $subfolders = [
            'Controllers',
            'Models',
            'Services',
            'Providers',
            'Requests',
            'Resources',
            'Routes',
            'Database/Migrations',
        ];

        $this->info("Creando estructura para el módulo: {$moduleName}...");

        if (File::makeDirectory($basePath, 0755, true, true)) {
            $this->info("Directorio '{$basePath}' creado.");

            foreach ($subfolders as $folder) {
                $folderPath = $basePath . '/' . $folder;
                if (!File::exists($folderPath)) {
                    File::makeDirectory($folderPath, 0755, true, true);
                    $this->line("  - Subcarpeta '{$folder}' creada.");
                } else {
                    $this->line("  - Subcarpeta '{$folder}' ya existe. Saltando.");
                }
            }

            $routesFilePath = $basePath . '/Routes/api.php';
            if (!File::exists($routesFilePath)) {
                $stub = "<?php\n\nuse Illuminate\Support\Facades\Route;\n\n/*\n|--------------------------------------------------------------------------\n| " . $moduleName . " API Routes\n|--------------------------------------------------------------------------\n|\n| Aquí puedes agregar las rutas API para el módulo " . $moduleName . ".\n\n*/\n\n// Route::prefix('".$moduleName."')->group(function () {\n//     Route::get('/', function () { return 'Hola mundo desde Módulo " . $moduleName . "!'; });\n// });\n";
                File::put($routesFilePath, $stub);
                $this->line("  - Archivo 'Routes/api.php' creado en el módulo.");
            } else {
                $this->line("  - Archivo 'Routes/api.php' ya existe. Saltando.");
            }


            $this->info("Módulo '{$moduleName}' creado exitosamente en: " . $basePath);
            $this->warn("Recuerda registrar las rutas del módulo en un Service Provider o en routes/api.php principal.");

        } else {
            $this->error("No se pudo crear el directorio base del módulo: '{$basePath}'. Verifica permisos.");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
