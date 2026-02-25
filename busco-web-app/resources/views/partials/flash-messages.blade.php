{{-- View: partials/flash-messages.blade.php | Purpose: Shared flash and validation message partial. --}}

@if (session('success'))
    <div class="flash flash-success" role="alert">
        <span class="flash-icon" aria-hidden="true">OK</span>
        <span class="flash-text">{{ session('success') }}</span>
        <button type="button" class="flash-close" onclick="this.parentElement.remove()" aria-label="Dismiss message">&times;</button>
    </div>
@endif

@if (session('error'))
    <div class="flash flash-error" role="alert">
        <span class="flash-icon" aria-hidden="true">X</span>
        <span class="flash-text">{{ session('error') }}</span>
        <button type="button" class="flash-close" onclick="this.parentElement.remove()" aria-label="Dismiss message">&times;</button>
    </div>
@endif

@if (session('warning'))
    <div class="flash flash-warning" role="alert">
        <span class="flash-icon" aria-hidden="true">!</span>
        <span class="flash-text">{{ session('warning') }}</span>
        <button type="button" class="flash-close" onclick="this.parentElement.remove()" aria-label="Dismiss message">&times;</button>
    </div>
@endif

@if ($errors->any())
    <div class="flash flash-error" role="alert">
        <div class="flash-text">
            <strong>Please fix the following errors:</strong>
            <ul style="margin: 8px 0 0 18px; padding: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="flash-close" onclick="this.parentElement.remove()" aria-label="Dismiss errors">&times;</button>
    </div>
@endif
