{{ Form::tbSelectBlock('type', $types, null, null, ['data-with-allowed-values' => json_encode([\App\Models\Attribute::TYPE_LIST, \App\Models\Attribute::TYPE_MULTIPLE_VALUES])]) }}