@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
        	 <div class="col-md-6">
                <section class="panel panel-default">
                    <header class="panel-heading">
                        Student Registration
                    </header>
                    <div class="panel panel-body" style="border-bottom: 1px solid #cc;">
                        {!! $chart->render() !!}
                    </div>
                </section>
           	</div>
            <div class="col-md-6">
                <section class="panel panel-default">
                    <header class="panel-heading">
                        Student Enrolment
                    </header>
                    <div class="panel panel-body" style="border-bottom: 1px solid #cc;">
                        {!! $chart->render() !!}
                    </div>
                </section>
           	</div>
     	</div>
        <div class="col-md-12">
        	 <div class="col-md-6">
                <section class="panel panel-default">
                    <header class="panel-heading">
                        Fees Payment
                    </header>
                    <div class="panel panel-body" style="border-bottom: 1px solid #cc;">
                        {!! $chart->render() !!}
                    </div>
                </section>
           	</div>
            <div class="col-md-6">
                <section class="panel panel-default">
                    <header class="panel-heading">
                        Fees Lodgements
                    </header>
                    <div class="panel panel-body" style="border-bottom: 1px solid #cc;">
                        {!! $chart->render() !!}
                    </div>
                </section>
           	</div>
     	</div>
 	</div>
@endsection
