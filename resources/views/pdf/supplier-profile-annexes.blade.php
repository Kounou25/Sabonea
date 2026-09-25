@php
    // A4 in points; each page is drawn with absolute positions, the document page is placed over it afterwards.
    [$pageWidth, $pageHeight] = $orientation === 'landscape' ? [841.89, 595.28] : [595.28, 841.89];
    $pages = [...($contents ? [['type' => 'contents', ...$contents]] : []), ...array_map(fn (array $frame): array => ['type' => 'frame', ...$frame], $frames)];
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
<meta charset="utf-8">
<title>{{ ui('pdf.annexes') }} — {{ $companyName }}</title>
<style>
    @include('pdf.partials.profile-base-styles')

    @page { margin: 0; }
    body { margin: 0; }

    .page { position: relative; width: {{ $pageWidth }}pt; height: {{ $pageHeight - 1 }}pt; overflow: hidden; page-break-after: always; }
    .page.last { page-break-after: auto; }

    .desk { position: absolute; top: 0; left: 0; width: {{ $pageWidth }}pt; height: {{ $pageHeight - 40 }}pt; background-color: #F5F2F9; }
    .sheet-shadow { position: absolute; background-color: #E2DAEC; }
    .sheet { position: absolute; background-color: #ffffff; border: .75pt solid #D9D1E6; }

    .page-footer {
        position: absolute; left: 30pt; top: {{ $pageHeight - 32.5 }}pt; width: {{ $pageWidth - 60 }}pt;
        border-top: .75pt solid #e6e0f0; padding-top: 4.5pt; font-size: 7pt; color: #8a7fa0;
    }
    .page-number { position: absolute; right: 0; top: 4.5pt; }

    /* No header on these pages: the document shown is named in the footer */
    .frame-footer { top: {{ $pageHeight - 36 }}pt; border-top: 0; padding-top: 0; }
    .frame-footer .page-number { top: 0; }
    .annex-label { font-weight: 600; color: #49037F; margin-bottom: 1.5pt; }

    /* Contents of the annexes */
    .contents { position: absolute; top: 30pt; left: 30pt; width: {{ $pageWidth - 60 }}pt; }
    .eyebrow { font-size: 7.5pt; font-weight: 600; letter-spacing: 1.2px; text-transform: uppercase; color: #FF7F00; }
    .contents-title { font-size: 22pt; font-weight: bold; color: #49037F; margin: 3px 0 6px; }
    .contents-intro { font-size: 9.5pt; color: #5c5270; margin-bottom: 18px; }
    .annex-list td { padding: 10px 10px 10px 0; border-bottom: 1px solid #f1ecf8; vertical-align: middle; }
    .annex-code {
        display: inline-block; width: 34px; padding: 5px 0; border-radius: 6px; text-align: center;
        background-color: #49037F; color: #ffffff; font-weight: bold; font-size: 9pt;
    }
    .annex-category { font-size: 7.5pt; color: #8a7fa0; }
    .annex-name { font-size: 10pt; font-weight: 600; color: #2c2340; }
    .annex-pages { width: 130px; color: #5c5270; font-size: 8.5pt; }
    .annex-start { width: 60px; text-align: right; padding-right: 0; font-weight: 600; color: #49037F; font-size: 10pt; }
</style>
</head>
<body>

@foreach ($pages as $page)
<div @class(['page', 'last' => $loop->last])>
    @if ($page['type'] === 'contents')
        <div class="contents">
            <div class="eyebrow">{{ $companyName }}</div>
            <div class="contents-title">{{ ui('pdf.annexes') }}</div>
            <div class="contents-intro">{{ ui('pdf.annexes_intro') }}</div>
            <table class="annex-list">
                @foreach ($page['annexes'] as $annex)
                <tr>
                    <td style="width: 44px;"><span class="annex-code">{{ $annex['code'] }}</span></td>
                    <td>
                        <div class="annex-category">{{ $annex['category'] }}</div>
                        <div class="annex-name">{{ $annex['attachment']->name }}</div>
                    </td>
                    <td class="annex-pages">
                        {{ $annex['pages'] < $annex['pageCount'] ? ui('pdf.annex_truncated', ['count' => $annex['pages'], 'total' => $annex['pageCount']]) : ui('pdf.annex_pages', ['count' => $annex['pages']]) }}
                    </td>
                    <td class="annex-start">{{ ui('pdf.annex_starts', ['page' => $annex['startPage']]) }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="page-footer">
            {{ $footer }}
            <span class="page-number">{{ $pageLabel($page['page'], $total) }}</span>
        </div>
    @else
        <div class="desk"></div>
        <div class="sheet-shadow" style="left: {{ $page['x'] + 2.5 }}pt; top: {{ $page['y'] + 2.5 }}pt; width: {{ $page['width'] }}pt; height: {{ $page['height'] }}pt;"></div>
        <div class="sheet" style="left: {{ $page['x'] - .75 }}pt; top: {{ $page['y'] - .75 }}pt; width: {{ $page['width'] }}pt; height: {{ $page['height'] }}pt;"></div>

        <div class="page-footer frame-footer">
            <div class="annex-label">
                {{ ui('pdf.annex', ['code' => $page['code']]) }} · {{ $page['category'] }} — {{ $page['attachment']->name }} · {{ ui('pdf.annex_sheet', ['page' => $page['sheet'], 'count' => $page['sheets']]) }}
            </div>
            {{ $footer }}
            <span class="page-number">{{ $pageLabel($page['page'], $total) }}</span>
        </div>
    @endif
</div>
@endforeach

</body>
</html>
