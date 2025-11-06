<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class StorageCopy extends Command
{
    protected $signature = 'storage:copy';

    protected $description = 'Copie les nouveaux fichiers de storage/app/public vers public/storage sans utiliser de symlink';

    public function handle()
    {
        $source = storage_path('app/public');
        $destination = public_path('storage');

        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
            $this->info('Dossier public/storage créé.');
        }

        $this->copyNewOrUpdatedFiles($source, $destination);

        $this->info('Fichiers mis à jour avec succès.');
        return Command::SUCCESS;
    }

    private function copyNewOrUpdatedFiles($source, $destination)
    {
        $files = File::allFiles($source);

        foreach ($files as $file) {
            $targetPath = $destination . DIRECTORY_SEPARATOR . $file->getRelativePathname();

            if (!File::exists($targetPath) || File::lastModified($file->getRealPath()) > File::lastModified($targetPath)) {
                // Créer les sous-dossiers si besoin
                if (!File::exists(dirname($targetPath))) {
                    File::makeDirectory(dirname($targetPath), 0755, true);
                }

                File::copy($file->getRealPath(), $targetPath);
                $this->info('Copié : ' . $file->getRelativePathname());
            }
        }
    }
}
