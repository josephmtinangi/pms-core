<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>INVOICE-{{ $invoice->number }}</title>
</head>
<body>
	
	<table>
		<tr>
			<td>
				<img src="http://localhost:8001/img/logo.png" alt="">
			</td>
			<td>
				{{ $agent->name }}<br>
				{{ $agent->postal_address }}<br>
				{{ $agent->physical_address }}<br>			
				{{ $agent->phone }}<br>			
				{{ $agent->email }}<br>			
			</td>
		</tr>
		<tr>
			<th>Invoice #</th>
			<td>{{ $invoice->number }}</td>
		</tr>
		<tr>
			<th>Invoice Date:</th>
			<td>{{ $invoice->created_at }}</td>
		</tr>
		<tr>
			<th>Due Date:</th>
			<td>{{ $invoice->expiry_at }}</td>
		</tr>
		<tr>
			<th>Invoiced To</th>
			<td>
				{{ $customerContract->customer->name() }}<br>
				{{ $customerContract->customer->postal_address }}<br>
				{{ $customerContract->customer->physical_address }}<br>
				{{ $customerContract->customer->phone }}<br>
				{{ $customerContract->customer->email }}<br>				
			</td>
		</tr>
		<tr>
			<th>AMOUNT DUE</th>
			<td>TSH {{ number_format($customerPaymentSchedule->amount_to_be_paid) }}</td>
		</tr>
		<tr>
			<th>DESCRIPTION</th>
			<td>
				Rent from {{ $customerPaymentSchedule->start_date }} to {{ $customerPaymentSchedule->end_date }}
				for room(s)
				@foreach($customerContract->rooms as $room)
					{{ $room->room->number }}, 
				@endforeach
			</td>
		</tr>
		<tr>
			<th>CONTROL NUMBER</th>
			<td>{{ $customerPaymentSchedule->control_number }}</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;">Thank you for doing business with us.</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;">
				If you have any question about this invoice, please contact {{ $agent->phone }}, {{ $agent->email }}
			</td>
		</tr>
	</table>

</body>
</html>