	<footer class="pull-right">
		<p class="navbar-text">
			<a href="http://www.pubtechltd.com/system-consult" target="_blank"><img src="{{url('/img/sch_logo.png')}}" height="50px" width="75px" alt="system-consult logo" /></a> 
            <!--text based navigation-->
			&nbsp; System Consult All rights reserved. &nbsp;&nbsp; &copy;
			<?php 
				ini_set('date.timezone', 'Europe/London');
				$startYear = 2013; 
				$thisYear = date('Y'); 
				if ($startYear == $thisYear) {
					echo $startYear;
				}else {
					echo "{$startYear}-{$thisYear}";
				}
			?> 
			  &nbsp;&nbsp;&nbsp;&nbsp; Last Sign On:
			{{ date_format( date_create(Auth::user()->previous_signon), 'd/m/Y H:i:s' ) }}
			
		</p>
	</footer> 