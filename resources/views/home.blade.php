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
                        <tbody>
                            <tr>
                            <th>Day</th>
                                <td>{{$reportData['daily']['transaction_count']}}</td>
                                <td>£{{$reportData['daily']['sale_sum']}}</td>
                                <td>£{{$reportData['daily']['commission_sum']}}</td>
                                <td>£{{$reportData['daily']['sale_average']}}</td>
                            </tr>
                            <tr>
                            <th>Month</th>
                                <td>{{$reportData['monthly']['transaction_count']}}</td>
                                <td>£{{$reportData['monthly']['sale_sum']}}</td>
                                <td>£{{$reportData['monthly']['commission_sum']}}</td>
                                <td>£{{$reportData['monthly']['sale_sum_average']}}</td>
                            </tr>
                        </tbody>
                    <thead>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


