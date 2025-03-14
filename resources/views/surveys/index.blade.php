@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Sondaje și Feedback</h2>
            @if (auth()->user()->isAdmin() || auth()->user()->isHR())
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSurveyModal">
                    <i class="bi bi-plus-lg me-1"></i> Sondaj Nou
                </button>
            @endif
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <ul class="nav nav-pills mb-3" id="surveyTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active"
                            type="button" role="tab" aria-controls="active" aria-selected="true">
                            Sondaje Active
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed"
                            type="button" role="tab" aria-controls="completed" aria-selected="false">
                            Completate
                        </button>
                    </li>
                    @if (auth()->user()->isAdmin() || auth()->user()->isHR())
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="drafts-tab" data-bs-toggle="tab" data-bs-target="#drafts"
                                type="button" role="tab" aria-controls="drafts" aria-selected="false">
                                Schițe
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="results-tab" data-bs-toggle="tab" data-bs-target="#results"
                                type="button" role="tab" aria-controls="results" aria-selected="false">
                                Rezultate
                            </button>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="tab-content" id="surveyTabContent">
            <!-- Active Surveys Tab -->
            <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                <div class="row">
                    @for ($i = 1; $i <= 6; $i++)
                        @php
                            $surveyTypes = [
                                ['Satisfacție Angajați', 'bi-emoji-smile', 'bg-primary'],
                                ['Feedback Training', 'bi-mortarboard', 'bg-success'],
                                ['Evaluare Inițiativă', 'bi-lightbulb', 'bg-info'],
                                ['Feedback 360°', 'bi-arrow-repeat', 'bg-warning'],
                                ['Cultură Organizațională', 'bi-building', 'bg-danger'],
                            ];
                            $survey = $surveyTypes[array_rand($surveyTypes)];
                            $questions = rand(5, 15);
                            $estTime = rand(3, 10);
                            $deadline = now()->addDays(rand(1, 14));
                            $responses = rand(0, 30);
                            $responsePercent = ($responses * 100) / 50; // assuming 50 total possible respondents
                        @endphp
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex mb-3">
                                        <div class="{{ $survey[2] }} text-white rounded-3 me-3 p-2"
                                            style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi {{ $survey[1] }} fs-5"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-1">{{ $survey[0] }} {{ $i }}</h5>
                                            <div class="text-muted small">
                                                <i class="bi bi-question-circle me-1"></i> {{ $questions }} întrebări
                                                <i class="bi bi-clock ms-2 me-1"></i> ~{{ $estTime }} minute
                                            </div>
                                        </div>
                                    </div>

                                    <p class="text-muted small mb-3">
                                        Acest sondaj are ca scop evaluarea {{ strtolower($survey[0]) }} în cadrul companiei
                                        noastre.
                                        Răspunsurile dumneavoastră sunt anonime și ne vor ajuta să îmbunătățim mediul de
                                        lucru.
                                    </p>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="text-muted small">
                                            <i class="bi bi-calendar me-1"></i> Deadline: {{ $deadline->format('d M Y') }}
                                        </div>
                                        <div class="badge {{ $survey[2] }}">{{ $responses }} răspunsuri</div>
                                    </div>

                                    <div class="progress mb-3" style="height: 6px;">
                                        <div class="progress-bar {{ $survey[2] }}" role="progressbar"
                                            style="width: {{ $responsePercent }}%" aria-valuenow="{{ $responsePercent }}"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="small text-muted">Completat {{ round($responsePercent) }}%</span>
                                        <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#takeSurveyModal{{ $i }}">
                                            Completează sondaj
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Completed Surveys Tab -->
            <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>Sondaj</th>
                                                <th>Tip</th>
                                                <th>Data completării</th>
                                                <th>Status</th>
                                                <th>Acțiuni</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 1; $i <= 5; $i++)
                                                @php
                                                    $surveyTypes = [
                                                        ['Satisfacție Angajați', 'bi-emoji-smile', 'bg-primary'],
                                                        ['Feedback Training', 'bi-mortarboard', 'bg-success'],
                                                        ['Evaluare Inițiativă', 'bi-lightbulb', 'bg-info'],
                                                        ['Feedback 360°', 'bi-arrow-repeat', 'bg-warning'],
                                                        ['Cultură Organizațională', 'bi-building', 'bg-danger'],
                                                    ];
                                                    $survey = $surveyTypes[array_rand($surveyTypes)];
                                                    $completionDate = now()->subDays(rand(1, 60));
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="{{ $survey[2] }} text-white rounded-3 me-3 p-2"
                                                                style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                                <i class="bi {{ $survey[1] }}"></i>
                                                            </div>
                                                            <div>
                                                                <div class="fw-medium">{{ $survey[0] }}
                                                                    {{ $i }}</div>
                                                                <div class="small text-muted">{{ rand(5, 15) }}
                                                                    întrebări</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $survey[2] }}">{{ $survey[0] }}</span>
                                                    </td>
                                                    <td>{{ $completionDate->format('d M Y, H:i') }}</td>
                                                    <td>
                                                        <span class="badge bg-success">Completat</span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-eye me-1"></i> Vezi răspunsuri
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Drafts Tab (Admin/HR only) -->
            <div class="tab-pane fade" id="drafts" role="tabpanel" aria-labelledby="drafts-tab">
                @if (auth()->user()->isAdmin() || auth()->user()->isHR())
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Sondaj</th>
                                                    <th>Tip</th>
                                                    <th>Creat la</th>
                                                    <th>Întrebări</th>
                                                    <th>Acțiuni</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @for ($i = 1; $i <= 3; $i++)
                                                    @php
                                                        $surveyTypes = [
                                                            ['Satisfacție Angajați', 'bi-emoji-smile', 'bg-primary'],
                                                            ['Feedback Training', 'bi-mortarboard', 'bg-success'],
                                                            ['Evaluare Inițiativă', 'bi-lightbulb', 'bg-info'],
                                                            ['Feedback 360°', 'bi-arrow-repeat', 'bg-warning'],
                                                            ['Cultură Organizațională', 'bi-building', 'bg-danger'],
                                                        ];
                                                        $survey = $surveyTypes[array_rand($surveyTypes)];
                                                        $creationDate = now()->subDays(rand(1, 10));
                                                        $questions = rand(0, 10);
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="{{ $survey[2] }} text-white rounded-3 me-3 p-2"
                                                                    style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                                    <i class="bi {{ $survey[1] }}"></i>
                                                                </div>
                                                                <div>
                                                                    <div class="fw-medium">{{ $survey[0] }} (Schiță)
                                                                    </div>
                                                                    <div class="small text-muted">Ultima modificare:
                                                                        {{ now()->subHours(rand(1, 72))->format('d M, H:i') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge {{ $survey[2] }}">{{ $survey[0] }}</span>
                                                        </td>
                                                        <td>{{ $creationDate->format('d M Y') }}</td>
                                                        <td>
                                                            {{ $questions }} întrebări
                                                            @if ($questions === 0)
                                                                <span class="badge bg-warning ms-1">Incomplet</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-warning me-1">
                                                                <i class="bi bi-pencil me-1"></i> Editează
                                                            </button>
                                                            <button class="btn btn-sm btn-success me-1">
                                                                <i class="bi bi-send me-1"></i> Publică
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-danger">
                                                                <i class="bi bi-trash me-1"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Results Tab (Admin/HR only) -->
            <div class="tab-pane fade" id="results" role="tabpanel" aria-labelledby="results-tab">
                @if (auth()->user()->isAdmin() || auth()->user()->isHR())
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Selectează sondaj pentru analiză</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="surveySelect" class="form-label">Sondaj</label>
                                                <select class="form-select" id="surveySelect">
                                                    <option value="">Selectează sondaj</option>
                                                    @foreach ($surveyTypes as $index => $survey)
                                                        <option value="{{ $index }}">{{ $survey[0] }}
                                                            {{ $index + 1 }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-3">
                                                <label for="dateFrom" class="form-label">De la</label>
                                                <input type="date" class="form-control" id="dateFrom"
                                                    value="{{ now()->subMonths(1)->format('Y-m-d') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-3">
                                                <label for="dateTo" class="form-label">Până la</label>
                                                <input type="date" class="form-control" id="dateTo"
                                                    value="{{ now()->format('Y-m-d') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-primary">
                                                <i class="bi bi-search me-1"></i> Analizează
                                            </button>
                                            <button class="btn btn-outline-secondary ms-2">
                                                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                                            </button>
                                            <button class="btn btn-outline-secondary ms-2">
                                                <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0">Satisfacție Generală</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="satisfactionChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0">Distribuție Răspunsuri</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="responsesDistributionChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Răspunsuri Detaliate</h5>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="surveyResponses">
                                @for ($q = 1; $q <= 5; $q++)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ $q }}">
                                            <button class="accordion-button {{ $q > 1 ? 'collapsed' : '' }}"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{ $q }}"
                                                aria-expanded="{{ $q === 1 ? 'true' : 'false' }}"
                                                aria-controls="collapse{{ $q }}">
                                                Întrebarea {{ $q }}: Cât de mulțumit sunteți de...?
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $q }}"
                                            class="accordion-collapse collapse {{ $q === 1 ? 'show' : '' }}"
                                            aria-labelledby="heading{{ $q }}"
                                            data-bs-parent="#surveyResponses">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <canvas id="question{{ $q }}Chart"
                                                            height="200"></canvas>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Comentarii ({{ rand(3, 15) }})</h6>
                                                        <div class="mt-3">
                                                            @for ($c = 1; $c <= 3; $c++)
                                                                <div
                                                                    class="mb-3 pb-3 {{ $c < 3 ? 'border-bottom' : '' }}">
                                                                    <div class="mb-2">
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-center">
                                                                            <div class="d-flex align-items-center">
                                                                                <span
                                                                                    class="badge bg-secondary me-2">Anonim</span>
                                                                                <span class="badge bg-light text-dark">
                                                                                    Rating: {{ rand(1, 5) }}/5
                                                                                </span>
                                                                            </div>
                                                                            <small class="text-muted">
                                                                                {{ now()->subDays(rand(1, 30))->format('d M Y') }}
                                                                            </small>
                                                                        </div>
                                                                    </div>
                                                                    <p class="mb-0 small">
                                                                        "Acesta este un comentariu de exemplu pentru
                                                                        întrebarea {{ $q }}.
                                                                        Oferă feedback valoros despre aspectele pozitive și
                                                                        negative ale companiei."
                                                                    </p>
                                                                </div>
                                                            @endfor
                                                            <a href="#" class="small text-decoration-none">Vezi
                                                                toate comentariile</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    </div>

    <!-- Modal - Create Survey (Admin/HR only) -->
    <div class="modal fade" id="createSurveyModal" tabindex="-1" aria-labelledby="createSurveyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSurveyModalLabel">Creează Sondaj Nou</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="surveyTitle" class="form-label">Titlu</label>
                            <input type="text" class="form-control" id="surveyTitle" placeholder="Titlul sondajului">
                        </div>
                        <div class="mb-3">
                            <label for="surveyDescription" class="form-label">Descriere</label>
                            <textarea class="form-control" id="surveyDescription" rows="3" placeholder="Descrieți scopul sondajului..."></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="surveyType" class="form-label">Tip sondaj</label>
                                    <select class="form-select" id="surveyType">
                                        <option value="satisfaction">Satisfacție Angajați</option>
                                        <option value="feedback">Feedback Training</option>
                                        <option value="initiative">Evaluare Inițiativă</option>
                                        <option value="360">Feedback 360°</option>
                                        <option value="culture">Cultură Organizațională</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="surveyAnonymous" class="form-label">Anonimitate</label>
                                    <select class="form-select" id="surveyAnonymous">
                                        <option value="true">Anonim</option>
                                        <option value="false">Ne-anonim</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="targetGroups" class="form-label">Grupuri țintă</label>
                                    <select class="form-select" id="targetGroups" multiple>
                                        <option value="all">Toți angajații</option>
                                        <option value="department1">Departament 1</option>
                                        <option value="department2">Departament 2</option>
                                        <option value="management">Management</option>
                                        <option value="new">Angajați noi</option>
                                    </select>
                                    <div class="form-text">Ține apăsat CTRL/CMD pentru a selecta mai multe opțiuni</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="surveyDeadline" class="form-label">Deadline</label>
                                    <input type="date" class="form-control" id="surveyDeadline"
                                        value="{{ now()->addDays(14)->format('Y-m-d') }}">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3">Întrebări</h5>

                        <div id="questionsContainer">
                            <div class="question-item card mb-3">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label">Întrebarea 1</label>
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-question">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control"
                                            placeholder="Introduceți întrebarea...">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tip răspuns</label>
                                        <select class="form-select question-type">
                                            <option value="rating">Rating (1-5)</option>
                                            <option value="yesno">Da/Nu</option>
                                            <option value="multiple">Alegere multiplă</option>
                                            <option value="text">Text liber</option>
                                        </select>
                                    </div>
                                    <div class="options-container" style="display: none;">
                                        <label class="form-label">Opțiuni</label>
                                        <div class="options-list">
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control" placeholder="Opțiune">
                                                <button class="btn btn-outline-danger remove-option" type="button">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary add-option">
                                            <i class="bi bi-plus"></i> Adaugă opțiune
                                        </button>
                                    </div>
                                    <div class="form-check mt-3">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="requiredQuestion1">
                                        <label class="form-check-label" for="requiredQuestion1">
                                            Obligatoriu
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-outline-primary" id="addQuestion">
                            <i class="bi bi-plus-lg me-1"></i> Adaugă întrebare
                        </button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                    <button type="button" class="btn btn-success" id="saveDraft">Salvează schiță</button>
                    <button type="button" class="btn btn-primary">Publică sondaj</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal - Take Survey -->
    @for ($i = 1; $i <= 6; $i++)
        <div class="modal fade" id="takeSurveyModal{{ $i }}" tabindex="-1"
            aria-labelledby="takeSurveyModal{{ $i }}Label" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        @php
                            $surveyTypes = [
                                ['Satisfacție Angajați', 'bi-emoji-smile', 'bg-primary'],
                                ['Feedback Training', 'bi-mortarboard', 'bg-success'],
                                ['Evaluare Inițiativă', 'bi-lightbulb', 'bg-info'],
                                ['Feedback 360°', 'bi-arrow-repeat', 'bg-warning'],
                                ['Cultură Organizațională', 'bi-building', 'bg-danger'],
                            ];
                            $survey = $surveyTypes[array_rand($surveyTypes)];
                        @endphp
                        <h5 class="modal-title" id="takeSurveyModal{{ $i }}Label">{{ $survey[0] }}
                            {{ $i }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info mb-4">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="bi bi-info-circle-fill fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Informații sondaj</h6>
                                    <p class="mb-0">
                                        Acest sondaj este <strong>{{ rand(0, 1) ? 'anonim' : 'ne-anonim' }}</strong>.
                                        Vă rugăm să răspundeți sincer la toate întrebările. Timpul estimat de completare:
                                        <strong>{{ rand(3, 10) }} minute</strong>.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <form>
                            @for ($q = 1; $q <= rand(5, 10); $q++)
                                @php
                                    $questionTypes = ['rating', 'yesno', 'multiple', 'text'];
                                    $questionType = $questionTypes[array_rand($questionTypes)];
                                @endphp
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h6 class="mb-3">Întrebarea {{ $q }}: Cât de mulțumit sunteți de
                                            {{ ['mediul de lucru', 'comunicarea în echipă', 'oportunitățile de dezvoltare', 'echilibrul muncă-viață', 'managementul echipei'][rand(0, 4)] }}?
                                        </h6>

                                        @if ($questionType === 'rating')
                                            <div class="rating-container d-flex justify-content-between mb-3">
                                                @for ($r = 1; $r <= 5; $r++)
                                                    <div class="form-check form-check-inline text-center">
                                                        <input class="form-check-input" type="radio"
                                                            name="question{{ $q }}_rating"
                                                            id="rating{{ $q }}_{{ $r }}"
                                                            value="{{ $r }}">
                                                        <label class="form-check-label d-block mt-1"
                                                            for="rating{{ $q }}_{{ $r }}">{{ $r }}</label>
                                                        <div class="small text-muted">
                                                            {{ $r === 1 ? 'Deloc' : ($r === 5 ? 'Foarte mult' : '') }}
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>
                                        @elseif($questionType === 'yesno')
                                            <div class="mb-3">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                        name="question{{ $q }}_yesno"
                                                        id="yes{{ $q }}" value="yes">
                                                    <label class="form-check-label"
                                                        for="yes{{ $q }}">Da</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                        name="question{{ $q }}_yesno"
                                                        id="no{{ $q }}" value="no">
                                                    <label class="form-check-label"
                                                        for="no{{ $q }}">Nu</label>
                                                </div>
                                            </div>
                                        @elseif($questionType === 'multiple')
                                            <div class="mb-3">
                                                @for ($o = 1; $o <= 4; $o++)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="question{{ $q }}_option{{ $o }}"
                                                            id="option{{ $q }}_{{ $o }}">
                                                        <label class="form-check-label"
                                                            for="option{{ $q }}_{{ $o }}">
                                                            Opțiunea {{ $o }}
                                                        </label>
                                                    </div>
                                                @endfor
                                            </div>
                                        @elseif($questionType === 'text')
                                            <div class="mb-3">
                                                <textarea class="form-control" name="question{{ $q }}_text" rows="3"
                                                    placeholder="Introduceți răspunsul dumneavoastră..."></textarea>
                                            </div>
                                        @endif

                                        @if (rand(0, 1))
                                            <div class="mt-3">
                                                <label class="form-label">Comentarii (opțional)</label>
                                                <textarea class="form-control" name="question{{ $q }}_comment" rows="2"
                                                    placeholder="Adăugați comentarii suplimentare..."></textarea>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endfor
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                        <button type="button" class="btn btn-primary">Trimite răspunsuri</button>
                    </div>
                </div>
            </div>
        </div>
    @endfor

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Survey Creation
                document.querySelectorAll('.question-type').forEach(function(select) {
                    select.addEventListener('change', function() {
                        const optionsContainer = this.closest('.question-item').querySelector(
                            '.options-container');
                        if (this.value === 'multiple') {
                            optionsContainer.style.display = 'block';
                        } else {
                            optionsContainer.style.display = 'none';
                        }
                    });
                });

                // Add Question Button
                document.getElementById('addQuestion')?.addEventListener('click', function() {
                    const questionsContainer = document.getElementById('questionsContainer');
                    const questionItems = questionsContainer.querySelectorAll('.question-item');
                    const newIndex = questionItems.length + 1;

                    const newQuestion = document.createElement('div');
                    newQuestion.className = 'question-item card mb-3';
                    newQuestion.innerHTML = `
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label">Întrebarea ${newIndex}</label>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-question">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <input type="text" class="form-control" placeholder="Introduceți întrebarea...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tip răspuns</label>
                    <select class="form-select question-type">
                        <option value="rating">Rating (1-5)</option>
                        <option value="yesno">Da/Nu</option>
                        <option value="multiple">Alegere multiplă</option>
                        <option value="text">Text liber</option>
                    </select>
                </div>
                <div class="options-container" style="display: none;">
                    <label class="form-label">Opțiuni</label>
                    <div class="options-list">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" placeholder="Opțiune">
                            <button class="btn btn-outline-danger remove-option" type="button">
                                <i class="bi bi-dash"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary add-option">
                        <i class="bi bi-plus"></i> Adaugă opțiune
                    </button>
                </div>
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" value="" id="requiredQuestion${newIndex}">
                    <label class="form-check-label" for="requiredQuestion${newIndex}">
                        Obligatoriu
                    </label>
                </div>
            </div>
        `;

                    questionsContainer.appendChild(newQuestion);

                    // Add event listener for the new question type select
                    const newQuestionType = newQuestion.querySelector('.question-type');
                    newQuestionType.addEventListener('change', function() {
                        const optionsContainer = this.closest('.question-item').querySelector(
                            '.options-container');
                        if (this.value === 'multiple') {
                            optionsContainer.style.display = 'block';
                        } else {
                            optionsContainer.style.display = 'none';
                        }
                    });

                    // Add event listener for remove question button
                    const removeBtn = newQuestion.querySelector('.remove-question');
                    removeBtn.addEventListener('click', function() {
                        newQuestion.remove();
                        // Update question numbering
                        updateQuestionNumbers();
                    });

                    // Add event listener for add option button
                    const addOptionBtn = newQuestion.querySelector('.add-option');
                    addOptionBtn.addEventListener('click', function() {
                        addOption(this);
                    });

                    // Add event listener for remove option buttons
                    const removeOptionBtns = newQuestion.querySelectorAll('.remove-option');
                    removeOptionBtns.forEach(btn => {
                        btn.addEventListener('click', function() {
                            this.closest('.input-group').remove();
                        });
                    });
                });

                // Remove Question Button
                document.querySelectorAll('.remove-question').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        this.closest('.question-item').remove();
                        // Update question numbering
                        updateQuestionNumbers();
                    });
                });

                // Add Option Button
                document.querySelectorAll('.add-option').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        addOption(this);
                    });
                });

                // Remove Option Button
                document.querySelectorAll('.remove-option').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        this.closest('.input-group').remove();
                    });
                });

                function addOption(button) {
                    const optionsList = button.previousElementSibling;
                    const newOption = document.createElement('div');
                    newOption.className = 'input-group mb-2';
                    newOption.innerHTML = `
            <input type="text" class="form-control" placeholder="Opțiune">
            <button class="btn btn-outline-danger remove-option" type="button">
                <i class="bi bi-dash"></i>
            </button>
        `;

                    optionsList.appendChild(newOption);

                    // Add event listener for the new remove option button
                    const removeBtn = newOption.querySelector('.remove-option');
                    removeBtn.addEventListener('click', function() {
                        newOption.remove();
                    });
                }

                function updateQuestionNumbers() {
                    const questionItems = document.querySelectorAll('.question-item');
                    questionItems.forEach((item, index) => {
                        const label = item.querySelector('.form-label');
                        if (label) {
                            label.textContent = `Întrebarea ${index + 1}`;
                        }

                        const checkbox = item.querySelector('.form-check-input[type="checkbox"]');
                        if (checkbox) {
                            checkbox.id = `requiredQuestion${index + 1}`;
                        }

                        const checkboxLabel = item.querySelector('.form-check-label');
                        if (checkboxLabel) {
                            checkboxLabel.setAttribute('for', `requiredQuestion${index + 1}`);
                        }
                    });
                }

                // Charts for results tab
                const satisfactionCtx = document.getElementById('satisfactionChart');
                if (satisfactionCtx) {
                    const satisfactionChart = new Chart(satisfactionCtx, {
                        type: 'line',
                        data: {
                            labels: ['Ian', 'Feb', 'Mar', 'Apr', 'Mai', 'Iun'],
                            datasets: [{
                                label: 'Scor mediu',
                                data: [3.2, 3.5, 3.8, 3.7, 4.1, 4.3],
                                borderColor: 'rgb(13, 110, 253)',
                                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                                tension: 0.3,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    min: 0,
                                    max: 5,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                }

                const responsesDistributionCtx = document.getElementById('responsesDistributionChart');
                if (responsesDistributionCtx) {
                    const responsesDistributionChart = new Chart(responsesDistributionCtx, {
                        type: 'pie',
                        data: {
                            labels: ['Foarte Mulțumit', 'Mulțumit', 'Neutru', 'Nemulțumit',
                                'Foarte Nemulțumit'],
                            datasets: [{
                                data: [30, 45, 15, 7, 3],
                                backgroundColor: [
                                    'rgba(25, 135, 84, 0.7)',
                                    'rgba(13, 202, 240, 0.7)',
                                    'rgba(255, 193, 7, 0.7)',
                                    'rgba(255, 127, 80, 0.7)',
                                    'rgba(220, 53, 69, 0.7)'
                                ],
                                borderColor: [
                                    'rgb(25, 135, 84)',
                                    'rgb(13, 202, 240)',
                                    'rgb(255, 193, 7)',
                                    'rgb(255, 127, 80)',
                                    'rgb(220, 53, 69)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                }

                // Individual question charts
                for (let q = 1; q <= 5; q++) {
                    const questionCtx = document.getElementById(`question${q}Chart`);
                    if (questionCtx) {
                        const questionChart = new Chart(questionCtx, {
                            type: 'bar',
                            data: {
                                labels: ['1', '2', '3', '4', '5'],
                                datasets: [{
                                    label: 'Număr de răspunsuri',
                                    data: [
                                        Math.floor(Math.random() * 10),
                                        Math.floor(Math.random() * 15) + 5,
                                        Math.floor(Math.random() * 20) + 10,
                                        Math.floor(Math.random() * 25) + 15,
                                        Math.floor(Math.random() * 20) + 10
                                    ],
                                    backgroundColor: [
                                        'rgba(220, 53, 69, 0.7)',
                                        'rgba(255, 127, 80, 0.7)',
                                        'rgba(255, 193, 7, 0.7)',
                                        'rgba(13, 202, 240, 0.7)',
                                        'rgba(25, 135, 84, 0.7)'
                                    ],
                                    borderColor: [
                                        'rgb(220, 53, 69)',
                                        'rgb(255, 127, 80)',
                                        'rgb(255, 193, 7)',
                                        'rgb(13, 202, 240)',
                                        'rgb(25, 135, 84)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            precision: 0
                                        }
                                    }
                                }
                            }
                        });
                    }
                }
            });
        </script>
    @endpush
@endsection
