        {
            name: '{{ $category['name'] }}',
            y: {{ $category['value'] }}, /*values add up to 100(category value/totalvalue * 100)*/
            url: '{{ action('BudgetController@getCategory', ['id' => $category['id'] ]) }}'
        }@unless($category['last']),@endunless