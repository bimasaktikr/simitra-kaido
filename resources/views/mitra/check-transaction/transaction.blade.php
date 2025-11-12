@php($title = 'Cek Mitra')
@include('mitra.check-transaction.header')
<main>
    <div class="container mt-5 mt-md-4">
        <div class="row mb-2">
            <!-- Single Column -->
            <div class="col-12">
                <div class="card shadow-sm" style="border: none; border-radius: 16px; overflow: hidden;">
                    <div class="card-body" style="padding: 2rem;">
                        <div class="row" style="align-items: center; margin: 0;">
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
                                    @for($i = 0; $i < $fullStars; $i++)
                                        <span style="color: #ffc107;">★</span>
                                    @endfor
                                    @if($halfStar)
                                        <span style="display: inline-block; position: relative; color: #e4e5e9;">★<span style="position: absolute; left: 0; top: 0; overflow: hidden; width: 50%; color: #ffc107;">★</span></span>
                                    @endif
                                    @for($i = 0; $i < $emptyStars; $i++)
                                        <span style="color: #e4e5e9;">★</span>
                                    @endfor
                                    <span style="font-size: 0.9rem; color: #6c757d; margin-left: 0.5rem;">({{ number_format($avgRating, 1) }})</span>
                                </div>
                                <div style="text-align: center; margin-bottom: 1rem;">
                                    <a href="#" id="beri-ulasan-link" style="display: inline-block; color: #0d6efd; background: #e7f1ff; text-decoration: none; font-size: 0.9rem; cursor: pointer; padding: 0.5rem 1.5rem; border-radius: 20px; transition: all 0.2s; font-weight: 500;">Beri Ulasan</a>
                                </div>
                            </div>
                            
                            <!-- Survey Info -->
                            <div class="col-md-6 col-12 survey-col" style="display: flex; flex-direction: column; justify-content: center; padding: 1.5rem;">
                                <div class="survey-box" style="background: #f8f9fa; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                    <div style="text-align: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #e9ecef;">
                                        <h5 style="font-weight: 600; font-size: 1.1rem; color: #2c3e50; margin: 0; line-height: 1.4;">
                                            {{ $survey->masterSurvey->name ?? $survey->name ?? 'N/A' }}
                                        </h5>
                                    </div>
                                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                            <div class="survey-row">
                                                <span style="color: #6c757d; font-weight: 500; font-size: 0.95rem;">Periode</span>
                                                <span class="survey-value" style="font-size:0.95rem;">{{ $survey->year ?? 'N/A' }}</span>
                                            </div>
                                            <div class="survey-row">
                                                <span style="color: #6c757d; font-weight: 500; font-size: 0.95rem;">Tim</span>
                                                <span class="survey-value" style="font-size:0.95rem;">{{ $survey->team->name ?? 'N/A' }}</span>
                                            </div>
                                            <div class="survey-row">
                                                <span style="color: #6c757d; font-weight: 500; font-size: 0.95rem;">Status</span>
                                                <span class="survey-value" style="display: inline-block; padding: 0.25rem 0.75rem; background: {{ $survey->status === 'active' ? '#28a745' : '#6c757d' }}; color: white; border-radius: 12px; font-size: 0.85rem;">
                                                    {{ ucfirst($survey->status ?? 'N/A') }}
                                                </span>
                                            </div>
                                    </div>
                                </div>
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

                    <!-- Modal Success -->
                    <div id="modal-success" 
                         class="modal fade" 
                         style="display: none;" 
                         aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
                            <div class="modal-content" style="border-radius: 10px; text-align: center;">
                                <div class="modal-body" style="padding: 2rem 1.5rem;">
                                    <div style="width: 80px; height: 80px; background: #0d6efd; border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center;">
                                        <svg style="width: 50px; height: 50px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <h5 style="margin: 0 0 1rem 0; font-size: 1.5rem; font-weight: 600; color: #5a5a5a;">Sukses</h5>
                                    <p style="margin: 0 0 1.5rem 0; color: #888; font-size: 0.95rem;">{{ session('success') }}</p>
                                    <button type="button" class="btn-success-ok" data-dismiss-success="modal" style="background: #0d6efd; color: white; border: none; padding: 0.75rem 3rem; border-radius: 5px; font-size: 1rem; font-weight: 500; cursor: pointer; width: 100%; max-width: 250px;">OK</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Review -->
                    <div id="modal-review" 
                         data-bs-backdrop="static" 
                         data-bs-keyboard="false" 
                         tabindex="-1" 
                         class="modal fade" 
                         style="display: none;" 
                         aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
                            <div class="modal-content" style="border-radius: 10px;">
                                <div class="modal-header" style="border-bottom: none; padding: 1.5rem 1.5rem 0.5rem;">
                                    <h5 class="modal-title" style="font-size: 1.5rem; font-weight: 600; color: #5a5a5a; margin: 0 auto;">Beri Ulasan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; right: 1rem; top: 1rem;"></button>
                                </div>
                                <div class="modal-body" style="padding: 1rem 2rem 1.5rem;">
                                    <form id="review-form" action="{{ route('review.store') }}" method="POST" novalidate>
                                        @csrf
                                        <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                                        <div class="mb-3" style="text-align: center;">
                                            <label for="rating" class="form-label" style="font-size: 1rem; font-weight: 500; color: #5a5a5a; margin-bottom: 1rem; display: block;">Rating</label>
                                            <div id="rating-stars" class="d-flex justify-content-center" style="gap: 0.3rem; font-size: 2.5rem; margin-bottom: 0.5rem;">
                                                <span class="star" data-value="1">★</span>
                                                <span class="star" data-value="2">★</span>
                                                <span class="star" data-value="3">★</span>
                                                <span class="star" data-value="4">★</span>
                                                <span class="star" data-value="5">★</span>
                                            </div>
                                            <input type="hidden" name="rating" id="rating-input" required>
                                        </div>
                                        <div class="mb-4">
                                            <label for="email" class="form-label" style="font-size: 1rem; font-weight: 500; color: #5a5a5a; margin-bottom: 0.5rem;">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email Anda..." style="border-radius: 8px; border: 1px solid #ddd; font-size: 0.95rem; font-family: inherit; padding: 0.375rem 0.75rem; background: #fff; color: #212529;" required>
                                        </div>
                                        <div class="mb-4">
                                            <label for="comment" class="form-label" style="font-size: 1rem; font-weight: 500; color: #5a5a5a; margin-bottom: 0.5rem;">Komentar</label>
                                            <textarea class="form-control" id="comment" name="comment" rows="4" placeholder="Masukkan komentar Anda..." style="border-radius: 8px; border: 1px solid #ddd; font-size: 0.95rem; font-family: inherit; padding: 0.375rem 0.75rem; background: #fff; color: #212529;"></textarea>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer" style="border-top: none; padding: 0 2rem 1.5rem; justify-content: center; gap: 1rem;">
                                    <button type="button" class="btn-cancel" data-bs-dismiss="modal" style="color: #888; background: #f5f5f5; border: none; padding: 0.75rem 2rem; border-radius: 5px; font-size: 1rem; font-weight: 500; cursor: pointer; min-width: 120px;">Batal</button>
                                    <button type="submit" form="review-form" class="btn btn-primary" style="background: #0d6efd; color: white; border: none; padding: 0.75rem 2rem; border-radius: 5px; font-size: 1rem; font-weight: 500; cursor: pointer; min-width: 120px;">Kirim Ulasan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('#rating-stars .star');
    const ratingInput = document.getElementById('rating-input');
    const modalReview = document.getElementById('modal-review');
    const modalSuccess = document.getElementById('modal-success');
    const beriUlasanLink = document.getElementById('beri-ulasan-link');

    // Show success modal if session has success message
    @if(session('success'))
    modalSuccess.style.display = 'block';
    modalSuccess.classList.add('show');
    modalSuccess.style.opacity = '1';
    @endif

    // Close success modal on button click
    const closeSuccessButtons = document.querySelectorAll('[data-dismiss-success="modal"]');
    closeSuccessButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            modalSuccess.style.display = 'none';
            modalSuccess.classList.remove('show');
            modalSuccess.style.opacity = '0';
        });
    });

    // Close success modal on background click
    modalSuccess.addEventListener('click', function(e) {
        if (e.target === modalSuccess) {
            modalSuccess.style.display = 'none';
            modalSuccess.classList.remove('show');
            modalSuccess.style.opacity = '0';
        }
    });

    // Open modal
    if (beriUlasanLink) {
        beriUlasanLink.addEventListener('click', function(e) {
            e.preventDefault();
            modalReview.style.display = 'block';
            modalReview.classList.add('show');
            modalReview.style.opacity = '1';
            document.body.style.overflow = 'hidden';
        });
    }

    // Close modal
    const closeButtons = document.querySelectorAll('[data-bs-dismiss="modal"]');
    closeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            modalReview.style.display = 'none';
            modalReview.classList.remove('show');
            modalReview.style.opacity = '0';
            document.body.style.overflow = 'auto';
        });
    });

    // Close on background click
    modalReview.addEventListener('click', function(e) {
        if (e.target === modalReview) {
            modalReview.style.display = 'none';
            modalReview.classList.remove('show');
            modalReview.style.opacity = '0';
            document.body.style.overflow = 'auto';
        }
    });

    // Star rating
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            ratingInput.value = value;

            stars.forEach(s => {
                if (s.getAttribute('data-value') <= value) {
                    s.style.color = '#ffc107';
                } else {
                    s.style.color = '#e4e5e9';
                }
            });
        });

        // Hover effect
        star.addEventListener('mouseenter', function() {
            const hoverValue = this.getAttribute('data-value');
            stars.forEach(s => {
                if (s.getAttribute('data-value') <= hoverValue) {
                    s.style.color = '#ffc107';
                } else {
                    s.style.color = '#e4e5e9';
                }
            });
        });
    });

    // Reset hover effect when mouse leaves the rating area
    const ratingStars = document.getElementById('rating-stars');
    ratingStars.addEventListener('mouseleave', function() {
        const currentRating = ratingInput.value;
        stars.forEach(s => {
            if (s.getAttribute('data-value') <= currentRating) {
                s.style.color = '#ffc107';
            } else {
                s.style.color = '#e4e5e9';
            }
        });
    });

    // Form validation
    const reviewForm = document.getElementById('review-form');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            const rating = ratingInput.value;
            const email = document.getElementById('email').value.trim();
            const comment = document.getElementById('comment').value.trim();

            if (!rating) {
                e.preventDefault();
                alert('Silakan pilih rating bintang terlebih dahulu!');
                return false;
            }

            if (!email) {
                e.preventDefault();
                alert('Silakan isi email terlebih dahulu!');
                return false;
            }

            // Basic email validation
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                e.preventDefault();
                alert('Format email tidak valid!');
                return false;
            }

            if (!comment) {
                e.preventDefault();
                alert('Silakan isi komentar terlebih dahulu!');
                return false;
            }
        });
    }
});
</script>

@include('mitra.check-transaction.footer')
