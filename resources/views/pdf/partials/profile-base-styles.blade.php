{{-- Styles shared by the supplier profile and its annex pages. NotoSansSC (Chinese) is registered in App\Pdf\DompdfRenderer. --}}
    @font-face { font-family: 'Poppins'; font-weight: normal; src: url('{{ $fontsPath }}/Poppins-Regular.ttf') format('truetype'); }
    @font-face { font-family: 'Poppins'; font-weight: 500; src: url('{{ $fontsPath }}/Poppins-Medium.ttf') format('truetype'); }
    @font-face { font-family: 'Poppins'; font-weight: 600; src: url('{{ $fontsPath }}/Poppins-SemiBold.ttf') format('truetype'); }
    @font-face { font-family: 'Poppins'; font-weight: bold; src: url('{{ $fontsPath }}/Poppins-Bold.ttf') format('truetype'); }

    body {
        font-family: 'Poppins', 'NotoSansSC', sans-serif;
        font-size: 9pt; line-height: 1.1; color: #3d3450;
    }
    table { border-collapse: collapse; width: 100%; }
    td { vertical-align: top; }
    .caps { font-size: 6.8pt; font-weight: 600; letter-spacing: .8px; text-transform: uppercase; color: #8a7fa0; }

    /* Header on the first page only (in the flow, bleeding into the page margins); footer on every page */
    #header {
        position: relative; margin: -40px -40px 29px -40px; height: 64px;
        background-color: #2C0350; color: #ffffff;
    }
    #header td { vertical-align: middle; padding: 0 40px; height: 64px; }
    #header .logo { height: 30px; }
    #header .doc-title { font-size: 10.5pt; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; text-align: right; }
    #header .doc-meta { font-size: 7pt; color: #cbb8e6; text-align: right; margin-top: 1px; }
    #header .stripe { position: absolute; left: 0; right: 0; bottom: -3px; height: 3px; background-color: #FF7F00; }

    #footer {
        position: fixed; bottom: -44px; left: 0; right: 0; height: 24px;
        border-top: 1px solid #e6e0f0; padding-top: 6px; padding-right: 90px; font-size: 7pt; color: #8a7fa0;
    }
