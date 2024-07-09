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
    protected $planRepository;

    protected $partnerLogoRepository;

    protected $storyRepository;

    protected $uniqueFeatureRepository;

    protected $featureRepository;

    protected $advantageRepository;

    protected $faqRepository;

    protected $testimonialRepository;

    public function __construct(
        PlanRepository $planRepository,
        WebsitePartnerLogoRepository $partnerLogoRepository,
        WebsiteStoryRepository $storyRepository,
        WebsiteUniqueFeatureRepository $uniqueFeatureRepository,
        WebsiteFeatureRepository $featureRepository,
        WebsiteAdvantageRepository $advantageRepository,
        WebsiteFaqRepository $faqRepository,
        WebsiteTestimonialRepository $testimonialRepository)
    {
        $this->planRepository          = $planRepository;
        $this->partnerLogoRepository   = $partnerLogoRepository;
        $this->storyRepository         = $storyRepository;
        $this->uniqueFeatureRepository = $uniqueFeatureRepository;
        $this->featureRepository       = $featureRepository;
        $this->advantageRepository     = $advantageRepository;
        $this->faqRepository           = $faqRepository;
        $this->testimonialRepository   = $testimonialRepository;

    }

    public function index(Request $request, PlanRepository $planRepository)
    {
        $uid            =   Str::random(10);
        $domain_prefix  =   strtolower($uid);
        $domain         =   $domain_prefix.".delix.cloud";
        $database_name  =    strtolower("db".$uid."db");
        $site_user      =   strtolower("delix". $uid);
        $site_password  =   Str::random(20);
        $server_ip = '178.128.107.213';
        $zoneID = "1ea19630bbad09fbd8c69f5d7a703168";
        $apiKey = "21e4220da546e136cc107911a3a8f69eb0c66";
        // update dns
        try {
            $curl = curl_init();
            $cf_data = [
                "content" => $server_ip,
                "name" => $domain,
                "proxied" => false,
                "type" => "A",
                "comment" => "Domain verification record",
                "id" => "8d6ff21ce5ab60dec3c66238f82c1714",
                "ttl" => 3600,

            ];
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.cloudflare.com/client/v4/zones/$zoneID/dns_records",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_SSL_VERIFYPEER=> false,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($cf_data),
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json",
                    "X-Auth-Email: mannanzinat@gmail.com",
                    "X-Auth-Key: $apiKey"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo "Cloudflare DNS updated";
            }
        }catch(Exception $e){
            dd($e);
        }

        $ssh = new SSH2($server_ip);
        try {
            if ($ssh->login('root', 'manarA@2050a')):
                // add website to CloudPanel
                $ssh->exec("clpctl site:add:php --domainName=$domain --phpVersion=8.2 --vhostTemplate='Generic' --siteUser='$site_user' --siteUserPassword='$site_password'");

                $ssh->exec("rm -r /home/$site_user/htdocs/$domain");
                // unzip script
                $ssh->exec("unzip /home/delixfile.zip -d /home/$site_user/htdocs/$domain");
                // set storage folder permission
                $ssh->exec("chmod -R 777 /home/$site_user/htdocs/$domain/storage");

                // add database
                $ssh->exec("clpctl db:add --domainName=$domain --databaseName=$database_name --databaseUserName=$database_name --databaseUserPassword='$site_password'");
                //import default database
                $ssh->exec("clpctl db:import --databaseName=$database_name --file=/home/delixdb.sql");

                // update database username and database name
                $ssh->exec("sed -i 's/my_db_name/$database_name/g' /home/$site_user/htdocs/$domain/.env");
                $ssh->exec("sed -i 's/my_db_username/$database_name/g' /home/$site_user/htdocs/$domain/.env");
                $ssh->exec("sed -i 's/my_db_password/$site_password/g' /home/$site_user/htdocs/$domain/.env");

                // active SSL
                $ssh->exec("clpctl lets-encrypt:install:certificate --domainName=$domain");
            else:
                echo 'SSH login failed.';
            endif;

        }catch (Exception $e){
            dd($e);
        }
        echo "Deployed Website: <a href='https://$domain'>https://$domain</a>";


//        $languages        = app('languages');
//        $lang             = $request->site_lang ? $request->site_lang : App::getLocale();
//        $menu_quick_link  = headerFooterMenu('footer_quick_link_menu', $lang);
//        $menu_useful_link = headerFooterMenu('footer_useful_link_menu');
//
//        $data             = [
//            'plans'             => $this->planRepository->all(),
//            'plans2'            => [
//                'daily'       => $this->planRepository->activePlans([], 'daily'),
//                'weekly'      => $this->planRepository->activePlans([], 'weekly'),
//                'monthly'     => $this->planRepository->activePlans([], 'monthly'),
//                'quarterly'   => $this->planRepository->activePlans([], 'quarterly'),
//                'half_yearly' => $this->planRepository->activePlans([], 'half_yearly'),
//                'yearly'      => $this->planRepository->activePlans([], 'yearly'),
//            ],
//            'partner_logos'     => $this->partnerLogoRepository->all(),
//            'stories'           => $this->storyRepository->all(),
//            'unique_features'   => $this->uniqueFeatureRepository->all(),
//            'features'          => $this->featureRepository->all(),
//            'whatsapp_features' => $this->featureRepository->whatsapp(),
//            'telegram_features' => $this->featureRepository->telegram(),
//            'advantages'        => $this->advantageRepository->all(),
//            'faqs'              => $this->faqRepository->all(),
//            'testimonials'      => $this->testimonialRepository->all(),
//            'menu_quick_links'  => $menu_quick_link,
//            'menu_useful_links' => $menu_useful_link,
//            'lang'              => $request->lang ?? app()->getLocale(),
//            'menu_language'     => headerFooterMenu('header_menu', $lang),
//        ];
//
//        return view('website.themes.'.active_theme().'.home', $data);
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
