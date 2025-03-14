@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Setări Sistem</h2>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">
                    Navigare Setări
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('settings.index') }}" class="list-group-item list-group-item-action active">
                        <i class="bi bi-gear me-2"></i> Generale
                    </a>
                    <a href="{{ route('settings.users') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-people me-2"></i> Utilizatori
                    </a>
                    <a href="{{ route('settings.roles') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-person-badge me-2"></i> Roluri și Permisiuni
                    </a>
                    <a href="{{ route('settings.backup') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-cloud-arrow-up me-2"></i> Backup & Restaurare
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Setări Generale</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="companyName" class="form-label">Numele Companiei</label>
                            <input type="text" class="form-control" id="companyName" name="company_name" value="Compania Dvs.">
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="companyEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="companyEmail" name="company_email" value="contact@exemplu.com">
                            </div>
                            <div class="col-md-6">
                                <label for="companyPhone" class="form-label">Telefon</label>
                                <input type="text" class="form-control" id="companyPhone" name="company_phone" value="+40 123 456 789">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="companyAddress" class="form-label">Adresă</label>
                            <textarea class="form-control" id="companyAddress" name="company_address" rows="2">Strada Exemplu, Nr. 123, București</textarea>
                        </div>
                        
                        <hr>
                        
                        <h6 class="mb-3">Setări Sistem</h6>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="defaultLanguage" class="form-label">Limba implicită</label>
                                <select class="form-select" id="defaultLanguage" name="default_language">
                                    <option value="ro" selected>Română</option>
                                    <option value="en">English</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="timezone" class="form-label">Fus orar</label>
                                <select class="form-select" id="timezone" name="timezone">
                                    <option value="Europe/Bucharest" selected>Europe/Bucharest</option>
                                    <option value="Europe/London">Europe/London</option>
                                    <option value="America/New_York">America/New_York</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="dateFormat" class="form-label">Format dată</label>
                                <select class="form-select" id="dateFormat" name="date_format">
                                    <option value="d/m/Y" selected>DD/MM/YYYY</option>
                                    <option value="m/d/Y">MM/DD/YYYY</option>
                                    <option value="Y-m-d">YYYY-MM-DD</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="timeFormat" class="form-label">Format oră</label>
                                <select class="form-select" id="timeFormat" name="time_format">
                                    <option value="H:i" selected>24 ore (14:30)</option>
                                    <option value="h:i A">12 ore (02:30 PM)</option>
                                </select>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h6 class="mb-3">Setări Email</h6>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="enableEmailNotifications" name="enable_email_notifications" checked>
                                <label class="form-check-label" for="enableEmailNotifications">Activează notificările prin email</label>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="mailDriver" class="form-label">Driver mail</label>
                                <select class="form-select" id="mailDriver" name="mail_driver">
                                    <option value="smtp" selected>SMTP</option>
                                    <option value="sendmail">Sendmail</option>
                                    <option value="mailgun">Mailgun</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="mailFromAddress" class="form-label">Adresă expeditor</label>
                                <input type="email" class="form-control" id="mailFromAddress" name="mail_from_address" value="no-reply@exemplu.com">
                            </div>
                        </div>
                        
                        <div class="mb-3 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Salvează Setările
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection