<?php namespace Ognestraz\Image\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ImageClear extends Command
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'image:clear {variant?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear image in public/image';

    /**
     * Create a new command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    protected function deleteFiles($dir)
    {
        $iDir = new \DirectoryIterator($dir);
        foreach ($iDir as $file) {
            if ($file->isFile()) {
                unlink($file->getPathname());
            }
        }
    }
    
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $variant = $this->argument('variant');
        $folder = base_path() . '/public/image/';
        
        if (null === $variant) {
            $dir = new \DirectoryIterator($folder);
            foreach ($dir as $file) {
                if ($file->isDir() && !$file->isDot()) {
                    $this->deleteFiles($folder . $file->getFilename());
                }
            }
        } else {
            $this->deleteFiles($folder . $variant);
        }
    }
}
