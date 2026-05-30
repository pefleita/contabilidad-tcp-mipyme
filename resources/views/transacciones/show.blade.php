@extends('layouts.app')

@section('title', 'Detalle de Transacción')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('transacciones.index') }}" class="p-2 text-slate-400 hover:text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h3 class="text-2xl font-bold text-slate-800">Detalle de Transacción</h3>
                <p class="text-sm text-slate-500">{{ $transaccion->fecha->format('d/m/Y') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('transacciones.edit', $transaccion) }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Editar
            </a>
            <form method="POST" action="{{ route('transacciones.destroy', $transaccion) }}" class="inline" onsubmit="return confirm('¿Eliminar esta transacción?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Eliminar
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <p class="text-sm text-emerald-800">{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <p class="text-sm text-slate-500">Monto</p>
            <p class="text-2xl font-bold {{ $transaccion->esIngreso() ? 'text-emerald-600' : 'text-rose-600' }}">
                {{ $transaccion->esIngreso() ? '+' : '-' }}${{ number_format($transaccion->monto, 2) }}
            </p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <p class="text-sm text-slate-500">Tipo</p>
            <p class="text-2xl font-bold text-slate-800 capitalize">{{ $transaccion->tipo }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <p class="text-sm text-slate-500">Estado</p>
            <p>
                @if($transaccion->estaConfirmado())
                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-sm font-medium">Confirmado</span>
                @elseif($transaccion->estaAnulado())
                <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-lg text-sm font-medium">Anulado</span>
                @else
                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-lg text-sm font-medium">Pendiente</span>
                @endif
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h4 class="text-lg font-semibold text-slate-800 mb-4">Información</h4>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm text-slate-500">Categoría</dt>
                    <dd class="text-sm text-slate-800">{{ $transaccion->categoria->nombre }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-slate-500">Método de Pago</dt>
                    <dd class="text-sm text-slate-800 capitalize">{{ $transaccion->metodo_pago }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-slate-500">Fecha</dt>
                    <dd class="text-sm text-slate-800">{{ $transaccion->fecha->format('d/m/Y') }}</dd>
                </div>
                @if($transaccion->descripcion)
                <div class="pt-3 border-t border-slate-100">
                    <dt class="text-sm text-slate-500 mb-1">Descripción</dt>
                    <dd class="text-sm text-slate-700">{{ $transaccion->descripcion }}</dd>
                </div>
                @endif
            </dl>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-slate-800">Comprobantes</h4>
                <span class="text-sm text-slate-500">{{ $comprobantes->count() }} archivos</span>
            </div>

            <form method="POST" action="{{ route('transacciones.upload-comprobante', $transaccion) }}" enctype="multipart/form-data" class="mb-4 p-4 border-2 border-dashed border-slate-200 rounded-lg hover:border-slate-300 transition-colors" id="upload-form">
                @csrf
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="text-sm text-slate-500 mb-2">Arrastra un archivo o haz clic para subir</p>
                    <p class="text-xs text-slate-400 mb-3">JPG, PNG o PDF. Máximo 5MB.</p>
                    <input type="file" name="archivo" accept=".jpg,.jpeg,.png,.pdf" class="hidden" id="archivo-input">
                    <div class="mb-3">
                        <select name="tipo" class="px-3 py-2 border border-slate-200 rounded-lg text-sm" required>
                            <option value="factura">Factura</option>
                            <option value="recibo">Recibo</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    <button type="button" id="upload-btn" class="px-4 py-2 bg-slate-900 text-white rounded-lg text-sm hover:bg-slate-800 transition-colors">
                        Subir Archivo
                    </button>
                </div>
            </form>

            @if($comprobantes->count() > 0)
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @foreach($comprobantes as $comprobante)
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-slate-200 rounded flex items-center justify-center">
                            @if(in_array(pathinfo($comprobante->archivo, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-700">{{ $comprobante->nombre_original }}</p>
                            <p class="text-xs text-slate-500 capitalize">{{ $comprobante->tipo }} · {{ number_format($comprobante->tamano / 1024, 1) }} KB</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ Storage::url($comprobante->archivo) }}" target="_blank" class="p-1 text-slate-400 hover:text-slate-600" title="Ver">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('transacciones.delete-comprobante', [$transaccion, $comprobante]) }}" class="inline" onsubmit="return confirm('¿Eliminar este comprobante?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1 text-slate-400 hover:text-red-600" title="Eliminar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-6 text-slate-500">
                <p class="text-sm">No hay comprobantes adjuntos</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadBtn = document.getElementById('upload-btn');
    const archivoInput = document.getElementById('archivo-input');
    const uploadForm = document.getElementById('upload-form');

    uploadBtn.addEventListener('click', function() {
        archivoInput.click();
    });

    archivoInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            uploadForm.submit();
        }
    });
});
</script>
@endsection
