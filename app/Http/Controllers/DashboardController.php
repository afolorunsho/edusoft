<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

use DB;
use App\models\Registration;
use App\models\StudentEnrol;
use App\models\FeesPayment;
use App\models\TxnFT;
use App\models\TxnExp;
use Charts;
use Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('authen');
    }
	public function dashboard()
    {
        return view('layouts.master');
    }
	
	//Present Class Occupancy: groupByClass
	//Present Section Occupancy
	//Student Absent per class
	//ratio of those who are present versus population
	//exited students per reason for exit
	//discipline students per class
	//students not yet enrolled but registered
	//fees payment per class
	//fees payment per bank
	//bank lodgement
	//subjects per class
	//exam per class
	
	public function dashboard_stat(){
		
		//do all for six months
		//$first_day_this_month = date('m-01-Y'); // hard-coded '01' for first day
		//$last_day_this_month  = date('m-t-Y');
		//$time = strtotime("2015-05-31");
		//$final = date("Y-m-d", strtotime("first day of next month", $time));
		//->groupByMonth('2016', true);
		//Groups the data based on a column: ->groupBy('game');

		$date_end = date('m-01-Y');
		$date_start = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $date_end ) ) . "-6 month" ) );
		
        $registration = Charts::database( Registration::all(), 'bar', 'highcharts')
			      ->title("Monthly Student Registration")
			      ->elementLabel("Total Students")
			      ->dimensions(1000, 500)
			      ->responsive(false)
			      ->lastByMonth(6, true);
				  //->groupByDay();
				  //->groupByYear();
		
		$enrolment = Charts::database(StudentEnrol::all(), 'bar', 'highcharts')
			      ->title("Monthly Student Enrolment")
			      ->elementLabel("Total Enrolment")
			      ->dimensions(1000, 500)
			      ->responsive(false)
			       ->lastByMonth(6, true);
				  //->groupByDay();
				  //->groupByYear();
		
		$fees = Charts::database(FeesPayment::all(), 'bar', 'highcharts')
			      	->title("Monthly Fees Payment")
			      	->elementLabel("Total Fees")
			      	->dimensions(1000, 500)
			      	->responsive(false)
					->lastByMonth(6, true);
					
		$expenses = Charts::database(TxnExp::all(), 'bar', 'highcharts')
			      	->title("Monthly Expenses")
			      	->elementLabel("Total Expenses")
			      	->dimensions(1000, 500)
			      	->responsive(false)
					->lastByMonth(6, true);
				  
		$lodge = Charts::database(TxnFT::all(), 'bar', 'highcharts')
			      	->title("Monthly Lodgement")
			      	->elementLabel("Total Lodgement")
			      	->dimensions(1000, 500)
			      	->responsive(false)
					->lastByMonth(6, true);
		/*
		->groupBy('game');
		$fees_pmt = DB::table('fees_payment')
					//->where(DB::raw("(DATE_FORMAT(fees_payment.payment_date,'%Y'))"),date('Y'))
					->where('fees_payment.payment_date', '>=', $date_start)
					->where('fees_payment.payment_date', '<', $date_end)
					->select(DB::raw("DATE_FORMAT(fees_payment.payment_date,'%M') AS payment_date"), 
								DB::raw('SUM(fees_payment.amount) AS amount'))
					->groupBy(DB::raw("DATE_FORMAT(fees_payment.payment_date,'%M')"))
					->orderBy(DB::raw("DATE_FORMAT(fees_payment.payment_date,'%m')"))
					->get();
					
		$fees = Charts::create('bar', 'highcharts')
			      ->elementLabel("Total Fees")
			      ->title("Monthly Fees")
			      ->dimensions(1000, 500)
				  ->values($fees_pmt->pluck('amount'))
				  ->labels($fees_pmt->pluck('payment_date'))
				  ->responsive(true);
					
		$bank_lodge = DB::table('txn_ft')
					->where(DB::raw("(DATE_FORMAT(txn_ft.txn_date,'%Y'))"),date('Y'))
					->select(DB::raw("DATE_FORMAT(txn_ft.txn_date,'%M') AS txn_date"), 
								DB::raw('SUM(txn_ft.amount) AS amount'))
					->groupBy(DB::raw("DATE_FORMAT(txn_ft.txn_date,'%M')"))
					->orderBy(DB::raw("DATE_FORMAT(txn_ft.txn_date,'%m')"))
					->get();
					
		$lodge = Charts::create('area', 'highcharts')
			      ->elementLabel("Total Lodgement")
			      ->title("Monthly Lodgement")
			      ->dimensions(1000, 500)
				  ->values($bank_lodge->pluck('amount'))
				  ->labels($bank_lodge->pluck('txn_date'))
				  ->responsive(true);
				  
		//where txn is ONLY for the current year alone
		$expense_table = DB::table('txn_exp')
					->where(DB::raw("(DATE_FORMAT(txn_exp.txn_date,'%Y'))"),date('Y'))
					->select(DB::raw("DATE_FORMAT(txn_exp.txn_date,'%M') AS txn_date"), 
								DB::raw('SUM(txn_exp.amount) AS amount'))
					->groupBy(DB::raw("DATE_FORMAT(txn_exp.txn_date,'%M')"))
					->orderBy(DB::raw("DATE_FORMAT(txn_exp.txn_date,'%m')"))
					->get();
					
		$expenses = Charts::create('area', 'highcharts')
			      ->elementLabel("Total Expense")
			      ->title("Monthly Expense")
			      ->dimensions(1000, 500)
				  ->values($expense_table->pluck('amount'))
				  ->labels($expense_table->pluck('txn_date'))
				  ->responsive(true);
		 */
		return view('charts.dashboard', compact('registration','enrolment','fees','lodge','expenses'));
	}
	
/*

For that I was trying to implement the row one with daily date and the column with daily collection ..But I'm not getting proper output means I was getting daily no of transaction not the total amount collected daily.

How do i correct that?
//using the transaction value(amount) rather than the transaction volume
$data = DB::table('carts')
	->select('carts.created_at', DB::raw('sum(carts.amount) as sum'))
	->groupBy('carts.created_at')
	->get(); 

$chart = Charts::database($data,'area', 'highcharts')
	->elementLabel("Total")
	->title('Monthly Collection')
	->dimensions(1000, 500)
	->responsive(false)
	->groupByDay();

Groups the data based on a column: e.g class_name, fees_name, gender, subjects etc
$chart = Charts::database(User::all(), 'bar', 'highcharts')
    ->setElementLabel("Total")
    ->setDimensions(1000, 500)
    ->setResponsive(false)
    ->groupBy('role');
	
// to display a specific year, pass the parameter. For example to display the months of 2016 and display a fancy output label:
$chart = Charts::database(User::all(), 'bar', 'highcharts')
    ->setElementLabel("Total")
    ->setDimensions(1000, 500)
    ->setResponsive(false)
    ->groupByMonth('2016', true); 
	as ->groupByMonth(); takes only the current year
	
// to display a specific month and/or year, pass the parameters. For example to display the days of september 2016 and display a fancy output label:
$chart = Charts::database(User::all(), 'bar', 'highcharts')
    ->setElementLabel("Total")
    ->setDimensions(1000, 500)
    ->setResponsive(false)
    ->groupByDay('09', '2016', true);

$chart = Charts::database(User::all(), 'bar', 'highcharts')
    ->setElementLabel("Total")
    ->setDimensions(1000, 500)
    ->setResponsive(false)
    ->lastByMonth();   //Default: $number = 6, $fancy = false

// to display a number of months behind, pass a int parameter. For example to display the last 6 months and use a fancy output:
$chart = Charts::database(User::all(), 'bar', 'highcharts')
    ->setElementLabel("Total")
    ->setDimensions(1000, 500)
    ->setResponsive(false)
    ->lastByMonth(6, true);

$chart = Charts::database(User::all(), 'bar', 'highcharts')
    ->setElementLabel("Total")
    ->setDimensions(1000, 500)
    ->setResponsive(false)
    ->lastByYear();  //Default: $number = 4

// to display a number of years behind, pass a int parameter. For example to display the last 3 years:
$chart = Charts::database(User::all(), 'bar', 'highcharts')
    ->setElementLabel("Total")
    ->setDimensions(1000, 500)
    ->setResponsive(false)
    ->lastByYear(3);


$chart = Charts::database(User::all(), 'bar', 'highcharts')
    ->setElementLabel("Total")
    ->setDimensions(1000, 500)
    ->setResponsive(false)
    ->lastByDay();  //Default: $number = 7, $fancy = false

// to display a number of days behind, pass a int parameter. For example to display the last 14 days and use a fancy output:
$chart = Charts::database(User::all(), 'bar', 'highcharts')
    ->setElementLabel("Total")
    ->setDimensions(1000, 500)
    ->setResponsive(false)
    ->lastByDay(14, true);
	
$chart = Charts::multi('bar', 'material')
// Setup the chart settings
->title("My Cool Chart")
// A dimension of 0 means it will take 100% of the space
->dimensions(0, 400) // Width x Height
// This defines a preset of colors already done:)
->template("material")
// You could always set them manually
// ->colors(['#2196F3', '#F44336', '#FFC107'])
// Setup the diferent datasets (this is a multi chart)
->dataset('Element 1', [5,20,100])
->dataset('Element 2', [15,30,80])
->dataset('Element 3', [25,10,40])
// Setup what the values mean
->labels(['One', 'Two', 'Three']);

->Groupby('quantity')

//critical ones
ChartJS  chartjs:  
Area Chart  area 
Line Chart  line 
Bar Chart  bar 
Pie Chart  pie 
Donut / Doughnut Chart  donut 

Highcharts  highcharts:  
Area Chart  area 
Line Chart  line 
Bar Chart  bar 
Pie Chart  pie 
Donut / Doughnut Chart  donut 
Geo Chart  geo 

Google Charts  google:  
Area Chart  area 
Line Chart  line 
Bar Chart  bar 
Pie Chart  pie 
Donut / Doughnut Chart  donut 
Geo Chart  geo 
Gauge Chart  gauge  Realtime Available

Google Material  material:  
Line Chart  line 
Bar Chart  bar 

Chartist  chartist:  
Area Chart  area 
Line Chart  line 
Bar Chart  bar 
Pie Chart  pie 
Donut / Doughnut Chart  donut 

FusionCharts  fusioncharts:  
Area Chart  area 
Line Chart  line 
Bar Chart  bar 
Pie Chart  pie 
Donut / Doughnut Chart  donut 

Morris JS  morris:  
Area Chart  area 
Line Chart  line 
Bar Chart  bar 
Donut / Doughnut Chart  donut 

Plottable JS  plottablejs:  
Area Chart  area 
Line Chart  line 
Bar Chart  bar 
Pie Chart  pie 

Minimalist  minimalist:  
Area Chart  area 
Line Chart  line 
Bar Chart  bar 
Pie Chart  pie 

///less important
5. donut chart
6. geo chart
7. gauge chart
8. temp chart
9. percentage chart
10. progressbar chart
11. areaspline chart
12. scatter chart



*/
	//include vital statistics here
	//Auth::user()->roles->first()->name
}
