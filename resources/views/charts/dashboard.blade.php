@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading">
                    Student Registration
                </header>
                <div class="panel panel-body" style="border-bottom: 1px solid #cc;">
                    {!! $registration->render() !!}
                </div>
            </section>
        </div>
       	<div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading">
                    Student Enrolment
                </header>
                <div class="panel panel-body" style="border-bottom: 1px solid #cc;">
                    {!! $enrolment->render() !!}
                </div>
            </section>
        </div>
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading">
                    Fees Payment
                </header>
                <div class="panel panel-body" style="border-bottom: 1px solid #cc;">
                    {!! $fees->render() !!}
                </div>
            </section>
        </div>
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading">
                    Fees Lodgements
                </header>
                <div class="panel panel-body" style="border-bottom: 1px solid #cc;">
                    {!! $lodge->render() !!}
                </div>
            </section>
        </div>
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading">
                    Expenses
                </header>
                <div class="panel panel-body" style="border-bottom: 1px solid #cc;">
                    {!! $expenses->render() !!}
                </div>
            </section>
        </div>
 	</div>
@endsection
