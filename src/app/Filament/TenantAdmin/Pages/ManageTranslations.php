<?php

namespace App\Filament\TenantAdmin\Pages;

use App\Enums\TenantPermission;
use App\Models\LanguageLine;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ManageTranslations extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-language';
    protected static ?string $navigationLabel = 'Translations';
    protected static ?string $title = 'Manage Translations';
    protected static string|\UnitEnum|null $navigationGroup = 'Shop Settings';
    protected static ?int $navigationSort = 96;

    protected string $view = 'filament.tenant-admin.pages.manage-translations';

    public function table(Table $table): Table
    {
        return $table
            ->query(LanguageLine::query())
            ->columns([
                Tables\Columns\TextColumn::make('group')
                    ->label('Group')
                    ->searchable(),
                Tables\Columns\TextColumn::make('key')
                    ->label('Key')
                    ->searchable(),
                Tables\Columns\TextColumn::make('text')
                    ->label('Translations')
                    ->formatStateUsing(function ($record) {
                        return collect($record->text)
                            ->map(fn($v, $k) => strtoupper($k) . ': ' . $v)
                            ->implode(' | ');
                    }),
            ])
            ->headerActions([
                Action::make('add_translation')
                    ->label('Add Translation')
                    ->color('primary')
                    ->schema([
                        Select::make('locale')
                            ->label('Language')
                            ->options(function () {
                                $supported = tenant()->settings?->supported_languages ?? [];

                                return collect($supported)
                                    ->mapWithKeys(fn($locale) => [
                                        $locale => \Locale::getDisplayName($locale, 'en') . ' (' . $locale . ')',
                                    ])
                                    ->sort()
                                    ->toArray();
                            })
                            ->required(),
                        Select::make('key')
                            ->label('Translation Key')
                            ->options(function () {
                                $dbKeys = LanguageLine::query()->pluck('key', 'key')->toArray();

                                $viewKeys = [
                                    'Add to cart', 'Add your review', 'All Products',
                                    'Already have an account?', 'Are you sure you want to delete your review?',
                                    'Browse all our products', 'Browse Products', 'Cancel', 'Cart',
                                    'Categories', 'Checkout', 'Clear Filters', 'Confirm Password',
                                    'Continue with Facebook', 'Continue with Google', 'Create Account',
                                    'Create one', 'CTA Primary Label', 'CTA Secondary Label',
                                    'Customer Reviews', 'Edited', 'Edit your review', 'Email Address',
                                    '★ Featured Badge', 'Forgot password?', 'found this helpful', 'Free',
                                    'Full Name', 'Helpful?', 'Hero Subtitle', 'Hero Title',
                                    'Highest Rated', 'Home', 'In stock',
                                    'Join us today and start shopping!', 'Latest', 'Loading', 'Loading...',
                                    'Lowest Rated', 'Most Helpful', 'My Account', 'My Orders',
                                    'New Arrivals', 'New Badge', 'No categories found',
                                    'No image available', 'No products available', 'No products found',
                                    'No reviews yet.', 'Order Summary', 'or sign in with email',
                                    'or sign up with email', 'Other', 'out of', 'Out of stock',
                                    'Password', 'Powered by', 'Price', 'Price: High to Low',
                                    'Price: Low to High', 'Product added to cart successfully!',
                                    'Product Description', 'Product Details', 'Products', 'Promo CTA',
                                    'Promo Subtitle', 'Promo Title', 'Quantity', 'Remember me',
                                    'Remove', 'Reviews', 'Shipping', 'Sign in', 'Sign In',
                                    'Signing out...', 'Sign Out', 'Sign Up', 'Sort by',
                                    'Submit Review', 'Subtotal', 'Top Rated', 'Total', 'Unit price',
                                    'Update Review', 'View', 'View all',
                                    'Welcome back! Sign in to your account.',
                                    'What are you looking for?', 'Write your review here...',
                                    'you@example.com',
                                    'You have not added any products to your cart yet.',
                                    'Your cart is empty!', 'Your name', 'Your Review',
                                    'Your review has been deleted.', 'Your review has been submitted!',
                                    'Your review has been updated!',
                                    'Your review is the only one, thank you!', 'Zoom',
                                ];

                                return collect(array_merge(array_values($dbKeys), $viewKeys))
                                    ->unique()
                                    ->sort()
                                    ->mapWithKeys(fn($key) => [$key => $key])
                                    ->toArray();
                            })
                            ->searchable()
                            ->createOptionForm([
                                TextInput::make('key')
                                    ->label('Custom Key')
                                    ->required(),
                            ])
                            ->createOptionUsing(fn(array $data) => $data['key'])
                            ->required(),
                        TextInput::make('value')
                            ->label('Translation')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $line = LanguageLine::firstOrNew([
                            'group' => '*',
                            'key'   => $data['key'],
                        ]);

                        $text = $line->text ?? [];
                        $text[$data['locale']] = $data['value'];
                        $line->text = $text;
                        $line->save();

                        Notification::make()
                            ->title('Translation saved!')
                            ->success()
                            ->send();
                    }),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('Edit')
                    ->schema(function ($record) {
                        $locales = array_keys($record->text ?? []);

                        $keyField = TextInput::make('key')
                            ->label('Key')
                            ->default($record->key)
                            ->required();

                        $translationFields = collect($locales)->map(fn($locale) =>
                            TextInput::make("text.{$locale}")
                                ->label(\Locale::getDisplayName($locale, 'en') . ' (' . strtoupper($locale) . ')')
                                ->default($record->text[$locale] ?? '')
                        )->toArray();

                        return [$keyField, ...$translationFields];
                    })
                    ->action(function ($record, array $data) {
                        $record->key  = $data['key'];
                        $record->text = array_filter($data['text'], fn($v) => $v !== null && $v !== '');
                        $record->save();

                        Notification::make()
                            ->title('Translation updated!')
                            ->success()
                            ->send();
                    }),
                DeleteAction::make(),
            ]);
    }

    public static function canAccess(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->tenantUserFor(tenant('id'))
            ?->can(TenantPermission::MANAGE_SETTINGS) ?? false;
    }
}