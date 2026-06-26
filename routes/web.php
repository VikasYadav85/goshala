<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\CampaignController as AdminCampaignController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\CowController as AdminCowController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DonationCategoryController as AdminDonationCategoryController;
use App\Http\Controllers\Admin\DonationController as AdminDonationController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\TeamMemberController as AdminTeamMemberController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\VolunteerController as AdminVolunteerController;
use App\Http\Controllers\Public\BlogController;
use App\Http\Controllers\Public\CampaignController as PublicCampaignController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\DonationController as PublicDonationController;
use App\Http\Controllers\Public\EventController as PublicEventController;
use App\Http\Controllers\Public\GalleryController as PublicGalleryController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\SubscriberController;
use App\Http\Controllers\Public\VolunteerController as PublicVolunteerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public site
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/our-goshala', [PageController::class, 'goshala'])->name('goshala');
Route::get('/transparency', [PageController::class, 'transparency'])->name('transparency');
Route::get('/testimonials', [PageController::class, 'testimonials'])->name('testimonials');
Route::get('/faqs', [PageController::class, 'faqs'])->name('faqs');

// Donations
Route::prefix('donate')->name('donations.')->group(function () {
    Route::get('/', [PublicDonationController::class, 'index'])->name('index');
    Route::get('/checkout', [PublicDonationController::class, 'create'])->name('create');
    Route::post('/checkout', [PublicDonationController::class, 'store'])->name('store');
    Route::get('/{donation}/pay', [PublicDonationController::class, 'pay'])->name('pay');
    Route::post('/{donation}/callback', [PublicDonationController::class, 'callback'])->name('callback');
    Route::post('/{donation}/upi-confirm', [PublicDonationController::class, 'upiConfirm'])->name('upi.confirm');
    Route::post('/{donation}/simulate', [PublicDonationController::class, 'simulate'])->name('simulate');
    Route::get('/{donation}/thanks', [PublicDonationController::class, 'thanks'])->name('thanks');
});

// Campaigns
Route::get('/campaigns', [PublicCampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/{slug}', [PublicCampaignController::class, 'show'])->name('campaigns.show');

// Events
Route::get('/events', [PublicEventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [PublicEventController::class, 'show'])->name('events.show');

// Gallery
Route::get('/gallery', [PublicGalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{slug}', [PublicGalleryController::class, 'show'])->name('gallery.show');

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Volunteer
Route::get('/volunteer', [PublicVolunteerController::class, 'create'])->name('volunteer.index');
Route::post('/volunteer', [PublicVolunteerController::class, 'store'])->name('volunteer.store');
Route::get('/volunteer/thank-you', [PublicVolunteerController::class, 'thanks'])->name('volunteer.thanks');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Newsletter
Route::post('/subscribe', [SubscriberController::class, 'store'])->name('subscribe');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/', AdminDashboardController::class)->name('dashboard');

        Route::get('donations', [AdminDonationController::class, 'index'])->name('donations.index');
        Route::get('donations/{donation}', [AdminDonationController::class, 'show'])->name('donations.show');
        Route::patch('donations/{donation}', [AdminDonationController::class, 'updateStatus'])->name('donations.update');

        Route::resource('cows', AdminCowController::class)->except(['show']);
        Route::resource('campaigns', AdminCampaignController::class)->except(['show']);
        Route::resource('events', AdminEventController::class)->except(['show']);

        Route::resource('blog', AdminBlogPostController::class)
            ->parameters(['blog' => 'post'])
            ->except(['show']);

        Route::get('volunteers', [AdminVolunteerController::class, 'index'])->name('volunteers.index');
        Route::get('volunteers/{volunteer}', [AdminVolunteerController::class, 'show'])->name('volunteers.show');
        Route::patch('volunteers/{volunteer}', [AdminVolunteerController::class, 'update'])->name('volunteers.update');

        Route::get('messages', [AdminContactMessageController::class, 'index'])->name('messages.index');
        Route::get('messages/{message}', [AdminContactMessageController::class, 'show'])->name('messages.show');
        Route::patch('messages/{message}', [AdminContactMessageController::class, 'update'])->name('messages.update');
        Route::delete('messages/{message}', [AdminContactMessageController::class, 'destroy'])->name('messages.destroy');

        Route::resource('testimonials', AdminTestimonialController::class)->except(['show']);

        Route::resource('team', AdminTeamMemberController::class)
            ->parameters(['team' => 'member'])
            ->except(['show']);

        Route::resource('donation-categories', AdminDonationCategoryController::class)
            ->parameters(['donation-categories' => 'category'])
            ->except(['show']);

        Route::resource('gallery', AdminGalleryController::class)
            ->parameters(['gallery' => 'album'])
            ->except(['show']);
        Route::post('gallery/{album}/items', [AdminGalleryController::class, 'addItem'])->name('gallery.items.store');
        Route::delete('gallery/items/{item}', [AdminGalleryController::class, 'destroyItem'])->name('gallery.items.destroy');

        Route::resource('faqs', AdminFaqController::class)->except(['show']);

        Route::get('settings', [AdminSettingsController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [AdminSettingsController::class, 'update'])->name('settings.update');
    });
});
