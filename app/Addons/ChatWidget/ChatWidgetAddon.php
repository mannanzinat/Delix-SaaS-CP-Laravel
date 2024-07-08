<?php

namespace App\Addons\ChatWidget;

use App\AddonManager\Addon;

class ChatWidgetAddon extends Addon
{
    public $name                 = 'ChatWidget';

    public $description          = 'ChatWidget Addon';

    public $version              = '1.0.0';

    public $author               = 'Spa Green';

    public $author_url           = 'https://codecanyon.net/user/spagreen/portfolio';

    public $tag                  = 'ChatWidget, Addon, Demo';

    public $addon_identifier     = 'chat_widget';

    public $required_cms_version = '1.0.0';

    public $required_app_version = '1.0.0';

    public $license              = 'General Public License';

    public $license_url          = 'https://mit-license.org/GPL';

    public function boot()
    {
        $this->enableViews();
        $this->enableRoutes();
    }

    public function addonActivated()
    {
        dd('I am activated');
    }

    public function addonDeactivated()
    {
        dd('I am deActivated');
    }
}
