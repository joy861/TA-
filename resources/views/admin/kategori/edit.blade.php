@extends('layouts.admin')

@section('content')

<style>
    .form-page-wrapper { max-width: 600px; margin: 0 auto; }

    .form-back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: rgba(13, 27, 42, 0.55);
        font-size: 0.82rem;
        text-decoration: none;
        margin-bottom: 1.25rem;
        transition: color 0.2s ease;
    }
    .form-back-link:hover { color: #c9a961; }

    .form-card {
        background: #ffffff;
        border: 1px solid rgba(13, 27, 42, 0.06);
        border-radius: 14px;
        overflow: hidden;
    }

    .form-card-header {
        padding: 1.6rem 2rem 1.4rem;
        border-bottom: 1px solid rgba(13, 27, 42, 0.06);
        display: flex;
        gap: 1.1rem;
        align-items: center;
    }

    .edit-kategori-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: rgba(201, 169, 97, 0.12);
        color: #c9a961;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .form-card-header-info { flex: 1; min-width: 0; }

    .form-card-header h2 {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.4rem;
        font-weight: 500;
        color: #0d1b2a;
        margin: 0 0 0.3rem;
        letter-spacing: -0.01em;
    }

    .form-card-header-meta {
        color: rgba(13, 27, 42, 0.55);
        font-size: 0.85rem;
    }

    .form-card-header-meta strong {
        color: #0d1b2a;
        font-weight: 500;
        text-transform: capitalize;
    }

    .form-section { padding: 1.6rem 2rem; }

    .form-section-label {
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.22em;
        color: #c9a961;
        margin-bottom: 1.1rem;
    }

    .form-field { margin-bottom: 1.1rem; }
    .form-field:last-child { margin-bottom: 0; }

    .form-field label {
        display: block;
        font-size: 0.85rem;
        font-weight: 500;
        color: #0d1b2a;
        margin-bottom: 0.5rem;
    }

    .form-field .field-hint {
        font-size: 0.78rem;
        color: rgba(13, 27, 42, 0.5);
        margin-top: 0.4rem;
    }

    .form-input {
        width: 100%;
        padding: 0.72rem 0.95rem;
        font-family: 'Inter', sans-serif;
        font-size: 0.92rem;
        color: #0d1b2a;
        background: #ffffff;
        border: 1px solid rgba(13, 27, 42, 0.12);
        border-radius: 8px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        outline: none;
    }

    .form-input::placeholder { color: rgba(13, 27, 42, 0.35); }

    .form-input:focus {
        border-color: #c9a961;
        box-shadow: 0 0 0 3px rgba(201, 169, 97, 0.12);
    }

    .form-actions {
        padding: 1.25rem 2rem;
        background: rgba(13, 27, 42, 0.015);
        border-top: 1px solid rgba(13, 27, 42, 0.06);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .btn-form {
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.7rem 1.5rem;
        border-radius: 8px;
        border: 1px solid transparent;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        transition: all 0.25s ease;
    }

    .btn-form-cancel {
        background: #ffffff;
        border-color: rgba(13, 27, 42, 0.15);
        color: #0d1b2a;
    }
    .btn-form-cancel:hover { background: rgba(13, 27, 42, 0.04); }

    .btn-form-save { background: #0d1b2a; color: #f4ede4; }
    .btn-form-save:hover { background: #c9a961; color: #0d1b2a; }

    .form-input.is-invalid { border-color: #c0392b; }
    .invalid-feedback {
        display: block;
        color: #c0392b;
        font-size: 0.78rem;
        margin-top: 0.4rem;
    }
</style>

<div class="form-page-wrapper">

    <a href="{{ route('kategori.index') }}" class="form-back-link">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kategori
    </a>

    <form action="{{ route('kategori.update', $kategori->id_kategori) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-card">

            <div class="form-card-header">
                <div class="edit-kategori-icon">
                    <i class="bi bi-folder-fill"></i>
                </div>
                <div class="form-card-header-info">
                    <h2>Edit Kategori</h2>
                    <div class="form-card-header-meta">
                        Mengubah kategori <strong>{{ $kategori->nama_kategori }}</strong>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-label">Detail Kategori</div>

                <div class="form-field">
                    <label for="nama_kategori">Nama Kategori</label>
                    <input type="text" id="nama_kategori" name="nama_kategori"
                           class="form-input @error('nama_kategori') is-invalid @enderror"
                           value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required autofocus>
                    <p class="field-hint">Mengubah nama kategori akan otomatis ter-update di semua menu yang terkait.</p>
                    @error('nama_kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('kategori.index') }}" class="btn-form btn-form-cancel">Batal</a>
                <button type="submit" class="btn-form btn-form-save">
                    <i class="bi bi-check2"></i> Simpan Perubahan
                </button>
            </div>

        </div>
    </form>
</div>

@endsection