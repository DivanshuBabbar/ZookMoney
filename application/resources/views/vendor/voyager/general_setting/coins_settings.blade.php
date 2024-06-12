@extends('voyager::master')
@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-person"></i> Coins Settings
        </h1>
        @include('voyager::multilingual.language-selector')
    </div>
    <style>
        .mail-content{
            word-wrap: break-word;
        }
    </style>
@stop
@section('content')
<div class="page-content browse container-fluid">
    @include('voyager::alerts')
    <style type="text/css">
        a{
            text-decoration: none !important;
            padding: 0px 7px 0px 6px !important;
        }

    </style>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-body">
                    <form action="{{route('admin.saveCoinsSetting')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-striped table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Coin Name</th>
                                            <th>Price</th>
                                            <th>Wallet Address</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table_tbody">
                                        @if(!empty($coins) && count($coins) > 0)
                                            @foreach($coins as $key=>$row)
                                                <tr>
                                                    <td>
                                                        {{$key+1}}
                                                        <input type="hidden" value="@isset($row->id){{$row->id}}@endisset" name="detail[{{$key}}][id]">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="" required="" value="@isset($row->coin_name){{$row->coin_name}}@endisset" name="detail[{{$key}}][coin_name]">
                                                    </td>
                                                    <td>
                                                        1 x = <input type="text" class="" value="@isset($row->price){{showAmount($row->price)}}@endisset" required="" name="detail[{{$key}}][price]">
                                                    </td>
                                                    <td>
                                                        <input type="text" style="width: 100%;" class="" value="@isset($row->wallet_address){{$row->wallet_address}}@endisset" name="detail[{{$key}}][wallet_address]">
                                                    </td>
                                                    <td>
                                                        @if($key == 0)
                                                            <a href="#" class="btn btn-xs btn-info add_new_row">add</a>
                                                        @else
                                                            <a href="#" class="btn btn-xs btn-danger delete_row">Delete</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>
                                                    1
                                                </td>
                                                <td>
                                                    <input type="text" class="" required="" name="detail[0][coin_name]">
                                                </td>
                                                <td>
                                                    1 x = <input type="text" class="" required="" name="detail[0][price]">
                                                </td>
                                                <td>
                                                    <input type="text" class="" style="width: 100%;" name="detail[0][wallet_address]">
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-xs btn-info add_new_row">add</a>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-info">Save</button>
                            </div>
                        </div>
                    </form>
                 </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded',function(){
        $('body').on('click','.add_new_row',function(event){
            event.preventDefault();
            var element = $(this);
            var tbody = element.closest('#table_tbody');
            var tr = tbody.find('tr');
            var index = tr.length;
            var length = parseFloat(index + 1);
            var html = '';
            html+='<tr>';
                html+='<td>'+length+'</td>';
                html+='<td><input type="text" class="" required="" name="detail['+index+'][coin_name]"></td>';
                html+='<td>1 x = <input type="text" class="" required="" name="detail['+index+'][price]"></td>';
                html+='<td><input type="text" class="" style="width: 100%;" name="detail['+index+'][wallet_address]"></td>';
                html+='<td><a href="#" class="btn btn-xs btn-danger delete_row">Delete</a></td>';
            html+'</tr>';
            tbody.append(html);
        });
        $('body').on('click','.delete_row',function(event){
            event.preventDefault();
            $(this).closest('tr').remove();
        });
    },false);
</script>
@endsection