<input type="text"
       class="form-control"
       name="setting[{{ $setting->id }}][{{ $index }}][{{ $field }}]"
       @if (isset($value)) value="{{ $value }}" @endif
       @if (!empty($disabled)) disabled="disabled" @endif
/>