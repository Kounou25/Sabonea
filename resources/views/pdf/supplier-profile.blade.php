<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
<meta charset="utf-8">
<title>{{ ui('pdf.title') }} — {{ $companyName }}</title>
<style>
    @include('pdf.partials.profile-base-styles')

    @page { margin: 40px 40px 64px 40px; }

    /* Hero */
    .hero td { vertical-align: top; }
    .eyebrow { font-size: 7.5pt; font-weight: 600; letter-spacing: 1.2px; text-transform: uppercase; color: #FF7F00; }
    .company { font-size: 22pt; font-weight: bold; line-height: 1.15; color: #49037F; margin: 3px 0 5px; }
    .location { font-size: 10pt; color: #5c5270; }
    .flagship { margin-top: 8px; font-size: 9pt; color: #5c5270; }
    .flagship strong { color: #2c2340; font-weight: 600; }
    .status { margin-top: 12px; }

    /* Identity tiles */
    /* Separated cells, so that all the tiles take the height of the tallest one (714px of content + the 8px gaps) */
    .facts { margin: 18px 0 0 -8px; width: 730px; border-collapse: separate; border-spacing: 8px 0; }
    .fact { background-color: #F7F3FC; border-radius: 8px; padding: 9px 12px 10px; }
    .fact-value { font-size: 10pt; font-weight: 600; color: #2c2340; margin-top: 2px; }

    /* Key points */
    .key-points { margin-top: 14px; border: 1px solid #ece6f5; border-radius: 10px; padding: 12px 14px 4px; }
    .key-points td { width: 25%; padding: 0 10px 10px 0; }
    .key-value { font-size: 9pt; font-weight: 600; color: #2c2340; margin-top: 2px; }

    /* Tags */
    .tag-columns { margin-top: 6px; }
    .tag-columns > tbody > tr > td { width: 50%; }
    .tag-columns td.left { padding-right: 14px; }
    .tag-group { margin-top: 12px; }
    .tag-group .caps { margin-bottom: 4px; }
    .tag { display: inline-block; padding: 3px 10px; margin: 0 4px 5px 0; border-radius: 10px; font-size: 8pt; font-weight: 500; }
    .tag-purple { background-color: #F2EAFB; color: #49037F; }
    .tag-green { background-color: #E7F3E6; color: #056800; }
    .tag-orange { background-color: #FFF1E0; color: #A34F00; }
    .tag-grey { background-color: #F1F0F4; color: #4a4458; }

    /* Internal cards */
    .cards { margin-top: 16px; }
    .cards > tbody > tr > td { width: 50%; }
    .cards td.left { padding-right: 8px; }
    .cards td.right { padding-left: 8px; }
    .card { border: 1px solid #ece6f5; border-radius: 10px; padding: 12px 16px; }
    .card-title { font-size: 8pt; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; color: #49037F; margin-bottom: 6px; }
    .kv td { padding: 3px 0; }
    .kv .k { width: 42%; padding-right: 8px; color: #8a7fa0; font-size: 8pt; }
    .kv .v { color: #2c2340; font-weight: 500; }
    .commitments { margin-top: 8px; padding-top: 8px; border-top: 1px solid #f1ecf8; }
    .commitments td { padding: 2px 0; font-size: 8pt; }
    .dot { display: inline-block; width: 7px; height: 7px; border-radius: 4px; margin-right: 6px; background-color: #056800; }
    .dot-no { background-color: #c42b3b; }
    .notes-card { margin-top: 16px; border: 1px solid #ffd9a8; background-color: #FFF8F0; border-radius: 10px; padding: 12px 16px; }
    .notes-card .card-title { color: #A34F00; }
    .notes { white-space: pre-line; color: #4a3a22; }
    .notice { margin-top: 16px; padding: 10px 14px; border-radius: 8px; background-color: #FFF8F0; color: #6b4a22; font-size: 8.5pt; }

    /* Detailed sections */
    .section { margin-top: 24px; }
    .section-title {
        font-size: 10.5pt; font-weight: bold; text-transform: uppercase; color: #2c2340;
        padding-bottom: 6px; border-bottom: 2px solid #49037F;
    }
    .section-number { color: #FF7F00; margin-right: 8px; }
    .fields tr { page-break-inside: avoid; }
    .fields td { width: 50%; padding: 8px 14px 8px 0; border-bottom: 1px solid #f1ecf8; }
    .fields td.wide { width: 100%; }
    .field-value { margin-top: 3px; color: #2c2340; }
    .pill { display: inline-block; padding: 1px 9px; border-radius: 9px; font-size: 8pt; font-weight: 600; }
    .pill-yes { background-color: #E7F3E6; color: #056800; }
    .pill-no { background-color: #F6E6E8; color: #9c2433; }

    /* Documents */
    .documents { margin-top: 6px; }
    .documents-continued { margin-top: 0; }
    .doc-type { width: 44px; }
    .documents th { text-align: left; padding: 8px 10px 6px 0; border-bottom: 1px solid #e6e0f0; }
    .documents td { padding: 8px 10px 8px 0; border-bottom: 1px solid #f1ecf8; vertical-align: middle; }
    .documents tr { page-break-inside: avoid; }
    .file-type {
        display: inline-block; width: 34px; padding: 3px 0; border-radius: 5px; text-align: center;
        font-size: 6.5pt; font-weight: bold; letter-spacing: .5px; color: #ffffff; background-color: #8a7fa0;
    }
    .file-type-pdf { background-color: #c42b3b; }
    .file-type-jpg, .file-type-png, .file-type-webp { background-color: #2f7d8c; }
    .file-type-xlsx { background-color: #1d7a45; }
    .doc-category { font-size: 7.5pt; color: #8a7fa0; }
    .doc-name { color: #2c2340; font-weight: 500; }
    .doc-size { width: 64px; color: #8a7fa0; font-size: 8pt; }
    .doc-status { width: 150px; text-align: right; padding-right: 0; }
    .status-pill { display: inline-block; padding: 2px 9px; border-radius: 9px; font-size: 7.5pt; font-weight: 600; }
    .status-annex { background-color: #F2EAFB; color: #49037F; }
    .status-warning { background-color: #FFF1E0; color: #A34F00; }
    .status-muted { background-color: #F1F0F4; color: #6c6280; }
    .on-request { margin-top: 10px; font-size: 8.5pt; color: #5c5270; }

    .cta { margin-top: 26px; padding: 18px 22px; border-radius: 10px; background-color: #2C0350; color: #ffffff; page-break-inside: avoid; }
    .cta-title { font-size: 12pt; font-weight: bold; margin-bottom: 4px; }
    .cta-contact { margin-top: 8px; font-size: 10pt; font-weight: 600; color: #FFB066; }
    .keep { page-break-inside: avoid; }
</style>
</head>
<body>

<div id="footer">{{ $footer }}</div>

<div id="header">
    <table>
        <tr>
            <td><img class="logo" src="{{ $logoPath }}" alt="Sabonea"></td>
            <td>
                <div class="doc-title">{{ ui('pdf.title') }}</div>
                <div class="doc-meta">{{ $companyName }} · {{ ui('pdf.reference') }} {{ $reference }} · {{ $generatedOn }}</div>
            </td>
        </tr>
    </table>
    <div class="stripe"></div>
</div>

{{-- Hero --}}
<table class="hero">
    <tr>
        <td>
            <div class="eyebrow">{{ $isForBuyer ? ui('pdf.presented_by') : ui('pdf.title') }}</div>
            <div class="company">{{ $companyName }}</div>
            @if ($location)
            <div class="location">{{ $location }}</div>
            @endif
            @if ($flagship)
            <div class="flagship">{{ ui('supplier_pdf.flagship_product') }} : <strong>{{ $flagship }}</strong></div>
            @endif
            @if ($followUp)
            <div class="status"><span @class(['pill', 'pill-yes' => ! $onboardingPending, 'status-annex' => $onboardingPending])>{{ $followUp['status'] }}</span></div>
            @endif
        </td>
    </tr>
</table>

@if ($facts)
<table class="facts">
    <tr>
        @foreach ($facts as $fact)
        <td class="fact" style="width: {{ floor(100 / count($facts)) }}%;">
            <div class="caps">{{ $fact['label'] }}</div>
            <div class="fact-value">{{ $fact['value'] }}</div>
        </td>
        @endforeach
    </tr>
</table>
@endif

@if ($keyPoints)
<div class="key-points keep">
    <div class="card-title">{{ ui('pdf.key_points') }}</div>
    <table>
        @foreach (array_chunk($keyPoints, 4) as $line)
        <tr>
            @foreach ($line as $point)
            <td>
                <div class="caps">{{ $point['label'] }}</div>
                <div class="key-value">{{ $point['value'] }}</div>
            </td>
            @endforeach
            @for ($i = count($line); $i < 4; $i++)<td></td>@endfor
        </tr>
        @endforeach
    </table>
</div>
@endif

@php
    [$tagsLeft, $tagsRight] = collect($tags)->partition(fn (array $group): bool => in_array($group['style'], ['purple', 'green'], true))->map->values()->all();
@endphp
@if ($tags)
<table class="tag-columns">
    <tr>
        @foreach ([$tagsLeft, $tagsRight] as $column)
        <td @class(['left' => $loop->first])>
            @foreach ($column as $group)
            <div class="tag-group keep">
                <div class="caps">{{ $group['label'] }}</div>
                @foreach ($group['items'] as $item)<span class="tag tag-{{ $group['style'] }}">{{ $item }}</span>@endforeach
            </div>
            @endforeach
        </td>
        @endforeach
    </tr>
</table>
@endif

@if ($contact || $followUp)
<table class="cards keep">
    <tr>
        @if ($contact)
        <td class="left">
            <div class="card">
                <div class="card-title">{{ ui('pdf.contact') }}</div>
                <table class="kv">
                    @foreach ($contact as $row)
                    <tr><td class="k">{{ $row['label'] }}</td><td class="v">{{ $row['value'] }}</td></tr>
                    @endforeach
                </table>
            </div>
        </td>
        @endif
        @if ($followUp)
        <td class="right">
            <div class="card">
                <div class="card-title">{{ ui('pdf.follow_up') }}</div>
                <table class="kv">
                    <tr><td class="k">{{ ui('pdf.status') }}</td><td class="v">{{ $followUp['status'] }}</td></tr>
                    @if ($followUp['contactReceived'])
                    <tr><td class="k">{{ ui('pdf.contact_received') }}</td><td class="v">{{ $followUp['contactReceived'] }}</td></tr>
                    @endif
                    @if ($followUp['onboardingReceived'])
                    <tr><td class="k">{{ ui('pdf.onboarding_received') }}</td><td class="v">{{ $followUp['onboardingReceived'] }}</td></tr>
                    @endif
                </table>
                @if ($followUp['commitments'])
                <table class="commitments">
                    @foreach ($followUp['commitments'] as $commitment)
                    <tr>
                        <td><span @class(['dot', 'dot-no' => ! $commitment['accepted']])></span>{{ $commitment['label'] }}</td>
                        <td style="text-align: right; color: #8a7fa0;">{{ $commitment['accepted'] ? ui('pdf.accepted') : ui('pdf.not_accepted') }}</td>
                    </tr>
                    @endforeach
                </table>
                @endif
            </div>
        </td>
        @endif
    </tr>
</table>
@endif

@if (filled($notes))
<div class="notes-card keep">
    <div class="card-title">{{ ui('pdf.notes') }}</div>
    <div class="notes">{{ $notes }}</div>
</div>
@endif

@if ($onboardingPending)
<div class="notice">{{ ui('pdf.onboarding_pending') }}</div>
@endif

{{-- Detailed answers: the title of a section always stays with what follows it --}}
@foreach ($sections as $section)
@php
    $documents = $section['documents'];
    $hasDocuments = $documents && ($documents['rows'] || $documents['onRequest'] > 0);
    $lines = $section['lines'];
    $firstLines = $hasDocuments ? [] : array_splice($lines, 0, 1);
@endphp
<div @class(['section', 'keep' => count($section['lines']) <= 6 && ! $hasDocuments])>
    <div class="keep">
        <div class="section-title"><span class="section-number">{{ $section['number'] }}</span>{{ $section['title'] }}</div>

        @if ($hasDocuments)
            @php
                $documentRows = $documents['rows'];
                $firstDocuments = array_splice($documentRows, 0, 3);
            @endphp
            @if ($firstDocuments)
                @include('pdf.partials.profile-documents', ['rows' => $firstDocuments, 'header' => true])
            @endif
            @if ($documents['onRequest'] > 0 && ! $documentRows)
            <div class="on-request">{{ ui('pdf.documents_more', ['count' => $documents['onRequest']]) }}</div>
            @endif
        @endif

        @if ($firstLines)
            @include('pdf.partials.profile-fields', ['lines' => $firstLines])
        @endif
    </div>

    @if ($hasDocuments && $documentRows)
        @include('pdf.partials.profile-documents', ['rows' => $documentRows, 'header' => false])
        @if ($documents['onRequest'] > 0)
        <div class="on-request">{{ ui('pdf.documents_more', ['count' => $documents['onRequest']]) }}</div>
        @endif
    @endif

    @if ($lines)
        @include('pdf.partials.profile-fields', ['lines' => $lines])
    @endif
</div>
@endforeach

@if ($isForBuyer)
<div class="cta">
    <div class="cta-title">{{ ui('pdf.buyer_cta_title') }}</div>
    <div>{{ ui('pdf.buyer_cta_text') }}</div>
    <div class="cta-contact">{{ $contactEmail }} · {{ $siteUrl }}</div>
</div>
@endif

</body>
</html>
