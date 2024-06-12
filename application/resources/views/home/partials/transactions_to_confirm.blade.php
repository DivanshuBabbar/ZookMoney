
@if($transactions_to_confirm->currentPage() <= $transactions_to_confirm->lastPage() and $transactions_to_confirm->total() > 0 )

  <div class="panel panel-default">
      <div class="panel-heading" style="border-bottom: 0; ">
        <div class="container">
          <div class="alert alert-info" role="alert">
            <h4 class="alert-heading">Well done!</h4>
            <p>Now you just have to confirm that this transaction is yours so that the money goes to it's destiny.</p>
            
          </div>
        </div>
      </div>

      <div class="panel-body">
        <div class="card user-account">
          <div class="header">
              <h2><strong>Pending</strong>Transactions</h2>
              
              <ul class="header-dropdown">
                  
                  <li class="remove">
                      <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                  </li>
              </ul>
              
          </div>
          <div class="body">
              <div class="table-responsive">
                  <table class="table m-b-0">
                      <thead>
                          <tr>
                              <th>id</th>
                              <th>{{__('Date')}}</th>
                              <th>{{__('time to expire')}}</th>
                              <th>{{__('Name')}}</th>
                              <th>{{__('Gross')}}</th>
                              <th>{{__('Fee')}}</th>
                              <th>{{__('Net')}}</th>
                              <th>{{__('Action')}}</th>
                          </tr>
                      </thead>
                      <tbody>
                        @forelse($transactions_to_confirm as $transaction)
                          <tr>
                            <td>{{$transaction->id}}</td>
                            <td>{{ localDate($transaction->created_at) }} <br> @include ('home.partials.status')</td>
                            <td>
                              @if($transaction->transactionable_type == 'App\Models\Send')
                              {{__('Funds')}} <br>{{__('Availability')}}
                              @elseif($transaction->transactionable_type == 'App\Models\Purchase')
                              5 Min
                              @endif
                            </td>
                            <td>

                            @include('home.partials.name')</a></td>
                            <td>{{$transaction->gross()}}</td>
                            <td>{{$transaction->fee()}}</td>
                            <td>{{$transaction->net()}}</td>

                            <td>
                              @if($transaction->transactionable_type == 'App\Models\Send')
                              <form action="{{route('sendMoneyConfirm', app()->getLocale())}}" method="post">
                              @elseif($transaction->transactionable_type == 'App\Models\Purchase')
                              <form action="{{route('purchaseConfirm', app()->getLocale())}}" method="post">
                              @endif
                              
                              {{csrf_field()}}
                              <input type="hidden" name="tid" value="{{$transaction->id}}">
                              <input type="submit"  class="btn btn-success btn-simple btn-round btn-xs pull-left" value="confirm">
                              </form>
                              <div class="clearfix"></div>
                            </td>
                            <td>

                              <form action="{{url('/')}}/{{app()->getLocale()}}/transaction/remove" method="post">
                                {{csrf_field()}}
                                <input type="hidden" name="tid" value="{{$transaction->id}}">
                                <input type="submit"  class="btn btn-link btn-xs pull-right" value="X">
                              </form>
                            </td>
                          </tr>
                        @empty
                        @endforelse 
                      </tbody>
                  </table>
              </div>
          </div>
        </div>
      </div>
      @if($transactions_to_confirm->LastPage() != 1)
        <div class="panel-footer">
            {{$transactions->links()}}
        </div>
      @else
      @endif
  </div>
@endif