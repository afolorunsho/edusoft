<script type="text/javascript">
	function cal_fields(row){
		var td_qty = row.find("input.qty").val();
		var td_price = row.find("input.price").val();
		var td_amount = row.find("input.amount").val();
		
		td_price = parseFloat(convertToNumbers(td_price));
		td_qty = parseFloat(convertToNumbers(td_qty));
		td_amount = parseFloat(convertToNumbers(td_amount));
		if(isNaN(td_price) === true) td_price = 0.00;
		if(isNaN(td_qty) === true) td_qty = 0.00;
		if(isNaN(td_amount) === true) td_amount = 0.00;
		
		if (td_qty > 0.00){
			if (td_price > 0.00){
				td_amount = td_qty * td_price;
				row.find('input.amount').val(addCommas(td_amount.toFixed(2)));
				
			}
			else if (td_price == 0.00){
				if (td_amount > 0.00){
					td_price = td_amount / td_qty;
					row.find('input.price').val(addCommas(td_price.toFixed(2)));
				}
			}
		}
		else if (td_qty == 0.00){
			if (td_price > 0.00 && td_amount > 0.00){
				 td_qty = td_amount / td_price;
				 row.find('input.qty').val(addCommas(td_qty.toFixed(2)));
			}
		}
	}
</script>
