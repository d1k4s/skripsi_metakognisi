@extends('backend.layouts.app')

@section('title', 'Detail Survey')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Detail Survey</h3>
                        <div>
                            <a href="{{ route('survey.export.csv', ['survey_ids' => [$survey->id]]) }}" class="btn btn-info">
                                <i class="bi bi-download"></i> Download CSV
                            </a>
                            <a href="{{ route('survey.export.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h4>Data Responden</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="15%">ID</th>
                                            <td>{{ $survey->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                            <td>{{ $survey->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th>NIM</th>
                                            <td>{{ $survey->nim }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jurusan</th>
                                            <td>{{ $survey->jurusan }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal</th>
                                            <td>{{ $survey->created_at->format('d-m-Y H:i:s') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div>
                            <h4>Jawaban Survei</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="75%">Pertanyaan</th>
                                            <th width="20%">Jawaban</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $responses = $survey->responses->keyBy('pertanyaan_id');
                                        @endphp

                                        @foreach ($questions as $index => $question)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $question }}</td>
                                                <td class="text-center">
                                                    @if (isset($responses[$index + 1]))
                                                        <span
                                                            class="badge bg-info fs-6">{{ $responses[$index + 1]->jawaban }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak dijawab</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
