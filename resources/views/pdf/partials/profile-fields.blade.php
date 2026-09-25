{{-- Answers two by two; long answers take the whole width. --}}
<table class="fields">
    @foreach ($lines as $line)
    <tr>
        @foreach ($line as $field)
        @php($wide = count($line) === 1 && $field['wide'])
        <td @class(['wide' => $wide]) @if ($wide) colspan="2" @endif>
            <div class="caps">{{ $field['label'] }}</div>
            <div class="field-value">
                @if ($field['tone'])
                <span class="pill pill-{{ $field['tone'] }}">{{ $field['value'] }}</span>
                @else
                {!! nl2br(e($field['value'])) !!}
                @endif
            </div>
        </td>
        @endforeach
        @if (count($line) === 1 && ! $line[0]['wide'])<td></td>@endif
    </tr>
    @endforeach
</table>
