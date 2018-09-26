@include('layouts.header')
<div class="container">
    @yield('nav')
    @foreach(['success','danger'] as $status)
        @if(session()->has($status))
            <div class="alert alert-{{$status}} text-center">
                {{session()->get($status)}}
            </div>
        @endif
    @endforeach

    @foreach($transactions as $transaction)
        <div id="accordion">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapse{{$transaction->id}}"
                                aria-expanded="true"
                                aria-controls="collapse{{$transaction->id}}">
                            {{$transaction->fromUserName}} перевел денежные средства {{$transaction->toUserName}}
                            Статус {{$transaction->status_name}}
                        </button>
                    </h5>
                </div>

                <div id="collapse{{$transaction->id}}" class="collapse" aria-labelledby="heading{{$transaction->id}}"
                     data-parent="#accordion">
                    <div class="card-body">
                        Сумма : {{$transaction->amount}} <br>
                        Дата : {{$transaction->dispatch_time}}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
</div>
