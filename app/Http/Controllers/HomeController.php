<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Repositories\PageRepository;
use App\Repositories\PlanRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Artisan;
use App\Repositories\WebsiteFaqRepository;
use App\Repositories\WebsiteStoryRepository;
use App\Repositories\WebsiteFeatureRepository;
use App\Repositories\WebsiteAdvantageRepository;
use App\Repositories\WebsitePartnerLogoRepository;
use App\Repositories\WebsiteTestimonialRepository;
use App\Repositories\WebsiteUniqueFeatureRepository;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;
use phpseclib3\Net\SSH2;

class HomeController extends Controller
{

    public function index()
    {
        return view('website.home');
    }

    public function page(Request $request, $link, PageRepository $pageRepository)
    {
        $page              = $pageRepository->findByLink($link);
        $lang              = $request->lang ?? app()->getLocale();
        $menu_quick_link   = headerFooterMenu('footer_quick_link_menu', $lang);
        $menu_useful_link  = headerFooterMenu('footer_useful_link_menu');
        $data['page_info'] = $pageRepository->getByLang($page->id, $lang);

        $data              = [
            'menu_quick_links'  => $menu_quick_link,
            'menu_useful_links' => $menu_useful_link,
            'lang'              => $request->lang ?? app()->getLocale(),
            'menu_language'     => headerFooterMenu('header_menu', $lang),
            'page_info'         => $pageRepository->getByLang($page->id, $lang),
        ];

        return view('website.themes.'.active_theme().'.page', $data);

        // return view('website.page', $data);
    }

    public function cacheClear()
    {
        try {
            Artisan::call('all:clear');
            Artisan::call('migrate', ['--force' => true]);
            Toastr::success(__('cache_cleared_successfully'));

            return back();
        } catch (\Exception $e) {
            // dd($e->getMessage());
            Toastr::error('something_went_wrong_please_try_again', 'Error!');

            return back();
        }
    }

    public function changeLanguage($locale): \Illuminate\Http\RedirectResponse
    {
        cache()->get('locale');
        app()->setLocale($locale);

        return redirect()->back();
    }
}
