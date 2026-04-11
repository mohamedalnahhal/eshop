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
             *   header, on_header, m_header, on_m_header,
             *   footer, on_footer,
             *   border, border_muted, border_input,
             *   border_header, border_m_header, border_header_input,
             *   border_m_header_input,
             *   gold, on_gold, gold_surface,
             *   success, warning, danger, info
             * }
             */
            $table->jsonb('palette')->nullable()->after('currency');

            /**
             * font: {
             *   primary_family, secondary_family,
             *   base_weight, heading_weight,
             *   line_height, letter_spacing,
             *   xs -> 7xl, lh-[loose/normal/tight/none]
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
             * header: {
             *   width, content_width
             *   padding_t, padding_b, margin_t, margin_b, sticky_t
             *   content_[padding_r/padding_l/margin_r/margin_l],
             *   search_px, search_py, gap,
             *   position, bg_opacity, backdrop_blur,
             *   title_weight, title_size,
             *   logo_width, logo_hight, icons_size,
             *   border_t, border_b, border_l, border_r,
             * }
             */
            $table->jsonb('header')->nullable()->after('inputs');

            /**
             * mobile header
             * 
             * m_header: {
             *   width, content_width
             *   padding_t, padding_b, margin_t, margin_b, sticky_t
             *   content_[padding_r/padding_l/margin_r/margin_l],
             *   search_px, search_py, gap,
             *   position, bg_opacity, backdrop_blur,
             *   title_weight, title_size,
             *   logo_width, logo_hight, icons_size,
             *   border_t, border_b, border_l, border_r,
             * }
             */
            $table->jsonb('m_header')->nullable()->after('header');

            /**
             * glows: {
             *   glow_shadow,
             *   card_shadow,
             *   button_shadow,
             *   input_shadow,
             *   header_shadow,
             *   m_header_shadow,
             *   modal_shadow
             * }
             */
            $table->jsonb('glows')->nullable()->after('m_header');

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
             *   header,
             *   m_header,
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
                'is_default', 'currency', 'palette', 'font', 'header',
                'm_header', 'buttons', 'inputs', 'glows', 'corners',
                'icon_pack',
            ]);
            $table->jsonb('palette');
            $table->string('font', 100);
        });
    }
};
