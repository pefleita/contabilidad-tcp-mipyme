@extends('layouts.app')

@section('title', 'Nuevo Asiento Contable')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('contabilidad.index') }}" class="text-slate-500 hover:text-slate-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m0 0l7-7m-7 7l7 7" />
            </svg>
        </a>
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Nuevo Asiento Contable</h3>
            <p class="text-slate-500">Registrar asiento de partida doble</p>
        </div>
    </div>

    <form method="POST" action="{{ route('contabilidad.store') }}" id="asientoForm">
        @csrf

        <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Número de Asiento</label>
                    <input type="text" name="numero_asiento" value="{{ old('numero_asiento', $proximoNumero) }}"
                        class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900 @error('numero_asiento') border-red-300 @enderror">
                    @error('numero_asiento')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Fecha</label>
                    <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}"
                        class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900 @error('fecha') border-red-300 @enderror">
                    @error('fecha')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Descripción</label>
                <textarea name="descripcion" rows="2"
                    class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900 @error('descripcion') border-red-300 @enderror">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6 mt-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-slate-800">Partidas</h4>
                <button type="button" id="addPartida"
                    class="flex items-center gap-1 px-3 py-1.5 text-sm bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Agregar Partida
                </button>
            </div>

            @error('partidas')
            <p class="text-xs text-red-600 mb-3">{{ $message }}</p>
            @enderror

            <div id="partidasContainer" class="space-y-3">
                <div class="grid grid-cols-12 gap-3 text-xs font-medium text-slate-500 px-3 mb-1">
                    <div class="col-span-4">Cuenta Contable</div>
                    <div class="col-span-2">Debe (CUP)</div>
                    <div class="col-span-2">Haber (CUP)</div>
                    <div class="col-span-3">Descripción</div>
                    <div class="col-span-1"></div>
                </div>
            </div>

            <div class="border-t border-slate-200 mt-4 pt-4">
                <div class="grid grid-cols-12 gap-3 px-3">
                    <div class="col-span-4 text-sm font-medium text-slate-700">Totales</div>
                    <div class="col-span-2 text-sm font-mono font-semibold text-slate-800" id="totalDebe">0.00</div>
                    <div class="col-span-2 text-sm font-mono font-semibold text-slate-800" id="totalHaber">0.00</div>
                    <div class="col-span-3 text-sm" id="diferencia"></div>
                    <div class="col-span-1"></div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 mt-6">
            <a href="{{ route('contabilidad.index') }}" class="px-6 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">Cancelar</a>
            <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-slate-900 rounded-lg hover:bg-slate-800 transition-colors">Guardar Asiento</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
const cuentas = @json($cuentas);

function addPartidaRow(data = {}) {
    const container = document.getElementById('partidasContainer');
    const index = container.querySelectorAll('.partida-row').length;
    const div = document.createElement('div');
    div.className = 'partida-row grid grid-cols-12 gap-3';
    div.innerHTML = `
        <div class="col-span-4">
            <select name="partidas[${index}][cuenta_id]"
                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900">
                <option value="">Seleccionar cuenta</option>
                ${cuentas.map(c => `<option value="${c.id}" ${data.cuenta_id == c.id ? 'selected' : ''}>${c.codigo} - ${c.nombre}</option>`).join('')}
            </select>
        </div>
        <div class="col-span-2">
            <input type="number" step="0.01" min="0" name="partidas[${index}][debe]" value="${data.debe || ''}"
                class="partida-debe w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-right font-mono focus:ring-2 focus:ring-slate-900 focus:border-slate-900"
                placeholder="0.00">
        </div>
        <div class="col-span-2">
            <input type="number" step="0.01" min="0" name="partidas[${index}][haber]" value="${data.haber || ''}"
                class="partida-haber w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-right font-mono focus:ring-2 focus:ring-slate-900 focus:border-slate-900"
                placeholder="0.00">
        </div>
        <div class="col-span-3">
            <input type="text" name="partidas[${index}][descripcion]" value="${data.descripcion || ''}"
                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900"
                placeholder="Opcional">
        </div>
        <div class="col-span-1 flex items-center">
            <button type="button" onclick="this.closest('.partida-row').remove(); updateTotals();"
                class="text-red-400 hover:text-red-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    `;
    container.appendChild(div);

    div.querySelectorAll('.partida-debe, .partida-haber').forEach(input => {
        input.addEventListener('input', function() {
            const debe = parseFloat(this.closest('.partida-row').querySelector('.partida-debe').value) || 0;
            const haber = parseFloat(this.closest('.partida-row').querySelector('.partida-haber').value) || 0;
            if (this.classList.contains('partida-debe') && debe > 0) {
                this.closest('.partida-row').querySelector('.partida-haber').value = '';
            }
            if (this.classList.contains('partida-haber') && haber > 0) {
                this.closest('.partida-row').querySelector('.partida-debe').value = '';
            }
            updateTotals();
        });
    });

    updateTotals();
}

function updateTotals() {
    let totalDebe = 0;
    let totalHaber = 0;

    document.querySelectorAll('.partida-row').forEach(row => {
        totalDebe += parseFloat(row.querySelector('.partida-debe').value) || 0;
        totalHaber += parseFloat(row.querySelector('.partida-haber').value) || 0;
    });

    document.getElementById('totalDebe').textContent = totalDebe.toFixed(2);
    document.getElementById('totalHaber').textContent = totalHaber.toFixed(2);

    const diff = document.getElementById('diferencia');
    if (totalDebe === totalHaber && totalDebe > 0) {
        diff.innerHTML = '<span class="text-emerald-600 font-medium">✓ Cuadra</span>';
    } else if (totalDebe > 0 || totalHaber > 0) {
        diff.innerHTML = '<span class="text-red-600 font-medium">✗ No cuadra</span>';
    } else {
        diff.innerHTML = '';
    }
}

document.getElementById('addPartida').addEventListener('click', () => addPartidaRow());

if (document.querySelectorAll('.partida-row').length === 0) {
    addPartidaRow();
    addPartidaRow();
}
</script>
@endpush
