{{-- Documents sent by the supplier (split in two tables so that the first rows stay with the section title). --}}
<table @class(['documents', 'documents-continued' => ! $header])>
    @if ($header)
    <tr>
        <th class="caps doc-type"></th>
        <th class="caps">{{ ui('pdf.document') }}</th>
        <th class="caps doc-size">{{ ui('pdf.size') }}</th>
        <th class="caps doc-status">{{ ui('pdf.in_this_document') }}</th>
    </tr>
    @endif
    @foreach ($rows as $document)
    <tr>
        <td class="doc-type"><span class="file-type file-type-{{ strtolower($document['type']) }}">{{ $document['type'] }}</span></td>
        <td>
            <div class="doc-category">{{ $document['category'] }}</div>
            <div class="doc-name">{{ $document['name'] }}</div>
        </td>
        <td class="doc-size">{{ $document['size'] }}</td>
        <td class="doc-status"><span class="status-pill status-{{ $document['tone'] }}">{{ $document['status'] }}</span></td>
    </tr>
    @endforeach
</table>
