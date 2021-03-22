<?php

namespace App\Jobs;

use Mail;
use Illuminate\Bus\Queueable;
use App\Models\UserEmailVerification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\SendEmailVerification as MailSendEmailVerification;

class SendEmailVerification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sendEmailVerification = UserEmailVerification::where('email', $this->data['email'])->where('status', UserEmailVerification::STATUS_NEW)->latest()->first();

        if ($sendEmailVerification) {
            
            Mail::to($this->data['email'])->send(new MailSendEmailVerification(
                $this->data['url'],
                $this->data['email'] 
            ));

            $sendEmailVerification->status = UserEmailVerification::STATUS_SEND;
            $sendEmailVerification->save();
        }
    }
}
