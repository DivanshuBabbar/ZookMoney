@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{asset('assets/admin/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/admin/css/buttons.dataTables.min.css')}}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('assets/admin/newdash/css/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" integrity="sha512-f0tzWhCwVFS3WeYaofoLWkTP62ObhewQ1EZn65oSYDZUg1+CyywGKkWzm8BxaJj5HGKI72PnMH9jYyIFz+GH7g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
  
    #bulk_upload_previous {
    float: left;
}
</style>
@endpush

@section('content')
    <div class="container">
    @include('flash')
        <div class="row">
            <div class="col-md-6">
                <button class="btn btn-primary btn-block" onclick="toggleBulkPayoutForm('INR')">Bulk Payout INR</button>
            </div>
            <div class="col-md-6">
                <button class="btn btn-primary btn-block" onclick="toggleBulkPayoutForm('USDT')" disabled>Bulk Payout USDT</button>
            </div>
        </div>
    </div>
    <div class="container" id="bulkPayoutINR" style="display: none;">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3" style="padding-left: 21px;margin-top:20px;">
                    <div class="form-group">
                        <a href="/application/storage/app/public/bulk_files/BulkfileFormat.xlsx" class="btn btn-primary" style="margin-bottom: 10px; display: block;">Download Sample</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <form id="uploadFormINR" action="{{ route('bulkuploadpayout', app()->getLocale()) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row" style="margin-top:20px;">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="file" name="fileToUpload" id="fileToUploadINR" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="scheduledataandtime" name="date_time" placeholder="Schedule Date & Time" required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary" onclick="toggleBulkPayoutForm('INR')">Upload File</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="bulk_upload">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Request Id</th>
                            <th scope="col">File Name</th>
                            <th scope="col">Number Of Payout</th>
                            <th scope="col">Total Payout</th>
                            <th scope="col">Status</th>
                            <th scope="col">Remarks</th>
                            <th scope="col">Response File</th>
                            <th scope="col">Schedule At</th>
                            <th scope="col">Uploaded At</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div
    
@endsection

@section('js')
   <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.js" integrity="sha512-9yoLVdmrMyzsX6TyGOawljEm8rPoM5oNmdUiQvhJuJPTk1qoycCK7HdRWZ10vRRlDlUVhCA/ytqCy78+UujHng==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
           $('#scheduledataandtime').datetimepicker({
            clearBtn: true,
            format: 'Y-m-d H:i:s'
        });
        
        function toggleBulkPayoutForm(currency) {
            var bulkPayoutForm = document.getElementById('bulkPayout' + currency);
            if (bulkPayoutForm.style.display === 'none' || bulkPayoutForm.style.display === '') {
                bulkPayoutForm.style.display = 'block';
                setTimeout(() => {
                    document.getElementById('fileToUpload' + currency).focus();
                }, 100);
            } else {
                bulkPayoutForm.style.display = 'none';
            }
        }

        $(document).ready(function () {
        $('#bulk_upload').DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            orderable: true,
            searching: false,
            pagingType: 'simple',
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            ajax: {
                url: '{{ route("bulk_payout.data",app()->getLocale()) }}',
                type: 'GET',
                error: function (xhr, error, thrown) {
                    console.log('AJAX Error:', error);
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                { data: 'request_id' },
                { data: 'file_name', orderable: false },
                { data: 'total_payout' },
                { data: 'total_amount' },
                { data: 'status' },
                { data: 'remarks' },
                { data: 'response_file' },
                {
                    data: 'data_time_schedular',
                    render: function (data) {
                        return formatDate(data);
                    }
                },
                { data: 'created_at' },
            ]

        });
    });
function formatDate(dateTimeStr) {
    const months = [
        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ];

    const [datePart, timePart] = dateTimeStr.split(' ');

    const [year, month, day] = datePart.split('-');
    const [hour, minute, second] = timePart.split(':');

    const monthName = months[parseInt(month, 10) - 1];
    const formattedDate = `${monthName} ${parseInt(day, 10)}, ${year}`;
    const formattedTime = `${parseInt(hour, 10) % 12 || 12}:${minute} ${parseInt(hour, 10) >= 12 ? 'PM' : 'AM'}`;

    return `${formattedDate}, ${formattedTime}`;
}

    </script>
@endsection

