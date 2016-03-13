@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">

                    @include('/reporting/dateForm')

                <table class="table table-striped" style="margin-top:20px">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Number of Transactions</th>
                            <th>Total Revenue</th>
                            <th>Total Commission</th>
                            <th>Average Order Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>{{$day}} of {{date('F')}}</th>
                            <td>{{$reportData['daily']['transaction_count']}}</td>
                            <td>£{{$reportData['daily']['sale_sum']}}</td>
                            <td>£{{$reportData['daily']['commission_sum']}}</td>
                            <td>£{{$reportData['daily']['sale_average']}}</td>
                        </tr>
                        <tr>
                            <th>{{date('F')}}</th>
                            <td>{{$reportData['monthly']['transaction_count']}}</td>
                            <td>£{{$reportData['monthly']['sale_sum']}}</td>
                            <td>£{{$reportData['monthly']['commission_sum']}}</td>
                            <td>£{{$reportData['monthly']['sale_sum_average']}}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-striped" style="margin-top:20px">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Id</th>
                            <th>Site URL</th>
                            <th>Company Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reportData['monthly']['top_affiliates'] as $key=>$affiliates)
                            @foreach ($affiliates as $affiliate)
                        <tr>
                            <th>{{$key+1}}</th>
                            <td>{{$affiliate->affiliate_id}}</td>
                            <td><a href="{{$affiliate->website}}">{{$affiliate->website}}</a></td>
                            <td>{{$affiliate->company}}</td>
                        </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

