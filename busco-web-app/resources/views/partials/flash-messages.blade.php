{{-- View: partials/flash-messages.blade.php | Purpose: Validation errors only (session flashes use toasts). --}}

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
