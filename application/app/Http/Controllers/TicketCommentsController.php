<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Ticket;
use App\Models\Ticketcomment;
use App\Mailers\AppMailer;
use Illuminate\Http\Request;
use Auth;
use App\Helpers\ClickupHelper;

class TicketCommentsController extends Controller{

	public function postTicketComment(Request $request, $lang, AppMailer $mailer)
	{
	    $this->validate($request, [
	        'comment'   => 'required'
	    ]);

		$filePath = null;
		if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('attachments', $fileName, 'public');
            $filePath = '/storage/app/public/'.$filePath;
        }
	        $comment = Ticketcomment::create([
	            'ticket_id' => $request->input('ticket_id'),
	            'user_id'   => Auth::user()->id,
	            'comment'   => $request->input('comment'),
				'attachment' =>  $filePath,
	        ]);

	        $clickupComment = ClickupHelper::clickupComment($request->input('clickup_ticket_id'),$request->input('comment'));
	
	        // send mail if the user commenting is not the ticket owner
	        if ($comment->ticket->user->id !== Auth::user()->id) {
	            $mailer->sendTicketComments($comment->ticket->user, Auth::user(), $comment->ticket, $comment);
	        }

	        flash("Your comment has be submitted.", "success");

	        return redirect()->back();
	}

	
}