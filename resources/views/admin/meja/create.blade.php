@extends('layouts.admin')

@section('content')

<style>
    .form-page-wrapper { max-width: 600px; margin: 0 auto; }

    .form-back-link {
        display: inline-flex; align-items: center; gap: 0.4rem;
        color: rgba(13, 27, 42, 0.55); font-size: 0.82rem;
        text-decoration: none; margin-bottom: 1.25rem;
        transition: color 0.2s ease;
    }
    .form-back-link:hover { color: #c9a961; }

    .form-card {
        background: #ffffff;
        border: 1px solid rgba(13, 27, 42, 0.06);
        border-radius: 14px; overflow: hidden;
    }

    .form-card-header { padding: 1.6rem 2rem 1.4rem; border-bottom: 1px solid rgba(13, 27, 42, 0.06); }
    .form-card-header h2 {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.5rem; font-weight: 500; color: #0d1b2a;
        margin: 0 0 0.3rem; letter-spacing: -0.01em;
    }
    .form-card-header p { color: rgba(13, 27, 42, 0.55); font-size: 0.85rem; margin: 0; }

    .form-section { padding: 1.6rem 2rem; }
    .form-section-label {
        font-size: 0.65rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.22em;
        color: #c9a961; margin-bottom: 1.1rem;
    }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 575px) { .form-row { grid-template-columns: 1fr; } }

    .form-field { margin-bottom: 1.1rem; }
    .form-field:last-child { margin-bottom: 0; }
    .form-field label {
        display: block; font-size: 0.85rem; font-weight: 500;
        color: #0d1b2a; margin-bottom: 0.5rem;
    }
    .form-field .field-hint {
        font-size: 0.78rem; color: rgba(13, 27, 42, 0.5); margin-top: 0.4rem;
    }

    .form-input, .form-select-custom {
        width: 100%; padding: 0.72rem 0.95rem;
        font-family: 'Inter', sans-serif; font-size: 0.92rem;
        color: #0d1b2a; background: #ffffff;
        border: 1px solid rgba(13, 27, 42, 0.12);
        border-radius: 8px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        outline: none;
    }
    .form-input::placeholder { color: rgba(13, 27, 42, 0.35); }
    .form-input:focus, .form-select-custom:focus {
        border-color: #c9a961;
        box-shadow: 0 0 0 3px rgba(201, 169, 97, 0.12);
    }
    .form-select-custom {
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%230d1b2a' d='M6 8.5L1.5 4h9z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 1rem center;
        padding-right: 2.5rem; cursor: pointer;
    }

    /* Number stepper */
    .number-stepper {
        display: flex; align-items: stretch;
        border: 1px solid rgba(13, 27, 42, 0.12);
        border-radius: 8px; overflow: hidden;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .number-stepper:focus-within {
        border-color: #c9a961;
        box-shadow: 0 0 0 3px rgba(201, 169, 97, 0.12);
    }
    .stepper-btn {
        background: rgba(13, 27, 42, 0.03);
        border: none; cursor: pointer;
        width: 42px; flex-shrink: 0;
        color: #0d1b2a; font-size: 1rem;
        display: flex; align-items: center; justify-content: center;
        transition: background 0.15s ease;
    }
    .stepper-btn:hover { background: rgba(201, 169, 97, 0.15); color: #c9a961; }
    .stepper-btn:first-child { border-right: 1px solid rgba(13, 27, 42, 0.08); }
    .stepper-btn:last-child { border-left: 1px solid rgba(13, 27, 42, 0.08); }
    .number-stepper input {
        flex: 1; border: none; outline: none;
        text-align: center; font-size: 1rem; font-weight: 500;
        color: #0d1b2a; background: transparent;
        font-family: 'Inter', sans-serif;
        -moz-appearance: textfield;
    }
    .number-stepper input::-webkit-outer-spin-button,
    .number-stepper input::-webkit-inner-spin-button {
        -webkit-appearance: none; margin: 0;
    }

    .form-actions {
        padding: 1.25rem 2rem;
        background: rgba(13, 27, 42, 0.015);
        border-top: 1px solid rgba(13, 27, 42, 0.06);
        display: flex; justify-content: flex-end; gap: 0.75rem;
    }
    .btn-form {
        font-size: 0.85rem; font-weight: 500;
        padding: 0.7rem 1.5rem; border-radius: 8px;
        border: 1px solid transparent; cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 0.45rem;
        transition: all 0.25s ease;
    }
    .btn-form-cancel {
        background: #ffffff; border-color: rgba(13, 27, 42, 0.15); color: #0d1b2a;
    }
    .btn-form-cancel:hover { background: rgba(13, 27, 42, 0.04); }
    .btn-form-save { background: #0d1b2a; color: #f4ede4; }
    .btn-form-save:hover { background: #c9a961; color: #0d1b2a; }

    .form-input.is-invalid, .form-select-custom.is-invalid, .number-stepper.is-invalid {
        border-color: #c0392b;
    }
    .invalid-feedback { display: block; color: #c0392b; font-size: 0.78rem; margin-top: 0.4rem; }
</style>

<div class="form-page-wrapper">

    <a href="{{ route('meja.index') }}" class="form-back-link">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Meja
    </a>

    <form action="{{ route('meja.store') }}" method="POST">
        @csrf

        <div class="form-card">

            <div class="form-card-header">
                <h2>Tambah Meja Baru</h2>
                <p>Daftarkan meja baru ke dalam sistem restoran</p>
            </div>

            <div class="form-section">
                <div class="form-section-label">Detail Meja</div>

                <div class="form-row">
                    <div class="form-field">
                        <label for="nomor_meja">Nomor Meja</label>
                        <div class="number-stepper">
                            <button type="button" class="stepper-btn" onclick="stepValue('nomor_meja', -1)">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" id="nomor_meja" name="nomor_meja" min="1"
                                   value="{{ old('nomor_meja', 1) }}" required>
                            <button type="button" class="stepper-btn" onclick="stepValue('nomor_meja', 1)">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        @error('nomor_meja') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-field">
                        <label for="kapasitas">Kapasitas <small style="color:rgba(13,27,42,0.45); font-weight:400;">(orang)</small></label>
                        <div class="number-stepper">
                            <button type="button" class="stepper-btn" onclick="stepValue('kapasitas', -1)">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" id="kapasitas" name="kapasitas" min="1"
                                   value="{{ old('kapasitas', 4) }}" required>
                            <button type="button" class="stepper-btn" onclick="stepValue('kapasitas', 1)">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        @error('kapasitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-field">
                    <label for="status">Status Awal</label>
                    <select id="status" name="status"
                            class="form-select-custom @error('status') is-invalid @enderror" required>
                        <option value="kosong" {{ old('status', 'kosong') == 'kosong' ? 'selected' : '' }}>Tersedia</option>
                        <option value="terisi" {{ old('status') == 'terisi' ? 'selected' : '' }}>Terisi</option>
                    </select>
                    <p class="field-hint">Status default untuk meja baru biasanya "Tersedia".</p>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('meja.index') }}" class="btn-form btn-form-cancel">Batal</a>
                <button type="submit" class="btn-form btn-form-save">
                    <i class="bi bi-check2"></i> Simpan Meja
                </button>
            </div>

        </div>
    </form>
</div>

<script>
    function stepValue(id, delta) {
        const input = document.getElementById(id);
        const current = parseInt(input.value) || 0;
        const min = parseInt(input.min) || 0;
        const next = current + delta;
        input.value = next < min ? min : next;
    }
</script>

@endsection