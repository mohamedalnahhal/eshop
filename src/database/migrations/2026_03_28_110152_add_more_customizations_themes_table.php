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
             *   on_primary, on_secondry, on_accent,
             *   background, card_bg, surface_100/200/300,
             *   text, text_muted,
             *   navbar, footer, border, border_muted, border_input,
             *   gold, on_gold, gold_surface,
             *   success, warning, danger, info
             * }
             */
            $table->jsonb('palette')->nullable()->after('currency');

            /**
             * font: {
             *   primary_family, secondary_family,
             *   base_size, base_weight, heading_weight,
             *   line_height, letter_spacing
             * }
             */
            $table->jsonb('font')->nullable()->after('palette');

            /**
             * buttons: {
             *   padding_x,
             *   padding_y,
             *   font_weight,
             *   uppercase, -- bool
             * }
             */
            $table->jsonb('buttons')->nullable()->after('font');

            /**
             * inputs: {
             *   padding_x,
             *   padding_y,
             *   font_weight,
             * }
             */
            $table->jsonb('inputs')->nullable()->after('buttons');

            /**
             * glows: {
             *   glow_shadow,
             *   card_shadow,
             *   button_shadow,
             *   input_shadow,
             *   navbar_shadow,
             *   modal_shadow
             * }
             */
            $table->jsonb('glows')->nullable()->after('inputs');

            /**
             * corners: {
             *   badge,    
             *   model,
             *   btn,      
             *   cta,    
             *   input,
             *   input-full,
             *   card,
             *   icon,
             *      
             *   sm,
             *   md,
             *   lg,
             *   xl,
             *   2xl,
             *   3xl,
             *   4xl,
             *   full
             * }
             */
            $table->jsonb('corners')->nullable()->after('glows');

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
                'buttons', 'inputs', 'glows', 'corners', 'icon_pack',
            ]);
            $table->jsonb('palette');
            $table->string('font', 100);
        });
    }
};
