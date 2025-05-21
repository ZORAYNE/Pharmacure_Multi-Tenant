<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TenantAcceptedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tenant;
    public $plaintextPassword;

    /**
     * Create a new message instance.
     *
     * @param  mixed  $tenant
     * @param  string|null  $plaintextPassword
     * @return void
     */
    public function __construct($tenant, $plaintextPassword = null)
    {
        $this->tenant = $tenant;
        $this->plaintextPassword = $plaintextPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Tenant Registration is Accepted')
                    ->view('emails.tenant_accepted')
                    ->with([
                        'tenantName' => $this->tenant->tenant_name,
                        'fullName' => $this->tenant->full_name,
                        'email' => $this->tenant->email,
                        'password' => $this->plaintextPassword,
                    ]);
    }
}
