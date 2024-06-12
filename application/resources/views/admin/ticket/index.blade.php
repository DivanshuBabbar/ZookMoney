@extends('admin.layouts.master')
@section('content')
@push('styles')
<link rel="stylesheet" href="{{asset('assets/admin/newdash/css/bootstrap-datepicker.min.css')}}">
<style>
    .chat-messages {
        max-height: 400px;
        overflow-y: auto;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    .message {
        display: flex;
        margin-bottom: 10px;
    }

    .message.sender .message-content {
        margin-left: auto;
        background-color: #e1ffc7;
    }

    .message.receiver .message-content {
        margin-right: auto;
        background-color: #ffffff;
    }

    .message-content {
        padding: 10px;
        border-radius: 5px;
        max-width: 60%;
    }

    .chat-input {
        display: flex;
        align-items: center;
        margin-top: 10px;
    }

    .chat-input .icon-container {
        display: flex;
        align-items: center;
    }

    .chat-input .icon {
        margin-right: 10px;
        cursor: pointer;
    }

    .chat-input textarea {
        flex: 1;
        resize: none;
    }

    .chat-input button {
        margin-left: 10px;
    }
   .chat-message {
      display: flex;
      margin-bottom: 10px;
   }

   .admin-message .message-content {
      background-color: #DCF8C6;
      border-radius: 8px;
      padding: 10px;
      max-width: 70%;
      align-self: flex-start;
   }

   .user-message {
      justify-content: flex-end;
      width: 100%;
   }

   .user-message .message-content {
      background-color: #FFFFFF;
      border-radius: 8px;
      padding: 10px;
      max-width: 70%;
      align-self: flex-end;
   }

   .message-content {
      display: inline-block;
   }

   .timestamp {
      font-size: 10px;
      color: #999;
   }
      .chat-container {
      display: flex;
      flex-direction: column;
      height: 400px;
      border: 1px solid #ccc;
      border-radius: 5px;
      overflow: hidden;
   }

   .chat-messages {
      flex-grow: 1;
      overflow-y: auto;
      padding: 10px;
   }

   .chat-input {
      display: flex;
      align-items: center;
      padding: 10px;
      background-color: #f9f9f9;
   }

   .icon-container {
      display: flex;
      align-items: center;
   }

   .icon {
      cursor: pointer;
      margin-right: 10px;
      color: #555;
      font-size: 20px;
   }

   .icon i {
      transition: color 0.3s ease;
   }

   .icon i:hover {
      color: #333;
   }

   .chat-input textarea {
      flex-grow: 1;
      margin-right: 10px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      resize: none;
   }

   .chat-input textarea:focus {
      outline: none;
      border-color: #66afe9;
      box-shadow: 0 0 5px rgba(102, 175, 233, 0.5);
   }

   .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
   }

   .btn-primary:hover {
      background-color: #0056b3;
      border-color: #0056b3;
   }

   .chat-container {
      display: flex;
      flex-direction: column;
      height: 400px; 
      background-image: linear-gradient(to right top, #d16ba5, #d96f9d, #df7596, #e37c90, #e6838b, #ea8e95, #ee9aa0, #f1a5aa, #f6b7c2, #f9cad8, #fbddea, #fff0f9);
      }

   .chat-messages {
      flex-grow: 1;
      overflow-y: auto;
      border-bottom: 1px solid #ccc;
      padding: 10px;
   }

   .chat-input {
      display: flex;
      align-items: center;
      padding: 10px;
   }

   .chat-input textarea {
      flex-grow: 1;
      margin-right: 10px;
   }

      .modal {
   display: none;
   position: fixed;
   z-index: 1;
   left: 0;
   top: 0;
   width: 100%;
   height: 100%;
   overflow: auto;
   background-color: rgba(0, 0, 0, 0.5);
   }

   .modal-content {
   background-color: #fefefe;
   margin: 15% auto;
   padding: 20px;
   border: 1px solid #888;
   width: 80%;
   max-width: 600px;
   border-radius: 8px;
   }

   .close {
   color: #aaa;
   float: right;
   font-size: 28px;
   font-weight: bold;
   }

   .close:hover,
   .close:focus {
   color: black;
   text-decoration: none;
   cursor: pointer;
   }
</style>
@endpush
<div class="content-wrapper">
   <div class="content-header">
   <button class="btn btn-info" style="float:right; margin-right:50px;" onclick="openModal()">Add Ticket</button>

<!-- Ticket Modal -->
<div id="ticketModal" class="modal" onclick="closeModal(event)">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Add Ticket</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-xs-12">
                                <div class="table-responsive" style="max-height: 400px; overflow-y: scroll;">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th>Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user as $index => $userData)
                                            <tr>
                                                <td>{{ $userData->id }}</td>
                                                <td>{{ $userData->name }}</td>
                                                <td>
                                                <button class="btn btn-primary" onclick="openEditModal('{{ $userData->id }}')">Edit</button> 
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>Edit Ticket</h2>
        <form action="{{ route('admin.ticketlist.data') }}" method="POST" enctype="multipart/form-data">
            @csrf             
            <input type="hidden" name="editId" id="editId">
            <div class="form-group">
               <label for="editTitle">Title:</label>
               <input type="text" class="form-control" id="editTitle" name="title" placeholder="Enter Title" required>
            </div>
            <div class="form-group">
               <label for="editCategory">Category:</label>
               <select class="form-control" id="editCategory" name="category" required>
                     @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                     @endforeach
               </select>
            </div>
            <div class="form-group">
               <label for="editPriority">Priority:</label>
               <select class="form-control" id="editPriority" name="priority" required>
                     <option value="low">Low</option>
                     <option value="medium">Medium</option>
                     <option value="high">High</option>
               </select>
            </div>
            <div class="form-group">
               <label for="editMessage">Message:</label>
               <textarea class="form-control" id="editMessage" name="message" rows="3" placeholder="Enter Message" required></textarea>
            </div>
            <div class="form-group">
               <label for="fileToUpload">Upload File:</label>
               <input type="file" class="form-control" id="fileToUpload" name="fileToUpload" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
         </form>
    </div>
</div>
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">{{$page_title}} </h1>
            </div>
         </div>
      </div>
   </div>
   <div class="row mx-3">
      <div class="col-sm-12">
            @include('flash')
      </div>
   </div>
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-3">
               <div class="form-group">
                     <label>Category</label>
                     <select class="form-control select2" style="width: 100%;" id="category">
                        <option value=''>-- Select All --</option>
                        @foreach($categories as $category)
                           <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                     </select>
               </div>
            </div>
            <div class="col-md-3">
               <div class="form-group">
                     <label>Ticket Status</label>
                     <select class="form-control select2" style="width: 100%;" id="ticketType">
                        <option value=''>-- Select Type --</option>
                        <option value='Open'>Open</option>
                        <option value='Closed'>Closed</option>
                     </select>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                     <label>Created at</label>
                     <div class="form-group d-flex">
                        <input type="text" class="form-control" id="transactionDateFrom" placeholder="From">
                        <input type="text" class="form-control mx-2" id="transactionDateTo" placeholder="To">
                     </div>
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-12">
               <div class="card">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-lg-12 col-xs-12">
                           <div class="table-responsive">
                              <table class="table align-items-center" id="ticketdatatable">
                                 <thead>
                                    <tr>
                                       <th scope="col">#</th>
                                       <th>Name</th>
                                       <th>Email</th>
                                       <th>Category</th>
                                       <th>Ticket ID</th>
                                       <th>Status</th>
                                       <th>File</th>
                                       <th>Last Updated</th>
                                       <th>Comment</th>
                                       <th>Action</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
</div>
</div>
</section>
</div>
<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="background-image: linear-gradient(to right top, #d16ba5, #d96f9d, #df7596, #e37c90, #e6838b, #ea8e95, #ee9aa0, #f1a5aa, #f6b7c2, #f9cad8, #fbddea, #fff0f9);">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Ticket Comment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body chat-container">
                <div class="chat-messages" id="chatMessages" style="max-height: 400px; overflow-y: auto; padding: 10px; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9; background-color: antiquewhite;">
                    <!-- Example messages -->
                    <div class="message sender">
                        <div class="message-content">Hello, how can I help you?</div>
                    </div>
                    <div class="message receiver">
                        <div class="message-content">I have an issue with my account.</div>
                    </div>
                </div>
                <form id="commentForm" action="{{ route('admin.store.comments') }}" method="POST" style="margin-top: 10px;">
                    @csrf
                    <div class="chat-input" style="display: flex; align-items: center;">
                        <div class="icon-container" style="display: flex; align-items: center;">
                            <input type="hidden" name="ticket_id" id="ticket_id" value="">
                            <input type="hidden" name="clickup_ticket_id" id="clickup_ticket_id" value="">
                            <input type="hidden" name="user_id" id="user_id" value="">
                            <label for="fileInput" class="icon" style="margin-right: 10px; cursor: pointer;" onclick="document.getElementById('fileInput').click();"><i class="fas fa-paperclip"></i></label>
                            <input type="file" name="attachment" id="fileInput" style="display: none;" onchange="displaySelectedFile(this)">
                            <label for="recordButton" class="icon" id="recordLabel" style="margin-right: 10px; cursor: pointer;"><i class="fas fa-video"></i></label>
                        </div>
                        <div id="selectedFileContainer" style="flex: 1; margin-right: 10px;"></div>
                        <textarea name="comment" class="form-control" id="comment_text" rows="1" placeholder="Type your message..." style="flex: 1; resize: none;" required></textarea>
                        <button type="submit" class="btn btn-primary" id="sendButton" style="margin-left: 10px;">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection

@push('scripts')
<script src="{{asset('assets/admin/newdash/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/admin/newdash/js/bootstrap-datepicker.en-US.min.js')}}" type="text/javascript"></script>

<script>
   function displaySelectedFile(input) {
        const container = document.getElementById('selectedFileContainer');
        container.innerHTML = '';
        if (input.files && input.files[0]) {
            const fileName = document.createElement('div');
            fileName.textContent = input.files[0].name;
            container.appendChild(fileName);
        }
    }
   function displaySelectedFile(input) {
        const selectedFileContainer = document.getElementById('selectedFileContainer');
        const file = input.files[0];
        
        if (file) {
            const fileName = file.name;
            const fileSize = getFileSizeString(file.size);
            
            selectedFileContainer.innerHTML = `
                <div>Selected File: ${fileName} (${fileSize})</div>
            `;
        } else {
            selectedFileContainer.innerHTML = '';
        }
    }
    
   function getFileSizeString(size) {
        const units = ['B', 'KB', 'MB', 'GB', 'TB'];
        let unitIndex = 0;
        while (size >= 2048 && unitIndex < units.length - 1) {
            size /= 2048;
            unitIndex++;
        }
        return size.toFixed(2) + ' ' + units[unitIndex];
   }
   function reply_click(d) {
      var ticket_id = d.getAttribute('data-id');
      var user_id = d.getAttribute('data-comment');
      var status = d.getAttribute('data-status');
      var clickup_ticket_id = d.getAttribute('data-ticket');
      fetchChatMessages(ticket_id);
      $('#commentModal').modal('show');
      $("#commentModal").css("z-index", "1500");
      if (status == 'Closed') {
         $('#comment_text').hide();
         $('#sendButton').hide();
         $('#submit_comment').hide();
         $('#ticket_id').val(ticket_id);
         $('#user_id').val(user_id);
         $('#clickup_ticket_id').val(clickup_ticket_id);

      } else if (status == 'Open') {
         $('#comment_text').show();
         $('#sendButton').show();
         $('#submit_comment').show();
         $('#ticket_id').val(ticket_id);
         $('#user_id').val(user_id);
         $('#clickup_ticket_id').val(clickup_ticket_id);
      }
   }

   function fetchChatMessages(ticketId) {
      $('#chatMessages').empty();
      $.ajax({
         type: 'GET',
         url: '{{ route('admin.fetch.comments') }}',
         data: { ticket_id: ticketId }, 
         success: function(messages) {
            
               messages.forEach(function(message) {
                  appendChatMessage(message);
               });
         },
         error: function(response) {
               console.error('Error fetching chat messages:', response);
         }
      });
   }

   function appendChatMessage(message) {
    const isAdmin = message.sender === 'admin';
    const messageClass = isAdmin ? 'admin-message' : 'user-message';
    const messageAlignment = isAdmin ? 'left' : 'right';
    const messageBackground = isAdmin ? '#DCF8C6' : '#FFFFFF';

    const comment = message.comment ? `<p>${message.comment}</p>` : '';
    const attachmentLink = message.attachment 
        ? `<a href="/application/${message.attachment}" target="_blank">View File</a>` 
        : '';

    const formattedMessage = `
        <div class="chat-message ${messageClass}" style="text-align: ${messageAlignment};">
            <div class="message-content" style="background-color: ${messageBackground};">
                ${comment}
                ${attachmentLink}
                <span class="timestamp">${message.created_at}</span>
            </div>
        </div>
    `;

    $('#chatMessages').append(formattedMessage);
    $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
   }


   $(document).ready(function() {
      $('#commentForm').on('submit', function(e) {
         e.preventDefault();
         var formData = new FormData(this);
         formData.append('_token', '{{ csrf_token() }}');

         $.ajax({
               type: 'POST',
               url: '{{ route('admin.store.comments') }}',
               data: formData,
               processData: false,
               contentType: false,
               success: function(response) {
                  $('#comment_text').val('');
                  $('#fileInput').val('');
                  $('#selectedFileContainer').empty();

                  appendChatMessage(response);
               },
               error: function(response) {
                  alert('An error occurred while submitting the comment.');
               }
         });
      });
   });
      function startRecording() {
         navigator.mediaDevices.getDisplayMedia({ video: true })
         .then(stream => {
            const recorder = new MediaRecorder(stream);
            const chunks = [];
            recorder.ondataavailable = e => chunks.push(e.data);
            recorder.onstop = e => {
                  const blob = new Blob(chunks, { type: 'video/webm' });
                  const url = URL.createObjectURL(blob);
                  const a = document.createElement('a');
                  a.href = url;
                  a.download = 'screen-record.webm';
                  document.body.appendChild(a);
                  a.click();
                  setTimeout(() => {
                     document.body.removeChild(a);
                     URL.revokeObjectURL(url);
                  }, 100);
            };
            recorder.start();
            setTimeout(() => recorder.stop(), 10000); 
         })
         .catch(err => console.error('Error starting screen recording:', err));
      }

   document.getElementById('recordLabel').addEventListener('click', startRecording);

   
   function openEditModal(index) {
      var editModal = document.getElementById('editModal');
      editModal.style.display = "block";

      var idInput = document.getElementById('editId');
      idInput.value = index; 
   } 

   function closeEditModal() {
      var editModal = document.getElementById('editModal');
      editModal.style.display = "none";
   }

   function openModal() {
      var ticketModal = document.getElementById('ticketModal');
      ticketModal.style.display = "block";
   }
   function closeModal(event) {       
      ticketModal.style.display = "none";
   }


   $('#show_comment').on('click',function(e) {
      e.preventDefault();
      var ticket_id = $('#ticket_id').val();
      var user_id =  $('#user_id').val();

       $.ajax({
         type: "GET",
         url: "{{ route('admin.show_comment') }}",
         data: {
            _token:"{{ csrf_token() }}",
            ticket_id:ticket_id,
            user_id:user_id
         },
        
         success: function(data){
            $('#show_ticket_comment').html('');
            var comment = data.comments;
            if (comment != '') {
               $.each(comment , function(index, val) {
                  $('#show_ticket_comment').append('<label for="show_ticket_comment">Commented on:'+ getDate(val['updated_at']) +'-' + val['comment'] + '</label><br>');
               });
            }else{
               $('#show_ticket_comment').append('<label for="show_ticket_comment">No Previous Comments</label>')
            }
         }
      });

   });

   function getDate(dateformat) {
      var date = new Date(dateformat);

      // Get year, month, and day part from the date
      var year = date.toLocaleString("default", { year: "numeric" });
      var month = date.toLocaleString("default", { month: "2-digit" });
      var day = date.toLocaleString("default", { day: "2-digit" });

      // Generate yyyy-mm-dd date string
      var formattedDate = year + "-" + month + "-" + day;
      return formattedDate;  // Prints: 2022-05-04
   }

   function reply_click(d) {
    var ticket_id = d.getAttribute('data-id');
    var user_id = d.getAttribute('data-comment');
    var status = d.getAttribute('data-status');
    var clickup_ticket_id = d.getAttribute('data-ticket');
   
    fetchChatMessages(ticket_id);
    $('#commentModal').modal('show');
    $("#commentModal").css("z-index", "1500");
    if (status == 'Closed') {
        $('#comment_text').hide();
        $('#sendButton').hide();
        $('.chat-input').hide();
        $('#submit_comment').hide();
        $('#ticket_id').val(ticket_id);
        $('#user_id').val(user_id);
         $('#clickup_ticket_id').val(clickup_ticket_id);

    } else if (status == 'Open') {
        $('#comment_text').show();
        $('.chat-input').show();
        $('#sendButton').show();
        $('#submit_comment').show();
        $('#ticket_id').val(ticket_id);
        $('#user_id').val(user_id);
        $('#clickup_ticket_id').val(clickup_ticket_id);
    }
}


   function close_ticket(d) {

     var ticket_id = d.getAttribute('data-id');
         $.ajax({
         type: "POST",
         url: "{{ route('admin.close_ticket') }}",
         data: {
            _token:"{{ csrf_token() }}",
            ticket_id:ticket_id
         },
        
        success: function(data){
           
               Swal.fire({
                 title: "Success!",
                 text: data.message,
                 icon: "success"
               });
               datatable.draw();
            
         },
         error:function(data){
           
          
               Swal.fire({
                 title: "Success!",
                 text: data.message,
                 icon: "success"
               });
               datatable.draw();
            
         }
      });
   }

   function open_ticket(d) {

   var ticket_id = d.getAttribute('data-id');
      $.ajax({
      type: "POST",
      url: "{{ route('admin.open_ticket') }}",
      data: {
         _token:"{{ csrf_token() }}",
         ticket_id:ticket_id
      },
      
      success: function(data){
        Swal.fire({
           title: "Success!",
           text: data.message,
           icon: "success"
        });
        datatable.draw();
      },
        error:function(data){
           
           Swal.fire({
               title: "Success!",
               text: data.message,
               icon: "success"
            });
            datatable.draw();
             
        }
   });
   }

   $('#commentModal').on('hidden.bs.modal', function (e) {
       $(this).find("textarea").val('').end();
       $('#show_ticket_comment').html('');
   });
  
   const datatable = $('#ticketdatatable').DataTable({
      searchDelay: 500,
      processing: true,
      serverSide: true,
    
      fixedHeader: true,
      lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
      ajax: {
            url: '{{ route("admin.getticketlist") }}',
            type: 'GET',
            data: function (d) {
                  d.ticket_type = $('#ticketType').val();
                  d.created_at_from = $('#transactionDateFrom').val();
                  d.created_at_to = $('#transactionDateTo').val();
                  d.category = $('#category').val();
            }
      },
      columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},
            { data: 'user_name' },
            { data: 'user_email' },
            { data: 'category' ,orderable:false,searchable:false},
            { data: 'ticket_id' },
            { data: 'status' },
            { data: 'file' },
            { data: 'updated_at' ,orderable:false},
            { data: 'comment' ,orderable:false},
            { data: 'action' ,orderable:false},

      ],
       
      columnDefs: [
         { 
            targets: '_all',
            defaultContent: 'N/A'
         }
      ],
      "language": 
      {     
         processing: '<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i><span class="sr-only">Loading...</span>',
      },
   });


   $('#transactionDateFrom').datepicker({
      clearBtn: true,
      format: {

         toDisplay: function (date, format, language) {
            return new Date(date).toLocaleDateString();
         },
         toValue: function (date, format, language) {
            return new Date(d);
         }
      }
   }).on('changeDate', function(e) {
      datatable.draw();
   });
   $('#transactionDateTo').datepicker({
      clearBtn: true,
      format: {

         toDisplay: function (date, format, language) {
            return new Date(date).toLocaleDateString();
         },
         toValue: function (date, format, language) {
            return new Date(d);
         }
      }
   }).on('changeDate', function(e) {
      datatable.draw();
   });
   $("#category").on('change', function (e) {
      datatable.draw();
   });
   $("#ticketType").on('change', function (e) {
      datatable.draw();
   });
 
</script>
@endpush