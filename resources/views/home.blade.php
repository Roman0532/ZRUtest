@include('layouts.header')

@foreach(['success','danger'] as $status)
    @if(session()->has($status))
        <div class="alert alert-{{$status}} text-center">
            {{session()->get($status)}}
        </div>
    @endif
@endforeach

@if ($errors->any())
    <div class="alert alert-danger text-center">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<script type="text/javascript">
    $(() => {
        $('#datetimepicker').datetimepicker({
            minDate: new Date(),
            locale: 'ru',
            format: 'YYYY-MM-DD HH:00:00'
        });

        $('#datetimepicker-two').datetimepicker({
            maxDate: new Date(),
            locale: 'ru',
            format: 'YYYY-MM-DD HH:00:00'
        });


        $('#datetimepicker-three').datetimepicker({
            maxDate: new Date(),
            locale: 'ru',
            format: 'YYYY-MM-DD HH:00:00'
        });
    });
</script>

<div class="container" style="background-color: #f8f9fa">
    @yield('nav')
    {{ Form::open(['action' => 'TransactionController@store','method' => 'POST']) }}
    {{ Form::token() }}
    <div id="test" class="form-group row">
        <label for="fromUser" class="col-1 col-form-label">От кого:</label>
        <div class="col-10">
            <select name="fromUser" class="form-control" id="fromUser">
                @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->first_name}}</option> <br>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="toUser" class="col-1 col-form-label">Кому:</label>
        <div class="col-10">
            <select name="toUser" class="form-control" id="toUser">
                @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->first_name}}</option> <br>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="text-input" class="col-1 col-form-label">Сумма:</label>
        <div class="col-2 ">
            <input class="form-control" type="number" step="0.50" name="amount" required min="0.50" value="0"
                   id="text-input">
        </div>
    </div>

    <div class="form-group row">
        <label for="time-input" class="col-1 col-form-label">Дата:</label>
        <div class='col-sm-6'>
            <div class="form-group">
                <div class='input-group date' id='datetimepicker'>
                    <input type='text' name="date" id="time-input" class="form-control"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <input type="submit" class="btn btn-primary" value="Создать">
    {{ Form::close() }}
    <br>
    {{ Form::open(['action' => 'TransactionController@getTransactionsInCsv','method' => 'POST']) }}
    {{ Form::token() }}
    <p>ВЫГРУЗИТЬ ДАННЫЕ В CSV</p>

    <div class="form-group row">
        <label for="time-input" class="col-1 col-form-label">С какого числа:</label>
        <div class='col-sm-6'>
            <div class="form-group">
                <div class='input-group date' id='datetimepicker-two'>
                    <input type='text' name="after-date" id="time-input" class="form-control"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="form-group row">
        <label for="time-input" class="col-1 col-form-label">До какого числа:</label>
        <div class='col-sm-6'>
            <div class="form-group">
                <div class='input-group date' id='datetimepicker-three'>
                    <input type='text' name="before-date" id="time-input" class="form-control"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <input type="submit" class="btn btn-primary" value="Выгрузить csv">
    {{ Form::close() }}
</div>
</div>

