@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Creează evaluare de performanță</div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('performance.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="user_id" class="form-label">Angajat</label>
                                <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                    <option value="">Selectează angajat</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('user_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="review_date" class="form-label">Data evaluării</label>
                                <input type="date" class="form-control @error('review_date') is-invalid @enderror" id="review_date" name="review_date" value="{{ old('review_date', now()->toDateString()) }}" required>
                                @error('review_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Perioada de început</label>
                                <div class="row">
                                    <div class="col">
                                        <select class="form-select @error('period_start_month') is-invalid @enderror" name="period_start_month" required>
                                            <option value="">Luna</option>
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ old('period_start_month') == $i ? 'selected' : '' }}>
                                                    {{ date("F", mktime(0, 0, 0, $i, 1)) }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('period_start_month')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <select class="form-select @error('period_start_year') is-invalid @enderror" name="period_start_year" required>
                                            <option value="">Anul</option>
                                            @for($i = now()->year - 5; $i <= now()->year; $i++)
                                                <option value="{{ $i }}" {{ old('period_start_year', now()->year - 1) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                        @error('period_start_year')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Perioada de sfârșit</label>
                                <div class="row">
                                    <div class="col">
                                        <select class="form-select @error('period_end_month') is-invalid @enderror" name="period_end_month" required>
                                            <option value="">Luna</option>
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ old('period_end_month', now()->month) == $i ? 'selected' : '' }}>
                                                    {{ date("F", mktime(0, 0, 0, $i, 1)) }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('period_end_month')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <select class="form-select @error('period_end_year') is-invalid @enderror" name="period_end_year" required>
                                            <option value="">Anul</option>
                                            @for($i = now()->year - 5; $i <= now()->year; $i++)
                                                <option value="{{ $i }}" {{ old('period_end_year', now()->year) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                        @error('period_end_year')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="accomplishments" class="form-label">Realizări</label>
                            <textarea class="form-control @error('accomplishments') is-invalid @enderror" id="accomplishments" name="accomplishments" rows="3">{{ old('accomplishments') }}</textarea>
                            @error('accomplishments')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="areas_for_improvement" class="form-label">Domenii pentru îmbunătățire</label>
                            <textarea class="form-control @error('areas_for_improvement') is-invalid @enderror" id="areas_for_improvement" name="areas_for_improvement" rows="3">{{ old('areas_for_improvement') }}</textarea>
                            @error('areas_for_improvement')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="goals" class="form-label">Obiective pentru următoarea perioadă</label>
                            <textarea class="form-control @error('goals') is-invalid @enderror" id="goals" name="goals" rows="3">{{ old('goals') }}</textarea>
                            @error('goals')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <h4 class="mt-4 mb-3">Evaluarea competențelor</h4>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="technical_skills_rating" class="form-label">Competențe tehnice (1-5)</label>
                                <select class="form-select @error('technical_skills_rating') is-invalid @enderror" id="technical_skills_rating" name="technical_skills_rating" required>
                                    <option value="">Selectează rating</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('technical_skills_rating') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('technical_skills_rating')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="technical_skills_comments" class="form-label">Comentarii competențe tehnice</label>
                                <textarea class="form-control @error('technical_skills_comments') is-invalid @enderror" id="technical_skills_comments" name="technical_skills_comments">{{ old('technical_skills_comments') }}</textarea>
                                @error('technical_skills_comments')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="communication_rating" class="form-label">Comunicare (1-5)</label>
                                <select class="form-select @error('communication_rating') is-invalid @enderror" id="communication_rating" name="communication_rating" required>
                                    <option value="">Selectează rating</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('communication_rating') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('communication_rating')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="communication_comments" class="form-label">Comentarii comunicare</label>
                                <textarea class="form-control @error('communication_comments') is-invalid @enderror" id="communication_comments" name="communication_comments">{{ old('communication_comments') }}</textarea>
                                @error('communication_comments')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="teamwork_rating" class="form-label">Lucru în echipă (1-5)</label>
                                <select class="form-select @error('teamwork_rating') is-invalid @enderror" id="teamwork_rating" name="teamwork_rating" required>
                                    <option value="">Selectează rating</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('teamwork_rating') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('teamwork_rating')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="teamwork_comments" class="form-label">Comentarii lucru în echipă</label>
                                <textarea class="form-control @error('teamwork_comments') is-invalid @enderror" id="teamwork_comments" name="teamwork_comments">{{ old('teamwork_comments') }}</textarea>
                                @error('teamwork_comments')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="initiative_rating" class="form-label">Inițiativă (1-5)</label>
                                <select class="form-select @error('initiative_rating') is-invalid @enderror" id="initiative_rating" name="initiative_rating" required>
                                    <option value="">Selectează rating</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('initiative_rating') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('initiative_rating')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="initiative_comments" class="form-label">Comentarii inițiativă</label>
                                <textarea class="form-control @error('initiative_comments') is-invalid @enderror" id="initiative_comments" name="initiative_comments">{{ old('initiative_comments') }}</textarea>
                                @error('initiative_comments')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="reliability_rating" class="form-label">Fiabilitate (1-5)</label>
                                <select class="form-select @error('reliability_rating') is-invalid @enderror" id="reliability_rating" name="reliability_rating" required>
                                    <option value="">Selectează rating</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('reliability_rating') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('reliability_rating')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="reliability_comments" class="form-label">Comentarii fiabilitate</label>
                                <textarea class="form-control @error('reliability_comments') is-invalid @enderror" id="reliability_comments" name="reliability_comments">{{ old('reliability_comments') }}</textarea>
                                @error('reliability_comments')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <h4 class="mt-4 mb-3">Evaluare generală</h4>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="overall_rating" class="form-label">Rating general (1-5)</label>
                                <select class="form-select @error('overall_rating') is-invalid @enderror" id="overall_rating" name="overall_rating" required>
                                    <option value="">Selectează rating</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('overall_rating') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('overall_rating')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="overall_comments" class="form-label">Comentarii generale</label>
                                <textarea class="form-control @error('overall_comments') is-invalid @enderror" id="overall_comments" name="overall_comments">{{ old('overall_comments') }}</textarea>
                                @error('overall_comments')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="submitted" {{ old('status') == 'submitted' ? 'selected' : '' }}>Trimite direct</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">
                                    Salvează evaluarea
                                </button>
                                <a href="{{ route('performance.index') }}" class="btn btn-secondary">Anulează</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection