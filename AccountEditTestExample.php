<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AccountEditTestExample extends DuskTestCase
{
    use RefreshDatabase;
    /**
     * A Dusk test to ensure that the user can edit and save data in web-account.
     */
    public function testDefaultAccountEditingNoSave(): void
    {
        $this->browse(function (Browser $browser) {
        $browser->visit('/')
                ->waitForLink('Log In')
                ->clickLink('Log In')
                ->assertPathIs('/login');
        
        $browser->assertSee('Hello, I\'m Handy,')
                ->assertSee('Your cheerful and supportive companion in this crazy world')
                ->assertVisible('@handy-logo')
                ->assertVisible('@login-email')
                ->assertAttribute('@login-email', 'placeholder', 'Your email')
                ->assertVisible('@login-submit')
                ->assertVisible('@login-has-account');
      
        $browser->typeSlowly('@login-email', "test@handm.app")
                ->click('@login-submit');
      
        $browser->waitFor('@login-code-1')
                ->assertSee('Hello, I\'m Handy,')
                ->assertSee('To sign in, enter the code from the email we\’ve sent to test@handm.app')
                ->assertVisible('@handy-logo')
                ->assertVisible('@login-code-1')
                ->assertVisible('@login-code-2')
                ->assertVisible('@login-code-3')
                ->assertVisible('@login-code-4')
                ->assertVisible('@login-submit-code')
                ->assertVisible('@login-resend-code');

        $browser->typeSlowly('@login-code-1', '...')
                ->typeSlowly('@login-code-2', '...')
                ->typeSlowly('@login-code-3', '...')
                ->typeSlowly('@login-code-4', '...');
      
        $browser->click('@login-submit-code')
                ->waitForRoute('account');
        
        $browser->waitFor('@handy-logo')
                ->assertSee('Your Purchases')
                ->assertSee('You don\'t have a subscription yet')
                ->assertSee('Subscribe to receive a charge of positive energy every day!')
                ->assertSee('Everything is')
                ->assertSee('in the App')
                ->assertSee('Download the app to take advantage of your personalized plan')
                ->assertVisible('@handy-logo')
                ->assertVisible('@account-edit-btn')
                ->assertVisible('@account-logout-btn')
                ->assertVisible('@account-choose-plan');
                    
        $browser->script('window.scrollTo(0, 1400);');
                    
        $browser->assertSee('Need help?')
                ->assertSee('You can request a refund by contacting our technical support team. If you have any questions, please do not hesitate to contact us. We will respond as soon as possible.')
                
                ->assertVisible('@download-app')
                ->assertVisible('@contact-us');

        $browser->script('window.scrollTo(0, 700);');
                    
        $browser->assertSee('test@handm.app');

        $browser->click('@account-edit-btn');

        $browser->waitForText('Edit Profile')
                ->assertSee('Edit Profile')
                ->assertVisible('@edit-account-back')
                ->assertVisible('@edit-account-email')
                ->assertVisible('@edit-account-name')
                ->waitFor('@edit-account-birthday')
                ->assertVisible('@edit-account-birthday')
                ->assertVisible('@edit-account-save')
                ->assertVisible('@edit-account-delete')

                ->assertAttribute('@edit-account-email', 'placeholder', 'Your email')
                ->assertAttribute('@edit-account-email', 'disabled', 'true')
                ->assertAttribute('@edit-account-name', 'placeholder', 'Your first name')
                ->assertAttribute('@edit-account-birthday', 'placeholder', 'Date of birth')
                    
                ->type('@edit-account-name', 'Test User')
                ->type('@edit-account-birthday', '01012001')
                    
                ->click('@edit-account-save');
            
        $browser->visit('/account')
                ->assertDontSee('test@handm.app')                    
                ->assertSee('Test User');
        });
    }
}

