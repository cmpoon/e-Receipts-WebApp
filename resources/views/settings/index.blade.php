@extends('layouts.master')

@section('content')
<div class="ui-field-contain">
<label for="number-1">Monthly Budget: </label>
<input type="number" data-clear-btn="false" name="number-1" id="number-1" data-mini="true"/>
</div>

<div class="ui-field-contain" >
<label for="select-choice-mini" class="select">Category:</label>
<select name="select-choice-mini" id="select-choice-mini" data-mini="true" data-inline="true" >
@each('settings.options', $categories, 'category')
</select>
</div>
<div class="ui-field-contain">
<label for="select-choice-mini" class="select">Category Budget:</label>
<input type="number" data-clear-btn="false" name="number-1" id="number-1" data-mini="true" data-inline="true"/>
</div>
@endsection