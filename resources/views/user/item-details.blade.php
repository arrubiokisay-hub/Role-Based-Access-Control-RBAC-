@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                
                {{-- Image Section — FULL FIX --}}
                @php
                    // Default values
                    $hasImage = false;
                    $imageUrl = null;
                    
                    // Safely check if image property exists and is not empty
                    if (isset($item->image) && !empty(trim($item->image))) {
                        $imgVal = trim($item->image);
                        
                        // Normalize path
                        if (str_starts_with($imgVal, 'uploads/')) {
                            $imageUrl = asset($imgVal);
                            $filePath = public_path($imgVal);
                        } else {
                            $imageUrl = asset("uploads/{$imgVal}");
                            $filePath = public_path("uploads/{$imgVal}");
                        }
                        
                        // Check file exists
                        $hasImage = file_exists($filePath) && is_file($filePath);
                    }
                @endphp
                
                @if($hasImage)
                    <div class="text-center bg-light">
                        <img src="{{ $imageUrl }}" 
                             class="img-fluid" 
                             style="max-height: 500px; width: 100%; object-fit: contain;" 
                             alt="{{ $item->item_name ?? 'Item' }} Photo">
                    </div>
                @else
                    <div class="bg-light text-center py-5 border-bottom">
                        <i class="fa-solid fa-image fa-4x text-muted opacity-25"></i>
                        <p class="text-muted mt-2">No Photo Available</p>
                    </div>
                @endif

                <div class="card-body p-5">
                    {{-- Badge & Date --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="badge bg-{{ $themeClass }} px-4 py-2 fs-6 shadow-sm">
                            {{ strtoupper($item->category) }}
                        </span>
                        <small class="text-muted">
                            <i class="fa-solid fa-calendar me-1"></i> 
                            Posted on: {{ \Carbon\Carbon::parse($item->created_at)->format('M d, Y') }}
                        </small>
                    </div>

                    {{-- Item Name --}}
                    <h1 class="fw-bold text-dark mb-3">{{ $item->item_name }}</h1>

                    {{-- Location & Contact --}}
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <p class="mb-1 fw-bold text-muted small">LOCATION</p>
                            <p class="fs-5 text-dark">
                                <i class="fa-solid fa-location-dot me-2 text-{{ $themeClass }}"></i> 
                                {{ $item->location }}
                            </p>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <p class="mb-1 fw-bold text-muted small">CONTACT INFO</p>
                            <p class="fs-5 text-dark fw-bold">
                                <i class="fa-solid fa-phone me-2 text-{{ $themeClass }}"></i> 
                                {{ $item->contact_info }}
                            </p>
                        </div>
                    </div>

                    <hr>

                    {{-- Description --}}
                    <div class="mt-4">
                        <h5 class="fw-bold mb-3">Description</h5>
                        <p class="text-secondary leading-relaxed" style="white-space: pre-wrap;">
                            {{ $item->description }}
                        </p>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="mt-5 pt-3 border-top d-flex gap-2">
                        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary px-4">
                            <i class="fa-solid fa-arrow-left me-2"></i>Back to Feed
                        </a>
                        <a href="tel:{{ $item->contact_info }}" class="btn btn-{{ $themeClass }} px-4 shadow-sm">
                            <i class="fa-solid fa-phone me-2"></i>Call Owner
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection