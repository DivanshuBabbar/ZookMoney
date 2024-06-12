<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use App\Mailers\AppMailer;
use App\Models\Ticketcategory;
use App\Models\Ticketcomment;
use Auth;
use App\User;
use App\Helpers\ClickupHelper;

class SupportTicketController extends Controller
{
    public function index()
    {
        $page_title ="Support Tickets";
        $active = 'ticket';
        $categories = Ticketcategory::all();
        $user = User::all();
        return view('admin.ticket.index',compact('active','page_title','categories','user'));
    }

    public function getticketlist(Request $request)
    {
        if (!request()->ajax()) {
            $page_title ="Support Tickets";
            $active = 'ticket';
            $categories = Ticketcategory::all();
            $user = User::all();
            return view('admin.ticket.index',compact('active','page_title','categories','user'));
        }

        $data = Ticket::query()->with('category')->with('User')->orderBy('id','desc')
            ->when(!empty(request()->ticket_type), function ($query) {
                return $query->where('status', request()->ticket_type );
            })
            ->when(!empty(request()->category), function ($query) {
                return $query->where('ticketcategory_id', request()->category );
            })
            ->when(!empty(request()->created_at_from), function ($query) {
                return $query->whereDate('created_at', '>=' ,date('Y-m-d', strtotime(request()->created_at_from)));
            })
            ->when(!empty(request()->created_at_to), function ($query) {
                return $query->whereDate('created_at', '<=' ,date('Y-m-d', strtotime(request()->created_at_to)));
            });

        return DataTables::eloquent($data)
            ->editColumn('category', function ($data) {
                return $data->category->name ?? '';
            }) 
            ->addColumn('user_name', function ($data) {
                return $data->User->name ?? '';
            }) 
            ->addColumn('user_email', function ($data) {
                return $data->User->email ?? '';
            })
            ->editColumn('ticket_id', function ($data) {
               return $data->ticket_id;
            }) 
            ->editColumn('status', function ($data) {
                return $data->status;
            })
            ->editColumn('updated_at', function ($data) {
                return !empty($data->updated_at) && ($data->updated_at instanceof Carbon)? $data->updated_at->timezone('Asia/Kolkata')->format('M j, Y, g:i A') : '-';

            })
            ->addColumn('comment', function ($data) {
                $badge = '';
                $badge .= '<button type="button" class="btn btn-primary" onClick="reply_click(this)" data-id="'.$data->id.'" id="comment_ticket_'.$data->ticket_id.'" data-comment="'.$data->user_id.'" data-status="'.$data->status.'" data-ticket="'.$data->ticket_id.'">Comment</button>&nbsp&nbsp&nbsp';
                return $badge;
            }) 
            ->editColumn('file', function ($data) {
                if (!empty($data->file)) {
                    return '<a href="/application/'.$data->file.'" target="_blank" style="color:blue;">View File</a>';
                }
                return "";
            })
            ->addColumn('action', function ($data) {
                $badge = '';
                if($data->status != 'Closed'){
                    $badge .= '<button type="button" class="btn btn-danger" onClick="close_ticket(this)" data-id="'.$data->ticket_id.'" id="close_ticket_'.$data->ticket_id.'" data-comment="'.$data->user_id.'">Close</button>';
                }else{
                    $badge .= '<button type="button" class="btn btn-warning" onClick="open_ticket(this)" style= "color:white;" data-id="'.$data->ticket_id.'" id="open_ticket_'.$data->ticket_id.'" data-comment="'.$data->user_id.'">Re-Open</button>';
                }
                return $badge;
            }) 
            ->addIndexColumn()
            ->rawColumns(['comment','action','file'])
            ->toJson();

    }
    public function postComment(Request $request, AppMailer $mailer)
    {
        $this->validate($request, [
            'comment'   => 'required'
        ]);
        $ticket = Ticket::where('ticket_id', $request->input('ticket_id'))->firstOrFail();

        $comment = Ticketcomment::create([
            'ticket_id' => $ticket->id ?? '',
            'user_id'   => $request->input('user_id'),
            'comment'   => $request->input('comment'),
        ]);

        // $mailer->sendTicketComments($comment->ticket->user, Auth::user(), $comment->ticket, $comment);

        flash("Your comment has be submitted.", "success");
        return redirect()->back();
    }

    public function closeTicket(Request $request, AppMailer $mailer){

        $ticket = Ticket::where('ticket_id', $request->input('ticket_id'))->firstOrFail();
        $ticket->status = 'Closed';
        $ticket->save();
        $ticketOwner = $ticket->user;
        $clickupComment = ClickupHelper::ClickupTicketStatusUpdate($ticket->ticket_id,'done');
        // $mailer->sendTicketStatusNotification($ticketOwner, $ticket);
        return response()->json(["ticketstatus" => 1, "message" => "The ticket has been closed."]);
    }

    public function open_ticket(Request $request, AppMailer $mailer){

        $ticket = Ticket::where('ticket_id', $request->input('ticket_id'))->firstOrFail();
        $ticket->status = 'Open';
        $ticket->save();
        $ticketOwner = $ticket->user;
        $clickupComment = ClickupHelper::ClickupTicketStatusUpdate($ticket->ticket_id,'backlog');
        return response()->json(["ticketstatus" => 1, "message" => "The ticket has been opened."]);
    }

    public function showComment(Request $request)
    {
        $ticket = Ticket::where('ticket_id', $request->input('ticket_id'))->firstOrFail();
        $comments = Ticketcomment::where('ticket_id',$ticket->id)->get();
        
        return response()->json(["comments" => $comments]);

    }

    public function getdata(Request $request){
        
        $this->validate($request, [
            'title'     => 'required',
            'category'  => 'required',
            'priority'  => 'required',
            'message'   => 'required',
            'fileToUpload' => 'required|file', 
        ]);
      
        if ($request->hasFile('fileToUpload')) {
            $file = $request->file('fileToUpload');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');
            $filePath = '/storage/app/public/'.$filePath;
            
        }

        $clickup = ClickupHelper::clickupTicket($request->input('title'),$request->input('priority'),$request->input('message'));
        $ticket_id = json_decode($clickup)->id ?? '';

        $ticket = new Ticket([
            'title'     => $request->input('title'),
            'user_id'   => $request->input('editId'),
            'ticket_id' => $ticket_id,
            'ticketcategory_id'  => $request->input('category'),
            'priority'  => $request->input('priority'),
            'message'   => $request->input('message'),
            'status'    => "Open",
            'file'   => $filePath
        ]);
        $ticket->save();        
        flash( "A ticket with ID: #$ticket->ticket_id has been opened." , "success");
        return redirect()->route('admin.ticketlist');
    }

    public function storeComment(Request $request)
    {
     

        // Validate the incoming request data
        $validated = $request->validate([
            'ticket_id' => 'required|integer|exists:tickets,id',
            'user_id' => 'required|integer|exists:users,id',
            'comment' => 'nullable',
        ]);
        // Create a new comment
        $comment = new Ticketcomment();
        $comment->user_id = $validated['user_id'];
        $comment->ticket_id = $validated['ticket_id'];
        $comment->comment = $validated['comment'];
        $comment->sender = 'admin';
    
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('attachments', $fileName, 'public');
            $filePath = '/storage/app/public/'.$filePath;
            $comment->attachment = $filePath;    
        }
        
        $comment->save();
        $clickupComment = ClickupHelper::clickupComment($request->input('clickup_ticket_id'),$validated['comment']);
        
        return response()->json(['id' => $comment->id,'user_id' => $comment->user_id,'ticket_id' => $comment->ticket_id,'comment' => $comment->comment,'attachment' => $comment->attachment,'created_at' => $comment->created_at->toDateTimeString(),'updated_at' => $comment->updated_at->toDateTimeString(), 'sender' => $comment->sender,]);
    }

    public function fetchComments(Request $request)
    {
        $ticketId = $request->input('ticket_id');
        $messages = Ticketcomment::where('ticket_id', $ticketId)
            ->where(function ($query) {
                $query->where('sender', 'admin')
                    ->orWhereNull('sender');
            })
            ->orderBy('created_at', 'asc')
            ->get();
                
        // Prepare the response data
        $formattedMessages = [];
        foreach ($messages as $message) {
            $formattedMessages[] = [
                'id' => $message->id,
                'user_id' => $message->user_id,
                'ticket_id' => $message->ticket_id,
                'comment' => $message->comment,
                'attachment' => $message->attachment,
                'created_at' => $message->created_at->toDateTimeString(),
                'updated_at' => $message->updated_at->toDateTimeString(),
                'sender' => $message->sender,
            ];
        }
    
        return response()->json($formattedMessages);
    }
    
     
}