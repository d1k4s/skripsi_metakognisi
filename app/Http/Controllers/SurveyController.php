<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\SurveyResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SurveyController extends Controller
{
    /**
     * Menampilkan halaman awal survey (data diri)
     */
    public function index()
    {
        // Reset session jika memulai survey baru
        Session::forget('survey_data');
        Session::forget('current_step');
        Session::forget('survey_answers');

        // Check if view exists and dump path for debugging
        $viewPath = resource_path('views/frontend/pages/survey/data-diri.blade.php');
        if (!file_exists($viewPath)) {
            // Create directory if it doesn't exist
            $directory = resource_path('views/frontend/pages/survey');
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            // Log error for debugging
            \Log::error("View file not found: {$viewPath}");
        }

        return view('frontend.pages.survey.data-diri');
    }

    /**
     * Menyimpan data diri dan menampilkan pertanyaan survey
     */
    public function saveDataDiri(Request $request)
    {
        // Validasi data dengan custom messages
        $messages = [
            'nama.required' => 'Nama tidak boleh kosong',
            'nama.max' => 'Nama tidak boleh lebih dari 255 karakter',
            'nim.required' => 'NIM tidak boleh kosong',
            'nim.max' => 'NIM tidak boleh lebih dari 50 karakter',
            'jurusan.required' => 'Jurusan harus diisi',
            'jurusan.max' => 'Jurusan tidak boleh lebih dari 100 karakter',
        ];

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:50',
            'jurusan' => 'required|string|max:100',
        ], $messages);

        // Simpan data diri ke session
        Session::put('survey_data', $validatedData);
        Session::put('current_step', 1);
        Session::put('survey_answers', []);

        return redirect()->route('survey.questions', ['step' => 1]);
    }

    /**
     * Menampilkan pertanyaan survey berdasarkan step
     */
    public function showQuestions($step)
    {
        // Pastikan user sudah mengisi data diri
        if (!Session::has('survey_data')) {
            return redirect()->route('survey.index')->with('error', 'Silakan isi data diri Anda terlebih dahulu');
        }

        // Konversi ke integer dan validasi step
        $step = (int)$step;

        // Daftar semua pertanyaan
        $allQuestions = $this->getSurveyQuestions();
        $totalQuestions = count($allQuestions);
        $totalSteps = ceil($totalQuestions / 5); // Jumlah pertanyaan dibagi 5 per halaman

        if ($step < 1 || $step > $totalSteps) {
            return redirect()->route('survey.questions', ['step' => 1]);
        }

        // Update current step di session
        Session::put('current_step', $step);

        // Siapkan range pertanyaan untuk step ini
        $startQuestion = ($step - 1) * 5 + 1;
        $endQuestion = min($startQuestion + 4, $totalQuestions); // Pastikan tidak melebihi jumlah pertanyaan

        // Ambil pertanyaan untuk step ini
        $questions = [];
        for ($i = $startQuestion; $i <= $endQuestion; $i++) {
            $arrayIndex = $i - 1; // Konversi ke zero-based index untuk array
            if (isset($allQuestions[$arrayIndex])) {
                $questions[$i] = $allQuestions[$arrayIndex];
            }
        }

        // Ambil jawaban yang sudah diisi sebelumnya (jika ada)
        $answers = Session::get('survey_answers', []);

        return view('frontend.pages.survey.questions', [
            'step' => $step,
            'totalSteps' => $totalSteps,
            'questions' => $questions,
            'startQuestion' => $startQuestion,
            'endQuestion' => $endQuestion,
            'answers' => $answers,
            'prevStep' => $step > 1 ? $step - 1 : null,
            'nextStep' => $step < $totalSteps ? $step + 1 : null,
        ]);
    }

    /**
     * Menyimpan jawaban pertanyaan survey per step
     */
    public function saveQuestions(Request $request, $step)
    {
        // Konversi step ke integer
        $step = (int)$step;

        // Get total questions and calculate total steps
        $totalQuestions = count($this->getSurveyQuestions());
        $totalSteps = ceil($totalQuestions / 5);

        // Validasi
        $startQuestion = ($step - 1) * 5 + 1;
        $endQuestion = min($startQuestion + 4, $totalQuestions);

        $rules = [];
        $messages = [];

        for ($i = $startQuestion; $i <= $endQuestion; $i++) {
            $rules["pertanyaan$i"] = 'required|integer|min:1|max:5';
            $messages["pertanyaan$i.required"] = "Pertanyaan $i harus dijawab";
            $messages["pertanyaan$i.integer"] = "Jawaban pertanyaan $i harus berupa angka";
            $messages["pertanyaan$i.min"] = "Jawaban pertanyaan $i minimal 1";
            $messages["pertanyaan$i.max"] = "Jawaban pertanyaan $i maksimal 5";
        }

        $validatedData = $request->validate($rules, $messages);

        // Simpan jawaban ke session
        $answers = Session::get('survey_answers', []);
        foreach ($validatedData as $key => $value) {
            $answers[$key] = $value;
        }
        Session::put('survey_answers', $answers);

        // Cek apakah ini step terakhir
        if ($step == $totalSteps) {
            // Jika step terakhir, proses semua data
            return $this->processAllData();
        } else {
            // Jika bukan step terakhir, lanjut ke step berikutnya
            return redirect()->route('survey.questions', ['step' => $step + 1]);
        }
    }

    /**
     * Proses menyimpan semua data ke database
     */
    private function processAllData()
    {
        // Ambil data dari session
        $surveyData = Session::get('survey_data');
        $answers = Session::get('survey_answers');

        // Buat log ke file untuk debugging
        $logPath = storage_path('app/survey_debug.txt');
        File::append($logPath, "\n\n" . date('Y-m-d H:i:s') . " - Processing all survey data\n");
        File::append($logPath, "Survey data: " . json_encode($surveyData) . "\n");
        File::append($logPath, "Answers: " . json_encode($answers) . "\n");

        try {
            // Gunakan transaksi database untuk memastikan semua data tersimpan atau tidak sama sekali
            DB::beginTransaction();

            // Simpan data responden
            $survey = Survey::create([
                'nama' => $surveyData['nama'],
                'nim' => $surveyData['nim'],
                'jurusan' => $surveyData['jurusan'],
            ]);

            File::append($logPath, "Survey created with ID: " . ($survey->id ?? 'NULL') . "\n");

            // Simpan jawaban survey
            $savedResponses = 0;
            foreach ($answers as $key => $value) {
                // Extract pertanyaan ID dari key (format: pertanyaan1, pertanyaan2, etc)
                $pertanyaanId = (int) str_replace('pertanyaan', '', $key);

                SurveyResponse::create([
                    'survey_id' => $survey->id,
                    'pertanyaan_id' => $pertanyaanId,
                    'jawaban' => $value,
                ]);
                $savedResponses++;
            }

            File::append($logPath, "Saved {$savedResponses} responses\n");

            // Commit transaksi jika semua berhasil
            DB::commit();

            File::append($logPath, "Transaction committed successfully\n");

            // Hapus data dari session setelah berhasil disimpan
            Session::forget('survey_data');
            Session::forget('current_step');
            Session::forget('survey_answers');

            return redirect()->route('survey.thankyou')->with('success', 'Terima kasih telah mengisi survey!');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();

            // Log error
            File::append($logPath, "ERROR: " . $e->getMessage() . "\n");
            File::append($logPath, "Trace: " . $e->getTraceAsString() . "\n");

            return redirect()->route('survey.index')
                ->with('error', 'Terjadi kesalahan saat menyimpan data survey. Silakan coba lagi.');
        }
    }

    /**
     * Tampilkan halaman terima kasih
     */
    public function thankYou()
    {
        return view('frontend.pages.survey.thankyou');
    }

    /**
     * Daftar semua pertanyaan survey
     */
    private function getSurveyQuestions()
    {
        return [
            'Saya menetapkan tujuan spesifik sebelum mulai mengerjakan tugas',
            'Saya mempertimbangkan beberapa alternatif solusi ketika menyelesaikan masalah',
            'Saya bertanya pada diri sendiri apakah saya sudah mempertimbangkan semua opsi ketika menyelesaikan masalah',
            'Saya mencoba menggunakan strategi yang pernah berhasil di masa lalu',
            'Saya mengatur langkah-langkah saya dengan hati-hati untuk menyelesaikan masalah',
            'Saya membaca instruksi dengan teliti sebelum mulai mengerjakan tugas',
            'Saya bertanya pada diri sendiri apakah saya memahami sesuatu dengan baik',
            'Saya mengevaluasi asumsi-asumsi saya ketika mengalami kebingungan',
            'Saya mengatur waktu saya agar mencapai tujuan dengan sebaik-baiknya',
            'Saya tahu apa yang diharapkan guru untuk saya pelajari',
            'Saya baik dalam mengingat informasi',
            'Saya menggunakan strategi belajar yang berbeda tergantung pada situasi',
            'Saya bertanya pada diri sendiri apakah ada cara yang lebih mudah setelah menyelesaikan tugas',
            'Saya mampu memotivasi diri untuk belajar ketika dibutuhkan',
            'Saya sadar strategi apa yang saya gunakan ketika belajar',
            'Saya menganalisis kegunaan strategi saat belajar',
            'Saya menggunakan kekuatan dan kelemahan intelektual saya saat belajar',
            'Saya baik dalam mengorganisir informasi',
            'Saya fokus pada makna dan signifikansi dari informasi baru',
            'Saya menciptakan contoh sendiri untuk membuat informasi lebih bermakna',
            'Saya menilai dengan baik seberapa baik saya memahami sesuatu',
            'Saya memiliki strategi khusus yang saya gunakan saat belajar',
            'Saya memikirkan apa yang sebenarnya perlu saya pelajari sebelum memulai tugas',
            'Saya bertanya pada diri sendiri pertanyaan tentang materi pelajaran sebelum mulai belajar',
            'Saya memikirkan beberapa cara untuk menyelesaikan masalah dan memilih yang terbaik',
            'Saya merangkum apa yang telah saya pelajari setelah selesai belajar',
            'Saya meminta bantuan orang lain ketika tidak memahami sesuatu',
            'Saya dapat memotivasi diri untuk belajar ketika dibutuhkan',
            'Saya sadar strategi apa yang saya gunakan ketika mengerjakan tugas',
            'Saya secara otomatis menganalisis kegunaan strategi ketika belajar',
            'Saya secara teratur berhenti sejenak untuk memeriksa pemahaman saya',
            'Saya tahu kapan setiap strategi yang saya gunakan akan paling efektif',
            'Saya bertanya pada diri sendiri seberapa baik saya mencapai tujuan setelah menyelesaikan tugas',
            'Saya membuat gambar atau diagram untuk membantu memahami saat belajar',
            'Saya mengevaluasi kembali asumsi saya ketika merasa bingung',
            'Saya mengubah strategi ketika gagal memahami',
            'Saya berhenti dan membaca ulang ketika menjadi bingung',
            'Saya mengubah teknik atau strategi belajar untuk mencapai tujuan',
            'Saya menggunakan struktur teks untuk membantu dalam belajar',
            'Saya membaca instruksi dengan hati-hati sebelum mulai mengerjakan tugas',
            'Saya bertanya pada diri sendiri apakah apa yang saya baca berhubungan dengan apa yang sudah saya ketahui',
            'Saya mengevaluasi strategi belajar saya secara berkala',
            'Saya mengecek kembali apa yang sudah saya kerjakan saat menghadapi konsep baru',
            'Saya mengatur tempo belajar saya untuk memastikan saya memiliki cukup waktu',
            'Saya mengatur ulang informasi untuk memudahkan saya dalam belajar',
            'Saya menggunakan kemampuan verbal untuk membantu saya belajar',
            'Saya menggunakan kelebihan intelektual saya untuk mengimbangi kelemahan saya',
            'Saya bertanya pada diri saya apakah saya telah mempertimbangkan semua pilihan saat menyelesaikan masalah',
            'Saya mencari tahu makna dari informasi yang tidak familier',
            'Saya berhenti dan membaca ulang saat informasi tidak jelas',
        ];
    }

    /**
     * Tampilkan halaman untuk mengekspor data survey
     */
    public function exportIndex()
    {
        $surveys = Survey::with('responses')->get();
        return view('backend.survey.export', compact('surveys'));
    }

    /**
     * Mengekspor data survey ke CSV
     */
    public function exportCsv(Request $request)
    {
        // Validasi input
        $request->validate([
            'survey_ids' => 'required|array',
            'survey_ids.*' => 'exists:surveys,id',
        ], [
            'survey_ids.required' => 'Pilih minimal satu survey untuk diekspor',
            'survey_ids.*.exists' => 'Survey yang dipilih tidak valid',
        ]);

        $selectedIds = $request->survey_ids;

        // Ambil semua pertanyaan
        $questions = $this->getSurveyQuestions();

        // Ambil data survey yang dipilih dengan responses
        $surveys = Survey::with('responses')->whereIn('id', $selectedIds)->get();

        // Buat nama file dengan timestamp
        $filename = 'survey_data_' . date('Y-m-d_His') . '.csv';

        // Set header untuk file CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        // Buat response stream untuk menangani file besar
        $callback = function() use ($surveys, $questions) {
            $file = fopen('php://output', 'w');

            // Set UTF-8 BOM untuk menangani karakter non-ASCII
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header CSV pertama: informasi data diri
            $header = ['ID', 'Nama', 'NIM', 'Jurusan', 'Tanggal Dibuat'];

            // Tambahkan semua pertanyaan ke header
            for ($i = 1; $i <= count($questions); $i++) {
                $header[] = "P$i"; // P1, P2, dst
            }

            fputcsv($file, $header);

            // Isi data per survey
            foreach ($surveys as $survey) {
                $row = [
                    $survey->id,
                    $survey->nama,
                    $survey->nim,
                    $survey->jurusan,
                    $survey->created_at->format('Y-m-d H:i:s'),
                ];

                // Buat array untuk menyimpan jawaban
                $answers = [];

                // Isi jawaban per pertanyaan
                foreach ($survey->responses as $response) {
                    $answers[$response->pertanyaan_id] = $response->jawaban;
                }

                // Tambahkan jawaban ke row, jika tidak ada jawaban isi dengan nilai kosong
                for ($i = 1; $i <= count($questions); $i++) {
                    $row[] = isset($answers[$i]) ? $answers[$i] : '';
                }

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Tampilkan detail survey tertentu
     */
    public function showDetail($id)
    {
        $survey = Survey::with('responses')->findOrFail($id);
        $questions = $this->getSurveyQuestions();

        return view('backend.survey.detail', compact('survey', 'questions'));
    }

    /**
     * Download semua data survey dalam format XLSX
     */
    public function downloadExcel()
    {
        // // Cek apakah user sudah login dan admin
        // if (!auth()->check() || !auth()->user()->hasRole('admin')) {
        //     abort(403, 'Unauthorized action.');
        // }

        // Ambil semua survey dengan responses
        $surveys = Survey::with('responses')->get();

        // Ambil semua pertanyaan
        $questions = $this->getSurveyQuestions();

        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul sheet
        $sheet->setTitle('Data Survey Metakognisi');

        // Buat header
        $headers = ['ID', 'Nama', 'NIM', 'Jurusan', 'Tanggal'];

        // Tambahkan nomor dan text pertanyaan ke header
        for ($i = 1; $i <= count($questions); $i++) {
            $headers[] = "P$i";
        }

        // Tambahkan header ke sheet
        $sheet->fromArray([$headers], NULL, 'A1');

        // Style untuk header
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4361EE'], // ultramarine-blue
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray($headerStyle);

        // Tambahkan data survey
        $row = 2; // mulai dari baris kedua
        foreach ($surveys as $survey) {
            // Data dasar
            $rowData = [
                $survey->id,
                $survey->nama,
                $survey->nim,
                $survey->jurusan,
                $survey->created_at->format('Y-m-d H:i:s'),
            ];

            // Buat array untuk menyimpan jawaban
            $answers = [];

            // Isi jawaban per pertanyaan
            foreach ($survey->responses as $response) {
                $answers[$response->pertanyaan_id] = $response->jawaban;
            }

            // Tambahkan jawaban ke row data
            for ($i = 1; $i <= count($questions); $i++) {
                $rowData[] = isset($answers[$i]) ? $answers[$i] : '';
            }

            // Tambahkan row data ke sheet
            $sheet->fromArray([$rowData], NULL, 'A' . $row);
            $row++;
        }

        // Auto-size kolom
        foreach (range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Freeze pane pada header
        $sheet->freezePane('A2');

        // Buat worksheet kedua untuk pertanyaan lengkap
        $questionSheet = $spreadsheet->createSheet();
        $questionSheet->setTitle('Daftar Pertanyaan');

        // Header untuk sheet pertanyaan
        $questionSheet->setCellValue('A1', 'No');
        $questionSheet->setCellValue('B1', 'Pertanyaan');
        $questionSheet->getStyle('A1:B1')->applyFromArray($headerStyle);

        // Isi data pertanyaan
        for ($i = 0; $i < count($questions); $i++) {
            $questionSheet->setCellValue('A' . ($i + 2), $i + 1);
            $questionSheet->setCellValue('B' . ($i + 2), $questions[$i]);
        }

        // Auto-size kolom pertanyaan
        $questionSheet->getColumnDimension('A')->setAutoSize(true);
        $questionSheet->getColumnDimension('B')->setAutoSize(true);

        // Set active sheet kembali ke sheet pertama
        $spreadsheet->setActiveSheetIndex(0);

        // Buat response untuk download
        $filename = 'data_survey_metakognisi_' . date('Y-m-d_His') . '.xlsx';

        // Create temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_');

        // Save file to temp location
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        // Return file as download
        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
