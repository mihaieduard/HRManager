@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Training & Dezvoltare</h2>
        @if(auth()->user()->isAdmin() || auth()->user()->isHR())
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTrainingModal">
            <i class="bi bi-plus-lg me-1"></i> Adaugă Training
        </button>
        @endif
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <ul class="nav nav-pills mb-3" id="trainingTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="available-tab" data-bs-toggle="tab" data-bs-target="#available" type="button" role="tab" aria-controls="available" aria-selected="true">
                        Training-uri Disponibile
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="my-trainings-tab" data-bs-toggle="tab" data-bs-target="#my-trainings" type="button" role="tab" aria-controls="my-trainings" aria-selected="false">
                        Training-urile Mele
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">
                        Finalizate
                    </button>
                </li>
                @if(auth()->user()->isAdmin() || auth()->user()->isHR())
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats" type="button" role="tab" aria-controls="stats" aria-selected="false">
                        Statistici
                    </button>
                </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="tab-content" id="trainingTabContent">
        <!-- Available Trainings Tab -->
        <div class="tab-pane fade show active" id="available" role="tabpanel" aria-labelledby="available-tab">
            <div class="row">
                @php
                    $categories = [
                        'tehnical' => ['Tehnic & IT', 'bi-laptop', 'bg-primary'],
                        'management' => ['Management', 'bi-briefcase', 'bg-success'],
                        'personal' => ['Dezvoltare Personală', 'bi-person', 'bg-info'],
                        'communication' => ['Comunicare', 'bi-chat-dots', 'bg-warning'],
                        'leadership' => ['Leadership', 'bi-trophy', 'bg-danger']
                    ];
                @endphp
                
                @foreach($categories as $categoryKey => $category)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div class="{{ $category[2] }} text-white rounded-3 me-3 p-2">
                                    <i class="bi {{ $category[1] }} fs-5"></i>
                                </div>
                                <h5 class="mb-0">{{ $category[0] }}</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @for($i = 1; $i <= 3; $i++)
                                <div class="list-group-item p-3 border-0">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">{{ $category[0] }} - Curs {{ $i }}</h6>
                                        <span class="badge {{ $category[2] }}">{{ rand(1, 10) }} module</span>
                                    </div>
                                    <p class="text-muted small mb-2">
                                        Acest curs vă oferă toate informațiile și competențele necesare pentru a excela în domeniul {{ strtolower($category[0]) }}.
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div>
                                            <span class="badge bg-light text-dark me-1">{{ rand(4, 16) }} ore</span>
                                            <span class="badge bg-light text-dark">{{ rand(10, 100) }} participanți</span>
                                        </div>
                                        <a href="#" class="btn btn-sm btn-outline-primary" 
                                           data-bs-toggle="modal" data-bs-target="#trainingDetailModal{{ $categoryKey }}{{ $i }}">
                                            Vezi detalii
                                        </a>
                                    </div>
                                </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- My Trainings Tab -->
        <div class="tab-pane fade" id="my-trainings" role="tabpanel" aria-labelledby="my-trainings-tab">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Training</th>
                                            <th>Categorie</th>
                                            <th>Progres</th>
                                            <th>Dată înscriere</th>
                                            <th>Deadline</th>
                                            <th>Acțiuni</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($i = 1; $i <= 5; $i++)
                                            @php
                                                $categoryIndex = array_rand($categories);
                                                $category = $categories[$categoryIndex];
                                                $progress = rand(0, 100);
                                                $enrollDate = now()->subDays(rand(1, 30));
                                                $deadline = $enrollDate->copy()->addDays(rand(30, 90));
                                                $daysLeft = now()->diffInDays($deadline, false);
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="{{ $category[2] }} text-white rounded-3 me-3 p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                            <i class="bi {{ $category[1] }}"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">{{ $category[0] }} - Training {{ $i }}</div>
                                                            <div class="small text-muted">{{ rand(4, 16) }} ore | {{ rand(2, 10) }} module</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $category[2] }}">{{ $category[0] }}</span>
                                                </td>
                                                <td style="width: 20%;">
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress flex-grow-1" style="height: 6px;">
                                                            <div class="progress-bar {{ $category[2] }}" role="progressbar" style="width: {{ $progress }}%" 
                                                                 aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="ms-2">{{ $progress }}%</span>
                                                    </div>
                                                </td>
                                                <td>{{ $enrollDate->format('d M Y') }}</td>
                                                <td>
                                                    <div class="{{ $daysLeft < 0 ? 'text-danger' : ($daysLeft < 7 ? 'text-warning' : '') }}">
                                                        {{ $deadline->format('d M Y') }}
                                                        @if($daysLeft < 0)
                                                            <i class="bi bi-exclamation-circle-fill ms-1" title="Deadline depășit"></i>
                                                        @elseif($daysLeft < 7)
                                                            <i class="bi bi-exclamation-triangle-fill ms-1" title="Deadline apropiat"></i>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary">Continuă</button>
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
        
        <!-- Completed Trainings Tab -->
        <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Training</th>
                                            <th>Categorie</th>
                                            <th>Dată finalizare</th>
                                            <th>Scor final</th>
                                            <th>Certificat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($i = 1; $i <= 5; $i++)
                                            @php
                                                $categoryIndex = array_rand($categories);
                                                $category = $categories[$categoryIndex];
                                                $completionDate = now()->subDays(rand(1, 180));
                                                $score = rand(70, 100);
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="{{ $category[2] }} text-white rounded-3 me-3 p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                            <i class="bi {{ $category[1] }}"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">{{ $category[0] }} - Training {{ $i }}</div>
                                                            <div class="small text-muted">{{ rand(4, 16) }} ore | {{ rand(2, 10) }} module</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $category[2] }}">{{ $category[0] }}</span>
                                                </td>
                                                <td>{{ $completionDate->format('d M Y') }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress flex-grow-1" style="height: 6px; width: 100px;">
                                                            <div class="progress-bar {{ $score >= 90 ? 'bg-success' : ($score >= 80 ? 'bg-info' : 'bg-warning') }}" 
                                                                 role="progressbar" style="width: {{ $score }}%" 
                                                                 aria-valuenow="{{ $score }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="ms-2">{{ $score }}%</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-download me-1"></i> Certificat
                                                    </a>
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
        
        <!-- Statistics Tab (Admin/HR only) -->
        <div class="tab-pane fade" id="stats" role="tabpanel" aria-labelledby="stats-tab">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">Training-uri pe Categorii</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="trainingCategoriesChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">Completare Training-uri (ultimele 6 luni)</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="trainingCompletionChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Top Angajați după Training-uri Finalizate</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Angajat</th>
                                            <th>Training-uri finalizate</th>
                                            <th>Ore de training</th>
                                            <th>Scor mediu</th>
                                            <th>Categorii</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($i = 1; $i <= 5; $i++)
                                            @php
                                                $trainingsCompleted = rand(3, 15);
                                                $trainingHours = $trainingsCompleted * rand(4, 12);
                                                $avgScore = rand(75, 98);
                                                $topCategories = array_rand($categories, min(3, count($categories)));
                                                if (!is_array($topCategories)) {
                                                    $topCategories = [$topCategories];
                                                }
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="https://ui-avatars.com/api/?name=User+{{ $i }}&background=random" alt="User" class="avatar me-2">
                                                        <div>
                                                            <div class="fw-medium">Utilizator {{ $i }}</div>
                                                            <div class="small text-muted">Departament Test</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $trainingsCompleted }}</td>
                                                <td>{{ $trainingHours }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress flex-grow-1" style="height: 6px; width: 100px;">
                                                            <div class="progress-bar {{ $avgScore >= 90 ? 'bg-success' : ($avgScore >= 80 ? 'bg-info' : 'bg-warning') }}" 
                                                                 role="progressbar" style="width: {{ $avgScore }}%" 
                                                                 aria-valuenow="{{ $avgScore }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="ms-2">{{ $avgScore }}%</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @foreach($topCategories as $catKey)
                                                        <span class="badge {{ $categories[$catKey][2] }} me-1">{{ $categories[$catKey][0] }}</span>
                                                    @endforeach
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
    </div>
</div>

<!-- Modal - Create Training (Admin/HR only) -->
<div class="modal fade" id="createTrainingModal" tabindex="-1" aria-labelledby="createTrainingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTrainingModalLabel">Adaugă Training Nou</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="trainingTitle" class="form-label">Titlu</label>
                        <input type="text" class="form-control" id="trainingTitle" placeholder="Titlul training-ului">
                    </div>
                    <div class="mb-3">
                        <label for="trainingDescription" class="form-label">Descriere</label>
                        <textarea class="form-control" id="trainingDescription" rows="3" placeholder="Detaliați training-ul..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="trainingCategory" class="form-label">Categorie</label>
                                <select class="form-select" id="trainingCategory">
                                    <option value="">Selectează categorie</option>
                                    @foreach($categories as $categoryKey => $category)
                                        <option value="{{ $categoryKey }}">{{ $category[0] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="trainingDuration" class="form-label">Durată (ore)</label>
                                <input type="number" class="form-control" id="trainingDuration" value="8">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="trainingModules" class="form-label">Număr de module</label>
                                <input type="number" class="form-control" id="trainingModules" value="5">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="trainingStatus" class="form-label">Status</label>
                                <select class="form-select" id="trainingStatus">
                                    <option value="active">Activ</option>
                                    <option value="draft">Draft</option>
                                    <option value="archived">Arhivat</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="trainingTarget" class="form-label">Destinat pentru</label>
                        <select class="form-select" id="trainingTarget" multiple>
                            <option value="all">Toți angajații</option>
                            <option value="new">Angajați noi</option>
                            <option value="technical">Departament Tehnic</option>
                            <option value="management">Management</option>
                            <option value="sales">Vânzări</option>
                        </select>
                        <div class="form-text">Ține apăsat CTRL/CMD pentru a selecta mai multe opțiuni</div>
                    </div>
                    <div class="mb-3">
                        <label for="trainingSyllabus" class="form-label">Syllabus</label>
                        <textarea class="form-control" id="trainingSyllabus" rows="5" placeholder="Introduceți conținutul training-ului..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                <button type="button" class="btn btn-primary">Salvează Training</button>
            </div>
        </div>
    </div>
</div>

<!-- Training Details Modals -->
@foreach($categories as $categoryKey => $category)
    @for($i = 1; $i <= 3; $i++)
        <div class="modal fade" id="trainingDetailModal{{ $categoryKey }}{{ $i }}" tabindex="-1" 
             aria-labelledby="trainingDetailModal{{ $categoryKey }}{{ $i }}Label" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header {{ $category[2] }} text-white">
                        <h5 class="modal-title" id="trainingDetailModal{{ $categoryKey }}{{ $i }}Label">
                            {{ $category[0] }} - Curs {{ $i }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <h5>Despre acest training</h5>
                                <p>
                                    Acest curs complet vă oferă toate informațiile și competențele necesare pentru a excela în domeniul {{ strtolower($category[0]) }}.
                                    Prin intermediul lecțiilor practice și teoriei solide, veți dobândi cunoștințe valoroase care vă vor ajuta să avansați în carieră.
                                </p>
                                <div class="d-flex mt-3">
                                    <div class="me-4">
                                        <div class="small text-muted mb-1">Durată</div>
                                        <div class="fw-medium">{{ rand(4, 16) }} ore</div>
                                    </div>
                                    <div class="me-4">
                                        <div class="small text-muted mb-1">Module</div>
                                        <div class="fw-medium">{{ rand(2, 10) }}</div>
                                    </div>
                                    <div class="me-4">
                                        <div class="small text-muted mb-1">Nivel</div>
                                        <div class="fw-medium">{{ ['Începător', 'Intermediar', 'Avansat'][rand(0, 2)] }}</div>
                                    </div>
                                    <div>
                                        <div class="small text-muted mb-1">Participanți</div>
                                        <div class="fw-medium">{{ rand(10, 100) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="mb-3">Ce vei învăța</h6>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0 border-0"><i class="bi bi-check-circle-fill text-success me-2"></i> Competența 1</li>
                                            <li class="list-group-item px-0 border-0"><i class="bi bi-check-circle-fill text-success me-2"></i> Competența 2</li>
                                            <li class="list-group-item px-0 border-0"><i class="bi bi-check-circle-fill text-success me-2"></i> Competența 3</li>
                                            <li class="list-group-item px-0 border-0"><i class="bi bi-check-circle-fill text-success me-2"></i> Competența 4</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5>Cuprins</h5>
                            <div class="accordion" id="syllabus{{ $categoryKey }}{{ $i }}">
                                @for($j = 1; $j <= 5; $j++)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $j }}{{ $categoryKey }}{{ $i }}">
                                        <button class="accordion-button {{ $j > 1 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" 
                                                data-bs-target="#collapse{{ $j }}{{ $categoryKey }}{{ $i }}" 
                                                aria-expanded="{{ $j === 1 ? 'true' : 'false' }}" 
                                                aria-controls="collapse{{ $j }}{{ $categoryKey }}{{ $i }}">
                                            Modulul {{ $j }}: Titlu modul de exemplu
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $j }}{{ $categoryKey }}{{ $i }}" 
                                         class="accordion-collapse collapse {{ $j === 1 ? 'show' : '' }}" 
                                         aria-labelledby="heading{{ $j }}{{ $categoryKey }}{{ $i }}" 
                                         data-bs-parent="#syllabus{{ $categoryKey }}{{ $i }}">
                                        <div class="accordion-body">
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-2"><i class="bi bi-play-circle me-2"></i> Lecția 1: Introducere (20 min)</li>
                                                <li class="mb-2"><i class="bi bi-play-circle me-2"></i> Lecția 2: Concepte de bază (30 min)</li>
                                                <li class="mb-2"><i class="bi bi-file-text me-2"></i> Materiale suplimentare</li>
                                                <li><i class="bi bi-question-circle me-2"></i> Quiz de evaluare</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            </div>
                        </div>
                        
                        <div>
                            <h5>Recenzii</h5>
                            @php
                                $rating = rand(35, 50) / 10;
                                $reviews = rand(10, 50);
                            @endphp
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-2">
                                    <span class="fw-bold fs-4">{{ $rating }}</span>
                                    <span class="text-muted">/5</span>
                                </div>
                                <div class="me-3">
                                    @for($star = 1; $star <= 5; $star++)
                                        @if($star <= floor($rating))
                                            <i class="bi bi-star-fill text-warning"></i>
                                        @elseif($star - 0.5 <= $rating)
                                            <i class="bi bi-star-half text-warning"></i>
                                        @else
                                            <i class="bi bi-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="text-muted">({{ $reviews }} recenzii)</div>
                            </div>
                            
                            <div class="row">
                                @for($r = 1; $r <= 2; $r++)
                                    @php
                                        $reviewRating = rand(3, 5);
                                        $reviewDate = now()->subDays(rand(1, 90));
                                    @endphp
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <img src="https://ui-avatars.com/api/?name=Review+{{ $r }}&background=random" alt="User" class="avatar me-2">
                                                        <div>
                                                            <div class="fw-medium">Utilizator Recenzie {{ $r }}</div>
                                                            <div class="small text-muted">{{ $reviewDate->format('d M Y') }}</div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        @for($star = 1; $star <= 5; $star++)
                                                            <i class="bi {{ $star <= $reviewRating ? 'bi-star-fill' : 'bi-star' }} text-warning small"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <p class="small mb-0">
                                                    Un training foarte util și bine structurat. Am învățat multe lucruri noi care mă vor ajuta în activitatea zilnică.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Închide</button>
                        <button type="button" class="btn {{ $category[2] }}">Înscrie-te la curs</button>
                    </div>
                </div>
            </div>
        </div>
    @endfor
@endforeach

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Training Categories Chart
        const categoriesCtx = document.getElementById('trainingCategoriesChart');
        if (categoriesCtx) {
            const categoriesChart = new Chart(categoriesCtx, {
                type: 'bar',
                data: {
                    labels: ['Tehnic & IT', 'Management', 'Dezvoltare Personală', 'Comunicare', 'Leadership'],
                    datasets: [{
                        label: 'Număr de training-uri',
                        data: [12, 8, 15, 6, 9],
                        backgroundColor: [
                            'rgba(13, 110, 253, 0.6)',
                            'rgba(25, 135, 84, 0.6)',
                            'rgba(13, 202, 240, 0.6)',
                            'rgba(255, 193, 7, 0.6)',
                            'rgba(220, 53, 69, 0.6)'
                        ],
                        borderColor: [
                            'rgb(13, 110, 253)',
                            'rgb(25, 135, 84)',
                            'rgb(13, 202, 240)',
                            'rgb(255, 193, 7)',
                            'rgb(220, 53, 69)'
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
        
        // Training Completion Chart
        const completionCtx = document.getElementById('trainingCompletionChart');
        if (completionCtx) {
            const months = [];
            const now = new Date();
            for (let i = 5; i >= 0; i--) {
                const month = new Date(now.getFullYear(), now.getMonth() - i, 1);
                months.push(month.toLocaleString('default', { month: 'short' }));
            }
            
            const completionChart = new Chart(completionCtx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Training-uri finalizate',
                        data: [8, 12, 15, 22, 18, 25],
                        borderColor: 'rgb(13, 110, 253)',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        tension: 0.3,
                        fill: true
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
    });
</script>
@endpush
@endsection