<?php
declare(strict_types=1);

namespace App\Models\Shop\ReturnRequest;

use App\Models\Shop\Order\Order;
use App\Models\Shop\Product\Product;
use App\Models\User;
use MosseboShopCore\Models\Base\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

/**
 * Class ReturnRequest
 *
 * @package App\Models\Shop\ReturnRequest
 */
class ReturnRequest extends BaseModel implements HasMedia
{
    use HasMediaTrait;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'quantity',
        'description',
        'status_id',
        'comment',
    ];

    protected $with = [
        'user',
        'product',
        'order',
        'status',
        'status.i18n',
    ];

    protected $table = 'shop_returns';

    protected $tableKey = 'Returns';

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this
            ->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this
            ->belongsTo(Order::class, 'order_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this
            ->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Get the status record associated with the returns entity.
     */
    public function status()
    {
        return $this->belongsTo(ReturnStatus::class, 'status_id');
    }

    /**
     * Register the media collections.
     */
    public function registerMediaCollections()
    {
        $this->addMediaCollection('attached')
            ->useDisk('s3')
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(150)
                    ->height(150);
            });
    }
}
