<?php

namespace App\Console\Commands;

use App\MediaLibrary\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RefactorMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:refactor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Changes media models to S3 disk and updates needed paths';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $media = collect(Media::all());
        foreach($media as $object){
            if (Str::contains($object->pathes,'\/uploads\/')){
                $object->pathes = str_replace('\/uploads\/','',$object->pathes);
                DB::update('update media set disk= ?, pathes = ? where id = ?', ['s3',$object->pathes,$object->id]);
            }
        }
        $this->info($media->first()->pathes);
    }
}
