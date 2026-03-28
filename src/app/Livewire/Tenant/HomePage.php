<?php

namespace App\Livewire\Tenant;

use App\Models\Product;
use Livewire\Component;
use Filament\Facades\Filament;

class HomePage extends Component
{

// --- Store Info & Branding ---
public string $storeName;
public ?string $storeLogo;
public ?string $storeFavicon; 
public string $storeSlogan; 
public string $contactEmail;
public ?string $contactPhone;

// --- Colors ---
public string $primaryColor;
public string $secondaryColor;
public string $accentColor; 
public string $backgroundColor;
public string $textColor;
public string $navbarColor; 
public string $footerColor; 
public string $borderColor; 

// --- Typography ---
public string $primaryFontFamily;
public string $secondaryFontFamily;
public string $baseFontSize;
public string $h1FontSize; 
public string $primaryFontWeight; 
public string $headingFontWeight; 
public string $lineHeight;
public string $letterSpacing; 

// --- UI Elements ---
public string $buttonShapeClass;
public string $cardShapeClass;
public string $inputShapeClass;

    public function mount()
    {
        $tenant = tenant();
        
        
        //If the field does not exist or is empty, we temporarily use an empty array.
        $themeSettings = $tenant->theme_config ? json_decode($tenant->theme_config, true) : [];

        // Setting the basic store data for the interface
 // --- Store Info & Branding ---
$this->storeName = $tenant->name ?? 'My Store';
$this->storeLogo = $tenant->logo_url ?? null;
$this->storeFavicon = $tenant->favicon_url ?? null;
$this->storeSlogan = $tenant->slogan ?? 'Welcome to our amazing store!';
$this->contactEmail = $tenant->contact_email ?? 'info@mystore.com';
$this->contactPhone = $tenant->contact_phone ?? null;

$themeSettings = $tenant->theme_config ? json_decode($tenant->theme_config, true) : [];

// --- Colors ---
$this->primaryColor = $themeSettings['primary_color'] ?? '#2563eb';   
$this->secondaryColor = $themeSettings['secondary_color'] ?? '#475569'; 
$this->accentColor = $themeSettings['accent_color'] ?? '#ef4444';  
$this->backgroundColor = $themeSettings['background_color'] ?? '#f8fafc'; 
$this->textColor = $themeSettings['text_color'] ?? '#0f172a';         
$this->navbarColor = $themeSettings['navbar_color'] ?? '#ffffff';     
$this->footerColor = $themeSettings['footer_color'] ?? '#1e293b';    
$this->borderColor = $themeSettings['border_color'] ?? '#e2e8f0';     

// --- Typography ---
$this->primaryFontFamily = $themeSettings['primary_font_family'] ?? 'Inter, sans-serif';
$this->secondaryFontFamily = $themeSettings['secondary_font_family'] ?? 'Merriweather, serif';
$this->baseFontSize = $themeSettings['base_font_size'] ?? '16px';
$this->h1FontSize = $themeSettings['h1_font_size'] ?? '2.25rem'; 
$this->primaryFontWeight = $themeSettings['primary_font_weight'] ?? '400';
$this->headingFontWeight = $themeSettings['heading_font_weight'] ?? '700';
$this->lineHeight = $themeSettings['line_height'] ?? '1.6';
$this->letterSpacing = $themeSettings['letter_spacing'] ?? 'normal';

// --- UI Elements ---
$this->buttonShapeClass = $themeSettings['button_shape'] ?? 'rounded-lg'; 
$this->cardShapeClass = $themeSettings['card_shape'] ?? 'rounded-xl shadow-sm border border-gray-100';
$this->inputShapeClass = $themeSettings['input_shape'] ?? 'rounded-md border-gray-300';
    }

    // function add to cart as exmaple
    public function addToCart(int $productId)
    {
        
        // Cart::add($productId);
        
        
        $this->dispatch('product-added-to-cart'); 
    }

    public function render()
    {

        $latestProducts = Product::with('media')
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.tenant.home-page', [
            'products' => $latestProducts,
        ])->layout('layouts.tenant'); 
    }
}