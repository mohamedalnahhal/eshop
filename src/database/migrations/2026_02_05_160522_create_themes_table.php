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
        Schema::create('themes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            $table->foreignUuid('tenant_id')
                  ->nullable()
                  ->default(null)
                  ->constrained('tenants')
                  ->onDelete('cascade');

            $table->string('name', 100); 

            $table->boolean('is_default')->default(false);

            /**
             * currency: { position ('before'|'after'), decimals }
             */
            $table->jsonb('currency')->nullable();

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
            $table->jsonb('palette')->nullable();

            /**
             * font: {
             *   primary_family, secondary_family,
             *   base_weight, heading_weight,
             *   line_height, letter_spacing,
             *   xs -> 7xl, lh-[loose/normal/tight/none]
             * }
             */
            $table->jsonb('font')->nullable();

            /**
             * buttons: {
             *   padding_x,
             *   padding_y,
             *   font_weight,
             *   uppercase, -- bool
             * }
             */
            $table->jsonb('buttons')->nullable();

            /**
             * inputs: {
             *   padding_x,
             *   padding_y,
             *   font_weight,
             * }
             */
            $table->jsonb('inputs')->nullable();

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
            $table->jsonb('header')->nullable();

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
            $table->jsonb('m_header')->nullable();

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
            $table->jsonb('glows')->nullable();

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
            $table->jsonb('corners')->nullable();

            $table->string('icon_pack')->default('heroicons');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
