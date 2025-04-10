<?php

namespace App\Mail;

use App\Models\GiftList;
use App\Models\FriendInvitation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GiftListInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $giftList;
    public $invitation;
    public $user;
    public $messageContent;  // Changed from $message to $messageContent to avoid confusion

    /**
     * Create a new message instance.
     */
    public function __construct(GiftList $giftList, FriendInvitation $invitation, User $user, $message = null)
    {
        $this->giftList = $giftList;
        $this->invitation = $invitation;
        $this->user = $user;
        $this->messageContent = $message;  // Store the message as messageContent
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Vous avez été invité à voir une liste de cadeaux')
                   ->view('emails.gift-list-invitation');
    }
}
