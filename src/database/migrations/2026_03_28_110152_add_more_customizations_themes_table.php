<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('themes', function (Blueprint $table) {
            
            if (Schema::hasColumn('themes', 'palette')) {
                $table->dropColumn('palette');
            }
            if (Schema::hasColumn('themes', 'font')) {
                $table->dropColumn('font');
            }

            $table->boolean('is_default')->default(false)->after('name');
            
            /**
             * currency: { position ('before'|'after'), decimals }
             */
            $table->jsonb('currency')->nullable()->after('is_default');
 
            /**
             * palette: {
             *   primary, secondary, accent,
             *   background, surface, text, text_muted,
             *   navbar, footer, border,
             *   success, warning, danger, info
             * }
             */
            $table->jsonb('palette')->nullable()->after('currency');
 
            /**
             * font: {
             *   primary_family, secondary_family,
             *   base_size, h1_size,
             *   base_weight, heading_weight,
             *   line_height, letter_spacing
             * }
             */
            $table->jsonb('font')->nullable()->after('palette');
 
            /**
             * buttons: {
             *   radius,          -- e.g. '0.375rem' | 'full'
             *   padding_x,
             *   padding_y,
             *   font_weight,
             *   uppercase,       -- bool
             *   shadow           -- 'none'|'sm'|'md'|'lg'
             * }
             */
            $table->jsonb('buttons')->nullable()->after('font');
 
            /**
             * glows: {
             *   card_shadow,
             *   button_shadow,
             *   input_shadow,
             *   navbar_shadow,
             *   modal_shadow
             * }
             */
            $table->jsonb('glows')->nullable()->after('buttons');
 
            /**
             * corners: {
             *   sm,
             *   md,
             *   lg,
             *   xl,
             *   full
             *   -- applied as: card → md, button → see buttons.radius, input → sm, badge → full
             * }
             */
            $table->jsonb('corners')->nullable()->after('glows');
 
            /**
             * icon_pack: 'heroicons' | 'phosphor' | 'lucide' | 'tabler' | 'bootstrap'
             */
            $table->string('icon_pack')->default('heroicons')->after('corners');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('themes', function (Blueprint $table) {
            $table->dropColumn([
                'is_default', 'currency', 'palette', 'font',
                'buttons', 'glows', 'corners', 'icon_pack',
            ]);
            $table->jsonb('palette');
            $table->string('font', 100);
        });
    }
};
