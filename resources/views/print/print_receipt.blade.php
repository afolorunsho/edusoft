<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Print Receipt</title>
        <style type="text/css">
			html,body{
				padding: 0;
				margin: 0;
				width: 100%;
				background: #fff;
				font-family:"Times New Roman", Times, serif;
				font-size: 10pt;
			}
			table{
				width: 700px;
				margin: 0 auto;
				text-align: left;
				border-collapse:collapse;
			}
			th{ padding-left: 2px;}
			th{ padding: 2px;}
			.aeu{
				text-align:right;
				padding-right: 10px;
				font-family:"Times New Roman", Times, serif;
			}
			.line-top{
				border-left: 1px solid;
				padding-left: 10px;
				font-family:"Times New Roman", Times, serif;
			}
			.verify{ font-family: "Times New Roman", Times, serif;}
			.imageAeu{ width: 50px; height:70px;}
			.th{
				background-color: #ddd;
				border: 1px solid;
				text-align: center;
			}
			.line-row{
				background-color: #fff;
				border: 1px solid;
				text-align: center;
			}
			#container{
				width: 100%;
				margin: 0 auto;
			}
			.khm-os{ font-family: "Times New Roman", Times, serif;}
			.divide{ width: 100%; margin: 0 auto;}
			
			hr.style1{
				border-top: 1px solid #8c8b8b;
			}
			hr.style2 {
				border-top: 3px double #8c8b8b;
			}
			hr.style3 {
				border-top: 1px dashed #8c8b8b;
			}
			hr.style4 {
				border-top: 1px dotted #8c8b8b;
			}
			hr.style5 {
				background-color: #fff;
				border-top: 2px dashed #8c8b8b;
			}
			hr.style6 {
				background-color: #fff;
				border-top: 2px dotted #8c8b8b;
			}
			hr.style7 {
				border-top: 1px solid #8c8b8b;
			Storage::	border-bottom: 1px solid #fff;
			}
			hr.style8 {
				border-top: 1px solid #8c8b8b;
				border-bottom: 1px solid #fff;
			}
			hr.style8:after {
				content: '';
				display: block;
				margin-top: 2px;
				border-top: 1px solid #8c8b8b;
				border-bottom: 1px solid #fff;
			}
			hr.style9 {
				border-top: 1px dashed #8c8b8b;
				border-bottom: 1px dashed #fff;
			}
			hr.style10 {
				border-top: 1px dotted #8c8b8b;
				border-bottom: 1px dotted #fff;
			}
			hr.style13 {
				height: 10px;
				border: 0;
				box-shadow: 0 10px 10px -10px #8c8b8b inset;
			}
			hr.style14 { 
			  border: 0; 
			  height: 1px; 
			  background-image: -webkit-linear-gradient(left, #f0f0f0, #8c8b8b, #f0f0f0);
			  background-image: -moz-linear-gradient(left, #f0f0f0, #8c8b8b, #f0f0f0);
			  background-image: -ms-linear-gradient(left, #f0f0f0, #8c8b8b, #f0f0f0);
			  background-image: -o-linear-gradient(left, #f0f0f0, #8c8b8b, #f0f0f0); 
			}
			hr.style15 {
				border-top: 4px double #8c8b8b;
				text-align: center;
			}
			hr.style15:after {
				content: '\002665';
				display: inline-block;
				position: relative;
				top: -15px;
				padding: 0 10px;
				background: #f0f0f0;
				color: #8c8b8b;
				font-size: 18px;
			}
			hr.style16 { 
			  border-top: 1px dashed #8c8b8b; 
			} 
			hr.style16:after { 
			  content: '\002702'; 
			  display: inline-block; 
			  position: relative; 
			  top: -12px; 
			  left: 40px; 
			  padding: 0 3px; 
			  background: #f0f0f0; 
			  color: #8c8b8b; 
			  font-size: 18px; 
			}
			hr.style17 {
				border-top: 1px solid #8c8b8b;
				text-align: center;
			}
			hr.style17:after {
				content: '§';
				display: inline-block;
				position: relative;
				top: -14px;
				padding: 0 10px;
				background: #f0f0f0;
				color: #8c8b8b;
				font-size: 18px;
				-webkit-transform: rotate(60deg);
				-moz-transform: rotate(60deg);
				transform: rotate(60deg);
			}
			hr.style18 { 
			  height: 30px; 
			  border-style: solid; 
			  border-color: #8c8b8b; 
			  border-width: 1px 0 0 0; 
			  border-radius: 20px; 
			} 
			hr.style18:before { 
			  display: block; 
			  content: ""; 
			  height: 30px; 
			  margin-top: -31px; 
			  border-style: solid; 
			  border-color: #8c8b8b; 
			  border-width: 0 0 1px 0; 
			  border-radius: 20px; 
			}
			hr{
				width: 100%;
				margin-left: 0;
				margin-right: 0;
				padding: 0;
				margin-top: 35px;
				margin-bottom: 20px;
				border: 0 none;
				border-top: 1px dashed #322f32;
				background: none;
				height: 0;
			}
			button{
				margin: 0 auto;
				text-align: center;
				height: 100%;
				width: 100%;
				cursor: pointer;
				font-weight:bold;
			}
			.length-limit{
				max-height: 350px; 
				min-height: 350px;
			}
			.div-button{
				width: 100%;
				margin-top: 0;
				height: 50px;
				text-align: center;
				margin-bottom: 10px;
				border-bottom: 1px solid;
				background:#ccc;	
			}
		</style>
    </head>
    <body>
    	<div class="div-button"><button onClick="printContent('divide')">Print</button></div>
        <div class="divide">
        	<?php for($i=0; $i<2; $i++) {?>
        	<div class="container">
                <div class="length-limit">
                    {{----------------------}}
                    <table>
                        <tr>
                            <td style="padding-left:40px; width: 50px;">
                            	<!----logo is a folder in public and use asset to access it--->
                                <img src= "asset(photo/institue/logo_pict.png) }}" class = "imageAeu">
                            </td>
                            <td class="aeu">
                                <b style="font-weight: normal;">Something</b>
                                <b>American School</b>
                            </td>
                            <td class="line-top">
                                <b style="font-weight: normal;">Something</b>
                                <b>RECEIPT</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: right;"></td>
                            <td colspan="0" style="text-align: right; padding-left:80px;">
                                <b> Receipt N<sup>o</sup>:{{ spintf("%05d", $invoice->receip_id)}}</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: right;"></td>
                            <td colspan="0" style="text-align: right; padding-left:80px;">
                                <b> Date: </b> {{ date('d-M-Y', strtotime($invoice->transact_date)}}</b>
                            </td>
                        </tr>
                    </table>
                    {{-------------------------------}}
                    <table>
                        <tr>
                            <td style="width: 120px; padding: 5px 0;">
                                Reg No:<b>{{ spintf("%05d", $invoice->reg_no)}}</b>
                            </td>
                            <td style="width: 120px; padding: 5px 0;">
                                First Name:<b>{{ $invoice->first_name}}</b>
                            </td>
                            <td class="line-top">
                                Last Name:<b>{{ $invoice->last_name}}</b>
                            </td>
                            <td>
                                Class:<b> {{ invoice->class }}</b>
                            </td>
                            <td>
                                Term:<b> {{ invoice->term }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: right;"></td>
                            <td colspan="0" style="text-align: right; padding-left:80px;">
                                <b> Receipt N<sup>o</sup>:{{ spintf("%05d", $invoice->receip_id)}}</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: right;"></td>
                            <td colspan="0" style="text-align: right; padding-left:80px;">
                                <b> Date: </b> {{ date('d-M-Y', strtotime($invoice->transact_date)}}</b>
                            </td>
                        </tr>
                    </table>
                    {{------------------------------}}
                    <table>
                        <thead>
                            <tr>
                                <th class="th" style="text-align: left;">Description</th>
                                <th class="th" style="width: 70px;">Fee</th>
                                <th class="th" style="width: 70px;">Disc/Schorlarship</th>
                                <th class="th" style="width: 70px;">Amount</th>
                                <th class="th" style="width: 70px;">Pay</th>
                                <th class="th" style="width: 70px;">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <td class="line-row" style="text-align: left;">
                                        {{ $status->detail}}</b>
                                    </td>
                                    <td class="line-row">
                                        ${{ number_format($invoice->school_fee,2) }}
                                    </td>
                                    <td class="line-row">
                                        {{ $studentFee->discount}}%</b>
                                    </td>
                                    <td class="line-row">
                                        ${{ number_format($studentFee->amount,2) }}
                                    </td>
                                    <td class="line-row">
                                        ${{ number_format($invoice->paid,2) }}
                                    </td>
                                    <td class="line-row">
                                        ${{ number_format($studentFee->amount-$totalPaid,2) }}
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                    {{---------------}}
                    <table>
                        <tr>
                            <td>
                                <b class="verify">Note:</b>
                                <p style="display: inline-block;">
                                    All payments are not refundable or transferrable
                                </p>
                            </td>
                            <td>
                                <b style="margin-bottom: 5px;">Cashier:{{ $invoice->name}}</b>
                                <br><br>
                                Printed: {{ date('d-M-Y' g:i:s A') }}
                            </td>
                            <td style="vertical-align:top;  ">
                                Printed By: {{ Auth::user()->name }}
                            </td>
                        </tr>
                    
                    </table>
                </div>
                <div>
                    <br><br><br><br><br><br>
                    {{-------------------------}}
                    <table>
                        <tr>
                            <td style="font-size: 10pt; text-align:center;">
                                <!---the address line-->
                                22, Kigoma Street Wuse Abuja
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 10pt; text-align:center;">
                                <!---telephone lines-->
                                Phone: (855) 08053281398
                            </td>
                        </tr>
                    </table>
                    {{------------------------}}
                </div>
          	</div>
            @if($i==0)
            	<br>
                <hr>
            <?php }?>
            
       	</div>
        <script type="text/javascript">
			function printContent(el){
				var restorepage = document.body.innerHTML;
				var printcontent = document.getElementById(el).innerHTML;
				document.body.innerHTML = printcontent;
				window.print;
				document.body.innerHTML = restorepage;
				window.close();
			}
		</script>
    </body>
</html>