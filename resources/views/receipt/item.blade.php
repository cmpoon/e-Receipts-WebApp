  <tr>
    <td style="">{{ $item->name }}</td>
    <td style="text-align:left">{{ round($item->quantity) }} {{$item->unit }} x {{ $item->unit_price }}</td>
    <td style="text-align:right">{{ $item->subtotal }}</td>
  </tr>
 
