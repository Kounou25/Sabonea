<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
<meta charset="utf-8">
<title>{{ ui('pdf.title') }} — {{ $companyName }}</title>
<style>
    @font-face { font-family: 'Poppins'; font-weight: normal; src: url('{{ $fontsPath }}/Poppins-Regular.ttf') format('truetype'); }
    @font-face { font-family: 'Poppins'; font-weight: 500; src: url('{{ $fontsPath }}/Poppins-Medium.ttf') format('truetype'); }
    @font-face { font-family: 'Poppins'; font-weight: 600; src: url('{{ $fontsPath }}/Poppins-SemiBold.ttf') format('truetype'); }
    @font-face { font-family: 'Poppins'; font-weight: bold; src: url('{{ $fontsPath }}/Poppins-Bold.ttf') format('truetype'); }
    {{-- NotoSansSC (Chinese) is registered in SupplierProfileDocument::registerChineseFont(). --}}

    @page { margin: 118px 40px 64px 40px; }

    body {
        font-family: 'Poppins', 'NotoSansSC', sans-serif;
        font-size: 9pt; line-height: 1.1; color: #3d3450;
    }
    table { border-collapse: collapse; width: 100%; }
    td { vertical-align: top; }

    /* Header and footer repeated on every page */
    #header {
        position: fixed; top: -118px; left: -40px; right: -40px; height: 86px;
        background-color: #2C0350; color: #ffffff;
    }
    #header td { vertical-align: middle; padding: 0 40px; height: 86px; }
    #header .logo { height: 34px; }
    #header .doc-title { font-size: 12pt; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; text-align: right; }
    #header .doc-meta { font-size: 7.5pt; color: #cbb8e6; text-align: right; }
    #header .stripe { position: absolute; left: 0; right: 0; bottom: 0; height: 4px; background-color: #FF7F00; }

    #footer {
        position: fixed; bottom: -44px; left: 0; right: 0; height: 24px;
        border-top: 1px solid #e6e0f0; padding-top: 6px; font-size: 7pt; color: #8a7fa0;
    }

    /* Hero */
    .eyebrow { font-size: 7.5pt; font-weight: 600; letter-spacing: 1.2px; text-transform: uppercase; color: #FF7F00; }
    .company { font-size: 21pt; font-weight: bold; line-height: 1.15; color: #49037F; margin: 2px 0 4px; }
    .location { font-size: 10pt; color: #5c5270; }
    .flagship { margin-top: 6px; font-size: 9pt; }
    .flagship strong { color: #2c2340; }
    .badge {
        display: inline-block; padding: 4px 12px; border-radius: 12px;
        background-color: #F2EAFB; color: #49037F; font-size: 8pt; font-weight: 600;
    }
    .badge-green { background-color: #E7F3E6; color: #056800; }

    /* Key facts */
    .facts { border-collapse: separate; border-spacing: 6px 0; margin: 16px -6px 6px; width: auto; }
    .fact { background-color: #F7F3FC; border-radius: 8px; padding: 9px 12px; }
    .fact-label { font-size: 6.8pt; font-weight: 600; letter-spacing: .8px; text-transform: uppercase; color: #8a7fa0; }
    .fact-value { font-size: 10pt; font-weight: 600; color: #2c2340; }

    /* Tags */
    .tag-group { margin-top: 12px; }
    .tag-label { font-size: 7pt; font-weight: 600; letter-spacing: .8px; text-transform: uppercase; color: #8a7fa0; margin-bottom: 4px; }
    .tag {
        display: inline-block; padding: 3px 10px; margin: 0 4px 5px 0; border-radius: 10px;
        font-size: 8pt; font-weight: 500;
    }
    .tag-purple { background-color: #F2EAFB; color: #49037F; }
    .tag-green { background-color: #E7F3E6; color: #056800; }
    .tag-orange { background-color: #FFF1E0; color: #A34F00; }
    .tag-grey { background-color: #F1F0F4; color: #4a4458; }

    /* Cards */
    .card { border: 1px solid #ece6f5; border-radius: 10px; padding: 14px 16px; margin-top: 16px; }
    .card-title { font-size: 8pt; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; color: #49037F; margin-bottom: 6px; }
    .kv td { padding: 3px 0; }
    .kv .k { width: 34%; padding-right: 8px; color: #8a7fa0; font-size: 8pt; }
    .kv-wide .k { width: 50%; }
    .kv .v { color: #2c2340; font-weight: 500; }
    .notice { margin-top: 16px; padding: 10px 14px; border-radius: 8px; background-color: #FFF8F0; color: #6b4a22; font-size: 8.5pt; }

    /* Detailed sections */
    .section { margin-top: 22px; }
    .section-title {
        font-size: 10.5pt; font-weight: bold; text-transform: uppercase; color: #2c2340;
        padding-bottom: 5px; border-bottom: 2px solid #49037F; margin-bottom: 4px;
    }
    .section-number { color: #FF7F00; margin-right: 8px; }
    .rows tr { page-break-inside: avoid; }
    .rows td { padding: 7px 0; border-bottom: 1px solid #f1ecf8; }
    .rows .label { width: 36%; padding-right: 14px; color: #8a7fa0; font-size: 8pt; }
    .rows .value { color: #2c2340; }

    .doc-files { color: #5c5270; font-size: 8pt; }
    .dot { display: inline-block; width: 8px; height: 8px; border-radius: 4px; margin-right: 6px; background-color: #056800; }
    .dot-no { background-color: #c42b3b; }
    .notes { white-space: pre-line; }

    .cta {
        margin-top: 24px; padding: 16px 20px; border-radius: 10px;
        background-color: #2C0350; color: #ffffff; page-break-inside: avoid;
    }
    .cta-title { font-size: 11pt; font-weight: bold; margin-bottom: 4px; }
    .cta-contact { margin-top: 6px; font-size: 10pt; font-weight: 600; color: #FFB066; }
    .keep { page-break-inside: avoid; }
</style>
</head>
<body>

<div id="header">
    <table>
        <tr>
            <td><img class="logo" src="{{ $logoPath }}" alt="Sabonea"></td>
            <td>
                <div class="doc-title">{{ ui('pdf.title') }}</div>
                <div class="doc-meta">{{ ui('pdf.reference') }} {{ $reference }} · {{ ui('pdf.generated_on', ['date' => $generatedOn]) }}</div>
            </td>
        </tr>
    </table>
    <div class="stripe"></div>
</div>

<div id="footer">
    <table>
        <tr>
            <td>Sabonea · Direct access to the best suppliers · {{ $isForBuyer ? ui('pdf.footer_buyer', ['email' => $contactEmail]) : ui('pdf.footer_internal') }}</td>
            <td style="width: 90px;">{{-- page number drawn by SupplierProfileDocument::pdf() --}}</td>
        </tr>
    </table>
</div>

{{-- Hero --}}
<table>
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
        </td>
        @if ($followUp)
        <td style="width: 190px; text-align: right;">
            <span @class(['badge', 'badge-green' => ! $onboardingPending])>{{ $followUp['status'] }}</span>
        </td>
        @endif
    </tr>
</table>

@if ($facts)
<table class="facts">
    <tr>
        @foreach ($facts as $fact)
        <td class="fact" style="width: {{ floor(100 / count($facts)) }}%;">
            <div class="fact-label">{{ $fact['label'] }}</div>
            <div class="fact-value">{{ $fact['value'] }}</div>
        </td>
        @endforeach
    </tr>
</table>
@endif

@foreach ($tags as $group)
<div class="tag-group keep">
    <div class="tag-label">{{ $group['label'] }}</div>
    @foreach ($group['items'] as $item)<span class="tag tag-{{ $group['style'] }}">{{ $item }}</span>@endforeach
</div>
@endforeach

@if ($contact || $followUp)
<table style="margin-top: 4px;">
    <tr>
        @if ($contact)
        <td style="width: 58%; padding-right: 8px;">
            <div class="card keep">
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
        <td style="padding-left: 8px;">
            <div class="card keep">
                <div class="card-title">{{ ui('pdf.follow_up') }}</div>
                <table class="kv kv-wide">
                    <tr><td class="k">{{ ui('pdf.status') }}</td><td class="v">{{ $followUp['status'] }}</td></tr>
                    @if ($followUp['contactReceived'])
                    <tr><td class="k">{{ ui('pdf.contact_received') }}</td><td class="v">{{ $followUp['contactReceived'] }}</td></tr>
                    @endif
                    @if ($followUp['onboardingReceived'])
                    <tr><td class="k">{{ ui('pdf.onboarding_received') }}</td><td class="v">{{ $followUp['onboardingReceived'] }}</td></tr>
                    @endif
                </table>
            </div>
        </td>
        @endif
    </tr>
</table>
@endif

@if ($onboardingPending)
<div class="notice">{{ ui('pdf.onboarding_pending') }}</div>
@endif

{{-- Detailed answers --}}
@foreach ($sections as $section)
<div @class(['section', 'keep' => count($section['rows']) <= 8])>
    <div class="section-title keep"><span class="section-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>{{ $section['title'] }}</div>
    <table class="rows">
        @foreach ($section['rows'] as $row)
        <tr>
            <td class="label">{{ $row['label'] }}</td>
            <td class="value">{!! nl2br(e($row['value'])) !!}</td>
        </tr>
        @endforeach
    </table>
</div>
@endforeach

@if ($commitments)
<div class="card keep">
    <div class="card-title">{{ ui('pdf.commitments') }}</div>
    <table class="kv">
        @foreach ($commitments as $commitment)
        <tr>
            <td class="k" style="width: 60%;">{{ $commitment['label'] }}</td>
            <td class="v"><span @class(['dot', 'dot-no' => ! $commitment['accepted']])></span>{{ $commitment['accepted'] ? ui('pdf.accepted') : ui('pdf.not_accepted') }}</td>
        </tr>
        @endforeach
    </table>
</div>
@endif

@if (filled($notes))
<div class="card keep" style="border-color: #ffd9a8; background-color: #FFF8F0;">
    <div class="card-title" style="color: #A34F00;">{{ ui('pdf.notes') }}</div>
    <div class="notes">{{ $notes }}</div>
</div>
@endif

@if ($isForBuyer)
<div class="cta">
    <div class="cta-title">{{ ui('pdf.buyer_cta_title') }}</div>
    <div>{{ ui('pdf.buyer_cta_text') }}</div>
    <div class="cta-contact">{{ $contactEmail }} · {{ $siteUrl }}</div>
</div>
@endif

</body>
</html>
