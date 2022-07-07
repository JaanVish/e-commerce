<?php

namespace Tests\Browser\Modules\LogActivity;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Laravel\Dusk\Browser;
use Modules\UserActivityLog\Entities\LogActivity as EntitiesLogActivity;
use Modules\UserActivityLog\Traits\LogActivity;
use Tests\DuskTestCase;

class ActivityLogTest extends DuskTestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        $loginactivities = EntitiesLogActivity::pluck('id');
        EntitiesLogActivity::destroy($loginactivities);

        parent::tearDown(); // TODO: Change the autogenerated stub
    }
    /**
     * A Dusk test example.
     *
     * @return void
     */

    public function test_for_log_activity()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/useractivitylog')
                ->assertSee('Activity Logs')
                ->waitFor('#activityDataTable > tbody', 25);
        });
    }

    public function test_for_login_logout_activity()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/useractivitylog/user-login')
                ->assertSee('Login - Logout Activity')
                ->waitFor('#loginLogoutDataTable > tbody', 25);
        });
    }
}
