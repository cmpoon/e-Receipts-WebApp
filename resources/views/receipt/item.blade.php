  <tr>
    <td style="width:50%">{{ $item->name }}</td>
    <td style="width:30%">{{ round($item->quantity) }} {{$item->unit }} x {{ $item->unit_price }}</td>
    <td style="text-align:right">{{ $item->subtotal }}</td>
  </tr>
 
