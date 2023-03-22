<?php

use App\Models\Shop\QuickFilter\QuickFilter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuickFiltersSlugColumnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $quickFilters = QuickFilter::query()->with('i18n')->get();

        foreach ($quickFilters as $quickFilter) {
            $slug = $quickFilter->id;

            if ($quickFilter->i18n->where('language_code', 'ru')->first()) {
                $slug = Str::slug(
                    cyrillic_to_latin($quickFilter->i18n->where('language_code', 'ru')->first()->title),
                    '-'
                );

                $iteration = 0;
                while (QuickFilter::query()->where('slug', $slug)->first() && $iteration < 100) {
                    if ($iteration > 0) {
                        $slug = Str::replaceLast((string) ($iteration - 1), (string) $iteration, $slug);
                    } else {
                        $slug = $slug . $iteration;
                    }

                    $iteration ++;
                }
            }

            $quickFilter->update(['slug' => $slug]);
        }
    }
}
