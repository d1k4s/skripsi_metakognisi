@extends('backend.layouts.app')

@section('title', 'Export Survey Data')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Export Survey Data</h3>
                    </div>
                    <div class="card-body">
                        @if ($surveys->isEmpty())
                            <div class="alert alert-info">
                                Belum ada data survey yang tersedia.
                            </div>
                        @else
                            <form action="{{ route('survey.export.csv') }}" method="GET">
                                <div class="mb-3">
                                    <label class="form-label">Pilih data yang akan diunduh:</label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th width="5%">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="checkAll">
                                                        </div>
                                                    </th>
                                                    <th width="5%">ID</th>
                                                    <th width="25%">Nama</th>
                                                    <th width="15%">NIM</th>
                                                    <th width="20%">Jurusan</th>
                                                    <th width="15%">Tanggal</th>
                                                    <th width="15%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($surveys as $survey)
                                                    <tr>
                                                        <td>
                                                            <div class="form-check">
                                                                <input class="form-check-input survey-checkbox"
                                                                    type="checkbox" name="survey_ids[]"
                                                                    value="{{ $survey->id }}">
                                                            </div>
                                                        </td>
                                                        <td>{{ $survey->id }}</td>
                                                        <td>{{ $survey->nama }}</td>
                                                        <td>{{ $survey->nim }}</td>
                                                        <td>{{ $survey->jurusan }}</td>
                                                        <td>{{ $survey->created_at->format('d/m/Y H:i') }}</td>
                                                        <td>
                                                            <a href="{{ route('survey.export.csv', ['survey_ids' => [$survey->id]]) }}"
                                                                class="btn btn-sm btn-info">
                                                                <i class="bi bi-download"></i> Download
                                                            </a>
                                                            <a href="{{ route('survey.export.detail', ['id' => $survey->id]) }}"
                                                                class="btn btn-sm btn-primary">
                                                                <i class="bi bi-eye"></i> Detail
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-download"></i> Download Selected
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle "Check All" functionality
            const checkAllBox = document.getElementById('checkAll');
            const checkboxes = document.querySelectorAll('.survey-checkbox');

            if (checkAllBox) {
                checkAllBox.addEventListener('change', function() {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = checkAllBox.checked;
                    });
                });
            }

            // Update "Check All" status when individual checkboxes change
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const allChecked = Array.from(checkboxes).every(c => c.checked);
                    const anyChecked = Array.from(checkboxes).some(c => c.checked);

                    if (checkAllBox) {
                        checkAllBox.checked = allChecked;
                        checkAllBox.indeterminate = anyChecked && !allChecked;
                    }
                });
            });
        });
    </script>
@endsection
