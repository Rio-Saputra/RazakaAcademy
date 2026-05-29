<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buklet Soal: {{ $tryout->title }}</title>
    <style>
        @page {
            margin: 2cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1F2937;
            line-height: 1.6;
            font-size: 11pt;
        }
        .header {
            border-bottom: 3px double #243A5E;
            padding-bottom: 12px;
            margin-bottom: 25px;
            text-align: center;
        }
        .brand-title {
            font-size: 18pt;
            font-weight: bold;
            color: #243A5E;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }
        .brand-subtitle {
            font-size: 10pt;
            color: #64748B;
            margin: 3px 0 0 0;
            font-style: italic;
        }
        .exam-info-table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
            font-size: 10pt;
        }
        .exam-info-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .exam-info-label {
            font-weight: bold;
            color: #243A5E;
            width: 18%;
        }
        .exam-info-value {
            width: 32%;
            color: #334155;
        }
        .instructions-box {
            border: 1px solid #CBD5E1;
            background-color: #F8FAFC;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 30px;
            font-size: 9.5pt;
            color: #475569;
        }
        .instructions-title {
            font-weight: bold;
            color: #243A5E;
            margin-top: 0;
            margin-bottom: 6px;
            text-transform: uppercase;
            font-size: 10pt;
            letter-spacing: 0.5px;
        }
        .instructions-list {
            margin: 0;
            padding-left: 20px;
        }
        .instructions-list li {
            margin-bottom: 4px;
        }
        .question-block {
            margin-bottom: 22px;
            page-break-inside: avoid;
        }
        .question-header {
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 8px;
        }
        .question-num {
            color: #243A5E;
            margin-right: 5px;
            display: inline;
        }
        .question-text {
            display: inline;
        }
        .options-list {
            margin-top: 6px;
            padding-left: 20px;
        }
        .option-item {
            margin-bottom: 5px;
        }
        .option-letter {
            font-weight: bold;
            color: #243A5E;
            margin-right: 6px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            color: #94A3B8;
            border-top: 1px solid #E2E8F0;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <!-- Header Ujian -->
    <div class="header">
        <h1 class="brand-title">Razaka Academy</h1>
        <p class="brand-subtitle">Sistem Pelatihan & Tryout Online Premium</p>
        
        <table class="exam-info-table">
            <tr>
                <td class="exam-info-label">Mata Ujian</td>
                <td class="exam-info-value">: {{ $tryout->title }}</td>
                <td class="exam-info-label">Durasi</td>
                <td class="exam-info-value">: {{ $tryout->duration }} Menit</td>
            </tr>
            <tr>
                <td class="exam-info-label">Paket Soal</td>
                <td class="exam-info-value">: {{ $tryout->package->name ?? '-' }}</td>
                <td class="exam-info-label">Jumlah Soal</td>
                <td class="exam-info-value">: {{ count($questions) }} Butir Soal</td>
            </tr>
        </table>
    </div>

    <!-- Petunjuk Ujian -->
    <div class="instructions-box">
        <h3 class="instructions-title">Petunjuk Pengerjaan Soal</h3>
        <ol class="instructions-list">
            <li>Bacalah doa sebelum memulai mengerjakan soal ujian.</li>
            <li>Periksa kembali kelengkapan soal dan baca pertanyaan dengan teliti sebelum menjawab.</li>
            <li>Pilihlah satu jawaban yang paling tepat dari pilihan A, B, C, atau D yang tersedia.</li>
            <li>Dilarang bekerja sama, menyontek, atau menggunakan alat bantu lain selama ujian berlangsung.</li>
        </ol>
    </div>

    <!-- Daftar Soal -->
    <div class="questions-container">
        @foreach($questions as $index => $q)
        <div class="question-block">
            <div class="question-header">
                <span class="question-num">{{ $index + 1 }}.</span>
                <div class="question-text">{!! $q->question_text !!}</div>
            </div>
            
            <div class="options-list">
                <div class="option-item">
                    <span class="option-letter">A.</span>
                    <span class="option-text">{{ $q->option_a }}</span>
                </div>
                <div class="option-item">
                    <span class="option-letter">B.</span>
                    <span class="option-text">{{ $q->option_b }}</span>
                </div>
                <div class="option-item">
                    <span class="option-letter">C.</span>
                    <span class="option-text">{{ $q->option_c }}</span>
                </div>
                <div class="option-item">
                    <span class="option-letter">D.</span>
                    <span class="option-text">{{ $q->option_d }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Footer Dokumen -->
    <div class="footer">
        Dokumen Soal Tryout Otomatis - Razaka Academy
    </div>

</body>
</html>
