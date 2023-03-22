<?php

declare(strict_types = 1);

namespace App\Jobs;

use App\Models\Shop\Product\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class AddMediaToProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Product $product
     */
    protected $product;

    /**
     * @var array $images
     */
    protected $images;

    /**
     * Create a new job instance.
     *
     * @param Product $product
     * @param array   $images
     *
     * @return void
     */
    public function __construct(Product $product, array $images)
    {
        $this->product = $product;
        $this->images = $images;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->images as $address => $hash) {
            /** @var Product $product */
            $product = $this->product;

            try {
                $product->addMediaFromUrl($address)
                    ->usingFileName(str_replace('.', '', uniqid('', true)) . ".jpg")
                    ->toMediaCollection('images');
            } catch (\Throwable $exception) {
                Log::error('During process of associating of media file to Product with ID: "'
                    . $product->getKey() . '". File from URL: ' . $address . ' can\'t be processed.\n\n'
                    . $exception->getTraceAsString());
            }
        }
    }
}
