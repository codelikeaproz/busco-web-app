@php($editing = isset($job))

<div class="form-grid" data-admin-job-form-grid style="grid-template-columns: repeat(2, minmax(0, 1fr)); gap:14px;">
    <div class="form-group" style="grid-column:1 / -1;">
        <label for="title">Job Title</label>
        <input id="title" name="title" type="text" class="form-input" value="{{ old('title', $job->title ?? '') }}" maxlength="255" required>
        @error('title')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="department">Department</label>
        <input id="department" name="department" type="text" class="form-input" value="{{ old('department', $job->department ?? '') }}" maxlength="100" placeholder="e.g. Operations, HR, MIS" required>
        @error('department')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="location">Location</label>
        <input id="location" name="location" type="text" class="form-input" value="{{ old('location', $job->location ?? '') }}" maxlength="255" placeholder="e.g. Bukidnon Plant" required>
        @error('location')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="employment_type">Employment Type</label>
        <select id="employment_type" name="employment_type" class="form-input" required>
            <option value="">Select type</option>
            @foreach($employmentTypes as $type)
                <option value="{{ $type }}" {{ old('employment_type', $job->employment_type ?? '') === $type ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
        </select>
        @error('employment_type')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="status">Hiring Status</label>
        <select id="status" name="status" class="form-input" required>
            @foreach($statuses as $status)
                <option value="{{ $status }}" {{ old('status', $job->status ?? \App\Models\JobOpening::STATUS_OPEN) === $status ? 'selected' : '' }}>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
        @error('status')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="posted_at">Posted Date</label>
        <input id="posted_at" name="posted_at" type="date" class="form-input" value="{{ old('posted_at', isset($job) && $job->posted_at ? $job->posted_at->format('Y-m-d') : now()->toDateString()) }}">
        @error('posted_at')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="deadline_at">Application Deadline (Optional)</label>
        <input id="deadline_at" name="deadline_at" type="date" class="form-input" value="{{ old('deadline_at', isset($job) && $job->deadline_at ? $job->deadline_at->format('Y-m-d') : '') }}">
        @error('deadline_at')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="application_email_display">Application Email</label>
        <input
            id="application_email_display"
            type="email"
            class="form-input"
            value="{{ old('application_email', $job->application_email ?? ($defaultApplicationEmail ?? 'hrd_buscosugarmill@yahoo.com')) }}"
            readonly
        >
        <small style="display:block; margin-top:6px; color:#637266;">Applicants apply via email only. Resume upload form is intentionally not used.</small>
    </div>

    <div class="form-group" style="grid-column:1 / -1;">
        <label for="summary">Short Summary (Optional)</label>
        <textarea id="summary" name="summary" class="form-input" rows="3" maxlength="500" placeholder="Shown in careers card preview.">{{ old('summary', $job->summary ?? '') }}</textarea>
        @error('summary')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group" style="grid-column:1 / -1;">
        <label for="description">Job Description</label>
        <textarea id="description" name="description" class="form-input" rows="8" required>{{ old('description', $job->description ?? '') }}</textarea>
        @error('description')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="responsibilities">Responsibilities (Optional)</label>
        <textarea id="responsibilities" name="responsibilities" class="form-input" rows="6" placeholder="One item per line is recommended.">{{ old('responsibilities', $job->responsibilities ?? '') }}</textarea>
        @error('responsibilities')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="qualifications">Qualifications (Optional)</label>
        <textarea id="qualifications" name="qualifications" class="form-input" rows="6" placeholder="One item per line is recommended.">{{ old('qualifications', $job->qualifications ?? '') }}</textarea>
        @error('qualifications')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
</div>

<style>
    @media (max-width: 920px) {
        .admin-panel [data-admin-job-form-grid] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
