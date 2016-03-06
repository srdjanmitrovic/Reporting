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
                                <td>{{$reportData['TransactionData']['day']['count']}}</td>
                                <td>£{{$reportData['RevenueData']['day']['sum']}}</td>
                                <td>£{{$reportData['CommissionData']['day']['sum']}}</td>
                                <td>£{{$reportData['RevenueData']['day']['avg']}}</td>
                            </tr>
                            <tr>
                            <th>Month</th>
                                <td>{{$reportData['TransactionData']['month']['count']}}</td>
                                <td>£{{$reportData['RevenueData']['month']['sum']}}</td>
                                <td>£{{$reportData['CommissionData']['month']['sum']}}</td>
                                <td>£{{$reportData['RevenueData']['month']['avg']}}</td>
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


