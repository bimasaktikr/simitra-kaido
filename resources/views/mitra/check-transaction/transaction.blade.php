@php($title = 'Cek Mitra')
@include('mitra.check-transaction.header')
<main>
    <div class="container mt-5 mt-md-4">
        <div class="row mb-2">
            <!-- Single Column -->
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row" style="align-items: center; margin: 2rem 0;">
                            <!-- Mitra Info -->
                            <div class="col-md-6 col-12" style="display: flex; flex-direction: column; justify-content: center; padding: 1.5rem;">
                                <div class="d-flex justify-content-center" style="margin-bottom: 1rem;">
                                    @if($mitra->photo)
                                        <img src="{{ asset('storage/' . $mitra->photo) }}" 
                                             alt="Foto {{ $mitra->name }}" 
                                             class="border shadow-sm" 
                                             style="max-width: 130px;">
                                    @else
                                        <div class="border shadow-sm" 
                                             style="width: 130px; height: 130px; display: flex; align-items: center; justify-content: center; background: #ffffff; border-radius: 8px;">
                                            <span style="font-size: 1.2rem; color: #9AA5B1; font-weight: 500;">FOTO</span>
                                        </div>
                                    @endif
                                </div>
                                <div style="text-align: center; font-size: 1.1rem; font-weight: 500; margin-bottom: 0.5rem;">
                                    {{ $mitra->name ?? '—' }}
                                </div>
                                <div style="text-align: center; font-size: 1.2rem; margin-bottom: 1rem;">
                                    <span style="color: #ffc107;">★★★★</span><span style="display: inline-block; position: relative; color: #e4e5e9;">★<span style="position: absolute; left: 0; top: 0; overflow: hidden; width: 50%; color: #ffc107;">★</span></span>
                                    <span style="font-size: 0.9rem; color: #6c757d; margin-left: 0.5rem;">(4.5)</span>
                                </div>
                            </div>
                            
                            <!-- Survey Info -->
                            <div class="col-md-6 col-12" style="display: flex; flex-direction: column; justify-content: center; padding: 1.5rem;">
                                <table class="table table-bordered table-striped mb-4">
                                    <tr>
                                        <td colspan="2">
                                            <div style="word-wrap: break-word; overflow-wrap: break-word; max-width: 100%; text-align: center; font-weight: 600; font-size: 1.1rem; padding: 0.5rem 0;">
                                                {{ $survey->masterSurvey->name ?? $survey->name ?? 'N/A' }} 
                                                @if(isset($survey->masterSurvey->code) || isset($survey->code))
                                                [{{ $survey->masterSurvey->code ?? $survey->code ?? '' }}]
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="label-with-colon"><span>Periode</span><span>:</span></span></td>
                                        <td>{{ $survey->year ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><span class="label-with-colon"><span>Tim</span><span>:</span></span></td>
                                        <td>{{ $survey->team->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><span class="label-with-colon"><span>Status</span><span>:</span></span></td>
                                        <td>{{ ucfirst($survey->status ?? 'N/A') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal -->
                    <div id="modal-input-nilai" 
                         data-bs-backdrop="dynamic" 
                         data-bs-keyboard="true" 
                         tabindex="-1" 
                         class="modal fade" 
                         style="display: none;" 
                         aria-hidden="true">
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@include('mitra.check-transaction.footer')
