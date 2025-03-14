@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Spațiu Colaborativ</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPostModal">
            <i class="bi bi-plus-lg me-1"></i> Postare Nouă
        </button>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Posts Feed -->
            <div class="mb-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" alt="User" class="avatar me-3">
                            <div class="flex-grow-1">
                                <div class="form-control bg-light" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#createPostModal">
                                    Ce aveți în minte astăzi?
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createPostModal">
                                <i class="bi bi-image me-1"></i> Imagine
                            </button>
                            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createPostModal">
                                <i class="bi bi-link-45deg me-1"></i> Link
                            </button>
                            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createPostModal">
                                <i class="bi bi-file-earmark-text me-1"></i> Document
                            </button>
                            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createPostModal">
                                <i class="bi bi-question-circle me-1"></i> Întrebare
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sample Posts -->
                @php
                    $postTypes = [
                        ['text', 'bi-chat-left-text'],
                        ['image', 'bi-image'],
                        ['question', 'bi-question-circle'],
                        ['document', 'bi-file-earmark-text'],
                        ['link', 'bi-link-45deg']
                    ];
                @endphp

                @for($i = 1; $i <= 5; $i++)
                    @php
                        $postType = $postTypes[array_rand($postTypes)];
                        $userName = "Utilizator " . $i;
                        $postDate = now()->subDays(rand(0, 30))->subHours(rand(1, 23));
                        $likesCount = rand(0, 50);
                        $commentsCount = rand(0, 15);
                        $sharesCount = rand(0, 10);
                    @endphp
                    <div class="card mb-4">
                        <div class="card-header bg-white p-3">
                            <div class="d-flex">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($userName) }}&background=random" alt="User" class="avatar me-3">
                                <div>
                                    <h6 class="mb-0">{{ $userName }}</h6>
                                    <div class="small text-muted">
                                        <i class="bi {{ $postType[1] }} me-1"></i> {{ $postDate->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <p>Acesta este un exemplu de {{ $postType[0] === 'text' ? 'postare text' : 'postare cu ' . $postType[0] }}. 
                               {{ $postType[0] === 'question' ? 'Ce părere aveți despre acest subiect?' : 'Este o ocazie pentru a împărtăși informații valoroase cu colegii.' }}</p>
                            
                            @if($postType[0] === 'image')
                                <div class="mt-3 mb-3">
                                    <img src="https://picsum.photos/seed/{{ $i }}/800/400" alt="Post Image" class="img-fluid rounded">
                                </div>
                            @elseif($postType[0] === 'link')
                                <div class="mt-3 mb-3">
                                    <div class="card">
                                        <div class="card-body p-3">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <i class="bi bi-link-45deg fs-3"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">Titlu Link Extern</h6>
                                                    <p class="mb-1 small">Descriere scurtă despre ce conține link-ul extern...</p>
                                                    <a href="#" class="small text-decoration-none">www.example.com</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($postType[0] === 'document')
                                <div class="mt-3 mb-3">
                                    <div class="card">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <i class="bi bi-file-earmark-pdf fs-3 text-danger"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">Document_{{ $i }}.pdf</h6>
                                                    <p class="mb-1 small text-muted">PDF - 2.5 MB</p>
                                                </div>
                                                <div class="ms-auto">
                                                    <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <button class="btn btn-sm text-primary">
                                        <i class="bi {{ $likesCount > 0 ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up' }} me-1"></i> {{ $likesCount }}
                                    </button>
                                    <button class="btn btn-sm text-primary" data-bs-toggle="collapse" data-bs-target="#comments{{ $i }}">
                                        <i class="bi bi-chat me-1"></i> {{ $commentsCount }}
                                    </button>
                                    <button class="btn btn-sm text-primary">
                                        <i class="bi bi-share me-1"></i> {{ $sharesCount }}
                                    </button>
                                </div>
                                <div>
                                    <button class="btn btn-sm text-secondary">
                                        <i class="bi bi-bookmark"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Comments Section -->
                            <div class="collapse mt-3" id="comments{{ $i }}">
                                <hr>
                                <h6 class="mb-3">Comentarii</h6>
                                
                                @if($commentsCount > 0)
                                    @for($j = 1; $j <= min(3, $commentsCount); $j++)
                                        @php
                                            $commentUserName = "Comentator " . $j;
                                            $commentDate = $postDate->copy()->addHours(rand(1, 24));
                                        @endphp
                                        <div class="d-flex mb-3">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($commentUserName) }}&background=random" alt="User" class="avatar avatar-sm me-2" style="width: 32px; height: 32px;">
                                            <div class="flex-grow-1">
                                                <div class="bg-light p-2 rounded">
                                                    <div class="d-flex justify-content-between">
                                                        <span class="fw-medium">{{ $commentUserName }}</span>
                                                        <span class="small text-muted">{{ $commentDate->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="mb-0 small">Acesta este un comentariu la postare. Conține feedback, întrebări sau observații relevante.</p>
                                                </div>
                                                <div class="mt-1 ms-1">
                                                    <button class="btn btn-sm text-primary p-0">
                                                        <i class="bi bi-hand-thumbs-up me-1"></i> {{ rand(0, 5) }}
                                                    </button>
                                                    <button class="btn btn-sm text-primary p-0 ms-2">
                                                        Răspunde
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                    
                                    @if($commentsCount > 3)
                                        <div class="text-center mt-2 mb-3">
                                            <a href="#" class="text-decoration-none">Vezi toate comentariile ({{ $commentsCount }})</a>
                                        </div>
                                    @endif
                                @else
                                    <p class="text-muted">Nu există comentarii încă.</p>
                                @endif
                                
                                <!-- Add Comment -->
                                <div class="d-flex mt-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" alt="User" class="avatar avatar-sm me-2" style="width: 32px; height: 32px;">
                                    <div class="flex-grow-1">
                                        <input type="text" class="form-control" placeholder="Adaugă un comentariu...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
                
                <div class="d-flex justify-content-center">
                    <button class="btn btn-outline-primary">Încarcă mai multe</button>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Trending Topics Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Trending în companie</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @for($i = 1; $i <= 5; $i++)
                            <li class="list-group-item px-0">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3 text-primary">
                                        <span class="fw-bold">#{{ $i }}</span>
                                    </div>
                                    <div>
                                        <div class="fw-medium">#hashtag{{ $i }}</div>
                                        <div class="small text-muted">{{ rand(10, 100) }} postări</div>
                                    </div>
                                </div>
                            </li>
                        @endfor
                    </ul>
                </div>
            </div>
            
            <!-- Upcoming Events Card -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Evenimente Viitoare</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">Toate</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @for($i = 1; $i <= 3; $i++)
                            @php
                                $eventTypes = [
                                    ['Team Building', 'bi-people-fill', 'bg-primary'],
                                    ['Workshop', 'bi-easel2', 'bg-success'],
                                    ['Prezentare', 'bi-projector', 'bg-info'],
                                    ['Aniversare', 'bi-cake2', 'bg-warning']
                                ];
                                $event = $eventTypes[array_rand($eventTypes)];
                                $eventDate = now()->addDays(rand(1, 30));
                            @endphp
                            <div class="list-group-item p-3">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="text-muted small">{{ $eventDate->format('M') }}</span>
                                            <span class="h5 mb-0">{{ $eventDate->format('d') }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $event[0] }} - Eveniment {{ $i }}</h6>
                                        <div class="small mb-2">
                                            <i class="bi bi-clock me-1"></i> {{ rand(8, 18) }}:00 - {{ rand(10, 20) }}:00
                                        </div>
                                        <div>
                                            <span class="badge {{ $event[2] }}">
                                                <i class="bi {{ $event[1] }} me-1"></i> {{ $event[0] }}
                                            </span>
                                            <span class="badge bg-light text-dark">{{ rand(5, 30) }} participanți</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
            
            <!-- Connect with Colleagues Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Colegi de conectat</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @for($i = 1; $i <= 4; $i++)
                            @php
                                $colName = "Coleg $i";
                                $colDept = ["HR", "Marketing", "Dezvoltare", "Vânzări", "Suport"][rand(0, 4)];
                                $mutualConnections = rand(2, 15);
                            @endphp
                            <div class="list-group-item p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($colName) }}&background=random" class="avatar me-3">
                                        <div>
                                            <h6 class="mb-0">{{ $colName }}</h6>
                                            <div class="small text-muted">{{ $colDept }}</div>
                                            <div class="small text-muted">{{ $mutualConnections }} conexiuni comune</div>
                                        </div>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-person-plus me-1"></i> Conectează
                                    </button>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="#" class="text-decoration-none">Vezi mai mulți colegi</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal - Create Post -->
<div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPostModalLabel">Creează o postare</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex mb-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" alt="User" class="avatar me-3">
                    <div>
                        <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                        <select class="form-select form-select-sm mt-1">
                            <option value="public">Vizibil pentru toți</option>
                            <option value="team">Doar echipa mea</option>
                            <option value="private">Privat</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <textarea class="form-control" id="postContent" rows="5" placeholder="La ce vă gândiți?"></textarea>
                </div>
                <div class="mb-3">
                    <div class="input-group mb-3" id="uploadContainer" style="display: none;">
                        <input type="file" class="form-control" id="fileUpload">
                        <button class="btn btn-outline-secondary" type="button" id="cancelUpload">Anulează</button>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="button" class="btn btn-light" id="addPhoto">
                            <i class="bi bi-image me-1"></i> Foto/Video
                        </button>
                        <button type="button" class="btn btn-light" id="addDocument">
                            <i class="bi bi-file-earmark me-1"></i> Document
                        </button>
                        <button type="button" class="btn btn-light">
                            <i class="bi bi-link-45deg me-1"></i> Link
                        </button>
                        <button type="button" class="btn btn-light">
                            <i class="bi bi-tag me-1"></i> Tag persoane
                        </button>
                        <button type="button" class="btn btn-light">
                            <i class="bi bi-question-circle me-1"></i> Întrebare
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                <button type="button" class="btn btn-primary">Postează</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // File upload handlers
        document.getElementById('addPhoto').addEventListener('click', function() {
            document.getElementById('uploadContainer').style.display = 'flex';
            document.getElementById('fileUpload').click();
        });
        
        document.getElementById('addDocument').addEventListener('click', function() {
            document.getElementById('uploadContainer').style.display = 'flex';
            document.getElementById('fileUpload').click();
        });
        
        document.getElementById('cancelUpload').addEventListener('click', function() {
            document.getElementById('uploadContainer').style.display = 'none';
            document.getElementById('fileUpload').value = '';
        });
    });
</script>
@endpush
@endsection