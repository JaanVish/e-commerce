<?php

namespace Tests\Browser\Modules\Wallet;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Modules\PaymentGateway\Entities\PaymentMethod;
use Modules\Wallet\Entities\WalletBalance;
use Tests\DuskTestCase;

class MyWalletTest extends DuskTestCase
{
    public function setUp(): void
    {
        parent::setUp();

    }

    public function tearDown(): void
    {
        $balances = WalletBalance::all();
        foreach($balances as $balance){
            $balance->delete();
        }

        $stribe = PaymentMethod::where('method', 'Stripe')->first();

        $stribe->update([
            'active_status' =>0
        ]);

        parent::tearDown(); // TODO: Change the autogenerated stub
    }
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_for_visit_index_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/wallet/seller/my-wallet-index')
                ->assertSee('Transaction List');
        });
    }

    public function test_for_recharge_amount(){
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/paymentgateway')
                ->assertSee('Activation')
                ->click('#DataTables_Table_0 > tbody > tr:nth-child(4) > td.text-right > label > div')
                ->waitFor('.toast-message',25)
                ->assertSeeIn('.toast-message', 'Successfully Updated')
                ->visit('/wallet/seller/my-wallet-index')
                ->assertSee('Transaction List')
                ->click('#main-content > section:nth-child(3) > div > div > div > div.box_header.common_table_header > div > ul > li:nth-child(1) > a')
                ->whenAvailable('#Recharge_Modal > div > div > div.modal-body', function($modal){
                    $modal->type('form > div > div.col-xl-12 > div > input', '300')
                        ->click('form > div > div.col-lg-12.text-center > div > button')
                        ->assertPathIs('/wallet/my-wallet-create');
                })
                ->assertSee('Choose Payment Gateway')
                ->click('#main-content > section > div > div > div > div.row > div:nth-child(2) > div > div > form > button')
                ->pause(1000)
                ->acceptDialog()
                ->pause(4000)
                ->whenAvailable('#container > section > span:nth-child(3) > div > div > main > form', function($modal){
                    $modal->type('div > div > div > div > div > div:nth-child(1) > div.StaggerGroup-child.is-head-0.is-tail-NaN > div > div > div > fieldset > span > div > div.Textbox-inputRow > input', 'test@test.com')
                        ->type('div > div > div > div > div > div:nth-child(1) > div.Section-child--padded > fieldset > div:nth-child(1) > div.StaggerGroup-child.is-head-1.is-tail-NaN > span > span:nth-child(1) > div > div.Textbox-inputRow > input', '4242 4242 4242 4242')
                        ->type('div > div > div > div > div > div:nth-child(1) > div.Section-child--padded > fieldset > div:nth-child(1) > div.StaggerGroup-child.is-head-2.is-tail-NaN > div.Fieldset-childLeft.u-size1of2.Fieldset-childBottom.Textbox.Textbox--iconLeft.can-setfocus > div.Textbox-inputRow > input', '02 / 23')
                        ->type('div > div > div > div > div > div:nth-child(1) > div.Section-child--padded > fieldset > div:nth-child(1) > div.StaggerGroup-child.is-head-2.is-tail-NaN > div.Fieldset-childRight.u-size1of2.Fieldset-childBottom.Textbox.Textbox--iconLeft.can-setfocus > div.Textbox-inputRow > input', '123')
                        ->click('nav > div > div > div > button');

                })
                ->pause(2000)
                ->assertPathIs('/wallet/seller/my-wallet-index');
        });
    }

    public function test_for_withdarw_amount(){
        WalletBalance::create([
            'user_id' => 1,
            'type' => "Deposite",
            'amount' => 200,
            'payment_method' => 4,
            'txn_id' => 'ch_1J9Pk9GRvmmDdlLV1ogZNRr2',
            'status' => 0
        ]);
        $this->test_for_visit_index_page();
        
        $this->browse(function (Browser $browser) {
            $browser->click('#main-content > section:nth-child(3) > div > div > div > div.box_header.common_table_header > div > ul > li:nth-child(2) > a')
                ->whenAvailable('#Withdraw_Modal > div > div > div.modal-body > form', function($modal){
                    $modal->type('div > div.col-xl-12.mt-2 > div > input', '0')
                        ->click('div > div.col-lg-12.text-center > div > button')
                        ->assertPathIs('/wallet/my-withdraw-requests');
                })
                ->waitFor('.toast-message',25)
                ->assertSeeIn('.toast-message', 'Withdraw Request has been sent Successfully !!!');
                
        });
    }

    public function test_for_visit_withdraw_page(){
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/wallet/my-withdraw-requests')
                ->assertSee('Withdraw history');
        });
    }


    public function test_for_withdraw_from_withdraw_request(){
        WalletBalance::create([
            'user_id' => 1,
            'type' => "Deposite",
            'amount' => 200,
            'payment_method' => 4,
            'txn_id' => 'ch_1J9Pk9GRvmmDdlLV1ogZNRr2',
            'status' => 1
        ]);
        $this->test_for_visit_withdraw_page();
        $this->browse(function (Browser $browser) {
            $browser->click('#main-content > section:nth-child(3) > div > div > div > div.box_header.common_table_header > div > ul > li > a')
                ->whenAvailable('#Withdraw_Modal > div > div > div.modal-body > form', function($modal){
                    $modal->type('div > div.col-xl-12.mt-2 > div > input', '10')
                        ->click('div > div.col-lg-12.text-center > div > button')
                        ->assertPathIs('/wallet/my-withdraw-requests');
                })
                ->waitFor('.toast-message',25)
                ->assertSeeIn('.toast-message', 'Withdraw Request has been sent Successfully !!!');
        });
    }

    public function test_for_edit_withdraw(){
        $this->test_for_withdraw_from_withdraw_request();

        $this->browse(function (Browser $browser) {
            $browser->waitFor('#myWithdrawTable > tbody > tr.odd > td:nth-child(8) > a', 25)
                ->click('#myWithdrawTable > tbody > tr.odd > td:nth-child(8) > a')
                ->whenAvailable('#Withdraw_EditModal > div > div > div.modal-body > form', function($modal){
                    $modal->type('div > div:nth-child(3) > div > input', '205')
                        ->click('div > div.col-lg-12.text-center > div > button')
                        ->assertPathIs('/wallet/my-withdraw-requests');
                })
                ->waitFor('.toast-message',25)
                ->assertSeeIn('.toast-message', 'Withdraw Request has been modified Successfully !!!');
        });
    }




}
