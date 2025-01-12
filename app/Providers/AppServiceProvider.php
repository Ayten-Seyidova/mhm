<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator as PaginationPaginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale(App::getLocale());
        PaginationPaginator::useBootstrap();
        Schema::defaultStringLength(191);
        date_default_timezone_set('Asia/Baku');

//        $lang = Language::all();
//        $setting = Setting::find(1);
//        $menus = Menu::where('status', 1)->orderBy('order_number', 'asc')->get();
//        $cards = Card::all();
//        $countries = Country::where('is_deleted', 0)->get();
//        $currencies = Currency::where('is_deleted', 0)->get();
//        $languages = Language::all();
//        $footerMenus1 = FooterMenu::where('status', 1)->where('type', 'help')->orderBy('order_number', 'asc')->get();
//        $footerMenus2 = FooterMenu::where('status', 1)->where('type', 'about')->orderBy('order_number', 'asc')->get();
//        $subCategories = SubCategory::where('is_deleted', 0)->where('status', 1)->get();
//        $categories = Category::where('is_deleted', 0)->where('status', 1)->get();
//        $seasons = Season::where('is_deleted', 0)->where('status', 1)->get();
//        $brands = Brand::where('is_deleted', 0)->where('status', 1)->get();
//
//        $user = \Illuminate\Support\Facades\Auth::guard('customer')->user();
//        View::share([
//            'lang' => $lang,
//            'menus' => $menus,
//            'setting' => $setting,
//            'cards' => $cards,
//            'footerMenus1' => $footerMenus1,
//            'footerMenus2' => $footerMenus2,
//            'countries' => $countries,
//            'currencies' => $currencies,
//            'languages' => $languages,
//            'subCategories' => $subCategories,
//            'categories' => $categories,
//            'seasons' => $seasons,
//            'brands' => $brands,
//            'user' => $user
//        ]);
    }
}
