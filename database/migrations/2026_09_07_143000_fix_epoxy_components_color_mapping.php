<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $solconBrandId = DB::table('brands')->where('code', 'SOL')->value('id') ?? 1;

        $components = DB::table('epoxy_components')
            ->where('brand_id', $solconBrandId)
            ->where(function ($q) {
                $q->where('category', 'Pouch')
                  ->orWhere('name', 'like', '%Filler Pouch%');
            })
            ->get();

        $colors = DB::table('epoxy_filler_colors')
            ->where('brand_id', $solconBrandId)
            ->get();

        foreach ($components as $comp) {
            $cleanName = trim(str_replace(['700gm ', ' Filler Pouch', '-B2'], '', $comp->name));
            if (strtolower($cleanName) === 'satillo') {
                $cleanName = 'Saltillo';
            }

            $matchedColor = $colors->first(function ($c) use ($cleanName) {
                return strcasecmp($c->name, $cleanName) === 0;
            });

            if (!$matchedColor) {
                $matchedColor = $colors->first(function ($c) use ($cleanName) {
                    return stripos($c->name, $cleanName) !== false || stripos($cleanName, $c->name) !== false;
                });
            }

            if ($matchedColor) {
                DB::table('epoxy_components')
                    ->where('id', $comp->id)
                    ->update([
                        'epoxy_filler_color_id' => $matchedColor->id,
                        'updated_at' => now(),
                    ]);

                // Also update any component preparations that referenced this component
                DB::table('epoxy_component_preparations')
                    ->where('epoxy_component_id', $comp->id)
                    ->update([
                        'epoxy_filler_color_id' => $matchedColor->id,
                        'updated_at' => now(),
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down needed for data correction
    }
};
